<?php
/**
 * Server-side consent reader.
 *
 * Reads the cscc_consent cookie set by the JS layer so PHP can gate
 * server-rendered output (e.g. embeds) on consent status.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CSCC_Consent_Store {

	const COOKIE_NAME = 'cscc_consent';

	/**
	 * Returns the full consent array from the cookie, or null if not set.
	 *
	 * @return array|null
	 */
	/** Allowed keys in the consent cookie and their sanitization type. */
	private static $allowed_keys = array(
		'necessary'  => 'bool',
		'analytics'  => 'bool',
		'marketing'  => 'bool',
		'functional' => 'bool',
		'timestamp'  => 'int',
		'version'    => 'string',
	);

	public static function get_consent() {
		if ( empty( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			return null;
		}

		$raw  = sanitize_text_field( wp_unslash( $_COOKIE[ self::COOKIE_NAME ] ) );
		$data = json_decode( $raw, true );

		if ( ! is_array( $data ) ) {
			return null;
		}

		// Whitelist keys and sanitize each value by expected type.
		$clean = array();
		foreach ( self::$allowed_keys as $key => $type ) {
			if ( ! array_key_exists( $key, $data ) ) {
				continue;
			}
			switch ( $type ) {
				case 'bool':
					$clean[ $key ] = (bool) $data[ $key ];
					break;
				case 'int':
					$clean[ $key ] = absint( $data[ $key ] );
					break;
				case 'string':
					$clean[ $key ] = sanitize_text_field( (string) $data[ $key ] );
					break;
			}
		}

		return ! empty( $clean ) ? $clean : null;
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
