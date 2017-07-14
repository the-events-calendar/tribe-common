(function( $, _ ) {
	'use strict';
	var $document = $( document ),
		selectors = {
			dependent: '.tribe-dependent',
			active: '.tribe-active',
			dependency: '.tribe-dependency',
			fields: 'input, select, textarea',
			advanced_fields: '.select2-container',
			linked: '.tribe-dependent-linked'
		};

	// Setup a Dependent
	$.fn.dependency = function () {
		this.each( function(){
			var selector = $( this ).data( 'depends' );
			$( selector ).addClass( selectors.dependency.replace( '.', '' ) ).data( 'dependent', $( this ) );
		} );
	};

	$document
		// Prevents double global actions
		.off( 'change.dependency verify.dependency', selectors.dependency )
		.on( {
			'verify.dependency': function( e ) {
				var $field = $( this ),
					selector = '#' + $field.attr( 'id' ),
					value = $field.val(),
					constraint_conditions;

				// We need an ID to make something depend on this
				if ( ! selector ) {
					return;
				}

				/**
				 * If we're hooking to a radio, we need to make sure changing
				 * any similarly _named_ ones trigger verify on all of them.
				 * The base code only triggers on direct interations.
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

				// setup each constraint truth condition
				// each function will be passed the value, the constraint and the depending field
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
					}
				};

				$dependents.each( function( k, dependent ) {
					var container_parent = $( this ).data( 'parent' );
					var $dependent = null;
					if ( container_parent ) {
						$dependent = $( this ).closest( container_parent ).find( dependent );
					} else {
						$dependent = $( dependent );
					}

					var constraints = {
							condition: $dependent.data( 'condition' ) || false,
							not_condition: $dependent.data( 'conditionNot' ) || false,
							is_not_empty: $dependent.data( 'conditionNotEmpty' ) || $dependent.is( '[data-condition-not-empty]' ),
							is_empty: $dependent.data( 'conditionEmpty' ) || $dependent.is( '[data-condition-empty]' ),
							is_numeric: $dependent.data( 'conditionIsNumeric' ) || $dependent.is( '[data-condition-is-numeric]' ),
							is_not_numeric: $dependent.data( 'conditionIsNotNumeric' ) || $dependent.is( '[data-condition-is-not-numeric]' ),
							is_checked: $dependent.data( 'conditionChecked' ) || $dependent.is( '[data-condition-is-checked]' ),
						},
						active_class = selectors.active.replace( '.', '' ),
						is_disabled = $field.is( ':disabled' ),
						condition_relation = $dependent.data( 'condition-relation' ) || 'or',
						passes;

					constraints = _.pick( constraints, function ( is_applicable ) {
						return is_applicable;
					} );

					if ( 'or' === condition_relation ) {
						passes = _.reduce( constraints, function ( passes, constraint, key ) {
							return passes || constraint_conditions[ key ]( value, constraint, $field );
						}, false );
					} else {
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

						$dependent
							.find( selectors.fields ).prop( 'disabled', false )
							.end().find( '.select2-container' ).select2( 'enable', true );
					} else {
						$dependent.removeClass( active_class );

						// ideally the class should be enough, but just in case...
						if ( $dependent.is( ':visible' ) ) {
							$dependent.hide();
						}

						$dependent
							.find( selectors.fields ).prop( 'disabled', true )
							.end().find( '.select2-container' ).select2( 'enable', false );

						if ( $dependent.data( 'select2' ) ) {
							$dependent.data( 'select2' ).container.removeClass( active_class );
						}
					}

					// Checks if any child elements have dependencies
					$dependent.find( selectors.dependency ).trigger( 'change' );
				} );
			},
			'change.dependency': function( e ) {
				$( this ).trigger( 'verify.dependency' );
			}
		}, selectors.dependency );

	// Configure on Document ready for the default trigger
	$document.ready( function() {
		$( selectors.dependent ).dependency();
		$( selectors.dependency ).trigger( 'verify.dependency' );
	} );
}( jQuery, _ ) );
