var tribe_auto_sysinfo = tribe_auto_sysinfo || {};

tribe_auto_sysinfo.ajax = {
	event: {}
};

(function ( $, my ) {
	'use strict';

	my.init = function () {
		this.init_ajax();
		this.init_copy();
	};

	/**
	 * Initialize system info opt in copy
	 */
	my.init_copy = function () {

		var clipboard = new Clipboard( '.system-info-copy-btn' );
		var button_icon = '<span class="dashicons dashicons-clipboard license-btn"></span>';
		var button_text = tribe_system_info.clipboard_btn_text;

		//Prevent Button From Doing Anything Else
		$( ".system-info-copy-btn" ).click( function ( e ) {
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

		this.$system_info_opt_in     = $( "#tribe_auto_sysinfo_opt_in" );
		this.$system_info_opt_in_msg = $( ".tribe-sysinfo-optin-msg" );

		this.$system_info_opt_in.change( function () {
			if ( this.checked ) {
				my.event.ajax( "generate" );
			} else {
				my.event.ajax( "remove" );
			}

		} );

	};

	my.event.ajax = function ( generate ) {

		var request = {
			"action": "tribe_toggle_sysinfo_optin",
			"confirm": tribe_system_info.sysinfo_optin_nonce,
			"generate_key": generate
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

	$( function () {
		my.init();
	} );

})( jQuery, tribe_auto_sysinfo.ajax );