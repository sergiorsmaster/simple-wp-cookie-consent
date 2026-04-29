<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CSCC_Admin {

	/** All tabs in display order, mapped to their settings group. */
	const TABS = array(
		'general'      => 'cscc_general',
		'appearance'   => 'cscc_appearance',
		'jurisdiction' => 'cscc_jurisdiction',
		'integrations' => 'cscc_integrations',
		'cookies'      => null, // no settings group — custom UI
		'help'         => null, // no settings group — documentation only
	);

	/**
	 * Return translatable tab labels.
	 *
	 * Cannot use a const because __() is a function call.
	 *
	 * @return array<string,string>
	 */
	public static function get_tab_labels() {
		return array(
			'general'      => __( 'General', 'consentric' ),
			'appearance'   => __( 'Appearance', 'consentric' ),
			'jurisdiction' => __( 'Jurisdiction', 'consentric' ),
			'integrations' => __( 'Integrations', 'consentric' ),
			'cookies'      => __( 'Cookies', 'consentric' ),
			'help'         => __( 'Help', 'consentric' ),
		);
	}

	public static function init() {
		add_action( 'admin_menu',            array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init',            array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_init',            array( __CLASS__, 'handle_cookie_actions' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		// Cookie scanner ajax endpoints (admin-only)
		add_action( 'wp_ajax_cscc_scan_server',  array( __CLASS__, 'ajax_scan_server' ) );
		add_action( 'wp_ajax_cscc_scan_client',  array( __CLASS__, 'ajax_scan_client' ) );
	}

	// -------------------------------------------------------------------------
	// Scanner ajax handlers
	// -------------------------------------------------------------------------

	public static function ajax_scan_server() {
		check_ajax_referer( 'cscc_scanner_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Forbidden', 403 );
		}

		require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-cookie-scanner.php';
		$result = CSCC_Cookie_Scanner::run_server_scan();

		if ( isset( $result['error'] ) ) {
			wp_send_json_error( $result['error'] );
		}
		wp_send_json_success( $result );
	}

	public static function ajax_scan_client() {
		check_ajax_referer( 'cscc_scanner_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Forbidden', 403 );
		}

		$names = isset( $_POST['cookies'] ) ? array_map( 'sanitize_text_field', wp_unslash( (array) $_POST['cookies'] ) ) : array();

		require_once CSCC_PLUGIN_DIR . 'includes/class-cscc-cookie-scanner.php';
		$result = CSCC_Cookie_Scanner::save_from_client( $names );

		wp_send_json_success( $result );
	}

	// -------------------------------------------------------------------------
	// Cookie CRUD actions
	// -------------------------------------------------------------------------

	public static function handle_cookie_actions() {
		if ( empty( $_REQUEST['page'] ) || $_REQUEST['page'] !== 'cscc-cookie-consent' ) {
			return;
		}
		if ( empty( $_REQUEST['tab'] ) || $_REQUEST['tab'] !== 'cookies' ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $wpdb;
		$table    = $wpdb->prefix . 'cscc_cookies';
		$redirect = admin_url( 'options-general.php?page=cscc-cookie-consent&tab=cookies' );

		// Add or Edit
		if ( ! empty( $_POST['cscc_cookie_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cscc_cookie_nonce'] ) ), 'cscc_save_cookie' ) ) {
				wp_die( 'Security check failed.' );
			}

			$data = array(
				'cookie_name' => sanitize_text_field( wp_unslash( $_POST['cookie_name'] ?? '' ) ),
				'category'    => sanitize_key( wp_unslash( $_POST['category'] ?? 'necessary' ) ),
				'service'     => sanitize_text_field( wp_unslash( $_POST['service'] ?? '' ) ),
				'description' => sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) ),
				'duration'    => sanitize_text_field( wp_unslash( $_POST['duration'] ?? '' ) ),
				'source'      => 'manual',
			);

			$cookie_id = absint( $_POST['cookie_id'] ?? 0 );

			if ( $cookie_id ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->update( $table, $data, array( 'id' => $cookie_id ) );
				set_transient( 'cscc_admin_notice', 'updated', 30 );
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->insert( $table, $data );
				set_transient( 'cscc_admin_notice', 'added', 30 );
			}

			wp_safe_redirect( $redirect );
			exit;
		}

		// Delete
		if ( ! empty( $_GET['action'] ) && 'delete_cookie' === sanitize_key( wp_unslash( $_GET['action'] ) ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ?? '' ) ), 'cscc_delete_cookie' ) ) {
				wp_die( 'Security check failed.' );
			}
			$cookie_id = isset( $_GET['cookie_id'] ) ? absint( $_GET['cookie_id'] ) : 0;
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->delete( $table, array( 'id' => $cookie_id ) );
			set_transient( 'cscc_admin_notice', 'deleted', 30 );
			wp_safe_redirect( $redirect );
			exit;
		}
	}

	// -------------------------------------------------------------------------
	// Menu
	// -------------------------------------------------------------------------

	public static function add_menu() {
		add_options_page(
			__( 'Cookie Consent Settings', 'consentric' ),
			__( 'Cookie Consent', 'consentric' ),
			'manage_options',
			'cscc-cookie-consent',
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
			'cscc_enabled'               => 'cscc_sanitize_checkbox',
			'cscc_banner_title'          => 'sanitize_text_field',
			'cscc_banner_text'           => 'sanitize_textarea_field',
			'cscc_accept_label'          => 'sanitize_text_field',
			'cscc_deny_label'            => 'sanitize_text_field',
			'cscc_preferences_label'     => 'sanitize_text_field',
			'cscc_modal_title'           => 'sanitize_text_field',
			'cscc_modal_intro'           => 'sanitize_textarea_field',
			'cscc_modal_save_label'      => 'sanitize_text_field',
			'cscc_modal_deny_label'      => 'sanitize_text_field',
			'cscc_privacy_policy_page'   => 'absint',
			'cscc_cookie_policy_page'    => 'absint',
			'cscc_imprint_page'          => 'absint',
			'cscc_show_preferences_icon' => 'cscc_sanitize_checkbox',
		) as $option => $cb ) {
			register_setting( 'cscc_general', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Appearance
		foreach ( array(
			'cscc_position'            => 'cscc_sanitize_position',
			'cscc_logo_source'         => 'cscc_sanitize_logo_source',
			'cscc_logo_url'            => 'esc_url_raw',
			'cscc_color_bg'            => 'sanitize_hex_color',
			'cscc_color_text'          => 'sanitize_hex_color',
			'cscc_color_accent'        => 'sanitize_hex_color',
			'cscc_border_radius'       => 'absint',
			'cscc_banner_max_width'    => 'cscc_sanitize_px',
			'cscc_banner_border_width' => 'cscc_sanitize_px',
			'cscc_banner_border_color' => 'sanitize_hex_color',
			'cscc_button_style'        => 'cscc_sanitize_button_style',
		) as $option => $cb ) {
			register_setting( 'cscc_appearance', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Jurisdiction
		foreach ( array(
			'cscc_jurisdiction'      => 'cscc_sanitize_jurisdiction',
			'cscc_ccpa_opt_out_text' => 'sanitize_text_field',
		) as $option => $cb ) {
			register_setting( 'cscc_jurisdiction', $option, array( 'sanitize_callback' => $cb ) );
		}

		// Integrations
		foreach ( array(
			'cscc_gtm_enabled'        => 'cscc_sanitize_checkbox',
			'cscc_gtm_mode'           => 'cscc_sanitize_gtm_mode',
			'cscc_gtm_wait_for_update'=> 'absint',
			'cscc_debug'              => 'cscc_sanitize_checkbox',
		) as $option => $cb ) {
			register_setting( 'cscc_integrations', $option, array( 'sanitize_callback' => $cb ) );
		}
	}

	// -------------------------------------------------------------------------
	// Assets
	// -------------------------------------------------------------------------

	public static function enqueue_scripts( $hook ) {
		if ( 'settings_page_cscc-cookie-consent' !== $hook ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style(
			'cscc-admin',
			CSCC_PLUGIN_URL . 'admin/assets/cscc-admin.css',
			array(),
			CSCC_VERSION
		);

		wp_enqueue_script(
			'cscc-admin',
			CSCC_PLUGIN_URL . 'admin/assets/cscc-admin.js',
			array( 'jquery' ),
			CSCC_VERSION,
			true
		);

		wp_localize_script( 'cscc-admin', 'csccAdmin', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'cscc_scanner_nonce' ),
			'i18n'    => array(
				'scanning'  => __( 'Scanning…', 'consentric' ),
				'scanDone'  => __( 'Scan complete.', 'consentric' ),
				'scanError' => __( 'Scan failed. Please try again.', 'consentric' ),
				'added'     => __( 'new cookie(s) found.', 'consentric' ),
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

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- tab navigation, no data processing
		$active_tab = isset( $_GET['tab'] ) && array_key_exists( sanitize_key( wp_unslash( $_GET['tab'] ) ), self::TABS )
			? sanitize_key( wp_unslash( $_GET['tab'] ) )
			: 'general';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$settings_group = self::TABS[ $active_tab ];
		?>
		<div class="wrap cscc-admin">
			<h1><?php esc_html_e( 'Cookie Consent Settings', 'consentric' ); ?></h1>

			<nav class="nav-tab-wrapper">
				<?php foreach ( self::get_tab_labels() as $slug => $label ) : ?>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=cscc-cookie-consent&tab=' . $slug ) ); ?>"
					   class="nav-tab <?php echo $active_tab === $slug ? 'nav-tab-active' : ''; ?>">
						<?php echo esc_html( $label ); ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<?php if ( $settings_group ) : ?>
			<form method="post" action="options.php" class="cscc-settings-form">
				<?php settings_fields( $settings_group ); ?>
			<?php endif; ?>

				<?php
				$view = CSCC_PLUGIN_DIR . 'admin/views/tab-' . $active_tab . '.php';
				if ( file_exists( $view ) ) {
					include $view;
				} else {
					echo '<p>' . esc_html__( 'This tab is coming soon.', 'consentric' ) . '</p>';
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
function cscc_sanitize_checkbox( $value ) {
	return $value ? '1' : '0';
}

// Sanitization helper for pixel values — returns '' when empty so absint
// doesn't convert blank fields to 0 and break CSS or form min validation.
function cscc_sanitize_px( $value ) {
	$int = absint( $value );
	return $int > 0 ? (string) $int : '';
}

// Whitelist sanitization helpers for enum/select settings.

function cscc_sanitize_position( $value ) {
	$allowed = array( 'bottom-left', 'bottom-right', 'bottom-full', 'top-full', 'center-modal' );
	return in_array( $value, $allowed, true ) ? $value : 'bottom-left';
}

function cscc_sanitize_logo_source( $value ) {
	$allowed = array( '', 'site_icon', 'custom' );
	return in_array( $value, $allowed, true ) ? $value : '';
}

function cscc_sanitize_button_style( $value ) {
	$allowed = array( 'outline', 'ghost' );
	return in_array( $value, $allowed, true ) ? $value : 'outline';
}

function cscc_sanitize_jurisdiction( $value ) {
	$allowed = array( 'gdpr', 'lgpd', 'ccpa', 'notice' );
	return in_array( $value, $allowed, true ) ? $value : 'gdpr';
}

function cscc_sanitize_gtm_mode( $value ) {
	$allowed = array( 'basic', 'advanced' );
	return in_array( $value, $allowed, true ) ? $value : 'basic';
}
