/* eslint-disable template-curly-spacing */
/**
 * Makes sure we have all the required levels on the Tribe Object
 *
 * @since TBD
 *
 * @type {PlainObject}
 */
tribe.events = tribe.events || {};

/**
 * Configures Integrations Admin Object on the Global Tribe variable
 *
 * @since TBD
 *
 * @type {PlainObject}
 */
tribe.events.integrationsSettingsAdmin = tribe.events.integrationsSettingsAdmin || {};

( function( $, obj, tribe_dropdowns ) {
	'use-strict';
	const $document = $( document );

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since TBD
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		integrationContainer: '.tec-settings-integrations',
		integrationAdd: '.tec-settings-integrations__add-api-key-button',
		messageWrap: '.tec-settings-integrations-message__wrap',

		// Individual Keys.
		integrationList: '.tec-settings-integrations-items__wrap',
		integrationItem: '.tec-settings-integrations-details__container',
		integrationName : '.tec-settings-integrations-details__name-input',
		integrationUser: '.tec-settings__users-dropdown',
		integrationGenerate: '.tec-settings-integrations-details-action__generate',
		integrationRevoke: '.tec-common-integrations-details-action__revoke',
	};

	/**
	 * Scroll to bottom of list of API Keys.
	 *
	 * @since TBD
	 */
	obj.scrollToBottom = function() {
		$( obj.selectors.integrationList ).animate( {
			scrollTop: $( obj.selectors.integrationList ).offset().top
		}, 500 );
	};

	/**
	 * Enables generating an API Key once a name is added and a user selected.
	 *
	 * @since TBD
	 */
	obj.handleEnableSave = function() {
		const $this = $( this );
		const $apiKey = $this.closest( obj.selectors.integrationItem );
		const integrationName = $apiKey.find( obj.selectors.integrationName ).val();
		const intergrationUser = $apiKey.find( `${ obj.selectors.integrationUser } option:selected` ).val(); // eslint-disable-line max-len
		const $generateInput = $apiKey.find( obj.selectors.integrationGenerate );

		$generateInput.prop( 'disabled', true );
		if ( integrationName && intergrationUser ) {
			$generateInput.prop( 'disabled', false );
		}
	};

	/**
	 * Handles the successful response from the backend to add API Key fields.
	 *
	 * @since TBD
	 *
	 * @param {string} html The HTML that adds a message and the page fields html.
	 */
	obj.onApiKeySuccess = function( html ) {
		const message = $( html ).filter( obj.selectors.messageWrap );
		const integrationItemWrap = $( html ).filter( obj.selectors.integrationItem );

		$( obj.selectors.messageWrap ).html( message );

		if ( 0 === integrationItemWrap.length ) {
			return;
		}

		$( obj.selectors.integrationList ).append( integrationItemWrap );

		// Setup dropdowns after loading api key fields.
		const $dropdowns = $( obj.selectors.integrationList )
					.find( tribe_dropdowns.selector.dropdown )
					.not( tribe_dropdowns.selector.created );

		$dropdowns.tribe_dropdowns();

		obj.scrollToBottom();
	};


	/**
	 * Handles adding a new API Key fields.
	 *
	 * @since TBD
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleAddApiKey = function( ev ) {
		ev.preventDefault();
		const url = $( this ).attr( 'href' );

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $( obj.selectors.integrationList ),
				success: obj.onApiKeySuccess,
			}
		);
	};

	/**
	 * Handles the successful response from the backend to generate new API Key pair.
	 *
	 * @since TBD
	 *
	 * @param {string} html The HTML that adds a message and the page fields html.
	 */
	obj.onGenerateKeySuccess = function( html ) {
		const $message = $( html ).filter( obj.selectors.messageWrap );
		const $integrationItemWrap = $( html ).filter( obj.selectors.integrationItem );

		$( obj.selectors.messageWrap ).html( $message );

		if ( 0 === $integrationItemWrap.length ) {
			return;
		}

		const consumerId = $integrationItemWrap.data( 'consumerId' );
		const existingPage = $document.find( `[data-consumer-id='${consumerId}']` );
		existingPage.replaceWith( $integrationItemWrap );

		//@todo this did not work.
		obj.scrollToBottom();
	};

	/**
	 * Handles generating consumer id and secret.
	 *
	 * @since TBD
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleGenerateKey = function( ev ) {
		ev.preventDefault();

		const $this = $( this );
		const url = $this.data( 'ajaxGenerateUrl' );
		const $integrationItem = $this.closest( obj.selectors.integrationItem );
		const consumerId = $integrationItem.data( 'consumerId' );
		const integrationName = $integrationItem.find( obj.selectors.integrationName ).val();
		const intergrationUser = $integrationItem.find( `${ obj.selectors.integrationUser } option:selected` ).val(); // eslint-disable-line max-len
		const permissions = 'read'

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $( this ).closest( obj.selectors.integrationItem ),
				data: {
					consumer_id: consumerId,
					name: integrationName,
					user_id: intergrationUser,
					permissions: permissions,
				},
				success: obj.onGenerateKeySuccess,
			}
		);
	};


	/**
	 * Handles the successful response from the backend to revoke API Keys.
	 *
	 * @since TBD
	 *
	 * @param {string} html The HTML that adds a message on the settings page.
	 */
	obj.onRevokeSuccess = function( html ) {
		$( obj.selectors.messageWrap ).html( html );

		// Delete marked integration wrap.
		$( `${ obj.selectors.integrationItem }.to-delete` ).remove();
	};

	/**
	 * Handles revoking an integration key.
	 *
	 * @since TBD
	 *
	 * @param {Event} event The click event.
	 */
	obj.handleRevoke = function( event ) {
		event.preventDefault();

		const $this = $( this );
		const url = $this.data( 'ajaxRevokeUrl' );
		const $integrationItem = $this.closest( obj.selectors.integrationItem );
		const consumerId = $integrationItem.data( 'consumerId' );
		const confirmed = confirm( $( this ).data( 'confirmation' ) );
		if ( ! confirmed ) {
			return;
		}

		// Add a class to mark for deletion.
		$integrationItem.addClass( 'to-delete' );

		$.ajax(
			url,
			{
				contentType: 'application/json',
				context: $( this ).closest( obj.selectors.integrationItem ),
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
	 * @since TBD
	 */
	obj.bindEvents = function() {
		$document
			.on(
				'change',
				`${obj.selectors.integrationName}, ${obj.selectors.integrationUser}`,
				obj.handleEnableSave
			)
			.on( 'click', obj.selectors.integrationGenerate, obj.handleGenerateKey )
			.on( 'click', obj.selectors.integrationRevoke, obj.handleRevoke );
		$( obj.selectors.integrationContainer )
			.on( 'click', obj.selectors.integrationAdd, obj.handleAddApiKey );
	};

	/**
	 * Handles the initialization of the admin when Document is ready
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		obj.bindEvents();
	};

	// Configure on document ready
	$( obj.ready );
} )( jQuery, tribe.events.integrationsSettingsAdmin, tribe_dropdowns );
