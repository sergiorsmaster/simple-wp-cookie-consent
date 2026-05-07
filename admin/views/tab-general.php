<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cscc-tab-content">

	<!-- Enable banner -->
	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Enable Cookie Banner', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<label class="cscc-admin-toggle">
				<input type="checkbox" name="cscc_enabled" value="1"
					<?php checked( '1', get_option( 'cscc_enabled', '1' ) ); ?>>
				<span class="cscc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Show the cookie consent banner on the frontend.', 'consentric-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- Banner title -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_banner_title">
			<?php esc_html_e( 'Banner Title', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_banner_title" name="cscc_banner_title" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_banner_title', __( 'We use cookies', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<!-- Banner text -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_banner_text">
			<?php esc_html_e( 'Banner Text', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<textarea id="cscc_banner_text" name="cscc_banner_text" class="large-text" rows="3"><?php
				echo esc_textarea( get_option( 'cscc_banner_text', __( 'We use cookies to improve your experience on our website. Please choose your cookie preferences below.', 'consentric-cookie-consent' ) ) );
			?></textarea>
		</div>
	</div>

	<!-- Button labels -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_accept_label">
			<?php esc_html_e( 'Accept Button Label', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_accept_label" name="cscc_accept_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_accept_label', __( 'Accept All', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_deny_label">
			<?php esc_html_e( 'Deny Button Label', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_deny_label" name="cscc_deny_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_deny_label', __( 'Deny All', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_preferences_label">
			<?php esc_html_e( 'Preferences Button Label', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_preferences_label" name="cscc_preferences_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_preferences_label', __( 'Preferences', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<hr>

	<!-- Preferences dialog text -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Preferences Dialog', 'consentric-cookie-consent' ); ?></h2>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_modal_title">
			<?php esc_html_e( 'Dialog Title', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_modal_title" name="cscc_modal_title" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_modal_title', __( 'Cookie Preferences', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_modal_intro">
			<?php esc_html_e( 'Dialog Description', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<textarea id="cscc_modal_intro" name="cscc_modal_intro" class="large-text" rows="2"><?php
				echo esc_textarea( get_option( 'cscc_modal_intro', __( 'Choose which cookies you allow. You can change your preferences at any time.', 'consentric-cookie-consent' ) ) );
			?></textarea>
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_modal_save_label">
			<?php esc_html_e( 'Save Button Label', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_modal_save_label" name="cscc_modal_save_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_modal_save_label', __( 'Save Preferences', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_modal_deny_label">
			<?php esc_html_e( 'Deny Button Label', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_modal_deny_label" name="cscc_modal_deny_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_modal_deny_label', __( 'Deny All', 'consentric-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<hr>

	<!-- Legal pages -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_privacy_policy_page">
			<?php esc_html_e( 'Privacy Policy Page', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php wp_dropdown_pages( array(
				'name'             => 'cscc_privacy_policy_page',
				'id'               => 'cscc_privacy_policy_page',
				'selected'         => absint( get_option( 'cscc_privacy_policy_page', 0 ) ),
				'show_option_none' => esc_html__( '— None —', 'consentric-cookie-consent' ),
				'option_none_value'=> 0,
			) ); ?>
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_cookie_policy_page">
			<?php esc_html_e( 'Cookie Policy Page', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php wp_dropdown_pages( array(
				'name'             => 'cscc_cookie_policy_page',
				'id'               => 'cscc_cookie_policy_page',
				'selected'         => absint( get_option( 'cscc_cookie_policy_page', 0 ) ),
				'show_option_none' => esc_html__( '— None —', 'consentric-cookie-consent' ),
				'option_none_value'=> 0,
			) ); ?>
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_imprint_page">
			<?php esc_html_e( 'Imprint Page', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php wp_dropdown_pages( array(
				'name'             => 'cscc_imprint_page',
				'id'               => 'cscc_imprint_page',
				'selected'         => absint( get_option( 'cscc_imprint_page', 0 ) ),
				'show_option_none' => esc_html__( '— None —', 'consentric-cookie-consent' ),
				'option_none_value'=> 0,
			) ); ?>
		</div>
	</div>

	<hr>

	<!-- Floating icon -->
	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Floating Preferences Icon', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<label class="cscc-admin-toggle">
				<input type="checkbox" name="cscc_show_preferences_icon" value="1"
					<?php checked( '1', get_option( 'cscc_show_preferences_icon', '1' ) ); ?>>
				<span class="cscc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Show a small cookie icon on the page after consent is saved, so visitors can change their preferences at any time. You can also use the [cscc_preferences] shortcode instead.', 'consentric-cookie-consent' ); ?>
			</p>
		</div>
	</div>

</div>
