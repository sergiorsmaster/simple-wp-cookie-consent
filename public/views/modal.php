<?php
/**
 * Cookie preferences modal template.
 *
 * Variables available (set by SCC_Public::render_modal()):
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
<div id="scc-modal" class="scc-modal" role="dialog" aria-modal="true" aria-labelledby="scc-modal-title"
	style="display:none">

	<div class="scc-modal__overlay" id="scc-modal-overlay" aria-hidden="true" tabindex="-1"></div>

	<div class="scc-modal__box">

		<button class="scc-modal__close" id="scc-modal-close"
			aria-label="<?php esc_attr_e('Close', 'consentric'); ?>">
			&#10005;
		</button>

		<div class="scc-modal__title" id="scc-modal-title">
			<?php echo esc_html($modal_title); ?>
		</div>

		<div class="scc-modal__intro">
			<?php echo esc_html($modal_intro); ?>
		</div>

		<div class="scc-modal__categories">

			<?php
			$categories = array(
				'necessary' => array(
					'label' => __('Necessary', 'consentric'),
					'description' => __('Required for the website to function properly. These cookies cannot be disabled.', 'consentric'),
					'always_on' => true,
				),
				'analytics' => array(
					'label' => __('Analytics', 'consentric'),
					'description' => __('Help us understand how visitors interact with the website by collecting anonymous data.', 'consentric'),
					'always_on' => false,
				),
				'marketing' => array(
					'label' => __('Marketing', 'consentric'),
					'description' => __('Used to track visitors across websites to display relevant and personalized advertisements.', 'consentric'),
					'always_on' => false,
				),
				'functional' => array(
					'label' => __('Functional', 'consentric'),
					'description' => __('Allow the website to remember your preferences such as language or region.', 'consentric'),
					'always_on' => false,
				),
			);

			foreach ($categories as $key => $cat):
				?>
				<div class="scc-modal__category">
					<div class="scc-modal__category-header">
						<span class="scc-modal__category-name">
							<?php echo esc_html($cat['label']); ?>
						</span>

						<?php if ($cat['always_on']): ?>
							<span class="scc-modal__always-on">
								<?php esc_html_e('Always active', 'consentric'); ?>
							</span>
						<?php else: ?>
							<label class="scc-toggle" aria-label="<?php echo esc_attr($cat['label']); ?>">
								<input type="checkbox" role="switch" class="scc-toggle__input"
									name="scc_<?php echo esc_attr($key); ?>" value="1"
									data-category="<?php echo esc_attr($key); ?>">
								<span class="scc-toggle__slider"></span>
							</label>
						<?php endif; ?>
					</div>

					<p class="scc-modal__category-desc">
						<?php echo esc_html($cat['description']); ?>
					</p>
				</div>
			<?php endforeach; ?>

		</div>

		<div class="scc-modal__footer">
			<button class="scc-btn scc-btn--deny" id="scc-modal-deny">
				<?php echo esc_html($modal_deny_label); ?>
			</button>
			<button class="scc-btn scc-btn--accept" id="scc-modal-save">
				<?php echo esc_html($modal_save_label); ?>
			</button>
		</div>

	</div>
</div>