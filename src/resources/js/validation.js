/**
 * Configures this Object in the Global Tribe variable
 *
 * @type {Object}
 */
tribe.validation = {};

( function( obj, $, _ ) {
	'use strict';
	var $document = $( document );

	/**
	 * Object containing all the selectors for Validation
	 *
	 * @since  TBD
	 *
	 * @type   {object}
	 */
	obj.selectors = {
		item: '.tribe-validation',
		fields: 'input, select, textarea',
		submit: 'input[type="submit"], button',
		error: '.tribe-validation-error',
	};

	/**
	 * FN (prototype) method from jQuery
	 *
	 * @since  TBD
	 *
	 * @type   {function}
	 */
	obj.fn = function () {
		return this.each( obj.setup );
	};

	/**
	 * Configures a Single Form for validation
	 *
	 * @since  TBD
	 *
	 * @type   {function}
	 */
	obj.setup = function ( i, item ) {
		var $item = $( item );

		// First we add the Class for the Form
		$item.addClass( obj.selectors.item.className() );

		// Prevent form normal invalidation to be triggered.
		$item.find( obj.selectors.submit ).on( 'click', obj.on_click_submit_buttons );

	};

	obj.on_validate = function ( event ) {
		var $item = $( this );

		console.log(  )
	};

	obj.on_submit = function ( event ) {
		var $item = $( this );

		$item.trigger( 'validation.tribe' );
	};


	/**
	 * Hijack the Browser the Invalidation for when we click publish
	 *
	 * Note that it this weird multi-method is required to go around
	 * the usage of 'invalid' event, which doesn't bubble up to 'form'
	 * only happens on the Field, which prevents us to use it on
	 * the ones that are created by JavaScript Templates
	 *
	 * @uses obj.on_invalid_field
	 *
	 * @return {void}
	 */
	obj.on_click_submit_buttons = function( event ) {
		var $submit = $( this );
		var $item = $submit.parents( obj.selectors.item );
		var $fields = $item.find( obj.selectors.fields );

		// Makes sure we don't have any invalid event on any fields.
		$fields.off( 'invalid.tribe' );

		// Configures one invalid trigger
		$fields.one( 'invalid.tribe', obj.on_invalid_field );
	};

	/**
	 * Add a class to mark fields that are invalid
	 *
	 * Mostly used right now to avoid datepickers from opening
	 *
	 * @uses obj.on_change_field_remove_error
	 *
	 * @return {void}
	 */
	obj.on_invalid_field = function( event ) {
		var $field = $( this );

		$field.addClass( obj.selectors.error.className() ).one( 'change', obj.on_change_field_remove_error );

		event.preventDefault();
		return false;
	};

	/**
	 * Removes error class on fields after they change
	 *
	 * @return {void}
	 */
	obj.on_change_field_remove_error = function( event ) {
		var $field = $( this );

		if ( $field.hasClass( obj.selectors.error.className() ) ) {
			$field.removeClass( obj.selectors.error.className() );
		}
	};

	/**
	 * Initializes the Validation for the base items
	 *
	 * @since  TBD
	 *
	 * @param  {object} event jQuery Event
	 *
	 * @type   {function}
	 */
	obj.on_ready = function ( event ) {
		$( obj.selectors.item ).validation();
	};

	/**
	 * Configures the jQuery Setup of the Validation
	 *
	 * @since  TBD
	 *
	 * @type   {function}
	 */
	$.fn.validation = obj.fn;

	/**
	 * Attaches ready method to the On Ready of Document
	 *
	 * @since  TBD
	 */
	$document.ready( obj.on_ready );
}( tribe.validation, jQuery, _ ) );
