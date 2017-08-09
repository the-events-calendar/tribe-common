/**
 * Configures this Object in the Global Tribe variable
 *
 * @since  TBD
 *
 * @type   {Object}
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
		valid: '.tribe-validation-valid',
	};

	/**
	 * Object containing all the conditions for the Fields
	 *
	 * @since  TBD
	 *
	 * @type   {object}
	 */
	obj.conditions = {
		required: function( val ) {
			return '' == val;
		}
	};

	/**
	 * FN (prototype) method from jQuery
	 *
	 * @since  TBD
	 *
	 * @type   {function}
	 */
	obj.fn = function() {
		return this.each( obj.setup );
	};

	/**
	 * Configures a Single Form for validation
	 *
	 * @since  TBD
	 *
	 * @param  {int}  index  Field Index
	 * @param  {DOM}  item   DOM element for the item
	 *
	 * @type   {function}
	 */
	obj.setup = function( i, item ) {
		var $item = $( item );

		// First we add the Class for the Form
		$item.addClass( obj.selectors.item.className() );

		// On Form Submit
		$item.on( 'submit.tribe', obj.onSubmit );

		// Actual Validation
		$item.on( 'validation.tribe', obj.onValidation );

		// Show the errors for all the fields
		$item.on( 'displayErrors.tribe', obj.onDisplayErrors );

		// Prevent form normal invalidation to be triggered.
		$document.on( 'click.tribe', obj.selectors.submit, obj.onClickSubmitButtons );
	};

	/**
	 * Validates a single Field
	 *
	 * @since  TBD
	 *
	 * @param  {int}  index  Field Index
	 * @param  {DOM}  item   DOM element for the field
	 *
	 * @return {void}
	 */
	obj.validate = function( index, field ) {
		var $field = $( field );
		var isValid = obj.isValid( $field );

		// If it's valid we bail
		if ( isValid ) {
			return;
		}

		$field.addClass( obj.selectors.error.className() );
		$field.one( 'change', obj.onChangeFieldRemoveError );
	};

	/**
	 * Validates a single Field
	 *
	 * @since  TBD
	 *
	 * @param  {object}  $field  jQuery Object for the field
	 *
	 * @return {bool}
	 */
	obj.isValid = function( $field ) {
		var valid = true;
		var value = $field.val();
		var isDisabled = $field.is( ':disabled' );
		var constraints = {
			required: $field.data( 'required' ) || $field.is( '[data-required]' ) || $field.is( '[required]' ) || false,
		};

		// Bail if it's a disabled field
		if ( isDisabled ) {
			return valid;
		}

		// Check which ones of these are valid
		constraints = _.pick( constraints, function( isApplicable ) {
			return isApplicable;
		} );

		// Verifies if we have a valid set of constraints
		valid = _.reduce( constraints, function( passes, constraint, key ) {
			return passes || obj.conditions[ key ]( value, constraint, $field );
		}, true );

		return valid;
	};

	/**
	 * Actually does the validation for the Form
	 *
	 * @since  TBD
	 *
	 * @param  {object} event JQuery Event
	 *
	 * @return {void|false}
	 */
	obj.onValidation = function( event ) {
		var $item = $( this );
		var $fields = $item.find( obj.selectors.fields );

		$fields.each( obj.validate );

		var $errors = $item.find( obj.selectors.error );

		// if there are errors we show the message and bail
		if ( 0 !== $errors.length ) {
			$item.trigger( 'displayErrors.tribe' );
			return;
		}

		// If we got here add the valid class
		$item.addClass( obj.selectors.valid.className() );
	};

	/**
	 * Fired on `displayErrors` for a validation form
	 *
	 * @since  TBD
	 *
	 * @param  {object} event JQuery Event
	 *
	 * @return {void}
	 */
	obj.onDisplayErrors = function( event ) {
		var $item = $( this );
		var $wpHeaderEnd = $( '.wp-header-end' );
		var $container = $( '<div>' ).addClass( 'notice notice-error tribe-notice' );
		var $errors = $item.find( obj.selectors.error );
		var $list = $( '<ul>' );

		$errors.each( function( i, field ) {
			var $field = $( field );
			var message = $field.data( 'validationError' );
			var $listItem = $( '<li>' ).text( message );
			$list.append( $listItem );
		} );

		$container.append( $list );

		$wpHeaderEnd.after( $container );
	};

	/**
	 * Hooks to the submit and if invalid prevents submit to happen
	 *
	 * @since  TBD
	 *
	 * @param  {object} event JQuery Event
	 *
	 * @return {void|false}
	 */
	obj.onSubmit = function( event ) {
		var $item = $( this );

		$item.trigger( 'validation.tribe' );

		var isValid = $item.is( obj.selectors.valid );

		// When Invalid we prevent the submit to happen
		if ( ! isValid ) {
			event.preventDefault();
			return false;
		}
	};

	/**
	 * Hijack the Browser the Invalidation
	 *
	 * Note that it this weird multi-method is required to go around
	 * the usage of 'invalid' event, which doesn't bubble up to 'form'
	 * only happens on the Field, which prevents us to use it on
	 * the ones that are created by JavaScript Templates
	 *
	 * @since  TBD
	 *
	 * @uses   obj.onInvalidField
	 *
	 * @param  {object} event JQuery Event
	 *
	 * @return {void}
	 */
	obj.onClickSubmitButtons = function( event ) {
		var $submit = $( this );
		var $item = $submit.parents( obj.selectors.item );

		// If we are not inside of the Validation just bail
		if ( 0 === $item.length ) {
			return;
		}

		var $fields = $item.find( obj.selectors.fields );

		// Makes sure we don't have any invalid event on any fields.
		$fields.off( 'invalid.tribe' );

		// Configures one invalid trigger
		$fields.one( 'invalid.tribe', obj.onInvalidField );
	};

	/**
	 * Add a class to mark fields that are invalid and add an one time
	 * event for these same fields to remove the class on `change`
	 *
	 * @since  TBD
	 *
	 * @uses obj.onChangeFieldRemoveError
	 *
	 * @param  {object} event JQuery Event
	 *
	 * @return {void|false}
	 */
	obj.onInvalidField = function( event ) {
		var $field = $( this );
		var $item = $field.parents( obj.selectors.item );

		// Adds the Class for marking the field with an error
		$field.addClass( obj.selectors.error.className() );

		// Shows the errors
		$item.trigger( 'displayErrors.tribe' );

		// Adds the Change event to allow removing the error class
		$field.one( 'change', obj.onChangeFieldRemoveError );

		event.preventDefault();
		return false;
	};

	/**
	 * Removes error class on fields after they change
	 *
	 * @return {void}
	 */
	obj.onChangeFieldRemoveError = function( event ) {
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
	 * @return {void}
	 */
	obj.onReady = function( event ) {
		$( obj.selectors.item ).validation();
	};

	/**
	 * Configures the jQuery Setup of the Validation
	 *
	 * @since  TBD
	 *
	 * @return {void}
	 */
	$.fn.validation = obj.fn;

	/**
	 * Attaches ready method to the On Ready of Document
	 *
	 * @since  TBD
	 */
	$document.ready( obj.onReady );
}( tribe.validation, jQuery, _ ) );
