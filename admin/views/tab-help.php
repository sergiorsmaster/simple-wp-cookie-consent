<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="scc-tab-content">

	<!-- =========================================================
	     CSS Classes & Custom Properties
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'CSS Classes &amp; Custom Properties', 'simple-cookie-consent' ); ?></h2>
	<p><?php esc_html_e( 'Use the Appearance tab\'s Custom CSS field to target these selectors, or enqueue your own stylesheet.', 'simple-cookie-consent' ); ?></p>

	<h3><?php esc_html_e( 'Key selectors', 'simple-cookie-consent' ); ?></h3>
	<table class="widefat striped scc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Selector', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Description', 'simple-cookie-consent' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr><td><code>.scc-banner</code></td><td><?php esc_html_e( 'The main banner container', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-banner__title</code></td><td><?php esc_html_e( 'Banner headline text', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-banner__text</code></td><td><?php esc_html_e( 'Banner body text', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-btn--accept</code></td><td><?php esc_html_e( 'Accept All button', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-btn--deny</code></td><td><?php esc_html_e( 'Deny All button', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-btn--preferences</code></td><td><?php esc_html_e( 'Preferences button', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-banner__links a</code></td><td><?php esc_html_e( 'Legal page links (Privacy Policy, Cookie Policy, Imprint)', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-modal__box</code></td><td><?php esc_html_e( 'Preferences modal container', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>.scc-preferences-icon</code></td><td><?php esc_html_e( 'Floating cookie icon button', 'simple-cookie-consent' ); ?></td></tr>
		</tbody>
	</table>

	<h3><?php esc_html_e( 'CSS custom properties', 'simple-cookie-consent' ); ?></h3>
	<p><?php esc_html_e( 'These are set on :root and control the visual theme. Override them in Custom CSS or with the Appearance settings.', 'simple-cookie-consent' ); ?></p>
	<table class="widefat striped scc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Property', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Default', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Description', 'simple-cookie-consent' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr><td><code>--scc-bg</code></td><td><code>#ffffff</code></td><td><?php esc_html_e( 'Banner background color', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>--scc-text</code></td><td><code>#111111</code></td><td><?php esc_html_e( 'Banner text color', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>--scc-accent</code></td><td><code>#0073aa</code></td><td><?php esc_html_e( 'Accent color (Accept button, toggles)', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>--scc-radius</code></td><td><code>6px</code></td><td><?php esc_html_e( 'Border radius for banner and buttons', 'simple-cookie-consent' ); ?></td></tr>
			<tr><td><code>--scc-border</code></td><td><code>0px solid #dddddd</code></td><td><?php esc_html_e( 'Banner border (width + color)', 'simple-cookie-consent' ); ?></td></tr>
		</tbody>
	</table>

	<hr>

	<!-- =========================================================
	     Shortcodes
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'Shortcodes', 'simple-cookie-consent' ); ?></h2>

	<h3><code>[scc_cookie_list]</code></h3>
	<p><?php esc_html_e( 'Renders a table of all cookies grouped by category. Inherits your theme\'s table styles.', 'simple-cookie-consent' ); ?></p>
	<table class="widefat striped scc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Attribute', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Default', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Description', 'simple-cookie-consent' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>category</code></td>
				<td><em><?php esc_html_e( 'all', 'simple-cookie-consent' ); ?></em></td>
				<td><?php esc_html_e( 'Filter to a single category: necessary, analytics, marketing, or functional.', 'simple-cookie-consent' ); ?></td>
			</tr>
		</tbody>
	</table>
	<p><?php esc_html_e( 'Examples:', 'simple-cookie-consent' ); ?></p>
	<pre class="scc-help-pre">[scc_cookie_list]
[scc_cookie_list category="analytics"]</pre>

	<h3><code>[scc_preferences]</code></h3>
	<p><?php esc_html_e( 'Renders a link (or button) that opens the preferences modal. Useful in footer menus or privacy pages.', 'simple-cookie-consent' ); ?></p>
	<table class="widefat striped scc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Attribute', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Default', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Description', 'simple-cookie-consent' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>label</code></td>
				<td><em><?php esc_html_e( 'Cookie Settings', 'simple-cookie-consent' ); ?></em></td>
				<td><?php esc_html_e( 'The visible link text.', 'simple-cookie-consent' ); ?></td>
			</tr>
			<tr>
				<td><code>class</code></td>
				<td><em><?php esc_html_e( '(none)', 'simple-cookie-consent' ); ?></em></td>
				<td><?php esc_html_e( 'Additional CSS class(es) to add to the link element.', 'simple-cookie-consent' ); ?></td>
			</tr>
		</tbody>
	</table>
	<p><?php esc_html_e( 'Examples:', 'simple-cookie-consent' ); ?></p>
	<pre class="scc-help-pre">[scc_preferences]
[scc_preferences label="Manage my cookies" class="footer-link"]</pre>

	<hr>

	<!-- =========================================================
	     JavaScript API
	     ========================================================= -->
	<h2 class="scc-section-title"><?php esc_html_e( 'JavaScript API', 'simple-cookie-consent' ); ?></h2>
	<p><?php esc_html_e( 'All methods are available on the global', 'simple-cookie-consent' ); ?> <code>window.SimpleCookieConsent</code> <?php esc_html_e( 'object.', 'simple-cookie-consent' ); ?></p>

	<table class="widefat striped scc-help-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Method / Event', 'simple-cookie-consent' ); ?></th>
				<th><?php esc_html_e( 'Description', 'simple-cookie-consent' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><code>SimpleCookieConsent.acceptAll()</code></td>
				<td><?php esc_html_e( 'Grant consent for all cookie categories and hide the banner.', 'simple-cookie-consent' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.denyAll()</code></td>
				<td><?php esc_html_e( 'Deny consent for all non-necessary categories and hide the banner.', 'simple-cookie-consent' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.saveConsent( categories )</code></td>
				<td><?php esc_html_e( 'Save a partial consent object. Example:', 'simple-cookie-consent' ); ?> <code>{ analytics: true, marketing: false }</code></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.openPreferences()</code></td>
				<td><?php esc_html_e( 'Programmatically open the preferences modal.', 'simple-cookie-consent' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.hasConsent( category )</code></td>
				<td><?php esc_html_e( 'Returns true if consent has been granted for the given category (e.g. "analytics").', 'simple-cookie-consent' ); ?></td>
			</tr>
			<tr>
				<td><code>SimpleCookieConsent.hasInteracted()</code></td>
				<td><?php esc_html_e( 'Returns true if the visitor has already made a consent choice (banner was actioned).', 'simple-cookie-consent' ); ?></td>
			</tr>
			<tr>
				<td><code>scc:consentUpdated</code> <em><?php esc_html_e( '(CustomEvent on document)', 'simple-cookie-consent' ); ?></em></td>
				<td><?php esc_html_e( 'Fired on document after any consent save. Listen with:', 'simple-cookie-consent' ); ?>
					<br><code>document.addEventListener('scc:consentUpdated', fn)</code>
				</td>
			</tr>
		</tbody>
	</table>

	<h3><?php esc_html_e( 'Usage example', 'simple-cookie-consent' ); ?></h3>
	<pre class="scc-help-pre">// Run analytics code only after consent is granted
document.addEventListener('scc:consentUpdated', function () {
    if ( SimpleCookieConsent.hasConsent('analytics') ) {
        // initialise your analytics library here
    }
});

// Check on page load if consent was already given in a previous visit
if ( SimpleCookieConsent.hasConsent('marketing') ) {
    // load marketing scripts
}</pre>

</div>
