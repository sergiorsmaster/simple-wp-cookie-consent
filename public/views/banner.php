<?php
/**
 * Cookie consent banner template.
 *
 * Variables available (set by CSCC_Public::render_banner()):
 *   $position     string  e.g. 'bottom-bar'
 *   $jurisdiction string  'gdpr' | 'lgpd' | 'ccpa'
 *   $title        string
 *   $text         string
 *   $logo_url     string  (may be empty)
 *   $accept_label string
 *   $deny_label   string
 *   $prefs_label  string
 *   $privacy_url  string  (may be empty)
 *   $cookie_url   string  (may be empty)
 *   $imprint_url  string  (may be empty)
 *   $ccpa_text    string
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<?php if ('center-modal' === $position): ?>
	<div class="cscc-banner-overlay" aria-hidden="true" style="display:none"></div>
<?php endif; ?>
<div id="cscc-banner" class="cscc-banner cscc-position-<?php echo esc_attr($position); ?>" role="dialog"
	aria-modal="true" aria-labelledby="cscc-banner-title" tabindex="-1" style="display:none">

	<div class="cscc-banner__inner">

		<div class="cscc-banner__content">
			<div class="cscc-banner__header">
				<?php if (!empty($logo_url)): ?>
					<img class="cscc-banner__logo" src="<?php echo esc_url($logo_url); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
				<div class="cscc-banner__title" id="cscc-banner-title">
					<?php echo esc_html($title); ?>
				</div>
			</div>
			<div class="cscc-banner__text">
				<?php echo esc_html($text); ?>
			</div>
		</div>

		<?php if ('ccpa' === $jurisdiction): ?>

			<div class="cscc-banner__actions">
				<button class="cscc-btn cscc-btn--deny" id="cscc-deny">
					<?php echo esc_html($ccpa_text); ?>
				</button>
				<button class="cscc-btn cscc-btn--accept" id="cscc-accept">
					<?php echo esc_html($accept_label); ?>
				</button>
			</div>

		<?php else: ?>

			<div class="cscc-banner__actions">
				<button class="cscc-btn cscc-btn--accept" id="cscc-accept">
					<?php echo esc_html($accept_label); ?>
				</button>
				<button class="cscc-btn cscc-btn--deny" id="cscc-deny">
					<?php echo esc_html($deny_label); ?>
				</button>
				<button class="cscc-btn cscc-btn--preferences" id="cscc-preferences">
					<?php echo esc_html($prefs_label); ?>
				</button>
			</div>

		<?php endif; ?>

		<?php
		$cscc_links = array();
		if (!empty($privacy_url)) {
			$cscc_links[] = '<li><a href="' . esc_url($privacy_url) . '">' . esc_html__('Privacy Policy', 'consentric-cookie-consent') . '</a></li>';
		}
		if (!empty($cookie_url)) {
			$cscc_links[] = '<li><a href="' . esc_url($cookie_url) . '">' . esc_html__('Cookie Policy', 'consentric-cookie-consent') . '</a></li>';
		}
		if (!empty($imprint_url)) {
			$cscc_links[] = '<li><a href="' . esc_url($imprint_url) . '">' . esc_html__('Imprint', 'consentric-cookie-consent') . '</a></li>';
		}
		if (!empty($cscc_links)):
			?>
			<ul class="cscc-banner__links">
				<?php echo wp_kses_post( implode( "\n\t\t\t\t", $cscc_links ) ); ?>
			</ul>
		<?php endif; ?>

	</div>
</div>