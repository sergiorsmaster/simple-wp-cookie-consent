/**
 * SCC Banner — show/hide logic
 *
 * Reads the consent cookie on page load and decides whether to show the banner.
 * Wires Accept / Deny buttons to SCC_Consent_Store helpers.
 * The Preferences button is wired in FEAT-07 (preferences modal).
 */

(function () {

	'use strict';

	var SCC = window.SimpleCookieConsent;
	if ( ! SCC ) return;

	var banner  = document.getElementById( 'scc-banner' );
	if ( ! banner ) return;

	var overlay = document.querySelector( '.scc-banner-overlay' );

	// Show banner (and overlay if present) only if visitor hasn't chosen yet.
	if ( ! SCC.hasInteracted() ) {
		banner.style.display = '';
		if ( overlay ) overlay.style.display = '';
		SCC.log( 'Banner: showing (no prior consent)' );
	} else {
		SCC.log( 'Banner: hidden (consent already stored)' );
	}

	// Hide banner + overlay whenever consent is saved.
	document.addEventListener( 'scc:consentUpdated', function () {
		banner.style.display = 'none';
		if ( overlay ) overlay.style.display = 'none';
		SCC.log( 'Banner: hidden after consent update' );
	} );

	// Accept All button
	var btnAccept = document.getElementById( 'scc-accept' );
	if ( btnAccept ) {
		btnAccept.addEventListener( 'click', function () {
			SCC.log( 'Banner: Accept All clicked' );
			SCC.acceptAll();
		} );
	}

	// Deny All button
	var btnDeny = document.getElementById( 'scc-deny' );
	if ( btnDeny ) {
		btnDeny.addEventListener( 'click', function () {
			SCC.log( 'Banner: Deny All clicked' );
			SCC.denyAll();
		} );
	}

	// Preferences button — opens modal
	var btnPrefs = document.getElementById( 'scc-preferences' );
	if ( btnPrefs ) {
		btnPrefs.addEventListener( 'click', function () {
			SCC.log( 'Banner: Preferences clicked' );
			document.dispatchEvent( new CustomEvent( 'scc:openPreferences' ) );
		} );
	}

	// Floating preferences icon — show after consent is stored
	var prefIcon = document.getElementById( 'scc-preferences-icon' );
	if ( prefIcon ) {
		if ( SCC.hasInteracted() ) {
			prefIcon.style.display = '';
			SCC.log( 'Preferences icon: visible (consent already stored)' );
		}
		document.addEventListener( 'scc:consentUpdated', function () {
			prefIcon.style.display = '';
			SCC.log( 'Preferences icon: visible after consent update' );
		} );
	}

} )();
