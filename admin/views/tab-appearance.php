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

	<!-- =========================================================
	     Layout
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Layout', 'simple-cookie-consent' ); ?></h2>

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

	<!-- Max width -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_banner_max_width">
			<?php esc_html_e( 'Banner Width (px)', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="number" id="scc_banner_max_width" name="scc_banner_max_width"
				value="<?php echo esc_attr( get_option( 'scc_banner_max_width', '200' ) ); ?>"
				min="0" max="1200" step="10" style="width:100px">
			<p class="description">
				<?php esc_html_e( 'Applies to corner and center modal positions (px). Set to 0 or leave empty to use the default width.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Colors
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Colors', 'simple-cookie-consent' ); ?></h2>

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

	<!-- =========================================================
	     Border & Shape
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Border & Shape', 'simple-cookie-consent' ); ?></h2>

	<!-- Border radius -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_border_radius">
			<?php esc_html_e( 'Corner Radius (px)', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control scc-field__control--inline">
			<input type="range" id="scc_border_radius" name="scc_border_radius"
				min="0" max="24" step="1"
				value="<?php echo esc_attr( get_option( 'scc_border_radius', '6' ) ); ?>"
				oninput="document.getElementById('scc_radius_val').textContent = this.value + 'px'">
			<span id="scc_radius_val"><?php echo esc_html( get_option( 'scc_border_radius', '6' ) ); ?>px</span>
			<p class="description">
				<?php esc_html_e( 'Applies to banner, modal, and buttons.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<!-- Border width -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_banner_border_width">
			<?php esc_html_e( 'Border Width (px)', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="number" id="scc_banner_border_width" name="scc_banner_border_width"
				value="<?php echo esc_attr( get_option( 'scc_banner_border_width', '0' ) ); ?>"
				min="0" max="10" step="1" style="width:70px">
		</div>
	</div>

	<!-- Border color -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_banner_border_color">
			<?php esc_html_e( 'Border Color', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_banner_border_color" name="scc_banner_border_color"
				value="<?php echo esc_attr( get_option( 'scc_banner_border_color', '#dddddd' ) ?: '#dddddd' ); ?>">
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Buttons
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Buttons', 'simple-cookie-consent' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Secondary Button Style', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<div class="scc-radio-group">

				<label class="scc-radio-card <?php echo get_option( 'scc_button_style', 'outline' ) === 'outline' ? 'is-selected' : ''; ?>">
					<div class="scc-radio-card__header">
						<input type="radio" name="scc_button_style" value="outline"
							class="scc-btn-style-radio"
							<?php checked( get_option( 'scc_button_style', 'outline' ), 'outline' ); ?>>
						<span class="scc-radio-card__title"><?php esc_html_e( 'Outline', 'simple-cookie-consent' ); ?></span>
						<span class="scc-badge scc-badge--blue"><?php esc_html_e( 'Recommended', 'simple-cookie-consent' ); ?></span>
					</div>
					<p class="scc-radio-card__desc">
						<?php esc_html_e( 'Deny and Preferences buttons appear as outlined buttons alongside Accept.', 'simple-cookie-consent' ); ?>
					</p>
				</label>

				<label class="scc-radio-card <?php echo get_option( 'scc_button_style', 'outline' ) === 'ghost' ? 'is-selected' : ''; ?>">
					<div class="scc-radio-card__header">
						<input type="radio" name="scc_button_style" value="ghost"
							class="scc-btn-style-radio"
							<?php checked( get_option( 'scc_button_style', 'outline' ), 'ghost' ); ?>>
						<span class="scc-radio-card__title"><?php esc_html_e( 'Ghost', 'simple-cookie-consent' ); ?></span>
					</div>
					<p class="scc-radio-card__desc">
						<?php esc_html_e( 'Deny and Preferences appear as text links with underline, keeping the Accept button as the only visual button.', 'simple-cookie-consent' ); ?>
					</p>
				</label>

			</div>
			<p class="description" style="margin-top:8px">
				<?php esc_html_e( 'The Accept button is always filled with the Accent color.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Logo
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Logo', 'simple-cookie-consent' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Logo Source', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<?php $logo_source = get_option( 'scc_logo_source', 'custom' ); ?>
			<label style="display:block;margin-bottom:6px">
				<input type="radio" name="scc_logo_source" value="none"
					class="scc-logo-source-radio" <?php checked( $logo_source, 'none' ); ?>>
				<?php esc_html_e( 'No logo', 'simple-cookie-consent' ); ?>
			</label>
			<label style="display:block;margin-bottom:6px">
				<input type="radio" name="scc_logo_source" value="site"
					class="scc-logo-source-radio" <?php checked( $logo_source, 'site' ); ?>>
				<?php esc_html_e( 'Use site logo (set in Customizer)', 'simple-cookie-consent' ); ?>
			</label>
			<label style="display:block;margin-bottom:10px">
				<input type="radio" name="scc_logo_source" value="custom"
					class="scc-logo-source-radio" <?php checked( $logo_source, 'custom' ); ?>>
				<?php esc_html_e( 'Custom upload', 'simple-cookie-consent' ); ?>
			</label>
		</div>
	</div>

	<!-- Custom logo upload — shown only when source = custom -->
	<?php $logo_url = get_option( 'scc_logo_url', '' ); ?>
	<div class="scc-field scc-logo-custom-field" <?php echo $logo_source !== 'custom' ? 'style="display:none"' : ''; ?>>
		<label class="scc-field__label">
			<?php esc_html_e( 'Upload Logo', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<div class="scc-media-field">
				<input type="hidden" id="scc_logo_url" name="scc_logo_url"
					value="<?php echo esc_attr( $logo_url ); ?>">
				<button type="button" class="button scc-media-select" data-target="#scc_logo_url">
					<?php esc_html_e( 'Select Image', 'simple-cookie-consent' ); ?>
				</button>
				<img src="<?php echo esc_url( $logo_url ); ?>" class="scc-logo-preview" alt=""
					style="max-height:40px;vertical-align:middle;margin-left:8px;<?php echo $logo_url ? '' : 'display:none'; ?>">
				<button type="button" class="button scc-media-remove" data-target="#scc_logo_url"
					<?php echo $logo_url ? '' : 'style="display:none"'; ?>>
					<?php esc_html_e( 'Remove', 'simple-cookie-consent' ); ?>
				</button>
			</div>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Custom CSS
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Custom CSS', 'simple-cookie-consent' ); ?></h2>

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
