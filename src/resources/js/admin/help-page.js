tribe.helpPage = tribe.helpPage || {};

( function ( $, obj ) {
	'use strict';

	obj.selectors = {
		copyButton: '.system-info-copy-btn',
		optInMsg: '.tribe-sysinfo-optin-msg',
		autoInfoOptIn: '#tribe_auto_sysinfo_opt_in',
		accordion: '.tec-ui-accordion',
		openSupportChat: '[data-open-support-chat]',
		helpHubIframe: '#tec-settings__support-hub-iframe',
		iframeLoader: '#tec-settings__support-hub-iframe-loader',
		modalButtonSpan: '#tec-settings-nav-modal-open span',
		navLinkText: '.tec-nav__link',
	};

	obj.setup = () => {
		obj.setupSystemInfo();
		obj.setupCopyButton();
		obj.setupTabs();
		obj.IframeSupportChatClickHandler();
		obj.IframeRender();
	};

	obj.IframeRender = () => {
		// Get the iframe and loader elements.
		const iframe = document.querySelector( obj.selectors.helpHubIframe );
		const loader = document.querySelector( obj.selectors.iframeLoader );
		// Add an event listener to detect when the iframe is fully loaded.
		if ( iframe ) {
			iframe.addEventListener( 'load', () => {
				// Hide the loader and show the iframe once loaded.
				iframe.classList.remove( 'hidden' );
				if ( loader ) {
					loader.classList.add( 'hidden' );
				}
			} );
		}
	};

	/**
	 * Sends a message to the iframe.
	 *
	 * @param {Object} message - The message object containing action and data.
	 */
	obj.sendMessageToIframe = ( message ) => {
		// Ensure the iframe has been loaded and is accessible.
		if ( document.querySelector( obj.selectors.helpHubIframe ).contentWindow ) {
			document.querySelector( obj.selectors.helpHubIframe ).contentWindow.postMessage( message, window.origin );
		}
	};

	/**
	 * Event listener callback for sending messages to open support chat in the iframe.
	 * For legacy support, this sends a postMessage with action 'runScript' and data 'openLivechat'.
	 *
	 * @param {Event} event - The click event object.
	 */
	obj.openSupportChatInIframe = ( event ) => {
		event.preventDefault();
		// For legacy support, keep the message as 'openLivechat'
		const message = { action: 'runScript', data: 'openLivechat' };
		obj.sendMessageToIframe( message );
	};

	obj.IframeSupportChatClickHandler = () => {
		const openSupportChatElement = document.querySelector( obj.selectors.openSupportChat );

		// Check if the element exists before adding the event listener
		if ( openSupportChatElement ) {
			openSupportChatElement.addEventListener( 'click', ( event ) => obj.openSupportChatInIframe( event ) );
		}
	};

	/**
	 * Will setup any accordions that are children of the parent node.
	 *
	 * @since 6.3.2
	 *
	 * @param {{object}} parent The parent jQuery node for precise filtering of accordions to target.
	 */
	obj.setupAccordionsFor = ( parent ) => {
		// Just extra careful of dependency.
		if ( ! $.fn.accordion ) {
			console.error( 'jQuery UI Accordion library is missing.' );
			return;
		}

		// Initialize the accordions.
		$( parent )
			.find( obj.selectors.accordion )
			.accordion( {
				active: true,
				collapsible: true,
				heightStyle: 'content',
				icons: { header: 'ui-icon-plus', activeHeader: 'ui-icon-minus' },
			} );
	};

	/**
	 * Initialize system info opt in copy
	 */
	obj.setupCopyButton = () => {
		if ( 'undefined' === typeof tribe_system_info ) {
			return;
		}

		const clipboard = new ClipboardJS( obj.selectors.copyButton ); /* eslint-disable-line no-undef */
		const button_icon = '<span class="dashicons dashicons-clipboard license-btn"></span>';
		const button_text = tribe_system_info.clipboard_btn_text;

		//Prevent Button From Doing Anything Else
		$( '.system-info-copy-btn' ).on( 'click', ( e ) => {
			e.preventDefault();
		} );

		clipboard.on( 'success', ( event ) => {
			event.clearSelection();
			event.trigger.innerHTML =
				button_icon + '<span class="optin-success">' + tribe_system_info.clipboard_copied_text + '<span>'; // eslint-disable-line max-len
			window.setTimeout( function () {
				event.trigger.innerHTML = button_icon + button_text;
			}, 5000 );
		} );

		clipboard.on( 'error', ( event ) => {
			event.trigger.innerHTML =
				button_icon + '<span class="optin-fail">' + tribe_system_info.clipboard_fail_text + '<span>'; // eslint-disable-line max-len
			window.setTimeout( () => {
				event.trigger.innerHTML = button_icon + button_text;
			}, 5000 );
		} );
	};

	/**
	 * Initialize system info opt in
	 */
	obj.setupSystemInfo = () => {
		if ( 'undefined' === typeof tribe_system_info ) {
			return;
		}

		obj.$system_info_opt_in = $( obj.selectors.autoInfoOptIn );
		obj.$system_info_opt_in_msg = $( obj.selectors.optInMsg );

		obj.$system_info_opt_in.on( 'change', ( e ) => {
			if ( e.target.checked ) {
				obj.doAjaxRequest( 'generate' );
			} else {
				obj.doAjaxRequest( 'remove' );
			}
		} );
	};

	obj.doAjaxRequest = ( generate ) => {
		const request = {
			action: 'tribe_toggle_sysinfo_optin',
			confirm: tribe_system_info.sysinfo_optin_nonce,
			generate_key: generate,
		};

		// Send our request
		$.post( ajaxurl, request, ( results ) => {
			if ( results.success ) {
				obj.$system_info_opt_in_msg.html( "<p class='optin-success'>" + results.data + '</p>' );
			} else {
				let html = "<p class='optin-fail'>" + tribe_system_info.sysinfo_error_message_text + '</p>';

				if ( results.data ) {
					if ( results.data.message ) {
						html += '<p>' + results.data.message + '</p>';
					} else if ( results.message ) {
						html += '<p>' + results.message + '</p>';
					}

					if ( results.data.code ) {
						html += '<p>' + tribe_system_info.sysinfo_error_code_text + ' ' + results.data.code + '</p>';
					}

					if ( results.data.status ) {
						html += '<p>' + tribe_system_info.sysinfo_error_status_text + results.data.status + '</p>';
					}
				}

				obj.$system_info_opt_in_msg.html( html ); // eslint-disable-line max-len
				$( obj.selectors.autoInfoOptIn ).prop( 'checked', false );
			}
		} );
	};

	/**
	 * Initialize the page tabs and on-page navigation.
	 *
	 * @since 6.3.2
	 * @since TBD Implement Tabs to history.
	 */
	obj.setupTabs = () => {
		const tabs = document.querySelectorAll('[data-tab-target]');
		const containers = document.querySelectorAll('.tec-tab-container');

		// Hide all tab containers initially.
		containers.forEach((container) => container.classList.add('hidden'));

		// Check URL param for tab value.
		const params = new URLSearchParams(window.location.search);
		const tabFromURL = params.get('tab');

		let currentTab = null;
		if (tabFromURL) {
			currentTab = document.querySelector(`[data-tab-target="${tabFromURL}"]`);
		} else {
			currentTab = document.querySelector('.tec-nav__tab.tec-nav__tab--active');
		}

		const tabContainer = currentTab
			? document.getElementById(currentTab.getAttribute('data-tab-target'))
			: null;

		// Update modal button text.
		if (currentTab) {
			const tabText = currentTab.querySelector(obj.selectors.navLinkText)?.textContent?.trim();
			const modalButtonSpan = document.querySelector(obj.selectors.modalButtonSpan);
			if (modalButtonSpan && tabText) {
				modalButtonSpan.textContent = tabText;
			}
		}

		// Show correct tab container on first load.
		if (tabContainer) {
			tabContainer.classList.remove('hidden');
		}

		// Set up listeners for tab clicks.
		obj.setupTabEventListeners(tabs, tabContainer);

		// If tabFromURL exists, run the logic immediately.
		if (tabFromURL && currentTab) {
			obj.updateActiveTab(tabs, tabFromURL);
			obj.updateActiveContent(currentTab, tabFromURL);
		}

		// Handle back/forward navigation.
		window.addEventListener('popstate', (event) => {
			const tab = event.state?.tab || new URLSearchParams(window.location.search).get('tab');

			if (tab) {
				obj.updateActiveTab(tabs, tab);
				const matchingTab = document.querySelector(`[data-tab-target="${tab}"]`);
				obj.updateActiveContent(matchingTab, tab);
			}
		});
	};

	/**
	 * Sets up event listeners and observers for tab navigation.
	 *
	 * @param {NodeList}    tabs                The list of tab elements.
	 * @param {HTMLElement} initialTabContainer The initial active tab container.
	 *
	 * @since 6.3.2
	 */
	obj.setupTabEventListeners = ( tabs, initialTabContainer ) => {
		// Set the initial active tab container in obj to track the currently visible tab content.
		obj.activeTabContainer = initialTabContainer;

		// Centralized click event listener for tabs
		document.addEventListener( 'click', ( event ) => {
			// Check if the clicked element is a tab with data-tab-target.
			const tab = event.target.closest( '[data-tab-target]' );
			if ( ! tab ) {
				return;
			}

			// Retrieve the target container ID from the data-tab-target attribute.
			const target = tab.getAttribute( 'data-tab-target' );

			// Update the active tab class and visible content.
			obj.updateActiveTab( tabs, target );
			obj.updateActiveContent( tab, target );
		} );
	};

	/**
	 * Updates the active tab by toggling the active class based on the target.
	 *
	 * @param {NodeList} tabs   The list of tab elements.
	 * @param {string}   target The target data-tab-target attribute value.
	 */
	obj.updateActiveTab = ( tabs, target ) => {
		// Remove the active class from all tabs.
		tabs.forEach( ( t ) => t.classList.remove( 'tec-nav__tab--active' ) );

		// Find and activate all tabs with the same data-tab-target.
		document.querySelectorAll( `[data-tab-target="${ target }"]` ).forEach( ( matchingTab ) => {
			matchingTab.classList.add( 'tec-nav__tab--active' );
		} );
	};

	/**
	 * Updates the active content container, showing the target container and updating modal button text.
	 *
	 * @param {HTMLElement} tab    The current active tab element.
	 * @param {string}      target The target data-tab-target attribute value.
	 */
	obj.updateActiveContent = ( tab, target ) => {
		// Hide the currently active container if it exists.
		if ( obj.activeTabContainer ) {
			obj.activeTabContainer.classList.add( 'hidden' );
		}

		// Select the new target container.
		const newTabContainer = document.getElementById( target );
		if ( newTabContainer ) {
			newTabContainer.classList.remove( 'hidden' );
			obj.activeTabContainer = newTabContainer;

			// Update modal button text.
			const tabText = newTabContainer.getAttribute( 'data-link-title' );
			const modalButtonSpan = document.querySelector( obj.selectors.modalButtonSpan );
			if ( modalButtonSpan && tabText ) {
				modalButtonSpan.textContent = tabText;
			}

			// Store tab in URL so reloads keep the same tab open.
			const url = new URL( window.location );
			url.searchParams.set( 'tab', target );
			window.history.pushState( { tab: target }, '', url );

			// Initialize accordions if needed
			obj.setupAccordionsFor( obj.activeTa.bContainer );
		}
	};

	$( obj.setup );
} )( jQuery, tribe.helpPage );
