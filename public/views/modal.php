<?php
/**
 * Cookie preferences modal template.
 *
 * Variables available (set by SCC_Public::render_modal()):
 *   $jurisdiction string  'gdpr' | 'lgpd' | 'ccpa'
 *   $privacy_url  string  (may be empty)
 *   $cookie_url   string  (may be empty)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="scc-modal"
	class="scc-modal"
	role="dialog"
	aria-modal="true"
	aria-labelledby="scc-modal-title"
	style="display:none">

	<div class="scc-modal__overlay" id="scc-modal-overlay"></div>

	<div class="scc-modal__box">

		<button class="scc-modal__close" id="scc-modal-close" aria-label="<?php esc_attr_e( 'Close', 'simple-cookie-consent' ); ?>">
			&#10005;
		</button>

		<h2 class="scc-modal__title" id="scc-modal-title">
			<?php esc_html_e( 'Cookie Preferences', 'simple-cookie-consent' ); ?>
		</h2>

		<p class="scc-modal__intro">
			<?php esc_html_e( 'Choose which cookies you allow. You can change your preferences at any time.', 'simple-cookie-consent' ); ?>
		</p>

		<div class="scc-modal__categories">

			<?php
			$categories = array(
				'necessary' => array(
					'label'       => __( 'Necessary', 'simple-cookie-consent' ),
					'description' => __( 'Required for the website to function properly. These cookies cannot be disabled.', 'simple-cookie-consent' ),
					'always_on'   => true,
				),
				'analytics'  => array(
					'label'       => __( 'Analytics', 'simple-cookie-consent' ),
					'description' => __( 'Help us understand how visitors interact with the website by collecting anonymous data.', 'simple-cookie-consent' ),
					'always_on'   => false,
				),
				'marketing'  => array(
					'label'       => __( 'Marketing', 'simple-cookie-consent' ),
					'description' => __( 'Used to track visitors across websites to display relevant and personalized advertisements.', 'simple-cookie-consent' ),
					'always_on'   => false,
				),
				'functional' => array(
					'label'       => __( 'Functional', 'simple-cookie-consent' ),
					'description' => __( 'Allow the website to remember your preferences such as language or region.', 'simple-cookie-consent' ),
					'always_on'   => false,
				),
			);

			foreach ( $categories as $key => $cat ) :
			?>
				<div class="scc-modal__category">
					<div class="scc-modal__category-header">
						<span class="scc-modal__category-name">
							<?php echo esc_html( $cat['label'] ); ?>
						</span>

						<?php if ( $cat['always_on'] ) : ?>
							<span class="scc-modal__always-on">
								<?php esc_html_e( 'Always active', 'simple-cookie-consent' ); ?>
							</span>
						<?php else : ?>
							<label class="scc-toggle" aria-label="<?php echo esc_attr( $cat['label'] ); ?>">
								<input
									type="checkbox"
									class="scc-toggle__input"
									name="scc_<?php echo esc_attr( $key ); ?>"
									value="1"
									data-category="<?php echo esc_attr( $key ); ?>">
								<span class="scc-toggle__slider"></span>
							</label>
						<?php endif; ?>
					</div>

					<p class="scc-modal__category-desc">
						<?php echo esc_html( $cat['description'] ); ?>
					</p>
				</div>
			<?php endforeach; ?>

		</div>

		<div class="scc-modal__footer">
			<button class="scc-btn scc-btn--deny" id="scc-modal-deny">
				<?php esc_html_e( 'Deny All', 'simple-cookie-consent' ); ?>
			</button>
			<button class="scc-btn scc-btn--accept" id="scc-modal-save">
				<?php esc_html_e( 'Save Preferences', 'simple-cookie-consent' ); ?>
			</button>
		</div>

	</div>
</div>
