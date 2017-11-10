/**
 * Tribe Dependency. Allows us to conditionally hide/show and disable elements
 * based on a data relationship.
 *
 * Glossary (in case you get lost like I did!)
 * dependent  = the element that is the "target" of the relationship the one that
 *                  is affected, thus dependent.
 * dependency = the element the relationship is based on the one we check in our conditions
 * active     = the class we use to denote an "active" (not hidden & enabled) element
 * selector   = the css selector for the dependency, must be an ID, includes the hash "#"
 * linked     = data attribute for linked dependents mainly for radio buttons to
 *                  ensure they all get triggered togther
 */
( function( $, _, obj ) {
	'use strict';
	var $document = $( document );
	var $window = $( window );
	var selectors = {
		dependent: '.tribe-dependent',
		active: '.tribe-active',
		dependency: '.tribe-dependency',
		dependencyVerified: '.tribe-dependency-verified',
		fields: 'input, select, textarea',
		advanced_fields: '.select2-container',
		linked: '.tribe-dependent-linked'
	};

	// Setup a Dependent
	$.fn.dependency = function () {
		return this.each( function(){
			var selector = $( this ).data( 'depends' );
			$( selector ).addClass( selectors.dependency.replace( '.', '' ) ).data( 'dependent', $( this ) );
		} );
	};

	$document
		// Prevents double global actions
		.off( 'change.dependency verify.dependency', selectors.dependency )
		.on( {
			'verify.dependency': function( e ) {
				var $field = $( this );
				var selector = '#' + $field.attr( 'id' );
				var value = $field.val();
				var constraint_conditions;

				// We need an ID to make something depend on this
				if ( ! selector ) {
					return;
				}

				/**
				 * If we're hooking to a radio, we need to make sure changing
				 * any similarly _named_ ones trigger verify on all of them.
				 * The base code only triggers on direct interactions.
				 *
				 * @since 4.5.8
				 */
				if ( $field.is( ':radio' ) ) {
					var $radios = $( "[name='" + $field.attr( 'name' ) + "']" );

					$radios.not( selectors.linked ).on( 'change', function() {
						$radios.trigger( 'verify.dependency' );
					} ).addClass( selectors.linked.replace( '.', '' ) );
				}

				// Fetch dependent elements
				var $dependents = $document.find( '[data-depends="' + selector + '"]' ).not( '.select2-container' );

				// Set up each constraint truth condition
				// Each function will be passed the value, the constraint and the dependent field
				constraint_conditions = {
					'condition': function ( val, constraint ) {
						return _.isArray( constraint ) ? -1 !== constraint.indexOf( val ) : val == constraint;
					},
					'not_condition': function ( val, constraint ) {
						return _.isArray( constraint ) ? -1 === constraint.indexOf( val ) : val != constraint;
					},
					'is_not_empty': function ( val ) {
						return '' != val;
					},
					'is_empty': function ( val ) {
						return '' === val;
					},
					'is_numeric': function ( val ) {
						return $.isNumeric( val );
					},
					'is_not_numeric': function ( val ) {
						return ! $.isNumeric( val );
					},
					'is_checked': function ( _, __, $field ) {
						return ( $field.is( ':checkbox' ) || $field.is( ':radio' ) ) ? $field.is( ':checked' ) : false;
					},
					'is_not_checked': function ( _, __, $field ) {
						return ( $field.is( ':checkbox' ) || $field.is( ':radio' ) ) ? ! $field.is( ':checked' ) : false;
					}
				};

				$dependents.each( function( k, dependent ) {
					var $dependent         = $( dependent );
					var hasDependentParent = $dependent.is( '[data-dependent-parent]' );

					if ( hasDependentParent ) {
						var dependentParent  = $dependent.data( 'dependentParent' );
						var $dependentParent = $dependent.closest( dependentParent );

						if ( 0 === $dependentParent.length ) {
							console.warn( 'Dependency: `data-dependent-parent` has bad selector', $dependent );
							return;
						}

						$dependent = $dependentParent.find( dependent );
					}

					var constraints = {
						condition: $dependent.is( '[data-condition]' ) ? $dependent.data( 'condition' ) : false,
						not_condition: $dependent.is( '[data-condition-not]' ) ? $dependent.data( 'conditionNot' ) : false,
						is_not_empty: $dependent.data( 'conditionIsNotEmpty' ) || $dependent.is( '[data-condition-is-not-empty]' ) || $dependent.data( 'conditionNotEmpty' ) || $dependent.is( '[data-condition-not-empty]' ),
						is_empty: $dependent.data( 'conditionIsEmpty' ) || $dependent.is( '[data-condition-is-empty]' ) || $dependent.data( 'conditionEmpty' ) || $dependent.is( '[data-condition-empty]' ),
						is_numeric: $dependent.data( 'conditionIsNumeric' ) || $dependent.is( '[data-condition-is-numeric]' ) || $dependent.data( 'conditionNumeric' ) || $dependent.is( '[data-condition-numeric]' ),
						is_not_numeric: $dependent.data( 'conditionIsNotNumeric' ) || $dependent.is( '[data-condition-is-not-numeric]' ),
						is_checked: $dependent.data( 'conditionIsChecked' ) || $dependent.is( '[data-condition-is-checked]' ) || $dependent.data( 'conditionChecked' ) || $dependent.is( '[data-condition-checked]' ),
						is_not_checked: $dependent.data( 'conditionIsNotChecked' ) || $dependent.is( '[data-condition-is-not-checked]' ) || $dependent.data( 'conditionNotChecked' ) || $dependent.is( '[data-condition-not-checked]' ),
					};

					var active_class       = selectors.active.replace( '.', '' );
					// Allows us to check a disabled dependency
					var allowDisabled     = $dependent.data( 'dependencyCheckDisabled' ) || $dependent.is( '[data-dependency-check-disabled]' );
					// If allowDisabled, then false - we don't care!
					var is_disabled        = allowDisabled ? false : $field.is( ':disabled' );
					var condition_relation = $dependent.data( 'condition-relation' ) || 'or';
					var passes;

					constraints = _.pick( constraints, function ( is_applicable ) {
						return false !== is_applicable;
					} );

					if ( 'or' === condition_relation ) {
						passes = _.reduce( constraints, function ( passes, constraint, key ) {
							return passes || constraint_conditions[ key ]( value, constraint, $field );
						}, false );
					} else {
						// There is no "and"!
						passes = _.reduce( constraints, function ( passes, constraint, key ) {
							return passes && constraint_conditions[ key ]( value, constraint, $field );
						}, true );
					}

					if ( passes && ! is_disabled ) {
						if ( $dependent.data( 'select2' ) ) {
							$dependent.data( 'select2' ).container.addClass( active_class );

							// ideally the class should be enough, but just in case...
							if ( $dependent.data( 'select2' ).container.is( ':hidden' ) ) {
								$dependent.data( 'select2' ).container.show();
							}
						} else {
							$dependent.addClass( active_class );

							// ideally the class should be enough, but just in case...
							if ( $dependent.is( ':hidden' ) ) {
								$dependent.show();
							}
						}

						$dependent.find( selectors.fields ).prop( 'disabled', false );

						if ( 'undefined' !== typeof $().select2 ) {
							$dependent.find( '.select2-container' ).select2( 'enable', true );
						}
					} else {
						$dependent.removeClass( active_class );

						// ideally the class should be enough, but just in case...
						if ( $dependent.is( ':visible' ) ) {
							$dependent.hide();
						}

						$dependent.find( selectors.fields ).prop( 'disabled', true );

						if ( 'undefined' !== typeof $().select2 ) {
							$dependent.find( '.select2-container' ).select2( 'enable', false );
						}

						if ( $dependent.data( 'select2' ) ) {
							$dependent.data( 'select2' ).container.removeClass( active_class );
						}
					}

					// Checks if any child elements have dependencies
					$dependent.find( selectors.dependency ).trigger( 'change' );
				} );

				$field.addClass( selectors.dependencyVerified.className() );
			},
			'change.dependency': function( e ) {
				$( this ).trigger( 'verify.dependency' );
			}
		}, selectors.dependency );

	obj.run = function ( event ) {
		// Fetch all dependents
		var $dependents = $( selectors.dependent );

		if ( $dependents.length ) {
			// Trigger Dependency Configuration on all of these
			$dependents.dependency();
		}

		// Fetch all Dependencies
		var $dependencies = $( selectors.dependency );

		if ( $dependencies.not( selectors.dependencyVerified ).length ) {
			// Now verify all the Dependencies
			$dependencies.trigger( 'verify.dependency' );
		}
	};

	// Configure on Document ready for the default trigger
	$document.ready( obj.run );

	// Configure on Window Load again
	$window.on( 'load', obj.run );
}( jQuery, _, {} ) );
