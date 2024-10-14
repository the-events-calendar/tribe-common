tribe.helpPage = tribe.helpPage || {};

( function ( $, obj ) {
	'use strict';

	obj.selectors = {
		copyButton: '.system-info-copy-btn',
		optInMsg: '.tribe-sysinfo-optin-msg',
		autoInfoOptIn: '#tribe_auto_sysinfo_opt_in',
		accordion: '.tec-ui-accordion',
	};

	obj.setup = function () {
		obj.setupSystemInfo();
		obj.setupCopyButton();
		obj.setupTabs();
	};

	/**
	 * Will setup any accordions that are children of the parent node.
	 *
	 * @since TBD
	 *
	 * @param {{object}} parent The parent jQuery node for precise filtering of accordions to target.
	 */
	obj.setupAccordionsFor = function ( parent ) {
		// Just extra careful of dependency.
		if ( ! $.fn.accordion ) {
			console.error( 'jQuery UI Accordion library is missing.' );
			return;
		}

		// Initialize the accordions.
		$( parent ).find( obj.selectors.accordion ).accordion(
			{
				active: true,
				collapsible: true,
				icons: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" }
			}
		);
	}

	/**
	 * Initialize system info opt in copy
	 */
	obj.setupCopyButton = function () {
		if ( 'undefined' === typeof tribe_system_info ) {
			return;
		}

		var clipboard = new ClipboardJS( obj.selectors.copyButton ); /* eslint-disable-line no-undef */
		var button_icon = '<span class="dashicons dashicons-clipboard license-btn"></span>';
		var button_text = tribe_system_info.clipboard_btn_text;

		//Prevent Button From Doing Anything Else
		$( '.system-info-copy-btn' ).on(
			'click',
			function ( e ) {
				e.preventDefault();
			}
		);

		clipboard.on( 'success', function ( event ) {
			event.clearSelection();
			event.trigger.innerHTML = button_icon + '<span class="optin-success">' + tribe_system_info.clipboard_copied_text + '<span>'; // eslint-disable-line max-len
			window.setTimeout( function () {
				event.trigger.innerHTML = button_icon + button_text;
			}, 5000 );
		} );

		clipboard.on( 'error', function ( event ) {
			event.trigger.innerHTML = button_icon + '<span class="optin-fail">' + tribe_system_info.clipboard_fail_text + '<span>'; // eslint-disable-line max-len
			window.setTimeout( function () {
				event.trigger.innerHTML = button_icon + button_text;
			}, 5000 );
		} );

	};

	/**
	 * Initialize system info opt in
	 */
	obj.setupSystemInfo = function () {
		if ( 'undefined' === typeof tribe_system_info ) {
			return;
		}

		obj.$system_info_opt_in     = $( obj.selectors.autoInfoOptIn );
		obj.$system_info_opt_in_msg = $( obj.selectors.optInMsg );

		obj.$system_info_opt_in.on( 'change', function () {
			if ( this.checked ) {
				obj.doAjaxRequest( 'generate' );
			} else {
				obj.doAjaxRequest( 'remove' );
			}
		} );

	};

	obj.doAjaxRequest = function ( generate ) {
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
					obj.$system_info_opt_in_msg.html( "<p class='optin-success'>" + results.data + "</p>" );
				} else {
					var html = "<p class='optin-fail'>"
						+ tribe_system_info.sysinfo_error_message_text
						+ "</p>";

					if ( results.data ) {
						if ( results.data.message ) {
							html += '<p>' + results.data.message + '</p>';
						} else if (  results.message ) {
							html += '<p>' + results.message + '</p>';
						}

						if ( results.data.code ) {
							html += '<p>'
							+ tribe_system_info.sysinfo_error_code_text
							+ ' '
							+ results.data.code
							+ '</p>';
						}

						if ( results.data.status ) {
							html += '<p>'
							+ tribe_system_info.sysinfo_error_status_text
							+ results.data.status
							+ '</p>';
						}

					}

					obj.$system_info_opt_in_msg.html( html ); // eslint-disable-line max-len
					$( obj.selectors.autoInfoOptIn ).prop( "checked", false );
				}
			}
		);

	};

	/**
	 * Initialize the page tabs and on-page navigation.
	 *
	 * @since TBD
	 */
	obj.setupTabs = function () {
		let currentTab   = $( '.tec-nav__tab.tec-nav__tab--subnav-active' );
		let tabContainer = $( '#' + currentTab.data( 'tab-target' ) );
		$( '.tec-tab-container' ).hide();
		tabContainer.show();

		$( '[data-tab-target]' ).on(
			'click',
			function () {
				let tab       = $( this );
				let tabTarget = $( '#' + tab.data( 'tab-target' ) );

				$( '[data-tab-target]' ).removeClass( 'tec-nav__tab--subnav-active' );
				$( '[data-tab-target="' + tab.data( 'tab-target' ) + '"]' )
					.addClass( 'tec-nav__tab--subnav-active' );

				tabContainer.hide();
				tabTarget.show();
				tabContainer = tabTarget;

				/**
				 * Because the tabs are hidden, we need to delay the accordion rendering until they
				 * are "shown" so the expander logic can size the node to the rendered height.
				 */
				obj.setupAccordionsFor( tabTarget );
			}
		);
	}

	$( obj.setup );

} )( jQuery, tribe.helpPage );
