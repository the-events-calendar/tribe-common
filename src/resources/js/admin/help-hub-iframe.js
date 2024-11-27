var tribe = tribe || {};
tribe.helpPage = tribe.helpPage || {};
window.DocsBotAI = window.DocsBotAI || {};

( ( $, obj ) => {
	'use strict';

	obj.selectors = {
		body: 'body',
		helpHubPageID: 'help-hub-page',
		docsbotWidget: 'docsbot-widget-embed',
		optOutMessage: '.tec-help-hub-iframe-opt-out-message',
	};

	/**
	 * Custom CSS for Docsbot.
	 * Note that you may need to use !important to override some element styles.
	 *
	 * @link https://docsbot.ai/documentation/developer/embeddable-chat-widget#custom-css
	 *
	 * @since 6.3.2
	 *
	 * @type {string}
	 */
	obj.DocsBotAIcss = `
						/* DocsBot iframe box dimensions */
						.docsbot-iframe-box {
							height: 740px;
							max-height: 740px;
						}

						/* Container styling */
						.docsbot-chat-container {
							font-family: Arial, Helvetica, sans-serif;
						}

						/* Inner container styling */
						.docsbot-chat-inner-container {
							border-radius: 0;
							background-color: #ffffff !important;
						}

						/* Bot message styling */
						.docsbot-chat-bot-message {
							border-color: #334aff;
							background: #E6E9FF !important;
							color: #000000 !important;
						}

						/* User message styling */
						.docsbot-user-chat-message {
							color: #000000 !important;
						}

						/* Header styling */
						.docsbot-chat-header {
							background-color: #ffffff !important;
							color: #000000 !important;
							border-bottom: solid 1px #C3C4C7;
						}

						/* Header content styling */
						.docsbot-chat-header-content h1 {
							text-align: left;
						}
						.docsbot-chat-header-content span {
							display: none;
						}

						/* Header button positioning */
						.docsbot-chat-header button {
							top: 14px !important;
						}

						/* Suggested questions container styling */
						.docsbot-chat-suggested-questions-container button {
							background-color: #F6F7F7 !important;
							border: solid 1px #C3C4C7 !important;
							border-radius: 3px;
							color: #000000 !important;
						}
						.docsbot-chat-suggested-questions-container span {
							color: #000000 !important;
						}
					`;


	/**
	 * Initializes the help page setup, verifying opt-in status.
	 *
	 * @since 6.3.2
	 * @return {void}
	 */
	obj.setup = () => {
		const bodyElement = document.getElementById( obj.selectors.helpHubPageID );
		const isOptedIn = bodyElement.getAttribute( 'data-opted-in' ) === '1';
		const optOutMessageElement = document.querySelector( obj.selectors.optOutMessage );
		const docsbotElement = document.getElementById( obj.selectors.docsbotWidget );
		// Only run Zendesk and DocsBot setup if the user has opted-in.
		if ( isOptedIn ) {
			obj.loadAndInitializeZendeskWidget();
			obj.initializeDocsBot();
		} else {
			optOutMessageElement.classList.remove( 'hide' );
			docsbotElement.classList.add( 'hide' );
			bodyElement.classList.add( 'blackout' );
		}
	};

	/**
	 * Dynamically loads the Zendesk Web Widget script.
	 *
	 * @since 6.3.2
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
	 * @link https://support.zendesk.com/hc/en-us/articles/4408836216218-Using-Web-Widget-Classic-to-embed-customer-service-in-your-website
	 *
	 * @since 6.3.2
	 * @return {void}
	 */
	obj.initializeZendesk = () => {
		obj.isZendeskInitialized = false;
		const bodyElement = document.getElementById( obj.selectors.helpHubPageID );

		zE(
			'webWidget',
			'hide',
			() => {
				obj.isZendeskInitialized = true;
			}
		);

		// Add 'blackout' class when the widget is opened.
		zE(
			'webWidget:on',
			'open',
			() => {
				if ( obj.isZendeskInitialized ) {
					bodyElement.classList.add( 'blackout' );
				}
			}
		);

		// Remove 'blackout' class when the widget is closed.
		zE(
			'webWidget:on',
			'close',
			() => {
				zE(
					'webWidget',
					'hide'
				);
				bodyElement.classList.remove( 'blackout' );
			}
		);
	};

	/**
	 * Handles incoming postMessage events, verifying origin and triggering actions based on the message.
	 *
	 * @since 6.3.2
	 * @param {Event} event - The postMessage event received.
	 * @return {void}
	 */
	obj.handlePostMessageEvents = ( event ) => {
		const bodyElement = document.getElementById( obj.selectors.helpHubPageID );

		if ( event.origin !== window.location.origin ) {
			return; // Ignore messages from untrusted origins.
		}

		const { action, data } = event.data;

		switch ( action ) {
			case 'runScript':
				if ( data === 'openZendesk' ) {
					zE(
						'webWidget',
						'show'
					);
					zE(
						'webWidget',
						'open'
					);
					bodyElement.classList.add( 'blackout' );
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
	 * @since 6.3.2
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
	 * @since 6.3.2
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
	 * @link https://docsbot.ai/documentation/developer/embeddable-chat-widget
	 *
	 * @since 6.3.2
	 * @return {void}
	 */
	obj.initializeDocsBot = () => {
		const bodyElement = document.getElementById( obj.selectors.helpHubPageID );
		document.getElementById(obj.selectors.docsbotWidget).classList.remove( 'hide' );
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
							options: {
								customCSS: obj.DocsBotAIcss,
							},
							supportCallback: ( event ) => {
								event.preventDefault();
								bodyElement.classList.add( 'blackout' );
								zE(
									'webWidget',
									'show'
								);
								zE(
									'webWidget',
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
