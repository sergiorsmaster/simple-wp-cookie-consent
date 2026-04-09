<?php
/**
 * Polylang compatibility.
 *
 * When Polylang is active, registers all user-facing option strings so they
 * appear in Polylang → Languages → String Translations and are returned in
 * the current language on the frontend.
 *
 * Completely inert when Polylang is not installed.
 *
 * @see https://polylang.pro/doc/developping-with-polylang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Polylang {

	/** Polylang string group name. */
	const GROUP = 'Simple Cookie Consent';

	/**
	 * Option key → human-readable name for Polylang's UI.
	 */
	private static $strings = array(
		'scc_banner_title'        => 'Banner title',
		'scc_banner_text'         => 'Banner text',
		'scc_accept_label'        => 'Accept button label',
		'scc_deny_label'          => 'Deny button label',
		'scc_preferences_label'   => 'Preferences button label',
		'scc_modal_title'         => 'Modal title',
		'scc_modal_intro'         => 'Modal description',
		'scc_modal_save_label'    => 'Modal save button label',
		'scc_modal_deny_label'    => 'Modal deny button label',
		'scc_ccpa_opt_out_text'   => 'CCPA opt-out button label',
	);

	public static function init() {
		// Defer the check — Polylang may not be loaded yet at plugin load time.
		add_action( 'init', array( __CLASS__, 'register_strings' ), 20 );
	}

	/**
	 * Register all translatable strings with Polylang.
	 * Called on 'init' so option values are already available.
	 */
	public static function register_strings() {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}

		foreach ( self::$strings as $option => $name ) {
			$value = get_option( $option, '' );
			if ( $value !== '' ) {
				pll_register_string( $name, $value, self::GROUP );
			}
		}
	}

	/**
	 * Return the translated value of an option string.
	 *
	 * Falls back to get_option() when Polylang is inactive or the string
	 * hasn't been registered yet.
	 *
	 * @param string $option   Option key (e.g. 'scc_banner_title').
	 * @param string $default  Default value if option is empty.
	 * @return string
	 */
	public static function translate( $option, $default = '' ) {
		$value = get_option( $option, $default );

		if ( function_exists( 'pll__' ) && $value !== '' ) {
			return pll__( $value );
		}

		return $value;
	}

	/**
	 * Whether Polylang is active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return function_exists( 'pll_register_string' );
	}
}
