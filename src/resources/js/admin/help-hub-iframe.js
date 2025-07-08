window.tribe = window.tribe || {};
tribe.helpPage = tribe.helpPage || {};
window.DocsBotAI = window.DocsBotAI || {};

// Beacon Manager namespace
tribe.helpPage.BeaconManager = {
	/**
	 * Initialize the Beacon stub if not already present.
	 *
	 * @since 6.8.2
	 * @return {void}
	 */
	initStub() {
		if ( ! window.Beacon || ! window.Beacon.readyQueue ) {
			window.Beacon = ( method, options, data ) => {
				window.Beacon.readyQueue.push( { method, options, data } );
			};
			window.Beacon.readyQueue = [];
		}
	},

	/**
	 * Check if Beacon is available.
	 *
	 * @since 6.8.2
	 * @return {boolean}
	 */
	isAvailable() {
		return typeof window.Beacon === 'function';
	},

	/**
	 * Call Beacon with the given method and arguments.
	 *
	 * @since 6.8.2
	 * @param {string} method - The Beacon method to call.
	 * @param {...any} args - Arguments to pass to the method.
	 * @return {void}
	 */
	call( method, ...args ) {
		if ( this.isAvailable() ) {
			window.Beacon( method, ...args );
		}
	},

	/**
	 * Initialize Beacon with the given key.
	 *
	 * @since 6.8.2
	 * @param {string} key - The Beacon key.
	 * @return {void}
	 */
	init( key ) {
		this.call( 'init', key );
	},

	/**
	 * Configure Beacon with the given options.
	 *
	 * @since 6.8.2
	 * @param {Object} config - Configuration options.
	 * @return {void}
	 */
	config( config ) {
		this.call( 'config', config );
	},

	/**
	 * Add an event listener to Beacon.
	 *
	 * @since 6.8.2
	 * @param {string} event - The event name.
	 * @param {Function} callback - The callback function.
	 * @return {void}
	 */
	on( event, callback ) {
		this.call( 'on', event, callback );
	},

	/**
	 * Identify a user with Beacon.
	 *
	 * @since 6.8.2
	 * @param {Object} userData - User identification data.
	 * @return {void}
	 */
	identify( userData ) {
		this.call( 'identify', userData );
	},

	/**
	 * Open the Beacon widget.
	 *
	 * @since 6.8.2
	 * @return {void}
	 */
	open() {
		this.call( 'open' );
	},

	/**
	 * Close the Beacon widget.
	 *
	 * @since 6.8.2
	 * @return {void}
	 */
	close() {
		this.call( 'close' );
	}
};

