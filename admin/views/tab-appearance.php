<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cscc_positions = array(
	'bottom-bar'   => __( 'Bottom Bar (full width)', 'consentric' ),
	'top-bar'      => __( 'Top Bar (full width)', 'consentric' ),
	'bottom-left'  => __( 'Bottom Left (corner box)', 'consentric' ),
	'bottom-right' => __( 'Bottom Right (corner box)', 'consentric' ),
	'center-modal' => __( 'Center Modal', 'consentric' ),
);
?>
<div class="cscc-tab-content">

	<!-- =========================================================
	     Layout
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Layout', 'consentric' ); ?></h2>

	<!-- Position -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_position">
			<?php esc_html_e( 'Banner Position', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<select id="cscc_position" name="cscc_position">
				<?php foreach ( $cscc_positions as $cscc_value => $cscc_label ) : ?>
					<option value="<?php echo esc_attr( $cscc_value ); ?>"
						<?php selected( get_option( 'cscc_position', 'bottom-bar' ), $cscc_value ); ?>>
						<?php echo esc_html( $cscc_label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<!-- Max width -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_banner_max_width">
			<?php esc_html_e( 'Banner Width (px)', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="number" id="cscc_banner_max_width" name="cscc_banner_max_width"
				value="<?php echo esc_attr( get_option( 'cscc_banner_max_width', '200' ) ); ?>"
				min="0" max="1200" step="10" style="width:100px">
			<p class="description">
				<?php esc_html_e( 'Applies to corner and center modal positions (px). Set to 0 or leave empty to use the default width.', 'consentric' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Colors
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Colors', 'consentric' ); ?></h2>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_color_bg">
			<?php esc_html_e( 'Background Color', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="color" id="cscc_color_bg" name="cscc_color_bg"
				value="<?php echo esc_attr( get_option( 'cscc_color_bg', '#ffffff' ) ?: '#ffffff' ); ?>">
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_color_text">
			<?php esc_html_e( 'Text Color', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="color" id="cscc_color_text" name="cscc_color_text"
				value="<?php echo esc_attr( get_option( 'cscc_color_text', '#111111' ) ?: '#111111' ); ?>">
		</div>
	</div>

	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_color_accent">
			<?php esc_html_e( 'Accent Color', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="color" id="cscc_color_accent" name="cscc_color_accent"
				value="<?php echo esc_attr( get_option( 'cscc_color_accent', '#0073aa' ) ?: '#0073aa' ); ?>">
			<p class="description">
				<?php esc_html_e( 'Used for the Accept button and toggle switch.', 'consentric' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Border & Shape
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Border & Shape', 'consentric' ); ?></h2>

	<!-- Border radius -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_border_radius">
			<?php esc_html_e( 'Corner Radius (px)', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control cscc-field__control--inline">
			<input type="range" id="cscc_border_radius" name="cscc_border_radius"
				min="0" max="48" step="1"
				value="<?php echo esc_attr( get_option( 'cscc_border_radius', '6' ) ); ?>"
				oninput="document.getElementById('cscc_radius_val').textContent = this.value + 'px'">
			<span id="cscc_radius_val"><?php echo esc_html( get_option( 'cscc_border_radius', '6' ) ); ?>px</span>
			<p class="description">
				<?php esc_html_e( 'Applies to banner, modal, and buttons.', 'consentric' ); ?>
			</p>
		</div>
	</div>

	<!-- Border width -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_banner_border_width">
			<?php esc_html_e( 'Border Width (px)', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="number" id="cscc_banner_border_width" name="cscc_banner_border_width"
				value="<?php echo esc_attr( get_option( 'cscc_banner_border_width', '0' ) ); ?>"
				min="0" max="10" step="1" style="width:70px">
		</div>
	</div>

	<!-- Border color -->
	<div class="cscc-field">
		<label class="cscc-field__label" for="cscc_banner_border_color">
			<?php esc_html_e( 'Border Color', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="color" id="cscc_banner_border_color" name="cscc_banner_border_color"
				value="<?php echo esc_attr( get_option( 'cscc_banner_border_color', '#dddddd' ) ?: '#dddddd' ); ?>">
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Buttons
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Buttons', 'consentric' ); ?></h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Secondary Button Style', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<div class="cscc-radio-group">

				<label class="cscc-radio-card <?php echo get_option( 'cscc_button_style', 'outline' ) === 'outline' ? 'is-selected' : ''; ?>">
					<div class="cscc-radio-card__header">
						<input type="radio" name="cscc_button_style" value="outline"
							class="cscc-btn-style-radio"
							<?php checked( get_option( 'cscc_button_style', 'outline' ), 'outline' ); ?>>
						<span class="cscc-radio-card__title"><?php esc_html_e( 'Outline', 'consentric' ); ?></span>
						<span class="cscc-badge cscc-badge--blue"><?php esc_html_e( 'Recommended', 'consentric' ); ?></span>
					</div>
					<p class="cscc-radio-card__desc">
						<?php esc_html_e( 'Deny and Preferences buttons appear as outlined buttons alongside Accept.', 'consentric' ); ?>
					</p>
				</label>

				<label class="cscc-radio-card <?php echo get_option( 'cscc_button_style', 'outline' ) === 'ghost' ? 'is-selected' : ''; ?>">
					<div class="cscc-radio-card__header">
						<input type="radio" name="cscc_button_style" value="ghost"
							class="cscc-btn-style-radio"
							<?php checked( get_option( 'cscc_button_style', 'outline' ), 'ghost' ); ?>>
						<span class="cscc-radio-card__title"><?php esc_html_e( 'Ghost', 'consentric' ); ?></span>
					</div>
					<p class="cscc-radio-card__desc">
						<?php esc_html_e( 'Deny and Preferences appear as text links with underline, keeping the Accept button as the only visual button.', 'consentric' ); ?>
					</p>
				</label>

			</div>
			<p class="description" style="margin-top:8px">
				<?php esc_html_e( 'The Accept button is always filled with the Accent color.', 'consentric' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Logo
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Logo', 'consentric' ); ?></h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Logo Source', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php $cscc_logo_source = get_option( 'cscc_logo_source', 'custom' ); ?>
			<label style="display:block;margin-bottom:6px">
				<input type="radio" name="cscc_logo_source" value="none"
					class="cscc-logo-source-radio" <?php checked( $cscc_logo_source, 'none' ); ?>>
				<?php esc_html_e( 'No logo', 'consentric' ); ?>
			</label>
			<label style="display:block;margin-bottom:6px">
				<input type="radio" name="cscc_logo_source" value="site"
					class="cscc-logo-source-radio" <?php checked( $cscc_logo_source, 'site' ); ?>>
				<?php esc_html_e( 'Use site logo (set in Customizer)', 'consentric' ); ?>
			</label>
			<label style="display:block;margin-bottom:10px">
				<input type="radio" name="cscc_logo_source" value="custom"
					class="cscc-logo-source-radio" <?php checked( $cscc_logo_source, 'custom' ); ?>>
				<?php esc_html_e( 'Custom upload', 'consentric' ); ?>
			</label>
		</div>
	</div>

	<!-- Custom logo upload — shown only when source = custom -->
	<?php $cscc_logo_url = get_option( 'cscc_logo_url', '' ); ?>
	<div class="cscc-field cscc-logo-custom-field" <?php echo $cscc_logo_source !== 'custom' ? 'style="display:none"' : ''; ?>>
		<label class="cscc-field__label">
			<?php esc_html_e( 'Upload Logo', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<div class="cscc-media-field">
				<input type="hidden" id="cscc_logo_url" name="cscc_logo_url"
					value="<?php echo esc_attr( $cscc_logo_url ); ?>">
				<button type="button" class="button cscc-media-select" data-target="#cscc_logo_url">
					<?php esc_html_e( 'Select Image', 'consentric' ); ?>
				</button>
				<img src="<?php echo esc_url( $cscc_logo_url ); ?>" class="cscc-logo-preview" alt=""
					style="max-height:40px;vertical-align:middle;margin-left:8px;<?php echo $cscc_logo_url ? '' : 'display:none'; ?>">
				<button type="button" class="button cscc-media-remove" data-target="#cscc_logo_url"
					<?php echo $cscc_logo_url ? '' : 'style="display:none"'; ?>>
					<?php esc_html_e( 'Remove', 'consentric' ); ?>
				</button>
			</div>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Custom CSS tip
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Custom CSS', 'consentric' ); ?></h2>

	<div class="notice notice-info inline" style="margin:0 0 16px;">
		<p>
			<?php
			printf(
				/* translators: 1: opening <strong> tag, 2: closing </strong> tag, 3: Appearance → Customize → Additional CSS path */
				esc_html__( 'To add custom CSS overrides, use the %1$sWordPress built-in CSS editor%2$s at %3$s. You can target selectors listed in the Help tab.', 'consentric' ),
				'<strong>',
				'</strong>',
				'<code>' . esc_html__( 'Appearance → Customize → Additional CSS', 'consentric' ) . '</code>'
			);
			?>
		</p>
	</div>

	<hr>

	<!-- =========================================================
	     Banner Preview
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Preview', 'consentric' ); ?></h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Preview Banner', 'consentric' ); ?>
		</label>
		<div class="cscc-field__control">
			<a href="<?php echo esc_url( add_query_arg( 'cscc_preview', '1', home_url( '/' ) ) ); ?>"
				target="_blank"
				class="button button-secondary">
				<?php esc_html_e( 'Open Preview', 'consentric' ); ?>
			</a>
			<p class="description">
				<?php esc_html_e( 'Opens your site homepage in a new tab and forces the cookie banner to appear, even if you have already given consent. This preview is only visible to logged-in administrators — regular visitors are never affected by this URL parameter.', 'consentric' ); ?>
			</p>
		</div>
	</div>

</div>
