<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Cookie_Scanner {

	/**
	 * Known cookie patterns: name prefix/exact → category.
	 * Checked against the start of the cookie name (prefix match).
	 */
	private static $known = array(
		// Google Analytics
		'_ga'                    => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => '2 years' ),
		'_gid'                   => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => '24 hours' ),
		'_gat'                   => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => '1 minute' ),
		'_gcl'                   => array( 'category' => 'marketing',  'service' => 'Google Ads',       'duration' => '90 days' ),
		'__utma'                 => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => '2 years' ),
		'__utmb'                 => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => '30 minutes' ),
		'__utmc'                 => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => 'Session' ),
		'__utmz'                 => array( 'category' => 'analytics',  'service' => 'Google Analytics', 'duration' => '6 months' ),
		// Google Tag Manager
		'_dc_gtm'                => array( 'category' => 'analytics',  'service' => 'Google Tag Manager', 'duration' => '1 minute' ),
		// Facebook / Meta
		'_fbp'                   => array( 'category' => 'marketing',  'service' => 'Facebook',         'duration' => '90 days' ),
		'_fbc'                   => array( 'category' => 'marketing',  'service' => 'Facebook',         'duration' => '90 days' ),
		'fr'                     => array( 'category' => 'marketing',  'service' => 'Facebook',         'duration' => '90 days' ),
		// WordPress core / functional
		'wordpress_'             => array( 'category' => 'necessary',  'service' => 'WordPress',        'duration' => 'Session' ),
		'wp-settings'            => array( 'category' => 'functional', 'service' => 'WordPress',        'duration' => '1 year' ),
		'wp_woocommerce'         => array( 'category' => 'necessary',  'service' => 'WooCommerce',      'duration' => 'Session' ),
		'woocommerce_'           => array( 'category' => 'necessary',  'service' => 'WooCommerce',      'duration' => 'Session' ),
		'PHPSESSID'              => array( 'category' => 'necessary',  'service' => 'PHP',              'duration' => 'Session' ),
		// Hotjar
		'_hj'                    => array( 'category' => 'analytics',  'service' => 'Hotjar',           'duration' => '1 year' ),
		// LinkedIn
		'li_'                    => array( 'category' => 'marketing',  'service' => 'LinkedIn',         'duration' => '30 days' ),
		'lidc'                   => array( 'category' => 'marketing',  'service' => 'LinkedIn',         'duration' => '1 day' ),
		'bcookie'                => array( 'category' => 'marketing',  'service' => 'LinkedIn',         'duration' => '2 years' ),
		// Twitter / X
		'_twitter_sess'          => array( 'category' => 'marketing',  'service' => 'Twitter',          'duration' => 'Session' ),
		'guest_id'               => array( 'category' => 'marketing',  'service' => 'Twitter',          'duration' => '2 years' ),
		// Cloudflare
		'__cf_bm'                => array( 'category' => 'necessary',  'service' => 'Cloudflare',       'duration' => '30 minutes' ),
		'__cfruid'               => array( 'category' => 'necessary',  'service' => 'Cloudflare',       'duration' => 'Session' ),
		// Cookie consent (our own)
		'scc_consent'            => array( 'category' => 'necessary',  'service' => 'Simple Cookie Consent', 'duration' => '1 year' ),
	);

	// -------------------------------------------------------------------------
	// PHP server-side scan: fetch own homepage, parse Set-Cookie headers
	// -------------------------------------------------------------------------

	public static function run_server_scan() {
		$url      = home_url( '/' );
		$response = wp_remote_get( $url, array(
			'timeout'    => 15,
			'user-agent' => 'SCC-Scanner/1.0',
			'cookies'    => array(), // fresh session — no existing cookies
		) );

		if ( is_wp_error( $response ) ) {
			return array( 'error' => $response->get_error_message() );
		}

		$headers     = wp_remote_retrieve_headers( $response );
		$set_cookies = array();

		// WP HTTP API returns headers as a Requests_IRI_Collection; normalise to array.
		$raw = $headers->getAll();
		foreach ( $raw as $name => $value ) {
			if ( strtolower( $name ) === 'set-cookie' ) {
				if ( is_array( $value ) ) {
					$set_cookies = array_merge( $set_cookies, $value );
				} else {
					$set_cookies[] = $value;
				}
			}
		}

		$names = array();
		foreach ( $set_cookies as $cookie_str ) {
			// The cookie name is the part before the first '='.
			$parts = explode( '=', $cookie_str, 2 );
			$name  = trim( $parts[0] );
			if ( $name !== '' ) {
				$names[] = $name;
			}
		}

		return self::save_new_cookies( $names, 'scan' );
	}

	// -------------------------------------------------------------------------
	// JS client scan: receives array of cookie names from the browser
	// -------------------------------------------------------------------------

	public static function save_from_client( array $names ) {
		return self::save_new_cookies( $names, 'scan' );
	}

	// -------------------------------------------------------------------------
	// Shared: insert only cookies not already in DB
	// -------------------------------------------------------------------------

	private static function save_new_cookies( array $names, $source ) {
		global $wpdb;
		$table = $wpdb->prefix . 'scc_cookies';

		// Fetch existing names to avoid duplicates.
		$existing = $wpdb->get_col( "SELECT cookie_name FROM {$table}" );

		$added = 0;
		foreach ( $names as $name ) {
			$name = sanitize_text_field( $name );
			if ( $name === '' || in_array( $name, $existing, true ) ) {
				continue;
			}

			$meta = self::lookup( $name );

			$wpdb->insert( $table, array(
				'cookie_name' => $name,
				'category'    => $meta['category'],
				'service'     => $meta['service'],
				'duration'    => $meta['duration'],
				'description' => '',
				'source'      => $source,
			) );

			$existing[] = $name; // prevent duplicates within the same batch
			$added++;
		}

		return array( 'added' => $added );
	}

	// -------------------------------------------------------------------------
	// Fallback lookup: match against known list (prefix match)
	// -------------------------------------------------------------------------

	public static function lookup( $name ) {
		foreach ( self::$known as $prefix => $meta ) {
			if ( strpos( $name, $prefix ) === 0 ) {
				return $meta;
			}
		}

		// Unknown → default to analytics (admin can recategorize).
		return array( 'category' => 'analytics', 'service' => '', 'duration' => '' );
	}
}
