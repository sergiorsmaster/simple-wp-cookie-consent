<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Public {

	public static function init() {
		add_action( 'wp_head',             array( __CLASS__, 'inject_gtm_consent_defaults' ), 1 );
		add_action( 'wp_enqueue_scripts',  array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'wp_footer',           array( __CLASS__, 'render_banner' ) );
		add_action( 'wp_footer',           array( __CLASS__, 'render_modal' ) );
		add_action( 'wp_footer',           array( __CLASS__, 'render_preferences_icon' ) );
	}

	/**
	 * Inject gtag('consent', 'default', {...}) as the very first <head> script.
	 *
	 * Must fire before GTM / any Google tag so that Consent Mode v2 is active
	 * from the moment those tags load. Only outputs when GTM integration is enabled.
	 */
	public static function inject_gtm_consent_defaults() {
		if ( ! get_option( 'scc_enabled', '1' ) ) {
			return;
		}

		if ( ! get_option( 'scc_gtm_enabled', '0' ) ) {
			return;
		}

		$wait_ms = (int) get_option( 'scc_gtm_wait_for_update', 500 );
		?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}

gtag('consent', 'default', {
	'ad_storage':             'denied',
	'analytics_storage':      'denied',
	'ad_user_data':           'denied',
	'ad_personalization':     'denied',
	'functionality_storage':  'denied',
	'personalization_storage':'denied',
	'security_storage':       'granted',
	'wait_for_update':        <?php echo $wait_ms; ?>
});

gtag('set', 'ads_data_redaction', true);
gtag('set', 'url_passthrough', false);
</script>
		<?php
	}

	/**
	 * Enqueue frontend scripts.
	 *
	 * CSS and JS always load so the modal and [scc_preferences] shortcode
	 * work regardless of the "enable banner" toggle.
	 */
	public static function enqueue_scripts() {

		// Core consent storage (always loaded)
		wp_enqueue_script(
			'scc-consent',
			SCC_PLUGIN_URL . 'public/assets/scc-consent.js',
			array(),
			SCC_VERSION,
			false // load in <head>
		);

		// Pass settings to JS
		wp_localize_script( 'scc-consent', 'sccSettings', array(
			'debug'   => (bool) get_option( 'scc_debug', '0' ),
			'gtmMode' => get_option( 'scc_gtm_mode', 'basic' ),
		) );

		// GTM bridge (only when GTM integration is enabled)
		if ( get_option( 'scc_gtm_enabled', '0' ) ) {
			wp_enqueue_script(
				'scc-gtm',
				SCC_PLUGIN_URL . 'public/assets/scc-gtm.js',
				array( 'scc-consent' ),
				SCC_VERSION,
				false // load in <head>, after scc-consent
			);
		}

		// Banner styles
		wp_enqueue_style(
			'scc-banner',
			SCC_PLUGIN_URL . 'public/assets/scc-banner.css',
			array(),
			SCC_VERSION
		);

		// Inject color CSS custom properties from settings
		$color_bg     = sanitize_hex_color( get_option( 'scc_color_bg',     '#ffffff' ) );
		$color_text   = sanitize_hex_color( get_option( 'scc_color_text',   '#111111' ) );
		$color_accent = sanitize_hex_color( get_option( 'scc_color_accent', '#0073aa' ) );
		$custom_css   = get_option( 'scc_custom_css', '' );

		$inline_css = ":root {
			--scc-bg:     {$color_bg};
			--scc-text:   {$color_text};
			--scc-accent: {$color_accent};
		}";

		if ( ! empty( $custom_css ) ) {
			$inline_css .= "\n" . wp_strip_all_tags( $custom_css );
		}

		wp_add_inline_style( 'scc-banner', $inline_css );

		// Banner JS (loaded in footer, after DOM is ready)
		wp_enqueue_script(
			'scc-banner',
			SCC_PLUGIN_URL . 'public/assets/scc-banner.js',
			array( 'scc-consent' ),
			SCC_VERSION,
			true // footer
		);

		// Preferences modal JS
		wp_enqueue_script(
			'scc-modal',
			SCC_PLUGIN_URL . 'public/assets/scc-modal.js',
			array( 'scc-banner' ),
			SCC_VERSION,
			true // footer
		);
	}

	/**
	 * Render the banner HTML in wp_footer.
	 */
	public static function render_banner() {
		if ( ! get_option( 'scc_enabled', '1' ) ) {
			return;
		}

		$jurisdiction = get_option( 'scc_jurisdiction', 'gdpr' );
		$position     = get_option( 'scc_position', 'bottom-bar' );

		// Resolve page URLs
		$privacy_page = (int) get_option( 'scc_privacy_policy_page', 0 );
		$cookie_page  = (int) get_option( 'scc_cookie_policy_page', 0 );
		$imprint_page = (int) get_option( 'scc_imprint_page', 0 );

		$privacy_url = $privacy_page ? get_permalink( $privacy_page ) : '';
		$cookie_url  = $cookie_page  ? get_permalink( $cookie_page )  : '';
		$imprint_url = $imprint_page ? get_permalink( $imprint_page ) : '';

		$title        = SCC_Polylang::translate( 'scc_banner_title',      __( 'We use cookies', 'simple-cookie-consent' ) );
		$text         = SCC_Polylang::translate( 'scc_banner_text',       __( 'We use cookies to improve your experience on our website. Please choose your cookie preferences below.', 'simple-cookie-consent' ) );
		$accept_label = SCC_Polylang::translate( 'scc_accept_label',      __( 'Accept All', 'simple-cookie-consent' ) );
		$deny_label   = SCC_Polylang::translate( 'scc_deny_label',        __( 'Deny All', 'simple-cookie-consent' ) );
		$prefs_label  = SCC_Polylang::translate( 'scc_preferences_label', __( 'Preferences', 'simple-cookie-consent' ) );
		$ccpa_text    = SCC_Polylang::translate( 'scc_ccpa_opt_out_text', __( 'Do Not Sell My Personal Information', 'simple-cookie-consent' ) );
		$logo_url     = get_option( 'scc_logo_url', '' );

		include SCC_PLUGIN_DIR . 'public/views/banner.php';
	}

	/**
	 * Render the floating preferences icon in wp_footer (optional).
	 */
	public static function render_preferences_icon() {
		if ( ! get_option( 'scc_enabled', '1' ) ) {
			return;
		}
		if ( ! get_option( 'scc_show_preferences_icon', '1' ) ) {
			return;
		}
		include SCC_PLUGIN_DIR . 'public/views/preferences-icon.php';
	}

	/**
	 * Render the preferences modal HTML in wp_footer.
	 */
	public static function render_modal() {
		if ( ! get_option( 'scc_enabled', '1' ) ) {
			return;
		}

		$jurisdiction = get_option( 'scc_jurisdiction', 'gdpr' );
		$privacy_page = (int) get_option( 'scc_privacy_policy_page', 0 );
		$cookie_page  = (int) get_option( 'scc_cookie_policy_page', 0 );
		$privacy_url  = $privacy_page ? get_permalink( $privacy_page ) : '';
		$cookie_url   = $cookie_page  ? get_permalink( $cookie_page )  : '';

		include SCC_PLUGIN_DIR . 'public/views/modal.php';
	}
}
