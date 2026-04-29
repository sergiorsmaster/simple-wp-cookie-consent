/**
 * SCC Consent Storage
 *
 * Handles reading and writing the cscc_consent cookie from the browser.
 * This module is the single source of truth for consent state on the client.
 *
 * Cookie format (JSON, 1 year, SameSite=Lax):
 * {
 *   "necessary": true,
 *   "analytics": false,
 *   "marketing": false,
 *   "functional": false,
 *   "timestamp": 1712345678,
 *   "version": "1"
 * }
 *
 * Debug mode: set csccSettings.debug = true (passed from PHP via wp_localize_script).
 * When enabled, all consent actions are logged to the browser console.
 */

window.SimpleCookieConsent = window.SimpleCookieConsent || {};

(function ( SCC ) {

	var COOKIE_NAME    = 'cscc_consent';
	var COOKIE_VERSION = '1';
	var COOKIE_DAYS    = 365;

	// Debug flag — set by PHP via wp_localize_script as window.csccSettings.debug
	var debug = window.csccSettings && window.csccSettings.debug;

	/** Internal logger — only outputs when debug mode is on. */
	SCC.log = function () {
		if ( ! debug ) return;
		var args = Array.prototype.slice.call( arguments );
		args.unshift( '[SCC]' );
		console.log.apply( console, args );
	};

	// -------------------------------------------------------------------------
	// Public API
	// -------------------------------------------------------------------------

	/**
	 * Read and parse the consent cookie.
	 * Returns null if the cookie is absent or malformed.
	 */
	SCC.getConsent = function () {
		var raw = _readCookie( COOKIE_NAME );
		if ( ! raw ) {
			SCC.log( 'getConsent: no cookie found' );
			return null;
		}

		try {
			var parsed = JSON.parse( decodeURIComponent( raw ) );
			SCC.log( 'getConsent:', parsed );
			return parsed;
		} catch ( e ) {
			SCC.log( 'getConsent: failed to parse cookie', e );
			return null;
		}
	};

	/**
	 * Returns true if the visitor has already made a choice.
	 */
	SCC.hasInteracted = function () {
		var result = SCC.getConsent() !== null;
		SCC.log( 'hasInteracted:', result );
		return result;
	};

	/**
	 * Returns true if consent for a specific category is granted.
	 * 'necessary' is always true.
	 */
	SCC.hasConsent = function ( category ) {
		if ( category === 'necessary' ) {
			SCC.log( 'hasConsent: necessary → always true' );
			return true;
		}
		var consent = SCC.getConsent();
		var result  = consent !== null && consent[ category ] === true;
		SCC.log( 'hasConsent:', category, '→', result );
		return result;
	};

	/**
	 * Persist a consent object and fire the consentUpdated event.
	 *
	 * @param {Object} categories  e.g. { analytics: true, marketing: false, functional: false }
	 */
	SCC.saveConsent = function ( categories ) {
		var consent = Object.assign(
			{ necessary: true },
			categories,
			{ timestamp: Math.floor( Date.now() / 1000 ), version: COOKIE_VERSION }
		);

		_writeCookie( COOKIE_NAME, JSON.stringify( consent ), COOKIE_DAYS );
		SCC.log( 'saveConsent: saved →', consent );

		document.dispatchEvent( new CustomEvent( 'cscc:consentUpdated', { detail: consent } ) );
	};

	/**
	 * Accept all non-necessary categories.
	 */
	SCC.acceptAll = function () {
		SCC.log( 'acceptAll' );
		SCC.saveConsent( { analytics: true, marketing: true, functional: true } );
	};

	/**
	 * Deny all non-necessary categories.
	 */
	SCC.denyAll = function () {
		SCC.log( 'denyAll' );
		SCC.saveConsent( { analytics: false, marketing: false, functional: false } );
	};

	// -------------------------------------------------------------------------
	// Private helpers
	// -------------------------------------------------------------------------

	function _writeCookie( name, value, days ) {
		var expires = '';
		if ( days ) {
			var date = new Date();
			date.setTime( date.getTime() + days * 24 * 60 * 60 * 1000 );
			expires = '; expires=' + date.toUTCString();
		}
		document.cookie = name + '=' + encodeURIComponent( value ) +
			expires + '; path=/; SameSite=Lax';
	}

	function _readCookie( name ) {
		var nameEQ  = name + '=';
		var cookies = document.cookie.split( ';' );
		for ( var i = 0; i < cookies.length; i++ ) {
			var c = cookies[ i ].trim();
			if ( c.indexOf( nameEQ ) === 0 ) {
				return c.substring( nameEQ.length );
			}
		}
		return null;
	}

} )( window.SimpleCookieConsent );
