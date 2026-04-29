/**
 * SCC Admin JS
 *
 * Handles the WordPress Media Library picker for the logo field.
 */

(function ( $ ) {
	'use strict';

	var mediaFrame;

	// "Select Image" button — opens the WP media library
	$( document ).on( 'click', '.cscc-media-select', function ( e ) {
		e.preventDefault();

		var $btn    = $( this );
		var $input  = $( $btn.data( 'target' ) );
		var $remove = $btn.siblings( '.cscc-media-remove' );
		var $preview = $btn.closest( '.cscc-field__control' ).find( '.cscc-logo-preview' );

		if ( mediaFrame ) {
			mediaFrame.open();
			return;
		}

		mediaFrame = wp.media( {
			title:    'Select Logo',
			button:   { text: 'Use this image' },
			multiple: false,
			library:  { type: 'image' },
		} );

		mediaFrame.on( 'select', function () {
			var attachment = mediaFrame.state().get( 'selection' ).first().toJSON();
			$input.val( attachment.url );
			$preview.attr( 'src', attachment.url ).show();
			$remove.show();
		} );

		mediaFrame.open();
	} );

	// "Remove" button — clears the logo field
	$( document ).on( 'click', '.cscc-media-remove', function ( e ) {
		e.preventDefault();

		var $btn     = $( this );
		var $input   = $( $btn.data( 'target' ) );
		var $preview = $btn.closest( '.cscc-field__control' ).find( '.cscc-logo-preview' );

		$input.val( '' );
		$preview.attr( 'src', '' ).hide();
		$btn.hide();
	} );

	// GTM toggle — show/hide GTM options block
	$( document ).on( 'change', '#cscc_gtm_enabled', function () {
		if ( $( this ).is( ':checked' ) ) {
			$( '.cscc-gtm-options' ).show();
		} else {
			$( '.cscc-gtm-options' ).hide();
		}
	} );

	// GTM mode cards — highlight selected
	$( document ).on( 'change', '.cscc-gtm-mode-radio', function () {
		$( '.cscc-radio-card' ).removeClass( 'is-selected' );
		$( this ).closest( '.cscc-radio-card' ).addClass( 'is-selected' );
	} );

	// Jurisdiction cards — highlight selected + show/hide CCPA field
	$( document ).on( 'change', '.cscc-jurisdiction-radio', function () {
		$( '.cscc-jurisdiction-card' ).removeClass( 'is-selected' );
		$( this ).closest( '.cscc-jurisdiction-card' ).addClass( 'is-selected' );

		if ( $( this ).val() === 'ccpa' ) {
			$( '.cscc-ccpa-field' ).show();
		} else {
			$( '.cscc-ccpa-field' ).hide();
		}
	} );

	// Appearance tab — button style radio cards
	$( document ).on( 'change', '.cscc-btn-style-radio', function () {
		$( '.cscc-radio-card' ).removeClass( 'is-selected' );
		$( this ).closest( '.cscc-radio-card' ).addClass( 'is-selected' );
	} );

	// Appearance tab — logo source radio → show/hide custom upload field
	$( document ).on( 'change', '.cscc-logo-source-radio', function () {
		if ( $( this ).val() === 'custom' ) {
			$( '.cscc-logo-custom-field' ).show();
		} else {
			$( '.cscc-logo-custom-field' ).hide();
		}
	} );

	// Cookies tab — Run Scanner
	$( document ).on( 'click', '#cscc-scan-btn', function () {
		var $btn    = $( this );
		var $result = $( '#cscc-scan-result' );
		var i18n    = ( window.csccAdmin && csccAdmin.i18n ) ? csccAdmin.i18n : {};

		$btn.prop( 'disabled', true ).text( i18n.scanning || 'Scanning…' );
		$result.hide();

		var totalAdded   = 0;
		var serverNotice = ''; // non-fatal server scan warning

		// Helper: run the JS client-side scan and finish
		function runClientScan() {
			var cookieNames = document.cookie
				.split( ';' )
				.map( function ( c ) { return c.trim().split( '=' )[0]; } )
				.filter( function ( n ) { return n !== ''; } );

			$.post( csccAdmin.ajaxUrl, {
				action:  'cscc_scan_client',
				nonce:   csccAdmin.nonce,
				cookies: cookieNames,
			} )
			.always( function ( res ) {
				if ( res && res.success ) {
					totalAdded += res.data.added || 0;
				}
				finish();
			} );
		}

		function finish() {
			$btn.prop( 'disabled', false ).text( 'Run Scanner' );

			var msg = ( i18n.scanDone || 'Scan complete.' ) +
				' ' + totalAdded + ' ' + ( i18n.added || 'new cookie(s) found.' );
			if ( serverNotice ) {
				msg += ' (Server scan: ' + serverNotice + ')';
			}

			$result
				.attr( 'class', 'cscc-notice cscc-notice--success' )
				.text( msg )
				.show();

			if ( totalAdded > 0 ) {
				setTimeout( function () { window.location.reload(); }, 1500 );
			}
		}

		// Step 1: PHP server-side scan (failure is non-fatal — still run client scan)
		$.post( csccAdmin.ajaxUrl, {
			action: 'cscc_scan_server',
			nonce:  csccAdmin.nonce,
		} )
		.always( function ( res ) {
			if ( res && res.success ) {
				totalAdded += res.data.added || 0;
			} else if ( res && ! res.success && res.data ) {
				serverNotice = res.data; // show as a note, not a blocker
			}
			runClientScan();
		} );
	} );

	// Cookies tab — "Add Cookie" button toggle
	$( document ).on( 'click', '#cscc-add-cookie-btn', function () {
		var $wrap = $( '#cscc-cookie-form-wrap' );
		$wrap.show();
		$( this ).hide();
		$wrap.find( 'input[name="cookie_name"]' ).focus();
	} );

	// Cookies tab — confirm delete
	$( document ).on( 'click', '.cscc-delete-link', function ( e ) {
		var name = $( this ).data( 'name' );
		if ( ! window.confirm( 'Delete cookie "' + name + '"?' ) ) {
			e.preventDefault();
		}
	} );

} )( jQuery );
