<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$positions = array(
	'bottom-bar'   => __( 'Bottom Bar (full width)', 'simple-cookie-consent' ),
	'top-bar'      => __( 'Top Bar (full width)', 'simple-cookie-consent' ),
	'bottom-left'  => __( 'Bottom Left (corner box)', 'simple-cookie-consent' ),
	'bottom-right' => __( 'Bottom Right (corner box)', 'simple-cookie-consent' ),
	'center-modal' => __( 'Center Modal', 'simple-cookie-consent' ),
);
?>
<div class="scc-tab-content">

	<!-- Position -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_position">
			<?php esc_html_e( 'Banner Position', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<select id="scc_position" name="scc_position">
				<?php foreach ( $positions as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"
						<?php selected( get_option( 'scc_position', 'bottom-bar' ), $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<hr>

	<!-- Colors -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_color_bg">
			<?php esc_html_e( 'Background Color', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_color_bg" name="scc_color_bg"
				value="<?php echo esc_attr( get_option( 'scc_color_bg', '#ffffff' ) ?: '#ffffff' ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_color_text">
			<?php esc_html_e( 'Text Color', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_color_text" name="scc_color_text"
				value="<?php echo esc_attr( get_option( 'scc_color_text', '#111111' ) ?: '#111111' ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_color_accent">
			<?php esc_html_e( 'Accent Color', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_color_accent" name="scc_color_accent"
				value="<?php echo esc_attr( get_option( 'scc_color_accent', '#0073aa' ) ?: '#0073aa' ); ?>">
			<p class="description">
				<?php esc_html_e( 'Used for the Accept button and toggle switch.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- Custom CSS -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_custom_css">
			<?php esc_html_e( 'Custom CSS', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<textarea id="scc_custom_css" name="scc_custom_css" class="large-text" rows="6"
				placeholder=".scc-banner { ... }"><?php
				echo esc_textarea( get_option( 'scc_custom_css', '' ) );
			?></textarea>
			<p class="description">
				<?php esc_html_e( 'Add custom CSS to override the default banner styles.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

</div>
