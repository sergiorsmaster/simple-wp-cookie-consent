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
		add_action( 'admin_init',            array( __CLASS__, 'handle_cookie_actions' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		// Cookie scanner ajax endpoints (admin-only)
		add_action( 'wp_ajax_scc_scan_server',  array( __CLASS__, 'ajax_scan_server' ) );
		add_action( 'wp_ajax_scc_scan_client',  array( __CLASS__, 'ajax_scan_client' ) );
	}

	// -------------------------------------------------------------------------
	// Scanner ajax handlers
	// -------------------------------------------------------------------------

	public static function ajax_scan_server() {
		check_ajax_referer( 'scc_scanner_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Forbidden', 403 );
		}

		require_once SCC_PLUGIN_DIR . 'includes/class-scc-cookie-scanner.php';
		$result = SCC_Cookie_Scanner::run_server_scan();

		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result['error'] );
		}
		wp_send_json_success( $result );
	}

	public static function ajax_scan_client() {
		check_ajax_referer( 'scc_scanner_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Forbidden', 403 );
		}

		$names = isset( $_POST['cookies'] ) ? (array) $_POST['cookies'] : array();

		require_once SCC_PLUGIN_DIR . 'includes/class-scc-cookie-scanner.php';
		$result = SCC_Cookie_Scanner::save_from_client( $names );

		wp_send_json_success( $result );
	}

	// -------------------------------------------------------------------------
	// Cookie CRUD actions
	// -------------------------------------------------------------------------

	public static function handle_cookie_actions() {
		if ( empty( $_REQUEST['page'] ) || $_REQUEST['page'] !== 'scc-cookie-consent' ) {
			return;
		}
		if ( empty( $_REQUEST['tab'] ) || $_REQUEST['tab'] !== 'cookies' ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $wpdb;
		$table    = $wpdb->prefix . 'scc_cookies';
		$redirect = admin_url( 'options-general.php?page=scc-cookie-consent&tab=cookies' );

		// Add or Edit
		if ( ! empty( $_POST['scc_cookie_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['scc_cookie_nonce'], 'scc_save_cookie' ) ) {
				wp_die( 'Security check failed.' );
			}

			$data = array(
				'cookie_name' => sanitize_text_field( $_POST['cookie_name'] ?? '' ),
				'category'    => sanitize_key( $_POST['category'] ?? 'necessary' ),
				'service'     => sanitize_text_field( $_POST['service'] ?? '' ),
				'description' => sanitize_textarea_field( $_POST['description'] ?? '' ),
				'duration'    => sanitize_text_field( $_POST['duration'] ?? '' ),
				'source'      => 'manual',
			);

			$cookie_id = absint( $_POST['cookie_id'] ?? 0 );

			if ( $cookie_id ) {
				$wpdb->update( $table, $data, array( 'id' => $cookie_id ) );
				$redirect .= '&scc_msg=updated';
			} else {
				$wpdb->insert( $table, $data );
				$redirect .= '&scc_msg=added';
			}

			wp_redirect( $redirect );
			exit;
		}

		// Delete
		if ( ! empty( $_GET['action'] ) && $_GET['action'] === 'delete_cookie' ) {
			if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'scc_delete_cookie' ) ) {
				wp_die( 'Security check failed.' );
			}
			$wpdb->delete( $table, array( 'id' => absint( $_GET['cookie_id'] ) ) );
			wp_redirect( $redirect . '&scc_msg=deleted' );
			exit;
		}
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
			'scc_accept_label'          => 'sanitize_text_field',
			'scc_deny_label'            => 'sanitize_text_field',
			'scc_preferences_label'     => 'sanitize_text_field',
			'scc_privacy_policy_page'   => 'absint',
			'scc_cookie_policy_page'    => 'absint',
			'scc_imprint_page'          => 'absint',
			'scc_show_preferences_icon' => 'scc_sanitize_checkbox',
		) as $option => $cb ) {
			register_setting( 'scc_general', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Appearance
		foreach ( array(
			'scc_position'            => 'sanitize_text_field',
			'scc_logo_source'         => 'sanitize_text_field',
			'scc_logo_url'            => 'esc_url_raw',
			'scc_color_bg'            => 'sanitize_hex_color',
			'scc_color_text'          => 'sanitize_hex_color',
			'scc_color_accent'        => 'sanitize_hex_color',
			'scc_border_radius'       => 'absint',
			'scc_banner_max_width'    => 'scc_sanitize_px',
			'scc_banner_border_width' => 'scc_sanitize_px',
			'scc_banner_border_color' => 'sanitize_hex_color',
			'scc_button_style'        => 'sanitize_text_field',
			'scc_custom_css'          => 'wp_strip_all_tags',
		) as $option => $cb ) {
			register_setting( 'scc_appearance', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Jurisdiction
		foreach ( array(
			'scc_jurisdiction'      => 'sanitize_text_field',
			'scc_ccpa_opt_out_text' => 'sanitize_text_field',
		) as $option => $cb ) {
			register_setting( 'scc_jurisdiction', $option, array( 'sanitize_callback' => $cb ) );
		}

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

		wp_localize_script( 'scc-admin', 'sccAdmin', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'scc_scanner_nonce' ),
			'i18n'    => array(
				'scanning'  => __( 'Scanning…', 'simple-cookie-consent' ),
				'scanDone'  => __( 'Scan complete.', 'simple-cookie-consent' ),
				'scanError' => __( 'Scan failed. Please try again.', 'simple-cookie-consent' ),
				'added'     => __( 'new cookie(s) found.', 'simple-cookie-consent' ),
			),
		) );
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

			<?php if ( $settings_group ) : ?>
			<form method="post" action="options.php" class="scc-settings-form">
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
			</form>
			<?php endif; ?>
		</div>
		<?php
	}
}

// Sanitization helper for checkboxes (not a built-in WP function).
function scc_sanitize_checkbox( $value ) {
	return $value ? '1' : '0';
}

// Sanitization helper for pixel values — returns '' when empty so absint
// doesn't convert blank fields to 0 and break CSS or form min validation.
function scc_sanitize_px( $value ) {
	$int = absint( $value );
	return $int > 0 ? (string) $int : '';
}
