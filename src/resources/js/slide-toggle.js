/* eslint-disable no-var */
/**
 * Makes sure we have all the required levels on the Tribe Object
 *
 * @since TBD
 *
 * @type {PlainObject}
 */
tribe.events = tribe.events || {};

/**
 * Configures Slide Toggle Object on the Global Tribe variable
 *
 * @since TBD
 *
 * @type {PlainObject}
 */
tribe.events.slideToggle = tribe.events.slideToggle || {};

/**
 * Initializes in a Strict env the code that manages the Slide Toggle.
 *
 * @since TBD
 *
 * @param  {PlainObject} $   jQuery
 * @param  {PlainObject} obj tribe.events.slideToggle
 *
 * @return {void}
 */
(function ( $, obj ) {
	'use-strict';
	var $document = $( document ); // eslint-disable-line no-var

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since TBD
	 *
	 * @type {PlainObject}
	 */
	obj.selector = {
		slideButton: '.tribe-common-slide-toggle__button',
		slideContent: '.tribe-common-slide-toggle__content',
		slideWrap: '.tribe-common-slide-toggle',
	};

	/**
	 * Handles the click on a slide toggle button.
	 *
	 * @since TBD
	 *
	 * @param {Event} ev The click event.
	 */
	obj.handleSlideToggle = function( ev ) {
		ev.preventDefault();

		// Set button and toggle aria-expanded.
		var $button = $( this );
		$button.attr( 'aria-expanded', function ( i, attr ) {
			return 'true' === attr ? 'false' : 'true';
		} );

		// Set parent and find panel to display and toggle aria-hidden.
		var $parent = $button.closest( obj.selector.slideWrap );
		$parent.toggleClass( 'active' );
		$parent.find( obj.selector.slideContent ).slideToggle().attr( 'aria-hidden', function ( i, attr ) {
			return 'true' === attr ? 'false' : 'true';
		} );
	};

	/**
	 * Bind events for slide toggle.
	 *
	 * @since TBD
	 *
	 * @return {void}
	 */
	obj.bindEvents = function() {
		$document.on( 'click', obj.selector.slideButton, obj.handleSlideToggle );
	};

	/**
	 * Handles the initialization of the admin when Document is ready
	 *
	 * @since 1.0.0
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		obj.bindEvents();
	};

	// Configure on document ready
	$( obj.ready );
})( jQuery, tribe.events.slideToggle );
