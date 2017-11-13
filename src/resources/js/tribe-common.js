// Run some magic to allow a better handling of class names for jQuery.hasClass type of methods
String.prototype.className = function () {
	return this.replace( '.', '' );
};

// Add a method to convert ID/Classes into JS easy/safe variable
String.prototype.varName = function () {
	return this.replace( '-', '_' );
};

/**
 * Creates a global Tribe Variable where we should start to store all the things
 * @type {object}
 */
var tribe = tribe || {};

/**
 * @todo  All these lines below should be copied into another file.
 *        we don't need all of this JS on all pages
 */

var tribe_auto_sysinfo = tribe_auto_sysinfo || {};

tribe_auto_sysinfo.ajax = {
	event: {}
};

(function ( $, my ) {
	'use strict';

	my.init = function () {
		this.init_ajax();
		this.init_copy();
		my.navigate_to_id();
	};

	/**
	 * Initialize system info opt in copy
	 */
	my.init_copy = function () {
		if ( 'undefined' === typeof tribe_system_info ) {
			return;
		}

		var clipboard = new Clipboard( '.system-info-copy-btn' );
		var button_icon = '<span class="dashicons dashicons-clipboard license-btn"></span>';
		var button_text = tribe_system_info.clipboard_btn_text;

		//Prevent Button From Doing Anything Else
		$( '.system-info-copy-btn' ).click( function ( e ) {
			e.preventDefault();
		} );

		clipboard.on( 'success', function ( event ) {
			event.clearSelection();
			event.trigger.innerHTML = button_icon + '<span class="optin-success">' + tribe_system_info.clipboard_copied_text + '<span>';
			window.setTimeout( function () {
				event.trigger.innerHTML = button_icon + button_text;
			}, 5000 );
		} );

		clipboard.on( 'error', function ( event ) {
			event.trigger.innerHTML = button_icon + '<span class="optin-fail">' + tribe_system_info.clipboard_fail_text + '<span>';
			window.setTimeout( function () {
				event.trigger.innerHTML = button_icon + button_text;
			}, 5000 );
		} );

	};

	/**
	 * Initialize system info opt in
	 */
	my.init_ajax = function () {
		if ( 'undefined' === typeof tribe_system_info ) {
			return;
		}

		this.$system_info_opt_in     = $( document.getElementById( 'tribe_auto_sysinfo_opt_in' ) );
		this.$system_info_opt_in_msg = $( '.tribe-sysinfo-optin-msg' );

		this.$system_info_opt_in.change( function () {
			if ( this.checked ) {
				my.event.ajax( 'generate' );
			} else {
				my.event.ajax( 'remove' );
			}

		} );

	};

	my.event.ajax = function ( generate ) {

		var request = {
			'action'       : 'tribe_toggle_sysinfo_optin',
			'confirm'      : tribe_system_info.sysinfo_optin_nonce,
			'generate_key' : generate
		};

		// Send our request
		$.post(
			ajaxurl,
			request,
			function ( results ) {
				if ( results.success ) {
					my.$system_info_opt_in_msg.html( "<p class=\'optin-success\'>" + results.data + "</p>" );
				} else {
					my.$system_info_opt_in_msg.html( "<p class=\'optin-fail\'>" + results.data.message + " Code:" + results.data.code + " Status:" + results.data.data.status + "</p>" );
					$( "#tribe_auto_sysinfo_opt_in" ).prop( "checked", false );
				}
			} );

	};

	/**
	 * Sets up listeners and callbacks to handle navigation to page #elements
	 * gracefully and in a way that doesn't result in the admin toolbar obscuring
	 * the target.
	 *
	 * @since 4.5.6
	 */
	my.navigate_to_id = function() {
		$( document ).ready( my.maybe_navigate_to_id_on_doc_ready );
		$( document ).on( 'click', '.tribe_events_page_tribe-common', my.maybe_navigate_to_id_after_click );
	};

	/**
	 * When the document is ready, check and see if the current location included
	 * a reference to a specific ID and trigger our offset/scroll position adjustment
	 * code if so.
	 *
	 * @since 4.5.6
	 */
	my.maybe_navigate_to_id_on_doc_ready = function() {
		var target = my.get_url_fragment( window.location.href );

		if ( ! target.length ) {
			return;
		}

		my.adjust_scroll_position( target );
	};

	/**
	 * If it looks like the user has navigated to a specific anchor within the page
	 * then trigger our scroll position adjustment.
	 *
	 * @since 4.5.6
	 *
	 * @param event
	 */
	my.maybe_navigate_to_id_after_click = function( event ) {
		var src_link = $( event.target ).attr( 'href' );

		// If we couldn't determine the URL, bail
		if ( 'undefined' === typeof src_link ) {
			return;
		}

		var target_id = my.get_url_fragment( src_link );

		// No ID/fragment in the URL? Bail
		if ( ! target_id ) {
			return;
		}

		my.adjust_scroll_position( target_id );
	}

	/**
	 * Adjust the scroll/viewport offset if necessary to stop the admin toolbar
	 * from obscuring the target element.
	 *
	 * @since 4.5.6
	 *
	 * @param {String} id
	 */
	my.adjust_scroll_position = function( id ) {
		// No toolbar, no problem
		if ( ! $( '#wpadminbar' ).length ) {
			return;
		}

		var element_position = $( '#' + id ).position();

		// Bail if the element doesn't actually exist
		if ( ! element_position ) {
			return;
		}

		// A fractional delay is needed to ensure our adjustment sticks
		setTimeout( function() {
			window.scroll( window.scrollX, element_position.top );
		} );
	};

	/**
	 * Attempts to extract the "#fragment" string from a URL and returns it
	 * (will be empty if not set).
	 *
	 * @since 4.5.6
	 *
	 * @param {String} url
	 *
	 * @returns {String}
	 */
	my.get_url_fragment = function( url ) {
		var fragment = url.match( /#([a-z0-9_-]+)$/i )

		if ( null === fragment ) {
			return '';
		}

		// Return the first captured group
		return fragment[ 1 ];
	};

	$( function () {
		my.init();
	} );

})( jQuery, tribe_auto_sysinfo.ajax );