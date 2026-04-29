<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cscc-tab-content">

	<!-- =========================================================
	     CSS Classes & Custom Properties
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'CSS Classes &amp; Custom Properties', 'consentric' ); ?></h2>
	<p><?php esc_html_e( 'Use the WordPress built-in CSS editor (Appearance → Customize → Additional CSS) or enqueue your own stylesheet to target these selectors.', 'consentric' ); ?></p>

	<h3><?php esc_html_e( 'Key selectors', 'consentric' ); ?></h3>
	<table class="widefat striped cscc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Selector', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Description', 'consentric' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr><td><code>.cscc-banner</code></td><td><?php esc_html_e( 'The main banner container', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-banner__title</code></td><td><?php esc_html_e( 'Banner headline text', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-banner__text</code></td><td><?php esc_html_e( 'Banner body text', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-btn--accept</code></td><td><?php esc_html_e( 'Accept All button', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-btn--deny</code></td><td><?php esc_html_e( 'Deny All button', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-btn--preferences</code></td><td><?php esc_html_e( 'Preferences button', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-banner__links</code></td><td><?php esc_html_e( 'Legal page links list (ul). Each link is a li > a.', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-modal__box</code></td><td><?php esc_html_e( 'Preferences modal container', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-preferences-icon</code></td><td><?php esc_html_e( 'Floating cookie icon button', 'consentric' ); ?></td></tr>
			<tr><td><code>.cscc-banner-overlay</code></td><td><?php esc_html_e( 'Full-screen backdrop shown behind the center-modal banner position', 'consentric' ); ?></td></tr>
		</tbody>
	</table>

	<h3><?php esc_html_e( 'CSS custom properties', 'consentric' ); ?></h3>
	<p><?php esc_html_e( 'These are set on :root and control the visual theme. Override them in Custom CSS or with the Appearance settings.', 'consentric' ); ?></p>
	<table class="widefat striped cscc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Property', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Default', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Description', 'consentric' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr><td><code>--cscc-bg</code></td><td><code>#ffffff</code></td><td><?php esc_html_e( 'Banner background color', 'consentric' ); ?></td></tr>
			<tr><td><code>--cscc-text</code></td><td><code>#111111</code></td><td><?php esc_html_e( 'Banner text color', 'consentric' ); ?></td></tr>
			<tr><td><code>--cscc-accent</code></td><td><code>#0073aa</code></td><td><?php esc_html_e( 'Accent color (Accept button, toggles)', 'consentric' ); ?></td></tr>
			<tr><td><code>--cscc-radius</code></td><td><code>6px</code></td><td><?php esc_html_e( 'Border radius for banner and buttons', 'consentric' ); ?></td></tr>
			<tr><td><code>--cscc-border</code></td><td><code>0px solid #dddddd</code></td><td><?php esc_html_e( 'Banner border (width + color)', 'consentric' ); ?></td></tr>
			<tr><td><code>--cscc-btn-border-width</code></td><td><code>1px</code></td><td><?php esc_html_e( 'Button border width (applies to all banner buttons)', 'consentric' ); ?></td></tr>
		</tbody>
	</table>

	<hr>

	<!-- =========================================================
	     Shortcodes
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'Shortcodes', 'consentric' ); ?></h2>

	<h3><code>[cscc_cookie_list]</code></h3>
	<p><?php esc_html_e( 'Renders a table of all cookies grouped by category. Inherits your theme\'s table styles.', 'consentric' ); ?></p>
	<table class="widefat striped cscc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Attribute', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Default', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Description', 'consentric' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>category</code></td>
				<td><em><?php esc_html_e( 'all', 'consentric' ); ?></em></td>
				<td><?php esc_html_e( 'Filter to a single category: necessary, analytics, marketing, or functional.', 'consentric' ); ?></td>
			</tr>
		</tbody>
	</table>
	<p><?php esc_html_e( 'Examples:', 'consentric' ); ?></p>
	<pre class="cscc-help-pre">[cscc_cookie_list]
[cscc_cookie_list category="analytics"]</pre>

	<h3><code>[cscc_preferences]</code></h3>
	<p><?php esc_html_e( 'Renders a link (or button) that opens the preferences modal. Useful in footer menus or privacy pages.', 'consentric' ); ?></p>
	<table class="widefat striped cscc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Attribute', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Default', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Description', 'consentric' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>label</code></td>
				<td><em><?php esc_html_e( 'Cookie Settings', 'consentric' ); ?></em></td>
				<td><?php esc_html_e( 'The visible link text.', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>class</code></td>
				<td><em><?php esc_html_e( '(none)', 'consentric' ); ?></em></td>
				<td><?php esc_html_e( 'Additional CSS class(es) to add to the link element.', 'consentric' ); ?></td>
			</tr>
		</tbody>
	</table>
	<p><?php esc_html_e( 'Examples:', 'consentric' ); ?></p>
	<pre class="cscc-help-pre">[cscc_preferences]
[cscc_preferences label="Manage my cookies" class="footer-link"]</pre>

	<hr>

	<!-- =========================================================
	     JavaScript API
	     ========================================================= -->
	<h2 class="cscc-section-title"><?php esc_html_e( 'JavaScript API', 'consentric' ); ?></h2>
	<p><?php esc_html_e( 'All methods are available on the global', 'consentric' ); ?> <code>window.SimpleCookieConsent</code> <?php esc_html_e( 'object.', 'consentric' ); ?></p>

	<table class="widefat striped cscc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Method / Event', 'consentric' ); ?></th>
				<th><?php esc_html_e( 'Description', 'consentric' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>SimpleCookieConsent.acceptAll()</code></td>
				<td><?php esc_html_e( 'Grant consent for all cookie categories and hide the banner.', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.denyAll()</code></td>
				<td><?php esc_html_e( 'Deny consent for all non-necessary categories and hide the banner.', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.saveConsent( categories )</code></td>
				<td><?php esc_html_e( 'Save a partial consent object. Example:', 'consentric' ); ?> <code>{ analytics: true, marketing: false }</code></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.openBanner()</code></td>
				<td><?php esc_html_e( 'Programmatically show the consent banner (e.g. from a "Review cookie settings" link).', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.openPreferences()</code></td>
				<td><?php esc_html_e( 'Programmatically open the preferences modal.', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.hasConsent( category )</code></td>
				<td><?php esc_html_e( 'Returns true if consent has been granted for the given category (e.g. "analytics").', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.hasInteracted()</code></td>
				<td><?php esc_html_e( 'Returns true if the visitor has already made a consent choice (banner was actioned).', 'consentric' ); ?></td>
			</tr>
			<tr>
				<td><code>cscc:consentUpdated</code> <em><?php esc_html_e( '(CustomEvent on document)', 'consentric' ); ?></em></td>
				<td><?php esc_html_e( 'Fired on document after any consent save. Listen with:', 'consentric' ); ?>
					<br><code>document.addEventListener('cscc:consentUpdated', fn)</code>
				</td>
			</tr>
		</tbody>
	</table>

	<h3><?php esc_html_e( 'Usage example', 'consentric' ); ?></h3>
	<pre class="cscc-help-pre">// Run analytics code only after consent is granted
document.addEventListener('cscc:consentUpdated', function () {
    if ( SimpleCookieConsent.hasConsent('analytics') ) {
        // initialise your analytics library here
    }
});

// Check on page load if consent was already given in a previous visit
if ( SimpleCookieConsent.hasConsent('marketing') ) {
    // load marketing scripts
}</pre>

</div>
