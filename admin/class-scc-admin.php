<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCC_Admin {

	/** All tabs in display order, mapped to their settings group. */
	const TABS = array(
		'general'      => 'scc_general',
		'appearance'   => 'scc_appearance',
		'jurisdiction' => 'scc_jurisdiction',
		'integrations' => 'scc_integrations',
		'cookies'      => null, // no settings group — custom UI
	);

	const TAB_LABELS = array(
		'general'      => 'General',
		'appearance'   => 'Appearance',
		'jurisdiction' => 'Jurisdiction',
		'integrations' => 'Integrations',
		'cookies'      => 'Cookies',
	);

	public static function init() {
		add_action( 'admin_menu',            array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init',            array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	// -------------------------------------------------------------------------
	// Menu
	// -------------------------------------------------------------------------

	public static function add_menu() {
		add_options_page(
			__( 'Cookie Consent Settings', 'simple-cookie-consent' ),
			__( 'Cookie Consent', 'simple-cookie-consent' ),
			'manage_options',
			'scc-cookie-consent',
			array( __CLASS__, 'render_page' )
		);
	}

	// -------------------------------------------------------------------------
	// Settings registration — one group per tab so saving one tab
	// never touches another tab's options.
	// -------------------------------------------------------------------------

	public static function register_settings() {

		// General
		foreach ( array(
			'scc_enabled'               => 'scc_sanitize_checkbox',
			'scc_banner_title'          => 'sanitize_text_field',
			'scc_banner_text'           => 'sanitize_textarea_field',
			'scc_logo_url'              => 'esc_url_raw',
			'scc_accept_label'          => 'sanitize_text_field',
			'scc_deny_label'            => 'sanitize_text_field',
			'scc_preferences_label'     => 'sanitize_text_field',
			'scc_privacy_policy_page'   => 'absint',
			'scc_cookie_policy_page'    => 'absint',
			'scc_imprint_page'          => 'absint',
			'scc_show_preferences_icon' => 'scc_sanitize_checkbox',
			'scc_ccpa_opt_out_text'     => 'sanitize_text_field',
		) as $option => $cb ) {
			register_setting( 'scc_general', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Appearance
		foreach ( array(
			'scc_position'    => 'sanitize_text_field',
			'scc_color_bg'    => 'sanitize_hex_color',
			'scc_color_text'  => 'sanitize_hex_color',
			'scc_color_accent'=> 'sanitize_hex_color',
			'scc_custom_css'  => 'wp_strip_all_tags',
		) as $option => $cb ) {
			register_setting( 'scc_appearance', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Jurisdiction
		register_setting( 'scc_jurisdiction', 'scc_jurisdiction', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );

		// Integrations
		foreach ( array(
			'scc_gtm_enabled'        => 'scc_sanitize_checkbox',
			'scc_gtm_mode'           => 'sanitize_text_field',
			'scc_gtm_wait_for_update'=> 'absint',
			'scc_debug'              => 'scc_sanitize_checkbox',
		) as $option => $cb ) {
			register_setting( 'scc_integrations', $option, array( 'sanitize_callback' => $cb ) );
		}
	}

	// -------------------------------------------------------------------------
	// Assets
	// -------------------------------------------------------------------------

	public static function enqueue_scripts( $hook ) {
		if ( 'settings_page_scc-cookie-consent' !== $hook ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style(
			'scc-admin',
			SCC_PLUGIN_URL . 'admin/assets/admin.css',
			array(),
			SCC_VERSION
		);

		wp_enqueue_script(
			'scc-admin',
			SCC_PLUGIN_URL . 'admin/assets/admin.js',
			array( 'jquery' ),
			SCC_VERSION,
			true
		);
	}

	// -------------------------------------------------------------------------
	// Page renderer
	// -------------------------------------------------------------------------

	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], self::TABS )
			? sanitize_key( $_GET['tab'] )
			: 'general';

		$settings_group = self::TABS[ $active_tab ];
		?>
		<div class="wrap scc-admin">
			<h1><?php esc_html_e( 'Cookie Consent Settings', 'simple-cookie-consent' ); ?></h1>

			<nav class="nav-tab-wrapper">
				<?php foreach ( self::TAB_LABELS as $slug => $label ) : ?>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=scc-cookie-consent&tab=' . $slug ) ); ?>"
					   class="nav-tab <?php echo $active_tab === $slug ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html( $label ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<form method="post" action="options.php" class="scc-settings-form">
				<?php if ( $settings_group ) : ?>
					<?php settings_fields( $settings_group ); ?>
				<?php endif; ?>

				<?php
				$view = SCC_PLUGIN_DIR . 'admin/views/tab-' . $active_tab . '.php';
				if ( file_exists( $view ) ) {
					include $view;
				} else {
					echo '<p>' . esc_html__( 'This tab is coming soon.', 'simple-cookie-consent' ) . '</p>';
				}
				?>

				<?php if ( $settings_group ) : ?>
					<?php submit_button(); ?>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}
}

// Sanitization helper for checkboxes (not a built-in WP function).
function scc_sanitize_checkbox( $value ) {
	return $value ? '1' : '0';
}