( ( $, obj ) => {
	'use strict';

	/**
	 * Selectors map for Help Hub DOM elements.
	 *
	 * @since 6.8.2
	 */
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

						/* Header styling */
						.docsbot-chat-header {
							background-color: #ffffff !important;
							border-bottom: solid 1px #C3C4C7;
							color: #000000 !important;
							padding: 10px 24px;
						}

						/* Header content styling */
						.docsbot-chat-header-content h1 {
							text-align: left;
						}
						.docsbot-chat-header-content span {
							display: none;
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
						.docsbot-user-chat-message {
							background-color: #0057C7;
						}
						.docsbot-chat-header-button {
							z-index:99999;
						}
					`;

	/**
	 * Utility: Get the Help Hub page element.
	 *
	 * @since 6.8.2
	 * @return {HTMLElement|null}
	 */
	obj.getHelpHubPageElement = function () {
		return document.getElementById( obj.selectors.helpHubPageID );
	};

	/**
	 * Utility: Check if user is opted in.
	 *
	 * @since 6.8.2
	 * @return {boolean}
	 */
	obj.isOptedIn = function () {
		const el = obj.getHelpHubPageElement();
		return el && el.getAttribute( 'data-opted-in' ) === '1';
	};

	/**
	 * HelpScoutManager handles Help Scout Beacon integration.
	 *
	 * @since 6.8.2
	 * @class
	 * @link https://developer.helpscout.com/beacon-2/web/javascript-api
	 */
	function HelpScoutManager( beaconKey, userIdentifiers ) {
		this.beaconKey = beaconKey;
		this.userIdentifiers = userIdentifiers || null;
		this.scriptLoaded = false;
		this.beaconReady = false;
		this.initPromise = null;
	}

	/**
	 * Loads the Help Scout Beacon script dynamically.
	 *
	 * @since 6.8.2
	 * @return {Promise<void>}
	 */
	HelpScoutManager.prototype.loadScript = function () {
		const self = this;
		if ( self.scriptLoaded === true ) {
			return Promise.resolve();
		}

		// Initialize the Beacon stub.
		tribe.helpPage.BeaconManager.initStub();

		return new Promise( ( resolve, reject ) => {
			const script = document.createElement( 'script' );
			script.src = 'https://beacon-v2.helpscout.net';
			script.async = true;
			script.onload = () => {
				self.scriptLoaded = true;
				resolve();
			};
			script.onerror = () => {
				reject( new Error( helpHubSettings.errorMessages.helpScoutScriptLoadFailed ) );
			};
			// Insert into <head> for best compatibility.
			document.head.appendChild( script );
		} );
	};

	/**
	 * Initializes the Help Scout Beacon widget.
	 *
	 * @since 6.8.2
	 * @return {Promise<void>}
	 */
	HelpScoutManager.prototype.initBeacon = function () {
		const self = this;
		if ( self.initPromise !== null ) {
			return self.initPromise;
		}
		self.initPromise = new Promise( ( resolve ) => {
			tribe.helpPage.BeaconManager.init( self.beaconKey );

			// Set z-index, manual style, and enable chat & ticket history.
			tribe.helpPage.BeaconManager.config( {
				 display: { zIndex: 1000000, style: 'manual' },
				 messaging: {
					 chatEnabled: true,
					 previousMessagesEnabled: true,
					 contactForm: {
						 showName: true,
					 }
				 }
			} );

			// Listen for open/close events to manage blackout UI.
			tribe.helpPage.BeaconManager.on(
				'open',
				() => {
					obj.toggleBlackout( true );
				}
			);

			tribe.helpPage.BeaconManager.on(
				'close',
				() => {
					obj.toggleBlackout( false );
				}
			);

			tribe.helpPage.BeaconManager.on(
				'ready',
				() => {
					self.beaconReady = true;
					if ( self.userIdentifiers !== null && self.userIdentifiers.name && self.userIdentifiers.email ) {
						tribe.helpPage.BeaconManager.identify( {
							name: self.userIdentifiers.name,
							email: self.userIdentifiers.email
						} );
					}
					resolve();
				}
			);
		} );
		return self.initPromise;
	};

	/**
	 * Opens the Help Scout Beacon widget programmatically.
	 *
	 * @since 6.8.2
	 * @return {void}
	 */
	HelpScoutManager.prototype.openBeacon = function () {
		tribe.helpPage.BeaconManager.open();
	};

	// Store HelpScoutManager instance for later use.
	obj.helpScoutManager = null;

	/**
	 * Initializes the help page setup, verifying opt-in status.
	 *
	 * @since 6.3.2
	 * @return {void}
	 */
	obj.setup = function () {
		const bodyElement = obj.getHelpHubPageElement();
		const optOutMessageElement = document.querySelector( obj.selectors.optOutMessage );
		const $docsbotElement = $( `#${obj.selectors.docsbotWidget}` );
		const isOptedIn = obj.isOptedIn();

		if ( isOptedIn !== true ) {
			// Opt-out: show message, hide widgets, blackout.
			if ( optOutMessageElement !== null ) {
				optOutMessageElement.classList.remove( 'hide' );
			}
			if ( $docsbotElement.length ) {
				$docsbotElement.addClass( 'hide' );
			}
			if ( bodyElement !== null ) {
				bodyElement.classList.add( 'blackout' );
			}
			return;
		}

		// Initialize Help Scout Beacon.
		const beaconKey = helpHubSettings.helpScoutBeaconKey;
		const userIdentifiers = helpHubSettings.userIdentifiers || null;
		obj.helpScoutManager = new HelpScoutManager(
			beaconKey,
			userIdentifiers
		);
		obj.helpScoutManager.loadScript()
			.then( () => {
				return obj.helpScoutManager.initBeacon();
			} );
		// Initialize DocsBot as before.
		obj.initializeDocsBot();
		if ( $docsbotElement.length ) {
			$docsbotElement.removeClass( 'hide' );
		}
		if ( optOutMessageElement !== null ) {
			optOutMessageElement.classList.add( 'hide' );
		}
		if ( bodyElement !== null ) {
			bodyElement.classList.remove( 'blackout' );
		}
	};

	/**
	 * Expose a method to open the Help Scout Beacon programmatically.
	 *
	 * @since 6.8.2
	 * @return {void}
	 */
	obj.openBeacon = function () {
		if ( obj.helpScoutManager !== null ) {
			tribe.helpPage.BeaconManager.close();
			obj.helpScoutManager.openBeacon();
		}
	};

	/**
	 * Observes for the DocsBot element to be available in the DOM.
	 *
	 * @since 6.3.2
	 * @param {string} selector - The CSS selector of the element to observe.
	 * @return {Promise} - Resolves when the element becomes available.
	 */
	obj.observeElement = ( selector ) => {
		return new Promise( ( resolve ) => {
			const element = document.querySelector( selector );
			if ( element !== null ) {
				return resolve( element );
			}
			const observer = new MutationObserver( ( mutations ) => {
				const foundElement = document.querySelector( selector );
				if ( foundElement !== null ) {
					resolve( foundElement );
					observer.disconnect();
				}
			} );
			observer.observe(
				document.body,
				{ childList: true, subtree: true }
			);
		} );
	};

	/**
	 * Initializes the DocsBot widget, handling its configuration and integration with Help Scout Beacon.
	 *
	 * @link https://docsbot.ai/documentation/developer/embeddable-chat-widget
	 *
	 * @since 6.3.2
	 * @return {void}
	 */
	obj.initializeDocsBot = function () {
		if ( window.DocsBotAIInitialized === true ) {
			return;
		}
		window.DocsBotAIInitialized = true;
		const $docsbotWidget = $( `#${obj.selectors.docsbotWidget}` );
		if ( $docsbotWidget.length ) {
			$docsbotWidget.removeClass( 'hide' );
		}
		if ( typeof DocsBotAI.init !== 'function' ) {
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
								 obj.observeElement( '#docsbotai-root' )
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
		}
		DocsBotAI.init( {
							id: helpHubSettings.docsbot_key,
							options: {
								customCSS: obj.DocsBotAIcss,
							},
							supportCallback: ( event ) => {
								event.preventDefault();
								obj.toggleBlackout( true );
								obj.openBeacon();
							},
						} );
	};

	/**
	 * Toggles the blackout class on the Help Hub page.
	 *
	 * @since 6.8.2
	 * @param {boolean} enable
	 * @return {void}
	 */
	obj.toggleBlackout = function ( enable ) {
		const el = obj.getHelpHubPageElement();
		if ( el !== null ) {
			el.classList.toggle(
				'blackout',
				enable
			);
		}
	};

	// Initialize the help page.
	$( obj.setup );

	// For legacy compatibility: open Beacon when asked to open Livechat (from old help-page.js postMessage).
	window.addEventListener(
		'message',
		( event ) => {
			// Only accept messages from the same origin.
			if ( event.origin !== window.location.origin ) {
				return;
			}
			const { action, data } = event.data || {};
			// For legacy compatibility: open Beacon when asked to open Livechat.
			if ( action === 'runScript' && data === 'openLivechat' ) {
				if ( typeof obj.openBeacon === 'function' ) {
					obj.openBeacon();
				}
			}
		}
	);
} )(
	jQuery,
	tribe.helpPage
);
