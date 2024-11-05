var tribe = tribe || {};
tribe.helpPage = tribe.helpPage || {};
window.DocsBotAI = window.DocsBotAI || {};

( ( $, obj ) => {
	'use strict';

	obj.selectors = {
		body: document.body,
		docsbotWidget: '#docsbot-widget-embed',
		optOutMessage: '.tec-help-hub-iframe-opt-out-message',
	};

	/**
	 * Initializes the help page setup, verifying opt-in status.
	 *
	 * @since TBD
	 * @return {void}
	 */
	obj.setup = () => {
		const isOptedIn = obj.selectors.body.getAttribute( 'data-opted-in' ) === '1';
		// Only run Zendesk and DocsBot setup if the user has opted-in.
		if ( isOptedIn ) {
			obj.loadAndInitializeZendeskWidget();
			obj.initializeDocsBot();
		} else {
			document.querySelector( obj.selectors.optOutMessage ).classList.remove( 'hide' );
			document.querySelector( obj.selectors.docsbotWidget ).classList.add( 'hide' );
			obj.selectors.body.classList.add( 'blackout' );
		}
	};

	/**
	 * Dynamically loads the Zendesk Web Widget script.
	 *
	 * @since TBD
	 * @param {string} zendeskKey - The Zendesk chat key.
	 * @returns {Promise} - A promise that resolves when the script is loaded.
	 */
	obj.loadZendeskWidgetScript = ( zendeskKey ) => {
		return new Promise( ( resolve, reject ) => {
			const script = document.createElement( 'script' );
			script.id = 'ze-snippet';
			script.src = `https://static.zdassets.com/ekr/snippet.js?key=${ zendeskKey }`;
			script.async = true;

			document.head.appendChild( script );

			script.onload = () => resolve();
			script.onerror = () => reject( new Error( 'Failed to load Zendesk Web Widget' ) );
		} );
	};

	/**
	 * Initializes the Zendesk widget, hides it initially, and sets up event listeners for open/close actions.
	 *
	 * @since TBD
	 * @return {void}
	 */
	obj.initializeZendesk = () => {
		obj.isZendeskInitialized = false;

		zE(
			'messenger',
			'hide',
			() => {
				obj.isZendeskInitialized = true;
			}
		);

		// Add 'blackout' class when the widget is opened.
		zE(
			'messenger:on',
			'open',
			() => {
				if ( obj.isZendeskInitialized ) {
					obj.selectors.body.classList.add( 'blackout' );
				}
			}
		);

		// Remove 'blackout' class when the widget is closed.
		zE(
			'messenger:on',
			'close',
			() => {
				zE(
					'messenger',
					'hide'
				);
				obj.selectors.body.classList.remove( 'blackout' );
			}
		);
	};

	/**
	 * Handles incoming postMessage events, verifying origin and triggering actions based on the message.
	 *
	 * @since TBD
	 * @param {Event} event - The postMessage event received.
	 * @return {void}
	 */
	obj.handlePostMessageEvents = ( event ) => {
		if ( event.origin !== window.location.origin ) {
			return; // Ignore messages from untrusted origins.
		}

		const { action, data } = event.data;

		switch ( action ) {
			case 'runScript':
				if ( data === 'openZendesk' ) {
					zE(
						'messenger',
						'show'
					);
					zE(
						'messenger',
						'open'
					);
					obj.selectors.body.classList.add( 'blackout' );
				}
				break;

			default:
				console.warn(
					'Unhandled action:',
					action
				);
				break;
		}
	};

	/**
	 * Loads and initializes the Zendesk widget, and sets up message listeners.
	 *
	 * @since TBD
	 * @return {void}
	 */
	obj.loadAndInitializeZendeskWidget = () => {
		obj.loadZendeskWidgetScript( helpHubSettings.zendeskChatKey )
			.then( () => obj.initializeZendesk() )
			.catch( ( error ) => console.error(
				'Zendesk Widget failed to load:',
				error
			) );

		// Listen for incoming messages.
		window.addEventListener(
			'message',
			obj.handlePostMessageEvents
		);
	};

	/**
	 * Observes for the DocsBot element to be available in the DOM.
	 *
	 * @since TBD
	 * @param {string} selector - The CSS selector of the element to observe.
	 * @returns {Promise} - Resolves when the element becomes available.
	 */
	obj.observeElement = ( selector ) => {
		return new Promise( ( resolve ) => {
			const element = document.querySelector( selector );
			if ( element ) {
				return resolve( element );
			}
			const observer = new MutationObserver( ( mutations ) => {
				const foundElement = document.querySelector( selector );
				if ( foundElement ) {
					resolve( foundElement );
					observer.disconnect(); // Ensure the observer stops after resolving.
				}
			} );
			observer.observe(
				document.body,
				{ childList: true, subtree: true }
			);
		} );
	};

	/**
	 * Initializes the DocsBot widget, handling its configuration and integration with Zendesk.
	 *
	 * @since TBD
	 * @return {void}
	 */
	obj.initializeDocsBot = () => {
		$( obj.selectors.docsbotWidget ).removeClass( 'hide' );
		DocsBotAI.init = ( e ) => {
			return new Promise( ( resolve, reject ) => {
				const script = document.createElement( 'script' );
				script.type = 'text/javascript';
				script.async = true;
				script.src = 'https://widget.docsbot.ai/chat.js';

				const firstScript = document.getElementsByTagName( 'script' )[ 0 ];
				firstScript.parentNode.insertBefore(
					script,
					firstScript
				);

				script.addEventListener(
					'load',
					() => {
						Promise.all( [
										 window.DocsBotAI.mount( { ...e } ),
										 obj.observeElement( '#docsbotai-root' ),
									 ] )
							.then( resolve )
							.catch( reject );
					}
				);

				script.addEventListener(
					'error',
					( error ) => {
						reject( error.message );
					}
				);
			} );
		};

		DocsBotAI.init( {
							id: helpHubSettings.docsbot_key,
							supportCallback: ( event ) => {
								event.preventDefault();
								DocsBotAI.unmount();
								zE(
									'messenger',
									'show'
								);
								zE(
									'messenger',
									'open'
								);
							},
						} );
	};

	// Initialize the help page.
	$( obj.setup );

} )(
	jQuery,
	tribe.helpPage
);
