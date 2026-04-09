<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="scc-tab-content">

	<!-- Enable banner -->
	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Enable Cookie Banner', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<label class="scc-admin-toggle">
				<input type="checkbox" name="scc_enabled" value="1"
					<?php checked( '1', get_option( 'scc_enabled', '1' ) ); ?>>
				<span class="scc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Show the cookie consent banner on the frontend.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- Banner title -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_banner_title">
			<?php esc_html_e( 'Banner Title', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_banner_title" name="scc_banner_title" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_banner_title', __( 'We use cookies', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<!-- Banner text -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_banner_text">
			<?php esc_html_e( 'Banner Text', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<textarea id="scc_banner_text" name="scc_banner_text" class="large-text" rows="3"><?php
				echo esc_textarea( get_option( 'scc_banner_text', __( 'We use cookies to improve your experience on our website. Please choose your cookie preferences below.', 'simple-cookie-consent' ) ) );
			?></textarea>
		</div>
	</div>

	<!-- Button labels -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_accept_label">
			<?php esc_html_e( 'Accept Button Label', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_accept_label" name="scc_accept_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_accept_label', __( 'Accept All', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_deny_label">
			<?php esc_html_e( 'Deny Button Label', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_deny_label" name="scc_deny_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_deny_label', __( 'Deny All', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_preferences_label">
			<?php esc_html_e( 'Preferences Button Label', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_preferences_label" name="scc_preferences_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_preferences_label', __( 'Preferences', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<hr>

	<!-- Preferences dialog text -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Preferences Dialog', 'simple-cookie-consent' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_modal_title">
			<?php esc_html_e( 'Dialog Title', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_modal_title" name="scc_modal_title" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_modal_title', __( 'Cookie Preferences', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_modal_intro">
			<?php esc_html_e( 'Dialog Description', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<textarea id="scc_modal_intro" name="scc_modal_intro" class="large-text" rows="2"><?php
				echo esc_textarea( get_option( 'scc_modal_intro', __( 'Choose which cookies you allow. You can change your preferences at any time.', 'simple-cookie-consent' ) ) );
			?></textarea>
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_modal_save_label">
			<?php esc_html_e( 'Save Button Label', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_modal_save_label" name="scc_modal_save_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_modal_save_label', __( 'Save Preferences', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_modal_deny_label">
			<?php esc_html_e( 'Deny Button Label', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_modal_deny_label" name="scc_modal_deny_label" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_modal_deny_label', __( 'Deny All', 'simple-cookie-consent' ) ) ); ?>">
		</div>
	</div>

	<hr>

	<!-- Legal pages -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_privacy_policy_page">
			<?php esc_html_e( 'Privacy Policy Page', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<?php wp_dropdown_pages( array(
				'name'             => 'scc_privacy_policy_page',
				'id'               => 'scc_privacy_policy_page',
				'selected'         => absint( get_option( 'scc_privacy_policy_page', 0 ) ),
				'show_option_none' => esc_html__( '— None —', 'simple-cookie-consent' ),
				'option_none_value'=> 0,
			) ); ?>
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_cookie_policy_page">
			<?php esc_html_e( 'Cookie Policy Page', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<?php wp_dropdown_pages( array(
				'name'             => 'scc_cookie_policy_page',
				'id'               => 'scc_cookie_policy_page',
				'selected'         => absint( get_option( 'scc_cookie_policy_page', 0 ) ),
				'show_option_none' => esc_html__( '— None —', 'simple-cookie-consent' ),
				'option_none_value'=> 0,
			) ); ?>
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_imprint_page">
			<?php esc_html_e( 'Imprint Page', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<?php wp_dropdown_pages( array(
				'name'             => 'scc_imprint_page',
				'id'               => 'scc_imprint_page',
				'selected'         => absint( get_option( 'scc_imprint_page', 0 ) ),
				'show_option_none' => esc_html__( '— None —', 'simple-cookie-consent' ),
				'option_none_value'=> 0,
			) ); ?>
		</div>
	</div>

	<hr>

	<!-- Floating icon -->
	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Floating Preferences Icon', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<label class="scc-admin-toggle">
				<input type="checkbox" name="scc_show_preferences_icon" value="1"
					<?php checked( '1', get_option( 'scc_show_preferences_icon', '1' ) ); ?>>
				<span class="scc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Show a small cookie icon on the page after consent is saved, so visitors can change their preferences at any time. You can also use the [scc_preferences] shortcode instead.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

</div>
