/**
 * Configures this Object in the Global Tribe variable
 *
 * @since 4.7
 *
 * @type   {Object}
 */
window.tribe.validation = {};

dayjs.extend( window.dayjs_plugin_customParseFormat );

( function ( obj, $, _ ) {
	'use strict';
	const $document = $( document );

	/**
	 * Object containing all the selectors for Validation
	 *
	 * @since 4.7.1
	 *
	 * @type   {Object}
	 */
	obj.selectors = {
		item: '.tribe-validation',
		fields: 'input, select, textarea',
		submit: '.tribe-validation-submit',
		submitButtons: 'input[type="submit"], button[type="submit"]',
		error: '.tribe-validation-error',
		valid: '.tribe-validation-valid',
		notice: '.tribe-notice-validation',
		noticeAfter: '.wp-header-end',
		noticeFallback: '.wrap > h1',
		noticeDismiss: '.notice-dismiss',
	};

	/**
	 * Object containing all the conditions for the Fields
	 *
	 * @since 4.7
	 *
	 * @type   {Object}
	 */
	obj.conditions = {
		isRequired( value ) {
			return '' != value; // eslint-disable-line eqeqeq
		},
		isGreaterThan( value, constraint, $field ) {
			const condition = obj.parseCondition( 'isGreaterThan', value, constraint, $field );

			// If we failed to parse Condition we don't check
			if ( false === condition ) {
				return true;
			}

			return condition.constraint < condition.value;
		},
		isGreaterOrEqualTo( value, constraint, $field ) {
			const condition = obj.parseCondition( 'isGreaterOrEqualTo', value, constraint, $field );

			// If we failed to parse Condition we don't check
			if ( false === condition ) {
				return true;
			}

			return condition.constraint <= condition.value;
		},
		isLessThan( value, constraint, $field ) {
			const condition = obj.parseCondition( 'isLessThan', value, constraint, $field );

			// If we failed to parse Condition we don't check
			if ( false === condition ) {
				return true;
			}

			return condition.constraint > condition.value;
		},
		isLessOrEqualTo( value, constraint, $field ) {
			const condition = obj.parseCondition( 'isLessOrEqualTo', value, constraint, $field );

			// If we failed to parse Condition we don't check
			if ( false === condition ) {
				return true;
			}

			return condition.constraint >= condition.value;
		},
		isEqualTo( value, constraint, $field ) {
			const condition = obj.parseCondition( 'isEqualTo', value, constraint, $field );

			// If we failed to parse Condition we don't check
			if ( false === condition ) {
				return true;
			}

			return condition.constraint == condition.value; // eslint-disable-line eqeqeq
		},
		isNotEqualTo( value, constraint, $field ) {
			const condition = obj.parseCondition( 'isNotEqualTo', value, constraint, $field );

			// If we failed to parse Condition we don't check
			if ( false === condition ) {
				return true;
			}

			return condition.constraint != condition.value; // eslint-disable-line eqeqeq
		},
		matchRegExp( value, constraint, $field ) {
			// eslint-disable-line no-unused-vars
			const exp = new RegExp( constraint, 'g' );
			const match = exp.exec( value );

			return null !== match;
		},
		notMatchRegExp( value, constraint, $field ) {
			// eslint-disable-line no-unused-vars
			const exp = new RegExp( constraint, 'g' );
			const match = exp.exec( value );

			return null === match;
		},
	};

	/**
	 * Object containing types of fields supported
	 *
	 * @since 4.7
	 *
	 * @type   {Object}
	 */
	obj.parseType = {
		datepicker( value, $constraint, $field ) {
			// eslint-disable-line no-unused-vars
			const formats = [
				'yyyy-mm-dd',
				'm/d/yyyy',
				'mm/dd/yyyy',
				'd/m/yyyy',
				'dd/mm/yyyy',
				'm-d-yyyy',
				'mm-dd-yyyy',
				'd-m-yyyy',
				'dd-mm-yyyy',
				'yyyy.mm.dd',
				'mm.dd.yyyy',
				'dd.mm.yyyy',
			];

			// Default Format Key
			let formatKey = 0;

			if ( $constraint.length && $constraint.attr( 'data-datepicker_format' ) ) {
				formatKey = $constraint.attr( 'data-datepicker_format' );
			} else if ( _.isString( formats[ $constraint ] ) ) {
				formatKey = formats[ $constraint ];
			} else if ( $constraint.parents( '[data-datepicker_format]' ).length ) {
				formatKey = $constraint.parents( '[data-datepicker_format]' ).eq( 0 ).data( 'datepicker_format' );
			}

			if ( 'undefined' === typeof formats[ formatKey ] || ! formats[ formatKey ] ) {
				formatKey = 0;
			}

			const format = formats[ formatKey ].toUpperCase();
			value = dayjs( value, format ).valueOf();

			return value;
		},
		default( value, $constraint, $field ) {
			// eslint-disable-line no-unused-vars
			if ( $.isNumeric( value ) ) {
				value = parseFloat( value, 10 );
			}

			return value;
		},
	};

	/**
	 * Parses the Condition for all the types of conditional and returns a
	 * better state of Value and Contraint based on the rules for each
	 *
	 * @since 4.7
	 *
	 * @type   {Function}
	 *
	 * @return {Object}
	 */
	obj.parseCondition = function ( conditional, value, constraint, $field ) {
		let type = $field.data( 'validationType' );
		let $constraint = null;
		const condition = { value, constraint };

		// When we don't have type we assume default
		if ( ! type && ! _.isFunction( obj.parseType[ type ] ) ) {
			type = 'default';
		}

		// If it's not Numeric we treat it like a Selector
		if ( ! $.isNumeric( constraint ) ) {
			$constraint = $( constraint );

			// Check if we got a valid selector before checking Disabled
			if ( ! $constraint.length ) {
				// Throws a warning so it's easy to spot on development and support
				console.warn( 'Tribe Validation:', 'Invalid selector for', $field, constraint );
				return false;
			}

			$constraint = $constraint.not( ':disabled' );

			// Verify only for active fields
			if ( ! $constraint.length ) {
				return false;
			}

			constraint = $constraint.val();
		}

		// Applies the type of validation
		condition.constraint = obj.parseType[ type ]( constraint, $constraint, $field );
		condition.value = obj.parseType[ type ]( value, $constraint, $field );

		return condition;
	};

	/**
	 * Object containing all the constraints for the Fields
	 *
	 * @since 4.7
	 *
	 * @type   {Object}
	 */
	obj.constraints = {
		isRequired( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// Verify by Data value
			value = $field.data( 'required' ) || value;
			value = $field.data( 'validationRequired' ) || value;
			value = $field.data( 'validationIsRequired' ) || value;

			// Verify by Attributes
			value = $field.is( '[required]' ) || value;
			value = $field.is( '[data-required]' ) || value;
			value = $field.is( '[data-validation-required]' ) || value;
			value = $field.is( '[data-validation-is-required]' ) || value;

			return value;
		},
		isGreaterThan( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-is-greater-than]' ) ) {
				value = $field.data( 'validationIsGreaterThan' );
			}

			return value;
		},
		isGreaterOrEqualTo( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-is-greater-or-equal-to]' ) ) {
				value = $field.data( 'validationIsGreaterOrEqualTo' );
			}

			return value;
		},
		isLessThan( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-is-less-than]' ) ) {
				value = $field.data( 'validationIsLessThan' );
			}

			return value;
		},
		isLessOrEqualTo( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-is-less-or-equal-to]' ) ) {
				value = $field.data( 'validationIsLessOrEqualTo' );
			}

			return value;
		},
		isEqualTo( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-is-equal-to]' ) ) {
				value = $field.data( 'validationIsEqualTo' );
			}

			return value;
		},
		isNotEqualTo( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-is-not-equal-to]' ) ) {
				value = $field.data( 'validationIsNotEqualTo' );
			}

			return value;
		},
		matchRegExp( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-match-regexp]' ) ) {
				value = $field.data( 'validationMatchRegexp' );
			}

			return value;
		},
		notMatchRegExp( $field ) {
			// Default to Null to prevent Conflicts
			let value = null;

			// If we have attribute, fetch the data value
			if ( $field.is( '[data-validation-not-match-regexp]' ) ) {
				value = $field.data( 'validationNotMatchRegexp' );
			}

			return value;
		},
	};

	/**
	 * FN (prototype) method from jQuery
	 *
	 * @since 4.7
	 *
	 * @type   {Function}
	 */
	obj.fn = function () {
		return this.each( obj.setup );
	};

	/**
	 * Configures a Single Form for validation
	 *
	 * @since 4.7
	 *
	 * @param {int} i    Field Index
	 * @param {DOM} item DOM element for the item
	 *
	 * @type   {Function}
	 */
	obj.setup = function ( i, item ) {
		const $item = $( item );

		// First we add the Class for the Form
		$item.addClass( obj.selectors.item.className() );

		// Make the Submit buttons have the required class for The Click
		$item.find( obj.selectors.submitButtons ).addClass( obj.selectors.submit.className() );

		// On Form Submit
		$item.on( 'submit.tribe', obj.onSubmit );

		// Actual Validation
		$item.on( 'validation.tribe', obj.onValidation );

		// Show the errors for all the fields
		$item.on( 'displayErrors.tribe', obj.onDisplayErrors );

		// Prevent form normal invalidation to be triggered.
		$document.on( 'click.tribe', obj.selectors.submit, obj.onClickSubmitButtons );

		// When click on dismiss of the notice for errors
		$document.on( 'click.tribe', obj.selectors.noticeDismiss, obj.onClickDismissNotice );
	};

	/**
	 * Validates a single Field
	 *
	 * @since 4.7
	 *
	 * @param {int} index Field Index
	 * @param      field
	 * @param {DOM} item  DOM element for the field
	 *
	 * @return {void}
	 */
	obj.validate = function ( index, field ) {
		const $field = $( field );
		const isValid = obj.isValid( $field );

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
	 * @since 4.7
	 *
	 * @param {Object} $field jQuery Object for the field
	 *
	 * @return {bool}
	 */
	obj.isValid = function ( $field ) {
		const constraints = obj.getConstraints( $field );

		if ( ! _.isObject( constraints ) ) {
			return constraints;
		}

		// It needs to be valid on all to be valid
		const valid = _.every( constraints );

		return valid;
	};

	/**
	 * Validates if a given Section has Errors
	 *
	 * @since 4.7
	 *
	 * @param         $item
	 * @param {Object} $field jQuery Object for the Section been validated
	 *
	 * @return {boolean}
	 */
	obj.hasErrors = function ( $item ) {
		const $errors = $item.find( obj.selectors.error ).not( ':disabled' );

		return 0 !== $errors.length;
	};

	/**
	 * Gets which constrains have Passed.
	 *
	 * @since 4.7
	 *
	 * @param {Object} $field jQuery Object for the field.
	 *
	 * @return {Object} Constraints that have passed.
	 */
	obj.getConstraints = function ( $field ) {
		const isDisabled = $field.is( ':disabled' );
		const valid = true;

		// Bail if it's a disabled field.
		if ( isDisabled ) {
			return valid;
		}

		let constraints = obj.getConstraintsValue( $field );
		const value = $field.val();

		// When we don't have constrains it's always valid.
		if ( _.isEmpty( constraints ) ) {
			return valid;
		}

		// Verifies if we have a valid set of constraints.
		constraints = _.mapObject( constraints, function ( constraint, key ) {
			return obj.conditions[ key ]( value, constraint, $field );
		} );

		return constraints;
	};

	/**
	 * Gets which constraint have valid values.
	 *
	 * @since 4.7
	 *
	 * @param {Object} $field Object with all the values for the constraints of a field.
	 *
	 * @return {Object}  Specific constraint value.
	 */
	obj.getConstraintsValue = function ( $field ) {
		const isDisabled = $field.is( ':disabled' );
		let constraints = {};

		// Bail if it's a disabled field
		if ( isDisabled ) {
			return constraints;
		}

		// Set to all constraints
		constraints = obj.constraints;

		// Fetch the values for each one of these
		constraints = _.mapObject( constraints, function ( isApplicable ) {
			return isApplicable( $field );
		} );

		// Check which ones of these are not null
		constraints = _.pick( constraints, function ( value ) {
			return null !== value;
		} );

		return constraints;
	};

	/**
	 * Gets which jQuery objects are related to a fields constraints
	 *
	 * @since 4.7
	 *
	 * @param {Object} $field jQuery Object for the fields
	 *
	 * @return {Object} Constraints for validation.
	 */
	obj.getConstraintsFields = function ( $field ) {
		let constraints = obj.getConstraintsValue( $field );

		// Fetch the values for each one of these
		constraints = _.mapObject( constraints, function ( constraint ) {
			let $constraint = null;
			if ( ! _.isNumber( constraint ) && ! _.isBoolean( constraint ) ) {
				$constraint = $( constraint );
			}

			return $constraint;
		} );

		// Check which ones of these are not null.
		constraints = _.pick( constraints, function ( value ) {
			return value instanceof jQuery;
		} );

		// Turn this into an proper array.
		constraints = _.values( constraints );

		// Add the current field.
		constraints.unshift( $field );

		// Convert to jQuery collection.
		constraints = $( constraints ).map( function () {
			return this.get();
		} );

		return constraints;
	};

	/**
	 * Actually does the validation for the Form
	 *
	 * @since 4.7
	 *
	 * @param {Object} event JQuery Event
	 *
	 * @return {void}
	 */
	obj.onValidation = function ( event ) {
		// eslint-disable-line no-unused-vars
		const $item = $( this );
		const $fields = $item.find( obj.selectors.fields );

		// Before Validation remove all Errors
		$fields.removeClass( obj.selectors.error.className() );

		// Validate all Fields
		$fields.each( obj.validate );

		const $errors = $item.find( obj.selectors.error ).not( ':disabled' );

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
	 * @since 4.7
	 *
	 * @param {Object} event JQuery Event
	 *
	 * @return {void}
	 */
	obj.onDisplayErrors = function ( event ) {
		// eslint-disable-line no-unused-vars
		const $item = $( this );
		const $errors = $item.find( obj.selectors.error ).not( ':disabled' );
		const $list = $( '<ul>' );
		const $dismiss = $( '<span>' ).addClass( obj.selectors.noticeDismiss.className() );

		// Tries to fetch if we have a given notice
		const $notice = $document.find( obj.selectors.notice );
		const $newNotice = $( '<div>' )
			.addClass( 'notice notice-error is-dismissible tribe-notice' )
			.addClass( obj.selectors.notice.className() )
			.append( $dismiss );

		// Builds based on the errors found in the form
		$errors.each( function ( i, field ) {
			const $field = $( field );
			const message = $field.data( 'validationError' );

			if ( _.isObject( message ) ) {
				const messages = {};
				const failed = obj.getConstraints( $field, false );

				// Maps the new Keys with CamelCase
				_.each( message, function ( value, key ) {
					messages[ tribe.utils.camelCase( key ) ] = value;
				} );

				_.each( failed, function ( value, key ) {
					// Only add error if this validation failed
					if ( value ) {
						return;
					}

					obj.addErrorLine( messages[ key ], $field, $list );
				} );
			} else {
				obj.addErrorLine( message, $field, $list );
			}
		} );

		// Appends the List of errors
		$newNotice.append( $list );

		// Verify if we need to add to the page or replace the existing
		if ( 0 === $notice.length ) {
			let $wpHeaderEnd = $document.find( obj.selectors.noticeAfter );

			if ( 0 === $wpHeaderEnd.length ) {
				$wpHeaderEnd = $document.find( obj.selectors.noticeFallback );
			}
			$wpHeaderEnd.after( $newNotice );
		} else {
			$notice.replaceWith( $newNotice );
		}
	};

	/**
	 * Validates a single Field.
	 *
	 * @since 4.7
	 *
	 * @param {string} message Message to be Attached.
	 * @param {Object} $field  jQuery Object for the field.
	 * @param {Object} $list   jQuery Object for list of Errors.
	 *
	 * @return {void} No return.
	 */
	obj.addErrorLine = function ( message, $field, $list ) {
		const $listItem = $( '<li>' ).text( message );

		// Add which field has thrown the error
		$listItem.data( 'validationField', $field );

		// Add which notice item is related to this error field
		$field.data( 'validationNoticeItem', $field );

		$list.append( $listItem );
	};

	/**
	 * Hooks to the submit and if invalid prevents submit from completing.
	 *
	 * @since 4.7
	 *
	 * @param {Object} event JQuery Event.
	 *
	 * @return {void|boolean}  When invalid it prevent bubble.
	 */
	obj.onSubmit = function ( event ) {
		const $item = $( this );

		$item.trigger( 'validation.tribe' );

		const isValid = $item.is( obj.selectors.valid );

		// When Invalid we prevents submit from completing
		if ( ! isValid ) {
			event.preventDefault();
			return false;
		}
	};

	/**
	 * Hijack the Browser the Invalidation.
	 *
	 * Note that it this weird multi-method is required to go around
	 * the usage of 'invalid' event, which doesn't bubble up to 'form'
	 * only happens on the Field, which prevents us to use it on
	 * the ones that are created by JavaScript Templates.
	 *
	 * @since 4.7
	 *
	 * @uses   obj.onInvalidField
	 *
	 * @param {Object} event JQuery Event
	 *
	 * @return {void} No return.
	 */
	obj.onClickSubmitButtons = function ( event ) {
		// eslint-disable-line no-unused-vars
		const $submit = $( this );
		const $item = $submit.parents( obj.selectors.item );

		// If we are not inside of the Validation just bail
		if ( 0 === $item.length ) {
			return;
		}

		// Triggers our validation also on the click of submit
		$item.trigger( 'validation.tribe' );

		const $fields = $item.find( obj.selectors.fields );

		// Makes sure we don't have any invalid event on any fields.
		$fields.off( 'invalid.tribe' );

		// Configures one invalid trigger
		$fields.one( 'invalid.tribe', obj.onInvalidField );
	};

	/**
	 * Add a class to mark fields that are invalid and add an one time
	 * event for these same fields to remove the class on `change`.
	 *
	 * @since 4.7
	 *
	 * @uses obj.onChangeFieldRemoveError
	 *
	 * @param {Event} event JQuery Event.
	 *
	 * @return {boolean} Return false to avoid bubble up.
	 */
	obj.onInvalidField = function ( event ) {
		const $field = $( this );
		const $item = $field.parents( obj.selectors.item );

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
	 * @since 4.7
	 *
	 * @param {Event} event JQuery Event.
	 *
	 * @return {void} No return.
	 */
	obj.onChangeFieldRemoveError = function ( event ) {
		// eslint-disable-line no-unused-vars
		const $field = $( this );
		const $relatedFields = obj.getConstraintsFields( $field );

		if ( 0 !== $relatedFields.filter( obj.selectors.error ).length ) {
			$relatedFields.removeClass( obj.selectors.error.className() );
		}
	};

	/**
	 * Removes the Notice.
	 *
	 * @since 4.7
	 *
	 * @param {Event} event JQuery Event.
	 *
	 * @return {void} No return.
	 */
	obj.onClickDismissNotice = function ( event ) {
		// eslint-disable-line no-unused-vars
		const $dismiss = $( this );
		const $notice = $dismiss.parents( obj.selectors.notice );

		// Deletes the Notice
		$notice.remove();
	};

	/**
	 * Initializes the Validation for the base items
	 *
	 * @since 4.7
	 *
	 * @param {Event} event JQuery Event.
	 *
	 * @return {void} No return.
	 */
	obj.onReady = function ( event ) {
		// eslint-disable-line no-unused-vars
		$( obj.selectors.item ).validation();
	};

	/**
	 * Configures the jQuery Setup of the Validation
	 *
	 * @since 4.7
	 *
	 * @return {void} No return.
	 */
	$.fn.validation = obj.fn;

	/**
	 * Attaches ready method to the On Ready of Document
	 *
	 * @since 4.7
	 */
	$( obj.onReady );
} )( window.tribe.validation, jQuery, window.underscore || window._ );
