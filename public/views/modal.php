<?php
/**
 * Cookie preferences modal template.
 *
 * Variables available (set by CSCC_Public::render_modal()):
 *   $jurisdiction     string  'gdpr' | 'lgpd' | 'ccpa'
 *   $privacy_url      string  (may be empty)
 *   $cookie_url       string  (may be empty)
 *   $modal_title      string
 *   $modal_intro      string
 *   $modal_save_label string
 *   $modal_deny_label string
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<div id="cscc-modal" class="cscc-modal" role="dialog" aria-modal="true" aria-labelledby="cscc-modal-title"
	style="display:none">

	<div class="cscc-modal__overlay" id="cscc-modal-overlay" aria-hidden="true" tabindex="-1"></div>

	<div class="cscc-modal__box">

		<button class="cscc-modal__close" id="cscc-modal-close"
			aria-label="<?php esc_attr_e('Close', 'consentric-cookie-consent'); ?>">
			&#10005;
		</button>

		<div class="cscc-modal__title" id="cscc-modal-title">
			<?php echo esc_html($modal_title); ?>
		</div>

		<div class="cscc-modal__intro">
			<?php echo esc_html($modal_intro); ?>
		</div>

		<div class="cscc-modal__categories">

			<?php
			$cscc_categories = array(
				'necessary' => array(
					'label' => __('Necessary', 'consentric-cookie-consent'),
					'description' => __('Required for the website to function properly. These cookies cannot be disabled.', 'consentric-cookie-consent'),
					'always_on' => true,
				),
				'analytics' => array(
					'label' => __('Analytics', 'consentric-cookie-consent'),
					'description' => __('Help us understand how visitors interact with the website by collecting anonymous data.', 'consentric-cookie-consent'),
					'always_on' => false,
				),
				'marketing' => array(
					'label' => __('Marketing', 'consentric-cookie-consent'),
					'description' => __('Used to track visitors across websites to display relevant and personalized advertisements.', 'consentric-cookie-consent'),
					'always_on' => false,
				),
				'functional' => array(
					'label' => __('Functional', 'consentric-cookie-consent'),
					'description' => __('Allow the website to remember your preferences such as language or region.', 'consentric-cookie-consent'),
					'always_on' => false,
				),
			);

			foreach ($cscc_categories as $cscc_key => $cscc_cat):
				?>
				<div class="cscc-modal__category">
					<div class="cscc-modal__category-header">
						<span class="cscc-modal__category-name">
							<?php echo esc_html($cscc_cat['label']); ?>
						</span>

						<?php if ($cscc_cat['always_on']): ?>
							<span class="cscc-modal__always-on">
								<?php esc_html_e('Always active', 'consentric-cookie-consent'); ?>
							</span>
						<?php else: ?>
							<label class="cscc-toggle" aria-label="<?php echo esc_attr($cscc_cat['label']); ?>">
								<input type="checkbox" role="switch" class="cscc-toggle__input"
									name="cscc_<?php echo esc_attr($cscc_key); ?>" value="1"
									data-category="<?php echo esc_attr($cscc_key); ?>">
								<span class="cscc-toggle__slider"></span>
							</label>
						<?php endif; ?>
					</div>

					<p class="cscc-modal__category-desc">
						<?php echo esc_html($cscc_cat['description']); ?>
					</p>
				</div>
			<?php endforeach; ?>

		</div>

		<div class="cscc-modal__footer">
			<button class="cscc-btn cscc-btn--deny" id="cscc-modal-deny">
				<?php echo esc_html($modal_deny_label); ?>
			</button>
			<button class="cscc-btn cscc-btn--accept" id="cscc-modal-save">
				<?php echo esc_html($modal_save_label); ?>
			</button>
		</div>

	</div>
</div>