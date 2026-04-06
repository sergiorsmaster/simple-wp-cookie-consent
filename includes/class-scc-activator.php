<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Activator {

	public static function activate() {
		self::create_tables();
		self::set_default_options();
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

	private static function set_default_options() {
		$defaults = array(
			'scc_enabled'            => '1',
			'scc_jurisdiction'       => 'gdpr',
			'scc_banner_title'       => __( 'We use cookies', 'simple-cookie-consent' ),
			'scc_banner_text'        => __( 'We use cookies to improve your experience on our website. Please choose your cookie preferences below.', 'simple-cookie-consent' ),
			'scc_accept_label'       => __( 'Accept All', 'simple-cookie-consent' ),
			'scc_deny_label'         => __( 'Deny All', 'simple-cookie-consent' ),
			'scc_preferences_label'  => __( 'Preferences', 'simple-cookie-consent' ),
			'scc_position'           => 'bottom-bar',
			'scc_color_bg'           => '#ffffff',
			'scc_color_text'         => '#111111',
			'scc_color_accent'       => '#0073aa',
			'scc_gtm_enabled'        => '0',
			'scc_gtm_mode'           => 'basic',
		);

		foreach ( $defaults as $key => $value ) {
			// add_option does nothing if the option already exists — safe to call on re-activation
			add_option( $key, $value );
		}
	}
}
