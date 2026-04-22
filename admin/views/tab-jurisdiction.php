<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current = get_option( 'scc_jurisdiction', 'gdpr' );

$jurisdictions = array(
	'gdpr' => array(
		'label'   => __( 'GDPR — European Union', 'consentric' ),
		'model'   => __( 'Opt-in', 'consentric' ),
		'summary' => __( 'Non-essential cookies are blocked until the visitor explicitly accepts them. Required for websites targeting users in the EU/EEA.', 'consentric' ),
		'details' => array(
			__( 'Banner shown on first visit', 'consentric' ),
			__( 'Cookies blocked until consent is granted', 'consentric' ),
			__( 'Accept All / Deny All / Preferences buttons shown', 'consentric' ),
			__( 'Consent stored for 1 year', 'consentric' ),
		),
	),
	'lgpd' => array(
		'label'   => __( 'LGPD — Brazil', 'consentric' ),
		'model'   => __( 'Opt-in', 'consentric' ),
		'summary' => __( 'Same opt-in model as GDPR. Required for websites targeting users in Brazil.', 'consentric' ),
		'details' => array(
			__( 'Banner shown on first visit', 'consentric' ),
			__( 'Cookies blocked until consent is granted', 'consentric' ),
			__( 'Accept All / Deny All / Preferences buttons shown', 'consentric' ),
			__( 'Consent stored for 1 year', 'consentric' ),
		),
	),
	'ccpa' => array(
		'label'   => __( 'CCPA — United States (California)', 'consentric' ),
		'model'   => __( 'Opt-out', 'consentric' ),
		'summary' => __( 'Cookies are allowed by default. Visitors can opt out of the sale of their personal data. Required for businesses targeting California residents above certain thresholds.', 'consentric' ),
		'details' => array(
			__( 'Banner shown as an informational notice', 'consentric' ),
			__( 'Cookies are NOT blocked before interaction', 'consentric' ),
			__( '"Do Not Sell My Personal Information" opt-out shown', 'consentric' ),
			__( 'Preferences button hidden', 'consentric' ),
		),
	),
);
?>
<div class="scc-tab-content">

	<div class="scc-field scc-field--top">
		<label class="scc-field__label">
			<?php esc_html_e( 'Applicable Law', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<p class="description" style="margin-bottom: 16px;">
				<?php esc_html_e( 'Select the privacy law that applies to your website. This controls whether the banner uses an opt-in or opt-out model.', 'consentric' ); ?>
			</p>

			<div class="scc-jurisdiction-options">
				<?php foreach ( $jurisdictions as $key => $data ) : ?>
					<label class="scc-jurisdiction-card <?php echo $current === $key ? 'is-selected' : ''; ?>">
						<div class="scc-jurisdiction-card__header">
							<input
								type="radio"
								name="scc_jurisdiction"
								value="<?php echo esc_attr( $key ); ?>"
								<?php checked( $current, $key ); ?>
								class="scc-jurisdiction-radio">
							<span class="scc-jurisdiction-card__title">
								<?php echo esc_html( $data['label'] ); ?>
							</span>
							<span class="scc-jurisdiction-card__model scc-model--<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $data['model'] ); ?>
							</span>
						</div>
						<p class="scc-jurisdiction-card__summary">
							<?php echo esc_html( $data['summary'] ); ?>
						</p>
						<ul class="scc-jurisdiction-card__details">
							<?php foreach ( $data['details'] as $detail ) : ?>
								<li><?php echo esc_html( $detail ); ?></li>
							<?php endforeach; ?>
						</ul>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<hr>

	<!-- CCPA opt-out text — shown for all jurisdictions but most relevant to CCPA -->
	<div class="scc-field scc-ccpa-field" style="<?php echo $current !== 'ccpa' ? 'display:none' : ''; ?>">
		<label class="scc-field__label" for="scc_ccpa_opt_out_text">
			<?php esc_html_e( '"Do Not Sell" Button Label', 'consentric' ); ?>
		</label>
		<div class="scc-field__control">
			<input type="text" id="scc_ccpa_opt_out_text" name="scc_ccpa_opt_out_text" class="regular-text"
				value="<?php echo esc_attr( get_option( 'scc_ccpa_opt_out_text', __( 'Do Not Sell My Personal Information', 'consentric' ) ) ); ?>">
			<p class="description">
				<?php esc_html_e( 'Label for the opt-out button shown in the banner when CCPA mode is active.', 'consentric' ); ?>
			</p>
		</div>
	</div>

</div>
