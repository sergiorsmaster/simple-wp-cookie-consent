<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cscc_current = get_option( 'cscc_jurisdiction', 'gdpr' );

$cscc_jurisdictions = array(
	'gdpr' => array(
		'label'   => __( 'GDPR — European Union', 'consentric-cookie-consent' ),
		'model'   => __( 'Opt-in', 'consentric-cookie-consent' ),
		'summary' => __( 'Non-essential cookies are blocked until the visitor explicitly accepts them. Required for websites targeting users in the EU/EEA.', 'consentric-cookie-consent' ),
		'details' => array(
			__( 'Banner shown on first visit', 'consentric-cookie-consent' ),
			__( 'Cookies blocked until consent is granted', 'consentric-cookie-consent' ),
			__( 'Accept All / Deny All / Preferences buttons shown', 'consentric-cookie-consent' ),
			__( 'Consent stored for 1 year', 'consentric-cookie-consent' ),
		),
	),
	'lgpd' => array(
		'label'   => __( 'LGPD — Brazil', 'consentric-cookie-consent' ),
		'model'   => __( 'Opt-in', 'consentric-cookie-consent' ),
		'summary' => __( 'Same opt-in model as GDPR. Required for websites targeting users in Brazil.', 'consentric-cookie-consent' ),
		'details' => array(
			__( 'Banner shown on first visit', 'consentric-cookie-consent' ),
			__( 'Cookies blocked until consent is granted', 'consentric-cookie-consent' ),
			__( 'Accept All / Deny All / Preferences buttons shown', 'consentric-cookie-consent' ),
			__( 'Consent stored for 1 year', 'consentric-cookie-consent' ),
		),
	),
	'ccpa' => array(
		'label'   => __( 'CCPA — United States (California)', 'consentric-cookie-consent' ),
		'model'   => __( 'Opt-out', 'consentric-cookie-consent' ),
		'summary' => __( 'Cookies are allowed by default. Visitors can opt out of the sale of their personal data. Required for businesses targeting California residents above certain thresholds.', 'consentric-cookie-consent' ),
		'details' => array(
			__( 'Banner shown as an informational notice', 'consentric-cookie-consent' ),
			__( 'Cookies are NOT blocked before interaction', 'consentric-cookie-consent' ),
			__( '"Do Not Sell My Personal Information" opt-out shown', 'consentric-cookie-consent' ),
			__( 'Preferences button hidden', 'consentric-cookie-consent' ),
		),
	),
);
?>
<div class="cscc-tab-content">

	<div class="cscc-field cscc-field--top">
		<label class="cscc-field__label">
			<?php esc_html_e( 'Applicable Law', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<p class="description" style="margin-bottom: 16px;">
				<?php esc_html_e( 'Select the privacy law that applies to your website. This controls whether the banner uses an opt-in or opt-out model.', 'consentric-cookie-consent' ); ?>
			</p>

			<div class="cscc-jurisdiction-options">
				<?php foreach ( $cscc_jurisdictions as $cscc_key => $cscc_data ) : ?>
					<label class="cscc-jurisdiction-card <?php echo $cscc_current === $cscc_key ? 'is-selected' : ''; ?>">
						<div class="cscc-jurisdiction-card__header">
							<input
								type="radio"
								name="cscc_jurisdiction"
								value="<?php echo esc_attr( $cscc_key ); ?>"
								<?php checked( $cscc_current, $cscc_key ); ?>
								class="cscc-jurisdiction-radio">
							<span class="cscc-jurisdiction-card__title">
								<?php echo esc_html( $cscc_data['label'] ); ?>
							</span>
							<span class="cscc-jurisdiction-card__model cscc-model--<?php echo esc_attr( $cscc_key ); ?>">
								<?php echo esc_html( $cscc_data['model'] ); ?>
							</span>
						</div>
						<p class="cscc-jurisdiction-card__summary">
							<?php echo esc_html( $cscc_data['summary'] ); ?>
						</p>
						<ul class="cscc-jurisdiction-card__details">
							<?php foreach ( $cscc_data['details'] as $cscc_detail ) : ?>
								<li><?php echo esc_html( $cscc_detail ); ?></li>
							<?php endforeach; ?>
						</ul>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<hr>

	<!-- CCPA opt-out text — shown for all jurisdictions but most relevant to CCPA -->
	<div class="cscc-field cscc-ccpa-field" style="<?php echo $cscc_current !== 'ccpa' ? 'display:none' : ''; ?>">
		<label class="cscc-field__label" for="cscc_ccpa_opt_out_text">
			<?php esc_html_e( '"Do Not Sell" Button Label', 'consentric-cookie-consent' ); ?>
		</label>
		<div class="cscc-field__control">
			<input type="text" id="cscc_ccpa_opt_out_text" name="cscc_ccpa_opt_out_text" class="regular-text"
				value="<?php echo esc_attr( get_option( 'cscc_ccpa_opt_out_text', __( 'Do Not Sell My Personal Information', 'consentric-cookie-consent' ) ) ); ?>">
			<p class="description">
				<?php esc_html_e( 'Label for the opt-out button shown in the banner when CCPA mode is active.', 'consentric-cookie-consent' ); ?>
			</p>
		</div>
	</div>

</div>
