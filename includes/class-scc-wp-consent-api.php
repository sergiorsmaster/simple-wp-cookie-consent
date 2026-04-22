<?php
/**
 * WP Consent Level API integration.
 *
 * Bridges our scc_consent cookie to the WP Consent Level API so that
 * third-party plugins (e.g. Site Kit) can read consent without knowing
 * anything about Consentric.
 *
 * Only active when the WP Consent Level API plugin is installed and running
 * (detected via the WP_CONSENT_API_VERSION constant).
 *
 * @see https://github.com/wordpress/wp-consent-level-api
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_WP_Consent_API {

	/**
	 * Maps our category slugs to WP Consent API consent types.
	 * 'necessary' is omitted — it is always granted and the API requires no call for it.
	 */
	private static $category_map = array(
		'analytics'  => 'statistics',
		'marketing'  => 'marketing',
		'functional' => 'preferences',
	);

	public static function init() {
		if ( ! defined( 'WP_CONSENT_API_VERSION' ) ) {
			return;
		}

		// Register our plugin as a consent provider.
		add_filter( 'wp_consent_api_registered_simple-cookie-consent', '__return_true' );

		// Sync consent on every frontend request (reads the cookie).
		add_action( 'init', array( __CLASS__, 'sync_consent' ), 20 );
	}

	/**
	 * Push current scc_consent cookie values into the WP Consent API.
	 */
	public static function sync_consent() {
		if ( ! function_exists( 'wp_set_consent' ) ) {
			return;
		}

		foreach ( self::$category_map as $scc_category => $wp_type ) {
			$value = SCC_Consent_Store::has_consent( $scc_category ) ? 'allow' : 'deny';
			wp_set_consent( $wp_type, $value );
		}
	}

	/**
	 * Whether the WP Consent Level API is available.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return defined( 'WP_CONSENT_API_VERSION' ) && function_exists( 'wp_set_consent' );
	}
}
