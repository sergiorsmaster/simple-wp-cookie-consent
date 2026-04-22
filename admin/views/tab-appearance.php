<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$positions = array(
	'bottom-bar'   => __( 'Bottom Bar (full width)', 'consentric' ),
	'top-bar'      => __( 'Top Bar (full width)', 'consentric' ),
	'bottom-left'  => __( 'Bottom Left (corner box)', 'consentric' ),
	'bottom-right' => __( 'Bottom Right (corner box)', 'consentric' ),
	'center-modal' => __( 'Center Modal', 'consentric' ),
);
?>
<div class="scc-tab-content">

	<!-- =========================================================
	     Layout
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Layout', 'consentric' ); ?></h2>

	<!-- Position -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_position">
			<?php esc_html_e( 'Banner Position', 'consentric' ); ?>
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
			<?php esc_html_e( 'Banner Width (px)', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="number" id="scc_banner_max_width" name="scc_banner_max_width"
				value="<?php echo esc_attr( get_option( 'scc_banner_max_width', '200' ) ); ?>"
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
	<h2 class="scc-section-title"><?php esc_html_e( 'Colors', 'consentric' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_color_bg">
			<?php esc_html_e( 'Background Color', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_color_bg" name="scc_color_bg"
				value="<?php echo esc_attr( get_option( 'scc_color_bg', '#ffffff' ) ?: '#ffffff' ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_color_text">
			<?php esc_html_e( 'Text Color', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_color_text" name="scc_color_text"
				value="<?php echo esc_attr( get_option( 'scc_color_text', '#111111' ) ?: '#111111' ); ?>">
		</div>
	</div>

	<div class="scc-field">
		<label class="scc-field__label" for="scc_color_accent">
			<?php esc_html_e( 'Accent Color', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="color" id="scc_color_accent" name="scc_color_accent"
				value="<?php echo esc_attr( get_option( 'scc_color_accent', '#0073aa' ) ?: '#0073aa' ); ?>">
			<p class="description">
				<?php esc_html_e( 'Used for the Accept button and toggle switch.', 'consentric' ); ?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Border & Shape
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Border & Shape', 'consentric' ); ?></h2>

	<!-- Border radius -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_border_radius">
			<?php esc_html_e( 'Corner Radius (px)', 'consentric' ); ?>
		</label>
		<div class="scc-field__control scc-field__control--inline">
			<input type="range" id="scc_border_radius" name="scc_border_radius"
				min="0" max="48" step="1"
				value="<?php echo esc_attr( get_option( 'scc_border_radius', '6' ) ); ?>"
				oninput="document.getElementById('scc_radius_val').textContent = this.value + 'px'">
			<span id="scc_radius_val"><?php echo esc_html( get_option( 'scc_border_radius', '6' ) ); ?>px</span>
			<p class="description">
				<?php esc_html_e( 'Applies to banner, modal, and buttons.', 'consentric' ); ?>
			</p>
		</div>
	</div>

	<!-- Border width -->
	<div class="scc-field">
		<label class="scc-field__label" for="scc_banner_border_width">
			<?php esc_html_e( 'Border Width (px)', 'consentric' ); ?>
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
			<?php esc_html_e( 'Border Color', 'consentric' ); ?>
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
	<h2 class="scc-section-title"><?php esc_html_e( 'Buttons', 'consentric' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Secondary Button Style', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<div class="scc-radio-group">

				<label class="scc-radio-card <?php echo get_option( 'scc_button_style', 'outline' ) === 'outline' ? 'is-selected' : ''; ?>">
					<div class="scc-radio-card__header">
						<input type="radio" name="scc_button_style" value="outline"
							class="scc-btn-style-radio"
							<?php checked( get_option( 'scc_button_style', 'outline' ), 'outline' ); ?>>
						<span class="scc-radio-card__title"><?php esc_html_e( 'Outline', 'consentric' ); ?></span>
						<span class="scc-badge scc-badge--blue"><?php esc_html_e( 'Recommended', 'consentric' ); ?></span>
					</div>
					<p class="scc-radio-card__desc">
						<?php esc_html_e( 'Deny and Preferences buttons appear as outlined buttons alongside Accept.', 'consentric' ); ?>
					</p>
				</label>

				<label class="scc-radio-card <?php echo get_option( 'scc_button_style', 'outline' ) === 'ghost' ? 'is-selected' : ''; ?>">
					<div class="scc-radio-card__header">
						<input type="radio" name="scc_button_style" value="ghost"
							class="scc-btn-style-radio"
							<?php checked( get_option( 'scc_button_style', 'outline' ), 'ghost' ); ?>>
						<span class="scc-radio-card__title"><?php esc_html_e( 'Ghost', 'consentric' ); ?></span>
					</div>
					<p class="scc-radio-card__desc">
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
	<h2 class="scc-section-title"><?php esc_html_e( 'Logo', 'consentric' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Logo Source', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<?php $logo_source = get_option( 'scc_logo_source', 'custom' ); ?>
			<label style="display:block;margin-bottom:6px">
				<input type="radio" name="scc_logo_source" value="none"
					class="scc-logo-source-radio" <?php checked( $logo_source, 'none' ); ?>>
				<?php esc_html_e( 'No logo', 'consentric' ); ?>
			</label>
			<label style="display:block;margin-bottom:6px">
				<input type="radio" name="scc_logo_source" value="site"
					class="scc-logo-source-radio" <?php checked( $logo_source, 'site' ); ?>>
				<?php esc_html_e( 'Use site logo (set in Customizer)', 'consentric' ); ?>
			</label>
			<label style="display:block;margin-bottom:10px">
				<input type="radio" name="scc_logo_source" value="custom"
					class="scc-logo-source-radio" <?php checked( $logo_source, 'custom' ); ?>>
				<?php esc_html_e( 'Custom upload', 'consentric' ); ?>
			</label>
		</div>
	</div>

	<!-- Custom logo upload — shown only when source = custom -->
	<?php $logo_url = get_option( 'scc_logo_url', '' ); ?>
	<div class="scc-field scc-logo-custom-field" <?php echo $logo_source !== 'custom' ? 'style="display:none"' : ''; ?>>
		<label class="scc-field__label">
			<?php esc_html_e( 'Upload Logo', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<div class="scc-media-field">
				<input type="hidden" id="scc_logo_url" name="scc_logo_url"
					value="<?php echo esc_attr( $logo_url ); ?>">
				<button type="button" class="button scc-media-select" data-target="#scc_logo_url">
					<?php esc_html_e( 'Select Image', 'consentric' ); ?>
				</button>
				<img src="<?php echo esc_url( $logo_url ); ?>" class="scc-logo-preview" alt=""
					style="max-height:40px;vertical-align:middle;margin-left:8px;<?php echo $logo_url ? '' : 'display:none'; ?>">
				<button type="button" class="button scc-media-remove" data-target="#scc_logo_url"
					<?php echo $logo_url ? '' : 'style="display:none"'; ?>>
					<?php esc_html_e( 'Remove', 'consentric' ); ?>
				</button>
			</div>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Custom CSS tip
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Custom CSS', 'consentric' ); ?></h2>

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
	<h2 class="scc-section-title"><?php esc_html_e( 'Preview', 'consentric' ); ?></h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Preview Banner', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<a href="<?php echo esc_url( add_query_arg( 'scc_preview', '1', home_url( '/' ) ) ); ?>"
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
