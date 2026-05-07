<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cscc_gtm_enabled = get_option( 'cscc_gtm_enabled', '0' );
$cscc_gtm_mode    = get_option( 'cscc_gtm_mode', 'basic' );
$cscc_gtm_wait    = get_option( 'cscc_gtm_wait_for_update', '500' );
$cscc_debug       = get_option( 'cscc_debug', '0' );
?>
<div class="cscc-tab-content">

	<!-- =========================================================
	     Google Tag Manager
	     ========================================================= -->
	<h2 class="cscc-section-title">
		<?php esc_html_e( 'Google Tag Manager', 'consentric-cookie-consent' ); ?>
	</h2>

	<!-- Enable GTM integration -->
	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Enable GTM Consent Mode v2', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<label class="cscc-admin-toggle">
				<input type="checkbox" name="cscc_gtm_enabled" value="1"
					id="cscc_gtm_enabled"
					<?php checked( '1', $cscc_gtm_enabled ); ?>>
				<span class="cscc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Injects gtag consent defaults before GTM loads and updates signals when the visitor makes a choice.', 'consentric-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<!-- GTM options — shown only when enabled -->
	<div class="cscc-gtm-options" <?php echo $cscc_gtm_enabled !== '1' ? 'style="display:none"' : ''; ?>>

		<!-- Mode -->
		<div class="cscc-field">
			<label class="cscc-field__label">
				<?php esc_html_e( 'Consent Mode', 'consentric-cookie-consent' ); ?>
			</label>
			<div class="cscc-field__control">
				<div class="cscc-radio-group">

					<label class="cscc-radio-card <?php echo $cscc_gtm_mode === 'basic' ? 'is-selected' : ''; ?>">
						<div class="cscc-radio-card__header">
							<input type="radio" name="cscc_gtm_mode" value="basic"
								class="cscc-gtm-mode-radio"
								<?php checked( $cscc_gtm_mode, 'basic' ); ?>>
							<span class="cscc-radio-card__title">
								<?php esc_html_e( 'Basic', 'consentric-cookie-consent' ); ?>
							</span>
							<span class="cscc-badge cscc-badge--blue">
								<?php esc_html_e( 'Recommended', 'consentric-cookie-consent' ); ?>
							</span>
						</div>
						<p class="cscc-radio-card__desc">
							<?php esc_html_e( 'Google tags do not fire before the visitor consents. Strictest compliance, but no data modelling for non-consenting users.', 'consentric-cookie-consent' ); ?>
						</p>
					</label>

					<label class="cscc-radio-card <?php echo $cscc_gtm_mode === 'advanced' ? 'is-selected' : ''; ?>">
						<div class="cscc-radio-card__header">
							<input type="radio" name="cscc_gtm_mode" value="advanced"
								class="cscc-gtm-mode-radio"
								<?php checked( $cscc_gtm_mode, 'advanced' ); ?>>
							<span class="cscc-radio-card__title">
								<?php esc_html_e( 'Advanced', 'consentric-cookie-consent' ); ?>
							</span>
						</div>
						<p class="cscc-radio-card__desc">
							<?php esc_html_e( 'Google tags fire in a limited cookieless mode before consent. Enables statistical modelling for non-consenting users. Requires GTM to be configured accordingly.', 'consentric-cookie-consent' ); ?>
						</p>
					</label>

				</div>
			</div>
		</div>

		<!-- Wait for update (ms) -->
		<div class="cscc-field">
			<label class="cscc-field__label" for="cscc_gtm_wait_for_update">
				<?php esc_html_e( 'Wait for Update (ms)', 'consentric-cookie-consent' ); ?>
			</label>
			<div class="cscc-field__control">
				<input type="number" id="cscc_gtm_wait_for_update" name="cscc_gtm_wait_for_update"
					value="<?php echo esc_attr( $cscc_gtm_wait ); ?>"
					min="0" max="5000" step="100" style="width:100px">
				<p class="description">
					<?php esc_html_e( 'How long (in milliseconds) GTM waits for a consent update before firing tags. Default: 500.', 'consentric-cookie-consent' ); ?>
				</p>
			</div>
		</div>

		<!-- Signal mapping info -->
		<div class="cscc-field">
			<label class="cscc-field__label">
				<?php esc_html_e( 'Signal Mapping', 'consentric-cookie-consent' ); ?>
			</label>
			<div class="cscc-field__control">
				<table class="cscc-signal-table widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Cookie Category', 'consentric-cookie-consent' ); ?></th>
							<th><?php esc_html_e( 'GTM Signals', 'consentric-cookie-consent' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong><?php esc_html_e( 'Necessary', 'consentric-cookie-consent' ); ?></strong></td>
							<td><code>security_storage: granted</code></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Analytics', 'consentric-cookie-consent' ); ?></strong></td>
							<td><code>analytics_storage</code></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Marketing', 'consentric-cookie-consent' ); ?></strong></td>
							<td><code>ad_storage, ad_user_data, ad_personalization</code></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Functional', 'consentric-cookie-consent' ); ?></strong></td>
							<td><code>functionality_storage, personalization_storage</code></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div><!-- .cscc-gtm-options -->

	<hr>

	<!-- =========================================================
	     Site Kit by Google
	     ========================================================= -->
	<h2 class="cscc-section-title">
		<?php esc_html_e( 'Site Kit by Google', 'consentric-cookie-consent' ); ?>
	</h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Compatibility', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php if ( defined( 'GOOGLESITEKIT_VERSION' ) ) : ?>
				<p class="cscc-notice cscc-notice--success">
					&#10003; <?php esc_html_e( 'Site Kit is active. Consent is passed automatically via GTM Consent Mode v2 signals and the WP Consent API.', 'consentric-cookie-consent' ); ?>
				</p>
			<?php else : ?>
				<p class="cscc-notice cscc-notice--info">
					<?php esc_html_e( 'Site Kit is not active. When installed, it will automatically respect GTM Consent Mode v2 signals set by this plugin.', 'consentric-cookie-consent' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     WP Consent Level API
	     ========================================================= -->
	<h2 class="cscc-section-title">
		<?php esc_html_e( 'WP Consent Level API', 'consentric-cookie-consent' ); ?>
	</h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Compatibility', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php if ( CSCC_WP_Consent_API::is_active() ) : ?>
				<p class="cscc-notice cscc-notice--success">
					&#10003; <?php esc_html_e( 'WP Consent Level API is active. Consent is synced automatically on every page load.', 'consentric-cookie-consent' ); ?>
				</p>
			<?php else : ?>
				<p class="cscc-notice cscc-notice--info">
					<?php
					printf(
						/* translators: 1: opening <a> tag, 2: closing </a> tag */
						esc_html__( 'WP Consent Level API is not active. %1$sInstall the plugin%2$s to allow third-party plugins to read consent without extra configuration.', 'consentric-cookie-consent' ),
						'<a href="https://github.com/wordpress/wp-consent-level-api" target="_blank" rel="noopener noreferrer">',
						'</a>'
					);
					?>
				</p>
			<?php endif; ?>
			<p class="description" style="margin-top:8px">
				<?php
				printf(
					/* translators: 1: opening <a> tag, 2: closing </a> tag */
					esc_html__( 'The %1$sWP Consent Level API%2$s is a standard interface that lets plugins like Site Kit read consent without knowing anything about this plugin.', 'consentric-cookie-consent' ),
					'<a href="https://github.com/wordpress/wp-consent-level-api" target="_blank" rel="noopener noreferrer">',
					'</a>'
				);
				?>
			</p>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Polylang
	     ========================================================= -->
	<h2 class="cscc-section-title">
		<?php esc_html_e( 'Polylang', 'consentric-cookie-consent' ); ?>
	</h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Compatibility', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<?php if ( CSCC_Polylang::is_active() ) : ?>
				<p class="cscc-notice cscc-notice--success">
					&#10003; <?php esc_html_e( 'Polylang is active. Banner strings are registered for translation under Languages → String Translations.', 'consentric-cookie-consent' ); ?>
				</p>
			<?php else : ?>
				<p class="cscc-notice cscc-notice--info">
					<?php esc_html_e( 'Polylang is not active. When installed, banner title, text, and button labels will be translatable per language.', 'consentric-cookie-consent' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Custom Scripts
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Custom Scripts', 'consentric-cookie-consent' ); ?></h2>

	<div class="notice notice-info inline" style="margin: 0 0 16px; padding: 10px 14px;">
		<p style="margin:0 0 10px;">
			<strong><?php esc_html_e( 'Need to fire Facebook Pixel, TikTok, Hotjar or other scripts based on consent?', 'consentric-cookie-consent' ); ?></strong>
		</p>
		<p style="margin:0 0 10px;">
			<?php esc_html_e( 'Do not paste those script tags directly into your WordPress theme or header. Scripts added outside of GTM will not be blocked or controlled by this plugin\'s consent mechanism.', 'consentric-cookie-consent' ); ?>
		</p>
		<p style="margin:0 0 10px;">
			<?php esc_html_e( 'Instead, add them as tags inside Google Tag Manager and use the built-in consent triggers (e.g. "Analytics Storage Consent Granted") to control when they fire. This way, scripts only load after the visitor grants consent for the matching category.', 'consentric-cookie-consent' ); ?>
		</p>
		<p style="margin:0 0 10px;">
			<?php echo wp_kses(
				sprintf(
					/* translators: %1$s opening link tag, %2$s closing link tag */
					__( '%1$sLearn how to use GTM with Consent Mode v2 →%2$s', 'consentric-cookie-consent' ),
					'<a href="https://developers.google.com/tag-platform/security/guides/consent?hl=en#gtm-and-consent-mode" target="_blank" rel="noopener noreferrer">',
					'</a>'
				),
				array( 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array() ) )
			); ?>
		</p>
		<p style="margin:0 0 10px; color: #646970; font-size: 12px;">
			<?php esc_html_e( 'You do not need Google Site Kit or the WP Consent API plugin for this to work. Just enable GTM Consent Mode v2 above and paste the standard GTM snippet into your site\'s header — this plugin handles the rest.', 'consentric-cookie-consent' ); ?>
		</p>
		<p style="margin:0; color: #646970; font-size: 12px;">
			<?php esc_html_e( 'A built-in Script Manager (no GTM required) is planned for a future release.', 'consentric-cookie-consent' ); ?>
		</p>
	</div>

	<hr>

	<!-- =========================================================
	     Debug
	     ========================================================= -->
	<h2 class="cscc-section-title">
		<?php esc_html_e( 'Debug', 'consentric-cookie-consent' ); ?>
	</h2>

	<div class="cscc-field">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Debug Mode', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<label class="cscc-admin-toggle">
				<input type="checkbox" name="cscc_debug" value="1"
					<?php checked( '1', $cscc_debug ); ?>>
				<span class="cscc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Log all consent actions to the browser console prefixed with [CSCC]. Disable on production.', 'consentric-cookie-consent' ); ?>
			</p>
		</div>
	</div>

</div>
