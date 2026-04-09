<?php
/**
 * Server-side consent reader.
 *
 * Reads the scc_consent cookie set by the JS layer so PHP can gate
 * server-rendered output (e.g. embeds) on consent status.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Consent_Store {

	const COOKIE_NAME = 'scc_consent';

	/**
	 * Returns the full consent array from the cookie, or null if not set.
	 *
	 * @return array|null
	 */
	public static function get_consent() {
		if ( empty( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			return null;
		}

		$data = json_decode( wp_unslash( $_COOKIE[ self::COOKIE_NAME ] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		if ( ! is_array( $data ) ) {
			return null;
		}

		return $data;
	}

	/**
	 * Check whether the visitor has given consent for a specific category.
	 *
	 * @param string $category  'necessary' | 'analytics' | 'marketing' | 'functional'
	 * @return bool  Returns true for 'necessary' always; true for others only if explicitly granted.
	 */
	public static function has_consent( $category ) {
		if ( 'necessary' === $category ) {
			return true;
		}

		$consent = self::get_consent();

		if ( null === $consent ) {
			return false;
		}

		return ! empty( $consent[ $category ] );
	}

	/**
	 * Check whether the visitor has made any choice (cookie exists).
	 *
	 * @return bool
	 */
	public static function has_interacted() {
		return ! empty( $_COOKIE[ self::COOKIE_NAME ] );
	}
}
