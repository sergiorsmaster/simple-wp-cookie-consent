<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Activator {

	public static function activate() {
		self::create_tables();
		self::seed_own_cookie();
		self::set_default_options();
		// Flush cached cookie DB index so it is rebuilt with the current CSV.
		delete_transient( 'scc_cookie_db_index' );
	}

	private static function create_tables() {
		global $wpdb;

		$table   = $wpdb->prefix . 'scc_cookies';
		$charset = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$table} (
			id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
			cookie_name VARCHAR(255)    NOT NULL,
			category    ENUM('necessary','analytics','marketing','functional') NOT NULL DEFAULT 'necessary',
			service     VARCHAR(255)    NOT NULL DEFAULT '',
			description TEXT,
			duration    VARCHAR(100)    NOT NULL DEFAULT '',
			source      ENUM('manual','scan','cookiedb') NOT NULL DEFAULT 'manual',
			PRIMARY KEY (id),
			UNIQUE KEY cookie_name (cookie_name)
		) {$charset};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( 'scc_db_version', SCC_VERSION );
	}

	/**
	 * Seed the plugin's own consent cookie into the cookie list table so it
	 * always appears in [scc_cookie_list] without requiring a manual scan.
	 * Uses INSERT IGNORE so re-activation or table upgrades never duplicate it.
	 */
	private static function seed_own_cookie() {
		global $wpdb;

		$table = $wpdb->prefix . 'scc_cookies';

		// Check if the row already exists to avoid duplicate-key errors on
		// hosts where IGNORE may not suppress all warnings.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table uses trusted prefix
		$exists = $wpdb->get_var(
			$wpdb->prepare( "SELECT id FROM {$table} WHERE cookie_name = %s LIMIT 1", 'scc_consent' )
		);

		if ( $exists ) {
			return;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->insert(
			$table,
			array(
				'cookie_name' => 'scc_consent',
				'category'    => 'necessary',
				'service'     => 'Simple Cookie Consent',
				'duration'    => '1 year',
				'description' => 'Stores the visitor\'s cookie consent preferences (categories granted/denied, timestamp, version). Set by this plugin.',
				'source'      => 'manual',
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s' )
		);
	}

	private static function set_default_options() {
		$defaults = array(
			'scc_enabled'            => '1',
			'scc_jurisdiction'       => 'gdpr',
			'scc_banner_title'       => __( 'We use cookies', 'simple-cookie-consent' ),
			'scc_banner_text'        => __( 'We use cookies to improve your experience on our website. Please choose your cookie preferences below.', 'simple-cookie-consent' ),
			'scc_accept_label'       => __( 'Accept All', 'simple-cookie-consent' ),
			'scc_deny_label'         => __( 'Deny All', 'simple-cookie-consent' ),
			'scc_preferences_label'  => __( 'Preferences', 'simple-cookie-consent' ),
			'scc_modal_title'        => __( 'Cookie Preferences', 'simple-cookie-consent' ),
			'scc_modal_intro'        => __( 'Choose which cookies you allow. You can change your preferences at any time.', 'simple-cookie-consent' ),
			'scc_modal_save_label'   => __( 'Save Preferences', 'simple-cookie-consent' ),
			'scc_modal_deny_label'   => __( 'Deny All', 'simple-cookie-consent' ),
			'scc_position'               => 'bottom-bar',
			'scc_color_bg'               => '#ffffff',
			'scc_color_text'             => '#111111',
			'scc_color_accent'           => '#0073aa',
			'scc_border_radius'          => '6',
			'scc_banner_max_width'       => '200',
			'scc_banner_border_width'    => '0',
			'scc_banner_border_color'    => '#dddddd',
			'scc_button_style'           => 'outline',
			'scc_logo_source'            => 'custom',
			'scc_show_preferences_icon'  => '1',
			'scc_gtm_enabled'        => '0',
			'scc_gtm_mode'           => 'basic',
			'scc_gtm_wait_for_update'=> '500',
			'scc_debug'              => '0',
		);

		foreach ( $defaults as $key => $value ) {
			// add_option does nothing if the option already exists — safe to call on re-activation
			add_option( $key, $value );
		}
	}
}
