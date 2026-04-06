/**
 * SCC Admin JS
 *
 * Handles the WordPress Media Library picker for the logo field.
 */

(function ( $ ) {
	'use strict';

	var mediaFrame;

	// "Select Image" button — opens the WP media library
	$( document ).on( 'click', '.scc-media-select', function ( e ) {
		e.preventDefault();

		var $btn    = $( this );
		var $input  = $( $btn.data( 'target' ) );
		var $remove = $btn.siblings( '.scc-media-remove' );
		var $preview = $btn.closest( '.scc-field__control' ).find( '.scc-logo-preview' );

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
	$( document ).on( 'click', '.scc-media-remove', function ( e ) {
		e.preventDefault();

		var $btn     = $( this );
		var $input   = $( $btn.data( 'target' ) );
		var $preview = $btn.closest( '.scc-field__control' ).find( '.scc-logo-preview' );

		$input.val( '' );
		$preview.attr( 'src', '' ).hide();
		$btn.hide();
	} );

	// GTM toggle — show/hide GTM options block
	$( document ).on( 'change', '#scc_gtm_enabled', function () {
		if ( $( this ).is( ':checked' ) ) {
			$( '.scc-gtm-options' ).show();
		} else {
			$( '.scc-gtm-options' ).hide();
		}
	} );

	// GTM mode cards — highlight selected
	$( document ).on( 'change', '.scc-gtm-mode-radio', function () {
		$( '.scc-radio-card' ).removeClass( 'is-selected' );
		$( this ).closest( '.scc-radio-card' ).addClass( 'is-selected' );
	} );

	// Jurisdiction cards — highlight selected + show/hide CCPA field
	$( document ).on( 'change', '.scc-jurisdiction-radio', function () {
		$( '.scc-jurisdiction-card' ).removeClass( 'is-selected' );
		$( this ).closest( '.scc-jurisdiction-card' ).addClass( 'is-selected' );

		if ( $( this ).val() === 'ccpa' ) {
			$( '.scc-ccpa-field' ).show();
		} else {
			$( '.scc-ccpa-field' ).hide();
		}
	} );

	// Cookies tab — Run Scanner
	$( document ).on( 'click', '#scc-scan-btn', function () {
		var $btn    = $( this );
		var $result = $( '#scc-scan-result' );
		var i18n    = ( window.sccAdmin && sccAdmin.i18n ) ? sccAdmin.i18n : {};

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

			$.post( sccAdmin.ajaxUrl, {
				action:  'scc_scan_client',
				nonce:   sccAdmin.nonce,
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
				.attr( 'class', 'scc-notice scc-notice--success' )
				.text( msg )
				.show();

			if ( totalAdded > 0 ) {
				setTimeout( function () { window.location.reload(); }, 1500 );
			}
		}

		// Step 1: PHP server-side scan (failure is non-fatal — still run client scan)
		$.post( sccAdmin.ajaxUrl, {
			action: 'scc_scan_server',
			nonce:  sccAdmin.nonce,
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
	$( document ).on( 'click', '#scc-add-cookie-btn', function () {
		var $wrap = $( '#scc-cookie-form-wrap' );
		$wrap.show();
		$( this ).hide();
		$wrap.find( 'input[name="cookie_name"]' ).focus();
	} );

	// Cookies tab — confirm delete
	$( document ).on( 'click', '.scc-delete-link', function ( e ) {
		var name = $( this ).data( 'name' );
		if ( ! window.confirm( 'Delete cookie "' + name + '"?' ) ) {
			e.preventDefault();
		}
	} );

} )( jQuery );
