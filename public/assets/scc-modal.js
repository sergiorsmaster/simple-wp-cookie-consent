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

	/** Element that had focus before the modal opened — restored on close. */
	var lastFocusedElement = null;

	// -------------------------------------------------------------------------
	// Focus trap helpers
	// -------------------------------------------------------------------------

	var FOCUSABLE = 'button:not([disabled]), [href], input:not([disabled]), ' +
		'select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

	function getFocusable( container ) {
		return Array.prototype.slice.call( container.querySelectorAll( FOCUSABLE ) )
			.filter( function ( el ) { return el.offsetParent !== null; } );
	}

	// -------------------------------------------------------------------------
	// Open / close
	// -------------------------------------------------------------------------

	function openModal() {
		lastFocusedElement = document.activeElement;
		populateToggles();
		modal.style.display = '';
		document.body.style.overflow = 'hidden';
		SCC.log( 'Modal: opened' );

		// Move focus to the close button (first interactive element)
		if ( btnClose ) btnClose.focus();
	}

	function closeModal() {
		modal.style.display = 'none';
		document.body.style.overflow = '';
		SCC.log( 'Modal: closed' );

		// Return focus to the element that triggered the modal
		if ( lastFocusedElement && typeof lastFocusedElement.focus === 'function' ) {
			lastFocusedElement.focus();
		}
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

	// Delegated listener for [data-scc-action="open-preferences"] links (shortcodes, etc.)
	document.addEventListener( 'click', function ( e ) {
		var trigger = e.target.closest( '[data-scc-action="open-preferences"]' );
		if ( trigger ) {
			e.preventDefault();
			openModal();
		}
	} );

	// Close on overlay click
	if ( overlay ) {
		overlay.addEventListener( 'click', closeModal );
	}

	// Close on ✕ button
	if ( btnClose ) {
		btnClose.addEventListener( 'click', closeModal );
	}

	// Close on Escape key + focus trap (Tab / Shift+Tab cycles within modal)
	document.addEventListener( 'keydown', function ( e ) {
		if ( modal.style.display === 'none' ) return;

		if ( e.key === 'Escape' ) {
			closeModal();
			return;
		}

		if ( e.key === 'Tab' ) {
			var focusable = getFocusable( modal.querySelector( '.scc-modal__box' ) || modal );
			if ( ! focusable.length ) return;

			var first = focusable[ 0 ];
			var last  = focusable[ focusable.length - 1 ];

			if ( e.shiftKey ) {
				// Shift+Tab — wrap backwards
				if ( document.activeElement === first ) {
					e.preventDefault();
					last.focus();
				}
			} else {
				// Tab — wrap forwards
				if ( document.activeElement === last ) {
					e.preventDefault();
					first.focus();
				}
			}
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
