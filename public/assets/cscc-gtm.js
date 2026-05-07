/**
 * CSCC — GTM Consent Mode v2 bridge (Basic + Advanced mode)
 *
 * Translates CSCC consent categories into GTM consent signals and calls
 * gtag('consent', 'update', ...) whenever the visitor's choices change.
 *
 * Signal mapping:
 *   analytics  → analytics_storage
 *   marketing  → ad_storage, ad_user_data, ad_personalization
 *   functional → functionality_storage, personalization_storage
 *   necessary  → security_storage (always 'granted')
 *
 * Modes (set via csccSettings.gtmMode):
 *
 *   basic    — Google tags do NOT fire before consent. After consent,
 *              tags fire normally. Strictest compliance, some data loss
 *              for non-consenting users.
 *
 *   advanced — Google tags fire immediately in a limited, cookieless mode.
 *              After consent update, full data collection resumes.
 *              Additionally pushes a custom dataLayer event ('cscc_consent_update')
 *              so GTM triggers can react to consent changes and Google can
 *              use statistical modeling for non-consenting users.
 *
 * The gtag('consent', 'default', {...}) call is handled server-side by PHP
 * (class-cscc-public.php) and is injected before this script loads.
 */

(function () {

	'use strict';

	var log = window.SimpleCookieConsent && window.SimpleCookieConsent.log
		? window.SimpleCookieConsent.log.bind(window.SimpleCookieConsent)
		: function () { };
	var gtmMode = window.csccSettings && window.csccSettings.gtmMode
		? window.csccSettings.gtmMode
		: 'basic';

	// Ensure dataLayer and gtag are available (GTM may not be present on page).
	window.dataLayer = window.dataLayer || [];
	function gtag() { window.dataLayer.push(arguments); }

	log('GTM mode:', gtmMode);

	/**
	 * Convert CSCC consent categories to GTM signal map.
	 *
	 * @param  {Object} consent  e.g. { necessary: true, analytics: false, ... }
	 * @return {Object}          GTM consent signals
	 */
	function toGtmSignals(consent) {
		function v(granted) { return granted ? 'granted' : 'denied'; }

		return {
			analytics_storage: v(consent.analytics),
			ad_storage: v(consent.marketing),
			ad_user_data: v(consent.marketing),
			ad_personalization: v(consent.marketing),
			functionality_storage: v(consent.functional),
			personalization_storage: v(consent.functional),
			security_storage: 'granted',
		};
	}

	/**
	 * Push a consent update to GTM.
	 * In Advanced mode, also pushes a custom dataLayer event so GTM triggers
	 * can react and Google can model data for non-consenting users.
	 *
	 * @param {Object} consent  CSCC consent object
	 */
	function updateGtmConsent(consent) {
		var signals = toGtmSignals(consent);
		log('GTM consent update →', signals);
		gtag('consent', 'update', signals);

		if (gtmMode === 'advanced') {
			window.dataLayer.push({
				event: 'cscc_consent_update',
				consent: signals,
			});
			log('GTM advanced: pushed cscc_consent_update event to dataLayer');
		}
	}

	// On page load: if the visitor already has a stored consent, update GTM
	// immediately so Google tags reflect their previous choice without waiting.
	var existing = window.SimpleCookieConsent && window.SimpleCookieConsent.getConsent();
	if (existing) {
		log('GTM: restoring consent from cookie on page load');
		updateGtmConsent(existing);
	} else {
		log('GTM: no existing consent cookie — defaults remain (all denied)');
	}

	// On every future consent change (Accept / Deny / Preferences), update GTM.
	document.addEventListener('cscc:consentUpdated', function (e) {
		log('GTM: cscc:consentUpdated received');
		updateGtmConsent(e.detail);
	});

})();
