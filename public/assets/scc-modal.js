/**
 * SCC Preferences Modal
 *
 * Opens on scc:openPreferences event (dispatched by the Preferences button).
 * Pre-populates toggles from existing consent cookie.
 * Save button calls SCC.saveConsent() with selected categories.
 */

(function () {

	'use strict';

	var SCC   = window.SimpleCookieConsent;
	var modal = document.getElementById( 'scc-modal' );

	if ( ! SCC || ! modal ) return;

	var overlay  = document.getElementById( 'scc-modal-overlay' );
	var btnClose = document.getElementById( 'scc-modal-close' );
	var btnSave  = document.getElementById( 'scc-modal-save' );
	var btnDeny  = document.getElementById( 'scc-modal-deny' );
	var inputs   = modal.querySelectorAll( '.scc-toggle__input[data-category]' );

	// -------------------------------------------------------------------------
	// Open / close
	// -------------------------------------------------------------------------

	function openModal() {
		populateToggles();
		modal.style.display = '';
		document.body.style.overflow = 'hidden';
		SCC.log( 'Modal: opened' );

		// Focus the close button for accessibility
		if ( btnClose ) btnClose.focus();
	}

	function closeModal() {
		modal.style.display = 'none';
		document.body.style.overflow = '';
		SCC.log( 'Modal: closed' );
	}

	// -------------------------------------------------------------------------
	// Pre-populate toggles from stored consent
	// -------------------------------------------------------------------------

	function populateToggles() {
		var consent = SCC.getConsent();

		inputs.forEach( function ( input ) {
			var cat = input.getAttribute( 'data-category' );
			if ( consent && typeof consent[ cat ] !== 'undefined' ) {
				input.checked = !! consent[ cat ];
			} else {
				// No prior consent — default all to unchecked (opt-in)
				input.checked = false;
			}
		} );
	}

	// -------------------------------------------------------------------------
	// Save selected preferences
	// -------------------------------------------------------------------------

	function savePreferences() {
		var categories = {};

		inputs.forEach( function ( input ) {
			categories[ input.getAttribute( 'data-category' ) ] = input.checked;
		} );

		SCC.log( 'Modal: saving preferences →', categories );
		SCC.saveConsent( categories );
		closeModal();
	}

	// -------------------------------------------------------------------------
	// Event listeners
	// -------------------------------------------------------------------------

	// Expose on public API so it can be called from JS or a footer link
	SCC.openPreferences = openModal;

	// Open when Preferences button fires the custom event
	document.addEventListener( 'scc:openPreferences', openModal );

	// Close on overlay click
	if ( overlay ) {
		overlay.addEventListener( 'click', closeModal );
	}

	// Close on ✕ button
	if ( btnClose ) {
		btnClose.addEventListener( 'click', closeModal );
	}

	// Close on Escape key
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && modal.style.display !== 'none' ) {
			closeModal();
		}
	} );

	// Save preferences
	if ( btnSave ) {
		btnSave.addEventListener( 'click', savePreferences );
	}

	// Deny all from modal
	if ( btnDeny ) {
		btnDeny.addEventListener( 'click', function () {
			SCC.log( 'Modal: Deny All clicked' );
			SCC.denyAll();
			closeModal();
		} );
	}

} )();
