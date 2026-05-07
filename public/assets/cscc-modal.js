/**
 * CSCC Preferences Modal
 *
 * Opens on cscc:openPreferences event (dispatched by the Preferences button).
 * Pre-populates toggles from existing consent cookie.
 * Save button calls CSCC.saveConsent() with selected categories.
 */

(function () {

	'use strict';

	var CSCC = window.SimpleCookieConsent;
	var modal = document.getElementById('cscc-modal');

	if (!CSCC || !modal) return;

	var overlay = document.getElementById('cscc-modal-overlay');
	var btnClose = document.getElementById('cscc-modal-close');
	var btnSave = document.getElementById('cscc-modal-save');
	var btnDeny = document.getElementById('cscc-modal-deny');
	var inputs = modal.querySelectorAll('.cscc-toggle__input[data-category]');

	/** Element that had focus before the modal opened — restored on close. */
	var lastFocusedElement = null;

	// -------------------------------------------------------------------------
	// Focus trap helpers
	// -------------------------------------------------------------------------

	var FOCUSABLE = 'button:not([disabled]), [href], input:not([disabled]), ' +
		'select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

	function getFocusable(container) {
		return Array.prototype.slice.call(container.querySelectorAll(FOCUSABLE))
			.filter(function (el) { return el.offsetParent !== null; });
	}

	// -------------------------------------------------------------------------
	// Open / close
	// -------------------------------------------------------------------------

	function openModal() {
		lastFocusedElement = document.activeElement;
		populateToggles();
		modal.style.display = '';
		document.body.style.overflow = 'hidden';
		CSCC.log('Modal: opened');

		// Move focus to the close button (first interactive element)
		if (btnClose) btnClose.focus();
	}

	function closeModal() {
		modal.style.display = 'none';
		document.body.style.overflow = '';
		CSCC.log('Modal: closed');

		// Return focus to the element that triggered the modal
		if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
			lastFocusedElement.focus();
		}
	}

	// -------------------------------------------------------------------------
	// Pre-populate toggles from stored consent
	// -------------------------------------------------------------------------

	function populateToggles() {
		var consent = CSCC.getConsent();

		inputs.forEach(function (input) {
			var cat = input.getAttribute('data-category');
			if (consent && typeof consent[cat] !== 'undefined') {
				input.checked = !!consent[cat];
			} else {
				// No prior consent — default all to unchecked (opt-in)
				input.checked = false;
			}
		});
	}

	// -------------------------------------------------------------------------
	// Save selected preferences
	// -------------------------------------------------------------------------

	function savePreferences() {
		var categories = {};

		inputs.forEach(function (input) {
			categories[input.getAttribute('data-category')] = input.checked;
		});

		CSCC.log('Modal: saving preferences →', categories);
		CSCC.saveConsent(categories);
		closeModal();
	}

	// -------------------------------------------------------------------------
	// Event listeners
	// -------------------------------------------------------------------------

	// Expose on public API so it can be called from JS or a footer link
	CSCC.openPreferences = openModal;

	// Open when Preferences button fires the custom event
	document.addEventListener('cscc:openPreferences', openModal);

	// Delegated listener for [data-cscc-action="open-preferences"] links (shortcodes, etc.)
	document.addEventListener('click', function (e) {
		var trigger = e.target.closest('[data-cscc-action="open-preferences"]');
		if (trigger) {
			e.preventDefault();
			openModal();
		}
	});

	// Close on overlay click
	if (overlay) {
		overlay.addEventListener('click', closeModal);
	}

	// Close on ✕ button
	if (btnClose) {
		btnClose.addEventListener('click', closeModal);
	}

	// Close on Escape key + focus trap (Tab / Shift+Tab cycles within modal)
	document.addEventListener('keydown', function (e) {
		if (modal.style.display === 'none') return;

		if (e.key === 'Escape') {
			closeModal();
			return;
		}

		if (e.key === 'Tab') {
			var focusable = getFocusable(modal.querySelector('.cscc-modal__box') || modal);
			if (!focusable.length) return;

			var first = focusable[0];
			var last = focusable[focusable.length - 1];

			if (e.shiftKey) {
				// Shift+Tab — wrap backwards
				if (document.activeElement === first) {
					e.preventDefault();
					last.focus();
				}
			} else {
				// Tab — wrap forwards
				if (document.activeElement === last) {
					e.preventDefault();
					first.focus();
				}
			}
		}
	});

	// Save preferences
	if (btnSave) {
		btnSave.addEventListener('click', savePreferences);
	}

	// Deny all from modal
	if (btnDeny) {
		btnDeny.addEventListener('click', function () {
			CSCC.log('Modal: Deny All clicked');
			CSCC.denyAll();
			closeModal();
		});
	}

})();
