<?php
if (!defined('ABSPATH')) {
	exit;
}

class CSCC_Cookie_Scanner
{

	/** Transient key for the parsed CSV index. */
	const DB_TRANSIENT = 'cscc_cookie_db_index';

	/** How long to cache the parsed index (24 hours). */
	const DB_TRANSIENT_TTL = DAY_IN_SECONDS;

	/** Path to the bundled CSV. */
	private static function csv_path()
	{
		return CSCC_PLUGIN_DIR . 'includes/data/open-cookie-database.csv';
	}

	// -------------------------------------------------------------------------
	// Open Cookie Database — parse & cache
	// -------------------------------------------------------------------------

	/**
	 * Returns the parsed cookie database as two arrays:
	 *   'exact'    => [ cookie_name => meta, ... ]
	 *   'wildcard' => [ prefix => meta, ... ]
	 *
	 * Result is cached in a WP transient for 24 h to avoid re-parsing the CSV
	 * on every request.
	 *
	 * @return array{exact: array, wildcard: array}
	 */
	public static function get_db_index()
	{
		$cached = get_transient(self::DB_TRANSIENT);
		if (is_array($cached)) {
			return $cached;
		}

		$index = array('exact' => array(), 'wildcard' => array());

		$csv = self::csv_path();
		if (!file_exists($csv)) {
			return $index;
		}

		$fh = fopen($csv, 'r'); // phpcs:ignore WordPress.WP.AlternativeFunctions
		if (!$fh) {
			return $index;
		}

		// Read header row and map column positions.
		$header = fgetcsv($fh);
		if (!$header) {
			fclose($fh); // phpcs:ignore WordPress.WP.AlternativeFunctions
			return $index;
		}

		$cols = array_flip(array_map('trim', $header));

		$col_name = $cols['Cookie / Data Key name'] ?? null;
		$col_platform = $cols['Platform'] ?? null;
		$col_category = $cols['Category'] ?? null;
		$col_desc = $cols['Description'] ?? null;
		$col_retain = $cols['Retention period'] ?? null;
		$col_wildcard = $cols['Wildcard match'] ?? null;

		if (is_null($col_name)) {
			fclose($fh); // phpcs:ignore WordPress.WP.AlternativeFunctions
			return $index;
		}

		while (($row = fgetcsv($fh)) !== false) {
			$name = isset($row[$col_name]) ? trim($row[$col_name]) : '';
			if ($name === '') {
				continue;
			}

			$meta = array(
				'category' => self::map_category(isset($row[$col_category]) ? $row[$col_category] : ''),
				'service' => isset($row[$col_platform]) ? trim($row[$col_platform]) : '',
				'duration' => isset($row[$col_retain]) ? trim($row[$col_retain]) : '',
				'description' => isset($row[$col_desc]) ? trim($row[$col_desc]) : '',
			);

			$wildcard = isset($row[$col_wildcard]) ? (int) $row[$col_wildcard] : 0;

			if ($wildcard) {
				$index['wildcard'][$name] = $meta;
			} else {
				$index['exact'][$name] = $meta;
			}
		}

		fclose($fh); // phpcs:ignore WordPress.WP.AlternativeFunctions

		set_transient(self::DB_TRANSIENT, $index, self::DB_TRANSIENT_TTL);

		return $index;
	}

	/**
	 * Return total number of cookies in the bundled database.
	 */
	public static function db_count()
	{
		$index = self::get_db_index();
		return count($index['exact']) + count($index['wildcard']);
	}

	/**
	 * Flush the cached index (e.g. after a plugin update).
	 */
	public static function flush_db_cache()
	{
		delete_transient(self::DB_TRANSIENT);
	}

	// -------------------------------------------------------------------------
	// Category mapping: CSV → plugin categories
	// -------------------------------------------------------------------------

	private static function map_category($csv_category)
	{
		$map = array(
			'functional' => 'functional',
			'analytics' => 'analytics',
			'marketing' => 'marketing',
			'security' => 'necessary',
		);
		return $map[strtolower(trim($csv_category))] ?? 'analytics';
	}

	// -------------------------------------------------------------------------
	// PHP server-side scan: fetch own homepage, parse Set-Cookie headers
	// -------------------------------------------------------------------------

	public static function run_server_scan()
	{
		$url = home_url('/');
		$response = wp_remote_get($url, array(
			'timeout' => 15,
			'user-agent' => 'CSCC-Scanner/1.0',
			'cookies' => array(),
		));

		if (is_wp_error($response)) {
			return array('error' => $response->get_error_message());
		}

		$headers = wp_remote_retrieve_headers($response);
		$set_cookies = array();

		$raw = $headers->getAll();
		foreach ($raw as $name => $value) {
			if (strtolower($name) === 'set-cookie') {
				if (is_array($value)) {
					$set_cookies = array_merge($set_cookies, $value);
				} else {
					$set_cookies[] = $value;
				}
			}
		}

		$names = array();
		foreach ($set_cookies as $cookie_str) {
			$parts = explode('=', $cookie_str, 2);
			$name = trim($parts[0]);
			if ($name !== '') {
				$names[] = $name;
			}
		}

		return self::save_new_cookies($names, 'scan');
	}

	// -------------------------------------------------------------------------
	// JS client scan: receives array of cookie names from the browser
	// -------------------------------------------------------------------------

