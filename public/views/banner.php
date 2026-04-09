<?php
/**
 * Cookie consent banner template.
 *
 * Variables available (set by SCC_Public::render_banner()):
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
	<div class="scc-banner-overlay" aria-hidden="true" style="display:none"></div>
<?php endif; ?>
<div id="scc-banner" class="scc-banner scc-position-<?php echo esc_attr($position); ?>" role="dialog"
	aria-modal="true" aria-labelledby="scc-banner-title" tabindex="-1" style="display:none">

	<div class="scc-banner__inner">

		<div class="scc-banner__content">
			<div class="scc-banner__header">
				<?php if (!empty($logo_url)): ?>
					<img class="scc-banner__logo" src="<?php echo esc_url($logo_url); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
				<div class="scc-banner__title" id="scc-banner-title">
					<?php echo esc_html($title); ?>
				</div>
			</div>
			<div class="scc-banner__text">
				<?php echo esc_html($text); ?>
			</div>
		</div>

		<?php if ('ccpa' === $jurisdiction): ?>

			<div class="scc-banner__actions">
				<button class="scc-btn scc-btn--deny" id="scc-deny">
					<?php echo esc_html($ccpa_text); ?>
				</button>
				<button class="scc-btn scc-btn--accept" id="scc-accept">
					<?php echo esc_html($accept_label); ?>
				</button>
			</div>

		<?php else: ?>

			<div class="scc-banner__actions">
				<button class="scc-btn scc-btn--accept" id="scc-accept">
					<?php echo esc_html($accept_label); ?>
				</button>
				<button class="scc-btn scc-btn--deny" id="scc-deny">
					<?php echo esc_html($deny_label); ?>
				</button>
				<button class="scc-btn scc-btn--preferences" id="scc-preferences">
					<?php echo esc_html($prefs_label); ?>
				</button>
			</div>

		<?php endif; ?>

		<?php
		$links = array();
		if (!empty($privacy_url)) {
			$links[] = '<li><a href="' . esc_url($privacy_url) . '">' . esc_html__('Privacy Policy', 'simple-cookie-consent') . '</a></li>';
		}
		if (!empty($cookie_url)) {
			$links[] = '<li><a href="' . esc_url($cookie_url) . '">' . esc_html__('Cookie Policy', 'simple-cookie-consent') . '</a></li>';
		}
		if (!empty($imprint_url)) {
			$links[] = '<li><a href="' . esc_url($imprint_url) . '">' . esc_html__('Imprint', 'simple-cookie-consent') . '</a></li>';
		}
		if (!empty($links)):
			?>
			<ul class="scc-banner__links">
				<?php echo wp_kses_post( implode( "\n\t\t\t\t", $links ) ); ?>
			</ul>
		<?php endif; ?>

	</div>
</div>