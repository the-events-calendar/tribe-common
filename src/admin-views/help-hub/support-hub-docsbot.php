<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Template $this              The template object.
 * @var bool            $is_opted_in       Whether the user has opted in to telemetry.
 * @var bool            $is_license_valid  Whether the user has any valid licenses.
 * @var string          $zendesk_chat_key  The zendesk chat ID.
 * @var string          $docblock_chat_key The Docblock AI Key.
 */

?>
<style>

    /* Blackout overlay */
    .zendesk-widget-open::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        pointer-events: none;
    }

    iframe[name="Messaging window"] {
        margin-right: -16px;
        z-index: 99999999 !important; /* Make sure the Zendesk Iframe is above DocBot */
    }
	#launcher{
		width:90px;
	}

</style>

<div id="docsbot-widget-embed" style="height: 600px; max-height: 100vh;"></div>

<!-- Start of Zendesk Widget script -->
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=<?php echo urlencode( $zendesk_chat_key ); ?>"></script>
<script type="text/javascript">
	/**
	 * Zendesk Widget Handler Object
	 *
	 * Handles the initialization, event listening, and postMessage actions for the Zendesk widget.
	 */
	const zendeskWidget = {
		/**
		 * Selectors for commonly accessed DOM elements
		 *
		 * @property {Object} selectors
		 * @property {Element} selectors.body - The body element of the document
		 */
		selectors: {
			body: document.body,
		},

		/**
		 * Method to check if zE (Zendesk Web Widget) is defined, then execute the provided callback.
		 * Polls every 100ms until zE is available.
		 *
		 * @param {Function} callback - The function to execute once zE is defined.
		 */
		onZendeskReady( callback ) {
			if ( typeof zE !== 'undefined' ) {
				callback(); // zE is available, run the callback
			} else {
				// Check again after a short delay
				setTimeout( () => {
					this.onZendeskReady( callback );
				}, 100 ); // Poll every 100 milliseconds
			}
		},

		/**
		 * Method to initialize the Zendesk widget's actions, including hiding the widget and setting up event listeners.
		 * It listens for 'open' and 'close' events to add/remove a class from the body.
		 */
		initializeZendesk() {
			this.isZendeskInitialized = false;

			// Initially close the widget and set the initialization flag
			zE( 'messenger', 'hide', () => {
				this.isZendeskInitialized = true; // Set the flag after the initial hide
			} );

			/**
			 * Listen for 'open' event, but only trigger after initialization.
			 * Adds the 'zendesk-widget-open' class to the body when the widget is opened.
			 */
			zE( 'messenger:on', 'open', () => {
				if ( this.isZendeskInitialized ) {
					this.selectors.body.classList.add( 'zendesk-widget-open' );
				}
			} );

			/**
			 * Listen for 'close' event.
			 * Removes the 'zendesk-widget-open' class from the body when the widget is closed.
			 */
			zE( 'messenger:on', 'close', () => {
				zE( 'messenger', 'hide' );
				this.selectors.body.classList.remove( 'zendesk-widget-open' );
			} );
		},

		/**
		 * Method to handle incoming postMessage events.
		 * It verifies the message origin and triggers actions based on the message content.
		 *
		 * @param {Event} event - The postMessage event received from another window/iframe.
		 */
		handleMessages( event ) {
			// Security check: Ensure the message is from a trusted origin
			if ( event.origin !== window.location.origin ) {
				return; // Ignore messages from untrusted origins
			}

			// Extract message data
			const message = event.data;

			// Handle different actions using a switch statement
			switch ( message.action ) {
				case 'runScript':
					// Only run the script if the data is 'openZendesk'
					if ( message.data === 'openZendesk' ) {
						zE( 'messenger', 'show' );
						zE( 'messenger', 'open' );
						document.body.classList.add( 'zendesk-widget-open' );
					}
					break;

				default:
					console.warn( 'Unhandled action:', message.action );
					break;
			}
		},
		/**
		 * Setup method to initialize all functionality.
		 * It calls the onZendeskReady method to initialize the widget and sets up message listeners.
		 */
		setup() {
			this.onZendeskReady( this.initializeZendesk.bind( this ) );

			// Listen for incoming messages
			window.addEventListener( 'message', ( event ) => this.handleMessages( event ) );
		}
	};

	// Call the setup method to run everything
	zendeskWidget.setup();
</script>


<!-- End of Zendesk Widget script -->
<script type="text/javascript">
	window.DocsBotAI = window.DocsBotAI || {};
	DocsBotAI.init = function ( e ) {
		return new Promise( ( t, r ) => {
			var n = document.createElement( "script" );
			n.type = "text/javascript";
			n.async = true;
			n.src = "https://widget.docsbot.ai/chat.js";
			let o = document.getElementsByTagName( "script" )[ 0 ];
			o.parentNode.insertBefore( n, o );
			n.addEventListener( "load", () => {
				let n;
				Promise.all( [
					new Promise( ( t, r ) => {
						window.DocsBotAI.mount( Object.assign( {}, e ) ).then( t ).catch( r );
					} ),
					( n = function e( t ) {
						return new Promise( e => {
							if ( document.querySelector( t ) ) {
								return e( document.querySelector( t ) );
							}
							let r = new MutationObserver( n => {
								if ( document.querySelector( t ) ) {
									return e( document.querySelector( t ) ), r.disconnect();
								}
							} );
							r.observe( document.body, { childList: true, subtree: true } );
						} );
					} )( "#docsbotai-root" )
				] ).then( () => t() ).catch( r );
			} );
			n.addEventListener( "error", e => {
				r( e.message );
			} );
		} );
	};

	// Initialize the DocsBotAI widget
	DocsBotAI.init( {
		id: "<?php echo esc_html( $docblock_chat_key ); ?>",
		supportCallback: function ( event, history ) {
			event.preventDefault(); // Prevent default behavior opening the url.
			DocsBotAI.unmount(); // Hide the widget.
			// Open the Zendesk Web Widget.
			zE( 'messenger', 'show' );
			zE( 'messenger', 'open' );
		},
	} )
</script>


