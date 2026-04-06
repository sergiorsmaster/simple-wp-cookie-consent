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
