=== Simple Cookie Consent ===
Contributors: sergiorsmaster
Tags: cookie consent, GDPR, CCPA, cookie banner, consent mode
Requires at least: 6.0
Tested up to: 6.9
Stable tag: 0.2.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

GDPR, LGPD & CCPA cookie consent with GTM Consent Mode v2, cookie scanner, and full customisation. Free forever.

== Description ==

**Tired of cookie consent plugins that lock the features you actually need behind a Pro plan or a monthly subscription?**

After years of building websites and constantly searching for a cookie consent solution that *just works* — without paywalls, nag screens, or stripped-down free tiers — I decided to build one myself. I knew exactly what was needed. I just never had the time. Finally, with the help of [Claude Code](https://claude.ai/claude-code), I built it: a plugin that covers what others only provide under a paid plan. **It's free. It's open-source. It's yours.**

---

### What makes Simple Cookie Consent different?

Most free cookie consent plugins give you a basic banner and then ask you to upgrade for anything that actually matters — GTM Consent Mode v2, a cookie scanner, jurisdiction settings, custom branding. Here, **all of that is included at no cost**, forever.

---

### Features

**🍪 Cookie Banner & Preferences Modal**
A clean, accessible cookie banner with a full preferences modal. Visitors can accept all, deny all, or choose category by category — Necessary, Analytics, Marketing, and Functional. All banner text, modal title, description, and button labels are fully editable from the admin.

**⚖️ GDPR, LGPD & CCPA Ready — Free**
Select your jurisdiction from the admin and the plugin adapts automatically:
- **GDPR / LGPD** (opt-in): all non-essential cookies are blocked until the visitor consents.
- **CCPA** (opt-out): cookies load by default with a "Do Not Sell My Personal Information" button.

**📡 Google Tag Manager Consent Mode v2 — Free**
Full GTM Consent Mode v2 support with Basic and Advanced modes. Consent signals (`ad_storage`, `analytics_storage`, `ad_user_data`, `ad_personalization`, `functionality_storage`, `personalization_storage`) are set before GTM loads and updated the moment a visitor makes a choice. Most plugins charge for this. Here it's built in. No extra plugins needed — just enable the integration and paste your standard GTM snippet. Works with any GTM setup, including Google Site Kit.

**🔍 Cookie Scanner — Free**
Automatically discovers cookies set on your website — both server-side (`Set-Cookie` headers) and client-side (JavaScript cookies). Each found cookie is matched against a bundled database of 2,000+ known cookies (powered by the [Open Cookie Database](https://github.com/jkwakman/Open-Cookie-Database)) to pre-fill the service name, category, and duration. No API keys, no cloud dependency.

**🎨 Fully Customisable Appearance**
Choose from 5 banner positions (bottom bar, top bar, bottom-left, bottom-right, centered modal). Customise background, text, and accent colours, or add your own CSS. Upload your logo directly from the Media Library.

**🌍 Translated & Polylang Compatible — Free**
Ships with Portuguese (PT and BR) and German translations out of the box. All banner strings are registered with Polylang's String Translations so your multilingual site can show a fully translated banner without any extra work.

**♿ Accessible by Default**
Focus traps in the banner and modal, proper `aria` attributes, keyboard navigation (Tab, Shift+Tab, Escape), semantic `role="dialog"` markup, and `role="switch"` on toggle inputs. Works well with screen readers and meets WCAG guidelines.

**🛠️ Developer Friendly**
Full JavaScript API (`SimpleCookieConsent.acceptAll()`, `.denyAll()`, `.openPreferences()`, `.openBanner()`, `.hasConsent()`, `.hasInteracted()`), a `scc:consentUpdated` event for custom integrations, CSS custom properties for theming, and a built-in Help tab with the complete reference.

**🔗 WP Consent Level API — Free**
Bridges your visitors' consent to the [WP Consent Level API](https://github.com/wordpress/wp-consent-level-api), making it compatible with Google Site Kit and any other plugin that reads the standard consent interface.

**📋 Cookie List Shortcode**
Add `[scc_cookie_list]` to any page to render a formatted, accessible table of your cookies grouped by category — perfect for your Cookie Policy page. Filter by category with `[scc_cookie_list categories="analytics,marketing"]`.

**⚙️ Preferences Shortcode**
Use `[scc_preferences label="Cookie Settings"]` anywhere to let visitors re-open the preferences modal from a link in your footer or cookie policy page.

**🚩 Floating Preferences Icon**
An optional floating icon lets visitors revisit their choices at any time without hunting for a link.

---

### Built in the open

This plugin was built in the open, with every line of code visible on [GitHub](https://github.com/sergiorsmaster/simple-wp-cookie-consent). If it helps you, **give it a star** ⭐ — it means a lot. If you find a bug or want to contribute a feature, pull requests are very welcome.

*Install and enjoy. It's yours.*

== Installation ==

1. Upload the `simple-cookie-consent` folder to `/wp-content/plugins/`, or install directly via **Plugins → Add New** in your WordPress dashboard.
2. Activate the plugin through the **Plugins** menu.
3. Go to **Settings → Cookie Consent** and configure your banner, jurisdiction, and integrations.

That's it. The banner appears automatically on the frontend once enabled.

== Frequently Asked Questions ==

= Is this plugin really free? Is there a Pro version? =
Yes, completely free. There is no Pro version, no subscription, and no hidden features. Everything described on this page is included.

= Which privacy regulations does it support? =
GDPR (EU), LGPD (Brazil), and CCPA (California). Select your jurisdiction under **Settings → Cookie Consent → Jurisdiction** and the plugin adapts the banner behaviour accordingly.

= Does it support Google Tag Manager Consent Mode v2? =
Yes. Enable it under **Settings → Cookie Consent → Integrations**. Choose Basic mode (tags don't fire before consent) or Advanced mode (tags fire in a cookieless state before consent, enabling data modelling). The plugin injects the `gtag('consent', 'default', {...})` call before GTM loads.

= Does it work with Google Site Kit? =
Yes. Enable the GTM Consent Mode v2 integration and, optionally, install the [WP Consent Level API](https://github.com/wordpress/wp-consent-level-api) plugin. Site Kit will read consent signals automatically.

= Does it work with Polylang? =
Yes. Install Polylang and your banner strings will appear under **Languages → String Translations** for per-language translation.

= How does the cookie scanner work? =
Click **Run Scanner** on the Cookies tab. The plugin fetches your homepage server-side (reading `Set-Cookie` response headers) and reads `document.cookie` client-side. New cookies are matched against a bundled database of 2,000+ known cookies and saved automatically. You can then review and adjust the category for each one.

= Can I add cookies manually? =
Yes. Use the **+ Add Cookie** button on the Cookies tab to add cookies manually with a name, category, service, duration, and description.

= Where is the consent stored? =
Consent is stored in a first-party cookie named `scc_consent` (JSON, 1 year, SameSite=Lax). No data is sent to any external server.

= How do I add a "Cookie Settings" link to my footer? =
Use the `[scc_preferences label="Cookie Settings"]` shortcode anywhere on your site.

= How do I add Facebook Pixel, Hotjar, TikTok or other scripts? =
Do not paste those scripts directly into your WordPress theme or header — they will not be blocked by consent. Instead, add them as tags inside Google Tag Manager and use the built-in consent triggers (e.g. "Analytics Storage Consent Granted") to control when they fire. This plugin sends the consent signals to GTM automatically.

= Does this plugin require Google Site Kit or WP Consent API? =
No. Both are optional. The plugin works standalone — just enable GTM Consent Mode v2 in the Integrations tab and paste the standard GTM snippet into your site's header. Site Kit and WP Consent API are supported automatically if installed, but not required.

= How do I show a cookie table on my Cookie Policy page? =
Add `[scc_cookie_list]` to any page. Use the optional `categories` attribute to filter: `[scc_cookie_list categories="analytics,marketing"]`.

== Screenshots ==

1. Cookie consent banner — bottom bar position
2. Preferences modal with category toggles
3. Admin — General settings tab
4. Admin — Jurisdiction selector (GDPR / LGPD / CCPA)
5. Admin — GTM Consent Mode v2 integration
6. Admin — Cookie list with scanner

== Changelog ==

= 0.1.0 =
* Initial release
* Cookie banner with 5 positions and full customisation
* Preferences modal with per-category toggles and editable text
* GDPR, LGPD, and CCPA jurisdiction modes
* GTM Consent Mode v2 — Basic and Advanced (no extra plugins needed)
* Cookie scanner (server-side + client-side)
* Bundled Open Cookie Database (2,264 entries)
* WP Consent Level API integration
* Polylang compatibility
* Portuguese (PT + BR) and German translations included
* [scc_cookie_list] and [scc_preferences] shortcodes
* Floating preferences icon
* Full JavaScript API (acceptAll, denyAll, openPreferences, openBanner, hasConsent, hasInteracted)
* Accessibility: focus traps, keyboard navigation, ARIA attributes
* Admin Help tab with CSS, shortcode, and JS API reference
* Banner preview mode for admins (?scc_preview=1)
* Debug mode for developers
* Clean uninstall

== Upgrade Notice ==

= 0.1.0 =
Initial release.
