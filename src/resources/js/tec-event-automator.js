/* eslint-disable template-curly-spacing */
/**
 * Makes sure we have all the required levels on the Tribe Object
 *
 * @since 1.0.0
 *
 * @type {PlainObject}
 */
tribe.events = tribe.events || {};

/**
 * Configures Event Automator Admin Object on the Global Tribe variable
 *
 * @since 1.0.0
 *
 * @type {PlainObject}
 */
tribe.events.automatorSettingsAdmin = tribe.events.automatorSettingsAdmin || {};

(function( $, obj, tribe_dropdowns ) {
	'use-strict';
	const $document = $( document );

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since 1.0.0
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		integrationContainer: '.tec-automator-settings',
		integrationAdd: '.tec-automator-settings__add-api-key-button',
		messageWrap: '.tec-automator-settings-message__wrap',

		// Individual Keys.
		integrationList: '.tec-automator-settings-items__wrap',
		integrationItem: '.tec-automator-settings-details__container',
		integrationName: '.tec-automator-settings-details__name-input',
		integrationUser: '.tec-settings-form__users-dropdown',
		integrationGenerate: '.tec-automator-settings-details-action__generate',
		integrationRevoke: '.tec-automator-settings-details-action__revoke',

		copyButton: '.tec-automator-settings__copy-btn',
		copyButtonTxt: '.tec-automator-settings__copy-btn-text',
		copySuccess: '.copy-success',
		copyFail: '.copy-fail',

		// Endpoint Dashboard
		dashboardContainer: '.tec-automator-dashboard',
		endpointContainer: '.tec-settings-connection-endpoint-dashboard-details__container',
		endpointActionButton: '.tec-settings-connection-endpoint-dashboard-details-action__button',
		endpointClearButton: '.tec-settings-connection-endpoint-dashboard-details-actions__clear',
		endpointDisableButton: '.tec-settings-connection-endpoint-dashboard-details-actions__disable',
		endpointEnableButton: '.tec-settings-connection-endpoint-dashboard-details-actions__enable',

		// Automator related selectors.
		automatorLoader: '.tribe-common-c-loader',
		automatorLoaderHiddenElement: '.tribe-common-a11y-hidden',
	};

	/**
	 * Scroll to bottom of list of API Keys.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $container The jQuery object of the integration's setting container.
	 */
	obj.scrollToBottom = function( $container ) {
		let totalHeight = 0;
		$container.find( obj.selectors.integrationItem ).each( function() {
			totalHeight += $( this ).outerHeight();
		} );

		$( obj.selectors.integrationList ).animate( {
			scrollTop: totalHeight
		}, 500 );
	};

	/**
	 * Validates the description and users field is setup for the key pair.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $integrationItem The jQuery object of the integration item wrap.
	 *
	 * @returns {boolean} Whether the description and user field has values.
	 */
	obj.validateFields = function( $integrationItem ) {
		const integrationName = $integrationItem.find( obj.selectors.integrationName ).val();
		const intergrationUser = $integrationItem.find( `${obj.selectors.integrationUser} option:selected` ).val(); // eslint-disable-line max-len
		if ( integrationName && intergrationUser ) {
			return true;
		}

		return false;
	};

	/**
	 * Handles the successful response from the backend to add API Key fields.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} html The HTML that adds a message and the page fields html.
	 * @param {object} event_target The target element of the event.
	 */
	obj.onAddApiKeyFieldsSuccess = function( html, event_target ) {
		const $this = $( event_target );
		const $container = $this.closest( obj.selectors.integrationContainer );
		const message = $( html ).filter( obj.selectors.messageWrap );
		const integrationItemWrap = $( html ).filter( obj.selectors.integrationItem );

		$container.find( obj.selectors.messageWrap ).html( message );

		if ( 0 === integrationItemWrap.length ) {
			return;
		}

		$container.find( obj.selectors.integrationList ).append( integrationItemWrap );

		// Setup dropdowns after loading api key fields.
		const $dropdowns = $( obj.selectors.integrationList )
			.find( tribe_dropdowns.selector.dropdown )
			.not( tribe_dropdowns.selector.created );

		$dropdowns.tribe_dropdowns();

		obj.hide( $container );

		obj.scrollToBottom( $container );
	};


	/**
	 * Handles adding a new API Key fields.
	 *
	 * @since 1.0.0
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleAddApiKey = function( event ) {
		event.preventDefault();
		const url = $( event.target ).attr( 'href' );

		const $integrationContainer = $( event.target ).closest( obj.selectors.integrationContainer );
		obj.show( $integrationContainer );

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $( obj.selectors.integrationList ),
				success: ( html ) => obj.onAddApiKeyFieldsSuccess( html, event.target ) ,
			}
		);
	};

	/**
	 * Handles the successful response from the backend to generate new API Key pair.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} html The HTML that adds a message and the page fields html.
	 * @param {object} event_target The target element of the event.
	 */
	obj.onGenerateKeySuccess = function( html, event_target ) {
		const $this = $( event_target );
		const $container = $this.closest( obj.selectors.integrationContainer );
		const $message = $( html ).filter( obj.selectors.messageWrap );
		const $integrationItemWrap = $( html ).filter( obj.selectors.integrationItem );

		$container.find( obj.selectors.messageWrap ).html( $message );
		obj.hide( $container );

		if ( 0 === $integrationItemWrap.length ) {
			return;
		}

		const consumerId = $integrationItemWrap.data( 'consumerId' );
		const existingPage = $document.find( `[data-consumer-id='${consumerId}']` );
		existingPage.replaceWith( $integrationItemWrap );

		obj.scrollToBottom( $container );
	};

	/**
	 * Handles generating consumer id and secret.
	 *
	 * @since 1.0.0
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleGenerateKey = function( event ) {
		event.preventDefault();

		const $this = $( event.target ).closest( obj.selectors.integrationGenerate );
		const url = $this.data( 'ajaxGenerateUrl' );
		const $integrationItem = $this.closest( obj.selectors.integrationItem );
		const is_valid = obj.validateFields( $integrationItem );
		if ( ! is_valid ) {
			window.alert( $this.data( 'generateError' ) );
		}

		const consumerId = $integrationItem.data( 'consumerId' );
		const integrationName = $integrationItem.find( obj.selectors.integrationName ).val();
		const intergrationUser = $integrationItem.find( `${obj.selectors.integrationUser} option:selected` ).val(); // eslint-disable-line max-len
		const permissions = 'read';

		const $integrationContainer = $this.closest( obj.selectors.integrationContainer );
		obj.show( $integrationContainer );

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $this.closest( obj.selectors.integrationItem ),
				data: {
					consumer_id: consumerId,
					name: integrationName,
					user_id: intergrationUser,
					permissions: permissions,
				},
				success: ( data ) => obj.onGenerateKeySuccess( data, $this ),
			}
		);
	};


	/**
	 * Handles the successful response from the backend to revoke API Keys.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} html The HTML that adds a message on the settings page.
	 */
	obj.onRevokeSuccess = function( html ) {
		const $integrationContainer = $( obj.selectors.integrationContainer );

		$integrationContainer.find( obj.selectors.messageWrap ).html( html );

		obj.hide( $integrationContainer );

		// Delete marked integration wrap.
		$( `${obj.selectors.integrationItem}.to-delete` ).remove();
	};

	/**
	 * Handles revoking an integration key.
	 *
	 * @since 1.0.0
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleRevoke = function( event ) {
		event.preventDefault();

		const $this = $( event.target ).closest( obj.selectors.integrationRevoke );
		const url = $this.data( 'ajaxRevokeUrl' );
		const $integrationItem = $this.closest( obj.selectors.integrationItem );
		const consumerId = $integrationItem.data( 'consumerId' );
		const confirmed = confirm( $this.data( 'confirmation' ) );
		if ( ! confirmed ) {
			return;
		}

		const $integrationContainer = $this.closest( obj.selectors.integrationContainer );
		obj.show( $integrationContainer );

		// Add a class to mark for deletion.
		$integrationItem.addClass( 'to-delete' );

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $this.closest( obj.selectors.integrationItem ),
				data: {
					consumer_id: consumerId,
				},
				success: obj.onRevokeSuccess,
			}
		);
	};

	/**
	 * Bind the integration events.
	 *
	 * @since 1.0.0
	 */
	obj.setupClipboard = function() {
		//Prevent Copy Button From Doing Anything Else
		$document.on(
			'click',
			`${ obj.selectors.copyButton }, ${ obj.selectors.copySuccess }, ${ obj.selectors.copyFail }`, // eslint-disable-line max-len
			function( e ) {
				e.preventDefault();
			}
		);

		const clipboard = new ClipboardJS( obj.selectors.copyButton );
		const button_text = tec_automator.clipboard_btn_text;

		clipboard.on( 'success', function( event ) {
			let copyText = event.trigger.querySelector( obj.selectors.copyButtonTxt );
			event.clearSelection();

			copyText.innerHTML = `
				<span class="${obj.selectors.copySuccess.replace( /\./g, '' )}">
					${tec_automator.clipboard_copied_text}
				<span>`;
			window.setTimeout( function() {
				copyText.innerHTML = button_text;
			}, 5000 );
		} );

		clipboard.on( 'error', function( event ) {
			let copyText = event.trigger.querySelector( obj.selectors.copyButtonTxt );

			copyText.innerHTML = `
				<span class="${obj.selectors.copyFail.replace( /\./g, '' )}">
					${tec_automator.clipboard_fail_text}
				<span>`;
			window.setTimeout( function() {
				copyText.innerHTML = button_text;
			}, 5000 );
		} );
	};

	/**
	 * Handles Endpoint actions in the dashboard.
	 *
	 * @since 1.4.0
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleEndpointAction = function( event ) {
		event.preventDefault();
		const $this = $( event.target ).closest( obj.selectors.endpointActionButton );
		const url = $this.data( 'ajaxActionUrl' );
		const $endPointContainer = $this.closest( obj.selectors.endpointContainer );
		const endpointId = $endPointContainer.data('endpointId');
		const confirmed = confirm( $this.data( 'confirmation' ) );
		if ( ! confirmed ) {
			return;
		}

		const $dashboardContainer = $this.closest( obj.selectors.dashboardContainer );
		obj.show( $dashboardContainer );

		// Add a class to mark for update.
		$endPointContainer.addClass( 'to-update' );

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $this.closest( obj.selectors.endpointContainer ),
				data: {
					endpoint_id: endpointId,
				},
				success: ( data ) => obj.onEndpointActionSuccess( data, $this ),
			}
		);
	};

	/**
	 * Handles the successful response from the backend to revoke API Keys.
	 *
	 * @since 1.4.0
	 *
	 * @param {string} html The HTML that adds a message on the settings page.
	 * @param {object} event_target The target element of the event.
	 */
	obj.onEndpointActionSuccess = function( html, event_target ) {
		const $this = $( event_target );
		const $dashboardContainer = $this.closest( obj.selectors.dashboardContainer );
		const $message = $( html ).filter( obj.selectors.messageWrap );

		$dashboardContainer.find( obj.selectors.messageWrap ).html( $message );

		obj.hide( $dashboardContainer );

		// Update marked endpoint wrap.
		const $endpointContainer = $( html ).filter( obj.selectors.endpointContainer );
		if ( 0 === $endpointContainer.length ) {
			return;
		}

		$( `${obj.selectors.endpointContainer}.to-update` ).replaceWith( $endpointContainer );

	};

	/**
	 * Show loader for the container.
	 *
	 * @since 1.4.0
	 *
	 * @param {jQuery} $container jQuery object of the container.
	 *
	 * @return {void}
	 */
	obj.show = function( $container ) {
		const $loader = $container.find( obj.selectors.automatorLoader );

		if ( $loader.length ) {
			$loader.removeClass( obj.selectors.automatorLoaderHiddenElement.className() );
		}
	};

	/**
	 * Hide loader for the container.
	 *
	 * @since 1.4.0
	 *
	 * @param {jQuery} $container jQuery object of the container.
	 *
	 * @return {void}
	 */
	obj.hide = function( $container ) {
		const $loader = $container.find( obj.selectors.automatorLoader );

		if ( $loader.length ) {
			$loader.addClass( obj.selectors.automatorLoaderHiddenElement.className() );
		}
	};

	/**
	 * Bind the integration events.
	 *
	 * @since 1.0.0
	 */
	obj.bindEvents = function() {
		$document
			.on( 'click', obj.selectors.integrationGenerate, obj.handleGenerateKey )
			.on( 'click', obj.selectors.integrationRevoke, obj.handleRevoke )
			.on( 'click', obj.selectors.endpointClearButton, obj.handleEndpointAction )
			.on( 'click', obj.selectors.endpointDisableButton, obj.handleEndpointAction )
			.on( 'click', obj.selectors.endpointEnableButton, obj.handleEndpointAction );
		$( obj.selectors.integrationContainer )
			.on( 'click', obj.selectors.integrationAdd, obj.handleAddApiKey );
	};

	/**
	 * Unbind the integration events.
	 *
	 * @since 1.0.0
	 */
	obj.unbindEvents = function() {};

	/**
	 * Handles the initialization of the admin when Document is ready
	 *
	 * @since 1.0.0
	 */
	obj.ready = function() {
		obj.setupClipboard();
		obj.bindEvents();
	};

	// Configure on document ready
	$( obj.ready );
})( jQuery, tribe.events.automatorSettingsAdmin, tribe_dropdowns );