	public static function save_from_client(array $names)
	{
		return self::save_new_cookies($names, 'scan');
	}

	// -------------------------------------------------------------------------
	// Shared: insert only cookies not already in DB
	// -------------------------------------------------------------------------

	private static function save_new_cookies(array $names, $source)
	{
		global $wpdb;
		$table = $wpdb->prefix . 'cscc_cookies';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- $table uses trusted prefix
		$existing = $wpdb->get_col("SELECT cookie_name FROM {$table}");

		$added = 0;
		foreach ($names as $name) {
			$name = sanitize_text_field($name);
			if ($name === '' || in_array($name, $existing, true)) {
				continue;
			}

			$meta = self::lookup($name);

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->insert($table, array(
				'cookie_name' => $name,
				'category' => $meta['category'],
				'service' => $meta['service'],
				'duration' => $meta['duration'],
				'description' => $meta['description'],
				'source' => $source,
			));

			$existing[] = $name;
			$added++;
		}

		return array('added' => $added);
	}

	// -------------------------------------------------------------------------
	// Lookup: Open Cookie Database first, built-in fallback second
	// -------------------------------------------------------------------------

	public static function lookup($name)
	{
		$index = self::get_db_index();

		// 1. Exact match in CSV database.
		if (isset($index['exact'][$name])) {
			return $index['exact'][$name];
		}

		// 2. Wildcard (prefix) match in CSV database.
		foreach ($index['wildcard'] as $prefix => $meta) {
			if (strpos($name, $prefix) === 0) {
				return $meta;
			}
		}

		// 3. Built-in fallback list (prefix match).
		foreach (self::$builtin as $prefix => $meta) {
			if (strpos($name, $prefix) === 0) {
				return $meta;
			}
		}

		// 4. Unknown — default to analytics so admin can recategorize.
		return array('category' => 'analytics', 'service' => '', 'duration' => '', 'description' => '');
	}

	// -------------------------------------------------------------------------
	// Built-in fallback list (covers common cookies not in the CSV)
	// -------------------------------------------------------------------------

	private static $builtin = array(
		'_ga' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => '2 years', 'description' => 'Registers a unique ID used to generate statistical data on how the visitor uses the website.'),
		'_gid' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => '24 hours', 'description' => 'Registers a unique ID used to generate statistical data on how the visitor uses the website.'),
		'_gat' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => '1 minute', 'description' => 'Used by Google Analytics to throttle request rate.'),
		'_gcl' => array('category' => 'marketing', 'service' => 'Google Ads', 'duration' => '90 days', 'description' => 'Used by Google AdSense to register and report website user actions after viewing or clicking one of the advertiser\'s ads.'),
		'__utma' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => '2 years', 'description' => 'Collects data on the number of times a user has visited the website.'),
		'__utmb' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => '30 minutes', 'description' => 'Registers a timestamp of exactly when the visitor entered the website.'),
		'__utmc' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => 'Session', 'description' => 'Registers a timestamp of exactly when the visitor left the website.'),
		'__utmz' => array('category' => 'analytics', 'service' => 'Google Analytics', 'duration' => '6 months', 'description' => 'Collects data on where the visitor came from, what search engine was used, and what link was clicked.'),
		'_dc_gtm' => array('category' => 'analytics', 'service' => 'Google Tag Manager', 'duration' => '1 minute', 'description' => 'Used by Google Tag Manager to control the loading of a Google Analytics script tag.'),
		'_fbp' => array('category' => 'marketing', 'service' => 'Facebook', 'duration' => '90 days', 'description' => 'Used by Facebook to deliver a series of advertisement products.'),
		'_fbc' => array('category' => 'marketing', 'service' => 'Facebook', 'duration' => '90 days', 'description' => 'Used by Facebook to register last time the user clicked on an ad.'),
		'wordpress_' => array('category' => 'necessary', 'service' => 'WordPress', 'duration' => 'Session', 'description' => 'WordPress authentication cookie.'),
		'wp-settings' => array('category' => 'functional', 'service' => 'WordPress', 'duration' => '1 year', 'description' => 'WordPress stores interface settings for each user.'),
		'woocommerce_' => array('category' => 'necessary', 'service' => 'WooCommerce', 'duration' => 'Session', 'description' => 'WooCommerce session cookie.'),
		'PHPSESSID' => array('category' => 'necessary', 'service' => 'PHP', 'duration' => 'Session', 'description' => 'Preserves user session state across page requests.'),
		'_hj' => array('category' => 'analytics', 'service' => 'Hotjar', 'duration' => '1 year', 'description' => 'Sets a unique ID for the session. This allows the website to obtain data on visitor behaviour for statistical purposes.'),
		'__cf_bm' => array('category' => 'necessary', 'service' => 'Cloudflare', 'duration' => '30 minutes', 'description' => 'Used by Cloudflare Bot Management to identify legitimate bots.'),
		'__cfruid' => array('category' => 'necessary', 'service' => 'Cloudflare', 'duration' => 'Session', 'description' => 'Used by Cloudflare to identify trusted web traffic.'),
		'cscc_consent' => array('category' => 'necessary', 'service' => 'Consentric', 'duration' => '1 year', 'description' => 'Stores the visitor\'s cookie consent preferences.'),
	);
}
