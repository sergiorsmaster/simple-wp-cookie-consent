<?php
if (!defined('ABSPATH')) {
	exit;
}

class SCC_Public
{

	public static function init()
	{
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
		add_action('wp_footer', array(__CLASS__, 'render_banner'));
		add_action('wp_footer', array(__CLASS__, 'render_modal'));
		add_action('wp_footer', array(__CLASS__, 'render_preferences_icon'));
	}

	/**
	 * Enqueue frontend scripts.
	 *
	 * CSS and JS always load so the modal and [scc_preferences] shortcode
	 * work regardless of the "enable banner" toggle.
	 */
	public static function enqueue_scripts()
	{

		// Core consent storage (always loaded)
		wp_enqueue_script(
			'scc-consent',
			SCC_PLUGIN_URL . 'public/assets/scc-consent.js',
			array(),
			SCC_VERSION,
			false // load in <head>
		);

		// Pass settings to JS
		$is_preview = isset( $_GET['scc_preview'] ) && current_user_can( 'manage_options' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_localize_script('scc-consent', 'sccSettings', array(
			'debug'   => (bool) get_option('scc_debug', '0'),
			'gtmMode' => get_option('scc_gtm_mode', 'basic'),
			'preview' => $is_preview,
		));

		// GTM Consent Mode v2 defaults — must run before any Google tag.
		if (get_option('scc_gtm_enabled', '0')) {
			$wait_ms = (int) get_option('scc_gtm_wait_for_update', 500);

			$gtm_defaults = "window.dataLayer = window.dataLayer || [];\n"
				. "function gtag() { dataLayer.push(arguments); }\n"
				. "gtag('consent', 'default', {\n"
				. "  'ad_storage': 'denied',\n"
				. "  'analytics_storage': 'denied',\n"
				. "  'ad_user_data': 'denied',\n"
				. "  'ad_personalization': 'denied',\n"
				. "  'functionality_storage': 'denied',\n"
				. "  'personalization_storage': 'denied',\n"
				. "  'security_storage': 'granted',\n"
				. "  'wait_for_update': " . $wait_ms . "\n"
				. "});\n"
				. "gtag('set', 'ads_data_redaction', true);\n"
				. "gtag('set', 'url_passthrough', false);";

			wp_add_inline_script( 'scc-consent', $gtm_defaults, 'before' );

			wp_enqueue_script(
				'scc-gtm',
				SCC_PLUGIN_URL . 'public/assets/scc-gtm.js',
				array('scc-consent'),
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

		// Inject CSS custom properties from settings
		$color_bg = sanitize_hex_color(get_option('scc_color_bg', '#ffffff')) ?: '#ffffff';
		$color_text = sanitize_hex_color(get_option('scc_color_text', '#111111')) ?: '#111111';
		$color_accent = sanitize_hex_color(get_option('scc_color_accent', '#0073aa')) ?: '#0073aa';
		$radius = absint(get_option('scc_border_radius', 6));
		$border_width = absint(get_option('scc_banner_border_width', '0'));
		$border_color = sanitize_hex_color(get_option('scc_banner_border_color', '#dddddd')) ?: '#dddddd';
		$max_width = absint(get_option('scc_banner_max_width', '200'));
		$button_style = sanitize_text_field(get_option('scc_button_style', 'outline'));

		// Secondary button vars (Deny + Preferences)
		$sec_border = ('ghost' === $button_style) ? 'transparent' : 'currentColor';
		$sec_decoration = ('ghost' === $button_style) ? 'inherit' : 'none';

		$inline_css = ":root {
			--scc-bg:             {$color_bg};
			--scc-text:           {$color_text};
			--scc-accent:         {$color_accent};
			--scc-radius:         {$radius}px;
			--scc-border:         {$border_width}px solid {$border_color};
			--scc-secondary-border:      {$sec_border};
			--scc-secondary-decoration:  {$sec_decoration};
		}";

		// Override width for corner/center positions if max-width is set
		if ($max_width > 0) {
			$inline_css .= "\n.scc-position-bottom-left,\n.scc-position-bottom-right { width: {$max_width}px; }";
			$inline_css .= "\n.scc-position-center-modal { width: {$max_width}px; }";
		}

		wp_add_inline_style('scc-banner', $inline_css);

		// Banner JS (loaded in footer, after DOM is ready)
		wp_enqueue_script(
			'scc-banner',
			SCC_PLUGIN_URL . 'public/assets/scc-banner.js',
			array('scc-consent'),
			SCC_VERSION,
			true // footer
		);

		// Preferences modal JS
		wp_enqueue_script(
			'scc-modal',
			SCC_PLUGIN_URL . 'public/assets/scc-modal.js',
			array('scc-banner'),
			SCC_VERSION,
			true // footer
		);
	}

	/**
	 * Render the banner HTML in wp_footer.
	 */
	public static function render_banner()
	{
		$is_preview = isset( $_GET['scc_preview'] ) && current_user_can( 'manage_options' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! $is_preview && ! get_option('scc_enabled', '1') ) {
			return;
		}

		$jurisdiction = get_option('scc_jurisdiction', 'gdpr');
		$position = get_option('scc_position', 'bottom-bar');

		// Resolve page URLs
		$privacy_page = (int) get_option('scc_privacy_policy_page', 0);
		$cookie_page = (int) get_option('scc_cookie_policy_page', 0);
		$imprint_page = (int) get_option('scc_imprint_page', 0);

		$privacy_url = $privacy_page ? get_permalink($privacy_page) : '';
		$cookie_url = $cookie_page ? get_permalink($cookie_page) : '';
		$imprint_url = $imprint_page ? get_permalink($imprint_page) : '';

		$title = SCC_Polylang::translate('scc_banner_title', __('We use cookies', 'consentric'));
		$text = SCC_Polylang::translate('scc_banner_text', __('We use cookies to improve your experience on our website. Please choose your cookie preferences below.', 'consentric'));
		$accept_label = SCC_Polylang::translate('scc_accept_label', __('Accept All', 'consentric'));
		$deny_label = SCC_Polylang::translate('scc_deny_label', __('Deny All', 'consentric'));
		$prefs_label = SCC_Polylang::translate('scc_preferences_label', __('Preferences', 'consentric'));
		$ccpa_text = SCC_Polylang::translate('scc_ccpa_opt_out_text', __('Do Not Sell My Personal Information', 'consentric'));
		$logo_source = get_option('scc_logo_source', 'custom');
		if ('site' === $logo_source) {
			$logo_id = get_theme_mod('custom_logo');
			$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
		} elseif ('custom' === $logo_source) {
			$logo_url = get_option('scc_logo_url', '');
		} else {
			$logo_url = '';
		}

		include SCC_PLUGIN_DIR . 'public/views/banner.php';
	}

	/**
	 * Render the floating preferences icon in wp_footer (optional).
	 */
	public static function render_preferences_icon()
	{
		if (!get_option('scc_enabled', '1')) {
			return;
		}
		if (!get_option('scc_show_preferences_icon', '1')) {
			return;
		}
		include SCC_PLUGIN_DIR . 'public/views/preferences-icon.php';
	}

	/**
	 * Render the preferences modal HTML in wp_footer.
	 */
	public static function render_modal()
	{
		if (!get_option('scc_enabled', '1')) {
			return;
		}

		$jurisdiction = get_option('scc_jurisdiction', 'gdpr');
		$privacy_page = (int) get_option('scc_privacy_policy_page', 0);
		$cookie_page  = (int) get_option('scc_cookie_policy_page', 0);
		$privacy_url  = $privacy_page ? get_permalink($privacy_page) : '';
		$cookie_url   = $cookie_page ? get_permalink($cookie_page) : '';

		$modal_title      = SCC_Polylang::translate( 'scc_modal_title',      __( 'Cookie Preferences', 'consentric' ) );
		$modal_intro      = SCC_Polylang::translate( 'scc_modal_intro',      __( 'Choose which cookies you allow. You can change your preferences at any time.', 'consentric' ) );
		$modal_save_label = SCC_Polylang::translate( 'scc_modal_save_label', __( 'Save Preferences', 'consentric' ) );
		$modal_deny_label = SCC_Polylang::translate( 'scc_modal_deny_label', __( 'Deny All', 'consentric' ) );

		include SCC_PLUGIN_DIR . 'public/views/modal.php';
	}
}
