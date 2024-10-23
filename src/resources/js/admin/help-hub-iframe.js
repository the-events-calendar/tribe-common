var tribe = tribe || {};
tribe.helpPage = tribe.helpPage || {};
window.DocsBotAI = window.DocsBotAI || {};

( function ( $, obj ) {
	'use strict';

	obj.selectors = {
		body: document.body,
		docsbotWidget: '#docsbot-widget-embed',
	};

	obj.setup = function () {
		const isOptedIn = obj.selectors.body.getAttribute( 'data-opted-in' ) === '1';
		// Only run Zendesk and DocsBot setup if the user has opted-in.
		if ( isOptedIn ) {
			obj.setupZendesk();
			obj.initializeDocsBot();
		} else {
			obj.selectors.body.classList.add( 'blackout' );
		}
	}

	/**
	 * Method to dynamically load the Zendesk Web Widget script.
	 *
	 * @param {string} zendeskKey - The Zendesk chat key.
	 * @returns {Promise} - A promise that resolves when the script is loaded.
	 */
	obj.loadZendeskWidget = function ( zendeskKey ) {
		return new Promise( ( resolve, reject ) => {
			// Create the script element.
			const script = document.createElement( 'script' );
			script.id = 'ze-snippet';
			script.src = `https://static.zdassets.com/ekr/snippet.js?key=${ zendeskKey }`;
			script.async = true;

			// Append the script to the head of the document.
			document.head.appendChild( script );

			// Resolve the promise when the script is loaded.
			script.onload = () => resolve();

			// Reject the promise on script load error.
			script.onerror = () => reject( new Error( 'Failed to load Zendesk Web Widget' ) );
		} );
	}

	/**
	 * Method to check if zE (Zendesk Web Widget) is defined, then execute the provided callback.
	 * Polls every 100ms until zE is available.
	 *
	 * @param {Function} callback - The function to execute once zE is defined.
	 */
	obj.onZendeskReady = function ( callback ) {
		if ( typeof zE !== 'undefined' ) {
			callback(); // zE is available, run the callback.
		} else {
			// Check again after a short delay.
			setTimeout(
				() => {
					obj.onZendeskReady( callback );
				}
				, 100
			); // Poll every 100 milliseconds.
		}
	};

	/**
	 * Method to initialize the Zendesk widget's actions, including hiding the widget and setting up event listeners.
	 * It listens for 'open' and 'close' events to add/remove a class from the body.
	 */
	obj.initializeZendesk = function () {
		obj.isZendeskInitialized = false;

		// Initially close the widget and set the initialization flag.
		zE(
			'messenger'
			, 'hide'
			, () => {
				obj.isZendeskInitialized = true; // Set the flag after the initial hide.
			}
		);

		/**
		 * Listen for 'open' event, but only trigger after initialization.
		 * Adds the 'blackout' class to the body when the widget is opened.
		 */
		zE(
			'messenger:on'
			, 'open'
			, () => {
				if ( obj.isZendeskInitialized ) {
					obj.selectors.body.classList.add( 'blackout' );
				}
			}
		);

		/**
		 * Listen for 'close' event.
		 * Removes the 'blackout' class from the body when the widget is closed.
		 */
		zE(
			'messenger:on'
			, 'close'
			, () => {
				zE( 'messenger', 'hide' );
				obj.selectors.body.classList.remove( 'blackout' );
			}
		);
	};

	/**
	 * Method to handle incoming postMessage events.
	 * It verifies the message origin and triggers actions based on the message content.
	 *
	 * @param {Event} event - The postMessage event received from another window/iframe.
	 */
	obj.handleMessages = function ( event ) {
		// Security check: Ensure the message is from a trusted origin.
		if ( event.origin !== window.location.origin ) {
			return; // Ignore messages from untrusted origins.
		}

		// Extract message data.
		const message = event.data;

		// Handle different actions using a switch statement.
		switch ( message.action ) {
			case 'runScript':
				// Only run the script if the data is 'openZendesk'.
				if ( message.data === 'openZendesk' ) {
					zE( 'messenger', 'show' );
					zE( 'messenger', 'open' );
					obj.selectors.body.classList.add( 'blackout' );
				}
				break;

			default:
				console.warn( 'Unhandled action:', message.action );
				break;
		}
	};

	/**
	 * Setup method to initialize all functionality.
	 * It dynamically loads the Zendesk Web Widget, and sets up message listeners.
	 */
	obj.setupZendesk = function () {
		obj.loadZendeskWidget( helpHubSettings.zendeskChatKey )
			.then( () => obj.onZendeskReady( obj.initializeZendesk ) )
			.catch( ( error ) => console.error( 'Zendesk Widget failed to load:', error ) );

		// Listen for incoming messages.
		window.addEventListener( 'message', ( event ) => obj.handleMessages( event ) );
	};

	// Method to initialize DocsBotAI widget.
	obj.initializeDocsBot = function () {
		$( obj.selectors.docsbotWidget ).removeClass( 'hide' );
		DocsBotAI.init = function ( e ) {
			return new Promise(
				(
					resolve,
					reject
				) => {
					var script = document.createElement( "script" );
					script.type = "text/javascript";
					script.async = true;
					script.src = "https://widget.docsbot.ai/chat.js";

					let firstScript = document.getElementsByTagName( "script" )[ 0 ];
					firstScript.parentNode.insertBefore( script, firstScript );

					script.addEventListener( "load", () => {
						let loadPromise;
						Promise.all( [
								new Promise( ( res, rej ) => {
									window.DocsBotAI.mount( Object.assign( {}, e ) )
										.then( res )
										.catch( rej );
								} )
								, (
									loadPromise = function check( selector ) {
										return new Promise( resolve => {
											if ( document.querySelector( selector ) ) {
												return resolve( document.querySelector( selector ) );
											}
											let observer = new MutationObserver( mutations => {
												if ( document.querySelector( selector ) ) {
													return resolve( document.querySelector( selector ) );
													observer.disconnect();
												}
											} );
											observer.observe( document.body, {
												childList: true
												, subtree: true
											} );
										} );
									}
								)( "#docsbotai-root" )
							] )
							.then( resolve )
							.catch( reject );
					} );

					script.addEventListener( "error", error => {
						reject( error.message );
					} );
				} );
		};

		// Initialize the DocsBotAI widget.
		DocsBotAI.init( {
			id: helpHubSettings.docsbot_key
			, supportCallback: function ( event, history ) {
				event.preventDefault(); // Prevent default behavior opening the url.
				DocsBotAI.unmount(); // Hide the widget.
				// Open the Zendesk Web Widget.
				zE( 'messenger', 'show' );
				zE( 'messenger', 'open' );
			}
			,
		} );
	};


	$( obj.setup );

} )( jQuery, tribe.helpPage );
