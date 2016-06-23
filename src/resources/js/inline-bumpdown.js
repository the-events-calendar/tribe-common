(function( $, _ ) {
	'use strict';
	var methods = {};

	methods.clicked = false;
	methods.opening = false;

	methods.close_bumpdown = function( $bumpdown ) {
		if ( ! $bumpdown.is( ':visible' ) ) {
			return;
		}//end if

		$bumpdown.find( '.tribe-bumpdown-close, .tribe-bumpdown-arrow' ).remove()
		$bumpdown.slideUp( 'fast' );

		var $trigger = $( '#' + $bumpdown.data( 'trigger' ) );
		$trigger.removeClass( 'tribe-active' );
	};

	methods.open_bumpdown = function( $bumpdown ) {
		if ( $bumpdown.is( ':visible' ) ) {
			return;
		}//end if

		var bumpdown = $bumpdown.data( 'bumpdown' ),
			source = {};

		source.halfway = bumpdown.$trigger.position().left;
		if ( 'block' === bumpdown.type ) {
			source.halfway = source.halfway - bumpdown.$parent.offset().left;
		}

		$bumpdown.prepend( '<a class="tribe-bumpdown-close" title="Close"><i class="dashicons dashicons-no"></i></a>' );
		$bumpdown.prepend( '<span class="tribe-bumpdown-arrow" style="left: ' + source.halfway + 'px;"></span>' );

		methods.opening = true;

		$bumpdown.slideDown( 'fast', function() {
			methods.opening = false;
		});

		bumpdown.$trigger.addClass( 'tribe-active' );
	};

	$.fn.bumpdown = function() {
		return this.each( function() {
			var $trigger = $( this ),
				ID = $trigger.attr( 'id' ),
				html = $trigger.data( 'bumpdown' ),
				$bumpdown;

			// If we currently don't have the ID, set it up
			if ( ! ID ) {
				ID = _.uniqueId( 'tribe-bumpdown-' );
				$trigger.attr( 'id', ID );
			}

			// Fetch the first Block-Level parent
			var $parent = $trigger.parents().filter( function() {
				return $.inArray( $( this ).css( 'display' ), [ 'block', 'table', 'table-cell', 'table-row' ] );
			}).first();

			$trigger.addClass( 'tribe-bumpdown-trigger' );

			var bumpdownSelector = '[data-trigger="' + ID + '"]',
				type;

			if ( ! html ) {
				$bumpdown = $( bumpdownSelector );
				type = 'block';
			} else {
				type = $parent.is( 'td, tr, td, table' ) ? 'table' : 'block';

				if ( 'table' === type ) {
					$bumpdown = $( '<td>' ).attr( { colspan: 2, 'data-trigger': ID } ).addClass( 'tribe-bumpdown-cell' ).html( html );
					var $row = $( '<tr>' ).append( $bumpdown ).addClass( 'tribe-bumpdown-row' );

					$parent = $trigger.parents( 'tr' ).first();

					$parent.after( $row );
				} else {
					$bumpdown = $( '<div>' ).attr( { 'data-trigger': ID } ).addClass( 'tribe-bumpdown-block' ).html( html );
					$trigger.after( $bumpdown );
				}
			}

			$bumpdown.data( 'bumpdown', {
				ID: ID,
				$trigger: $trigger,
				$parent: $parent,
				type: type
			} ).addClass( 'tribe-bumpdown' );

			$( document ).on( 'click', bumpdownSelector, function() {
				if ( $bumpdown.is( ':visible' ) && ! methods.opening ) {
					methods.clicked = true;
				}
			});

			$( document ).on( 'click', function() {
				if ( ! methods.clicked && ! methods.opening && $bumpdown.is( ':visible' ) ) {
					methods.close_bumpdown( $bumpdown );
				}

				methods.clicked = false;
			});

			$( document ).hoverIntent( {
				over: function() {
					if ( ! $bumpdown.is( ':visible' ) ) {
						methods.open_bumpdown( $bumpdown );
					}
				},
				out: function() {}, // Prevents Notice on JS
				selector: '.bumpdown-trigger',
				interval: 300,
			} );

			$trigger.on( 'click', function( e ) {
				e.preventDefault();

				if ( ! $bumpdown.is( ':visible' ) ) {
					e.stopPropagation();
					methods.open_bumpdown( $bumpdown );
				}
			});

			$bumpdown.find( '.tribe-bumpdown-close' ).on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				methods.close_bumpdown( $bumpdown );
			});
		});
	};
}( jQuery, _ ) );
