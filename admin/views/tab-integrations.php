<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gtm_enabled  = get_option( 'scc_gtm_enabled', '0' );
$gtm_mode     = get_option( 'scc_gtm_mode', 'basic' );
$gtm_wait     = get_option( 'scc_gtm_wait_for_update', '500' );
$debug        = get_option( 'scc_debug', '0' );
?>
<div class="scc-tab-content">

	<!-- =========================================================
	     Google Tag Manager
	     ========================================================= -->
	<h2 class="scc-section-title">
		<?php esc_html_e( 'Google Tag Manager', 'simple-cookie-consent' ); ?>
	</h2>

	<!-- Enable GTM integration -->
	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Enable GTM Consent Mode v2', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<label class="scc-admin-toggle">
				<input type="checkbox" name="scc_gtm_enabled" value="1"
					id="scc_gtm_enabled"
					<?php checked( '1', $gtm_enabled ); ?>>
				<span class="scc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Injects gtag consent defaults before GTM loads and updates signals when the visitor makes a choice.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

	<!-- GTM options — shown only when enabled -->
	<div class="scc-gtm-options" <?php echo $gtm_enabled !== '1' ? 'style="display:none"' : ''; ?>>

		<!-- Mode -->
		<div class="scc-field">
			<label class="scc-field__label">
				<?php esc_html_e( 'Consent Mode', 'simple-cookie-consent' ); ?>
			</label>
			<div class="scc-field__control">
				<div class="scc-radio-group">

					<label class="scc-radio-card <?php echo $gtm_mode === 'basic' ? 'is-selected' : ''; ?>">
						<div class="scc-radio-card__header">
							<input type="radio" name="scc_gtm_mode" value="basic"
								class="scc-gtm-mode-radio"
								<?php checked( $gtm_mode, 'basic' ); ?>>
							<span class="scc-radio-card__title">
								<?php esc_html_e( 'Basic', 'simple-cookie-consent' ); ?>
							</span>
							<span class="scc-badge scc-badge--blue">
								<?php esc_html_e( 'Recommended', 'simple-cookie-consent' ); ?>
							</span>
						</div>
						<p class="scc-radio-card__desc">
							<?php esc_html_e( 'Google tags do not fire before the visitor consents. Strictest compliance, but no data modelling for non-consenting users.', 'simple-cookie-consent' ); ?>
						</p>
					</label>

					<label class="scc-radio-card <?php echo $gtm_mode === 'advanced' ? 'is-selected' : ''; ?>">
						<div class="scc-radio-card__header">
							<input type="radio" name="scc_gtm_mode" value="advanced"
								class="scc-gtm-mode-radio"
								<?php checked( $gtm_mode, 'advanced' ); ?>>
							<span class="scc-radio-card__title">
								<?php esc_html_e( 'Advanced', 'simple-cookie-consent' ); ?>
							</span>
						</div>
						<p class="scc-radio-card__desc">
							<?php esc_html_e( 'Google tags fire in a limited cookieless mode before consent. Enables statistical modelling for non-consenting users. Requires GTM to be configured accordingly.', 'simple-cookie-consent' ); ?>
						</p>
					</label>

				</div>
			</div>
		</div>

		<!-- Wait for update (ms) -->
		<div class="scc-field">
			<label class="scc-field__label" for="scc_gtm_wait_for_update">
				<?php esc_html_e( 'Wait for Update (ms)', 'simple-cookie-consent' ); ?>
			</label>
			<div class="scc-field__control">
				<input type="number" id="scc_gtm_wait_for_update" name="scc_gtm_wait_for_update"
					value="<?php echo esc_attr( $gtm_wait ); ?>"
					min="0" max="5000" step="100" style="width:100px">
				<p class="description">
					<?php esc_html_e( 'How long (in milliseconds) GTM waits for a consent update before firing tags. Default: 500.', 'simple-cookie-consent' ); ?>
				</p>
			</div>
		</div>

		<!-- Signal mapping info -->
		<div class="scc-field">
			<label class="scc-field__label">
				<?php esc_html_e( 'Signal Mapping', 'simple-cookie-consent' ); ?>
			</label>
			<div class="scc-field__control">
				<table class="scc-signal-table widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Cookie Category', 'simple-cookie-consent' ); ?></th>
							<th><?php esc_html_e( 'GTM Signals', 'simple-cookie-consent' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong><?php esc_html_e( 'Necessary', 'simple-cookie-consent' ); ?></strong></td>
							<td><code>security_storage: granted</code></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Analytics', 'simple-cookie-consent' ); ?></strong></td>
							<td><code>analytics_storage</code></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Marketing', 'simple-cookie-consent' ); ?></strong></td>
							<td><code>ad_storage, ad_user_data, ad_personalization</code></td>
						</tr>
						<tr>
							<td><strong><?php esc_html_e( 'Functional', 'simple-cookie-consent' ); ?></strong></td>
							<td><code>functionality_storage, personalization_storage</code></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div><!-- .scc-gtm-options -->

	<hr>

	<!-- =========================================================
	     Site Kit by Google
	     ========================================================= -->
	<h2 class="scc-section-title">
		<?php esc_html_e( 'Site Kit by Google', 'simple-cookie-consent' ); ?>
	</h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Compatibility', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<?php if ( defined( 'GOOGLESITEKIT_VERSION' ) ) : ?>
				<p class="scc-notice scc-notice--success">
					&#10003; <?php esc_html_e( 'Site Kit is active. Consent is passed automatically via GTM Consent Mode v2 signals and the WP Consent API.', 'simple-cookie-consent' ); ?>
				</p>
			<?php else : ?>
				<p class="scc-notice scc-notice--info">
					<?php esc_html_e( 'Site Kit is not active. When installed, it will automatically respect GTM Consent Mode v2 signals set by this plugin.', 'simple-cookie-consent' ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<hr>

	<!-- =========================================================
	     Debug
	     ========================================================= -->
	<h2 class="scc-section-title">
		<?php esc_html_e( 'Debug', 'simple-cookie-consent' ); ?>
	</h2>

	<div class="scc-field">
		<label class="scc-field__label">
			<?php esc_html_e( 'Debug Mode', 'simple-cookie-consent' ); ?>
		</label>
		<div class="scc-field__control">
			<label class="scc-admin-toggle">
				<input type="checkbox" name="scc_debug" value="1"
					<?php checked( '1', $debug ); ?>>
				<span class="scc-admin-toggle__slider"></span>
			</label>
			<p class="description">
				<?php esc_html_e( 'Log all consent actions to the browser console prefixed with [SCC]. Disable on production.', 'simple-cookie-consent' ); ?>
			</p>
		</div>
	</div>

</div>
