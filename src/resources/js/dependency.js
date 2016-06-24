(function( $, _ ) {
	'use strict';
	var $document = $( document ),
		selectors = {
			dependent: '.tribe-dependent',
			active: '.tribe-active',
			dependency: '.tribe-dependency',
			fields: 'input, select, textarea',
			advanced_fields: '.select2-container'
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
					value = $field.val();

				// We need an ID to make something depend on this
				if ( ! selector ) {
					return;
				}

				// Fetch dependent elements
				var $dependents = $document.find( '[data-depends="' + selector + '"]' );

				$dependents.each( function( k, dependent ) {
					var $dependent = $( dependent ),
						condition = $dependent.data( 'condition' ),
						is_not_empty = $dependent.data( 'conditionNotEmpty' ) || $dependent.is( '[data-condition-not-empty]' ),
						is_empty = $dependent.data( 'conditionEmpty' ) || $dependent.is( '[data-condition-empty]' ),
						is_disabled = $field.is( ':disabled' );

					if (
						(
							( is_empty && '' == value )  ||
							( is_not_empty && '' != value ) ||
							( _.isArray( condition ) && -1 !== _.findIndex( condition, value ) ) ||
							( value == condition )
						) && ! is_disabled
					) {
						$dependent
							.addClass( selectors.active.replace( '.', '' ) )
							.find( selectors.fields ).prop( 'disabled', false );
					} else {
						$dependent
							.removeClass( selectors.active.replace( '.', '' ) )
							.find( selectors.fields ).prop( 'disabled', true );
					}

					// Checks if any child elements have dependencies
					$dependent.find( selectors.dependency ).trigger( 'verify.dependency' );
				} );
			},
			'change.dependency': function( e ) {
				$( this ).trigger( 'verify.dependency' );
			}
		}, selectors.dependency )

	// Configure on Document ready for the default trigger
	$document.ready( function() {
		$( selectors.dependent ).dependency();
		$( selectors.dependency ).trigger( 'verify.dependency' );
	} );
}( jQuery, _ ) );