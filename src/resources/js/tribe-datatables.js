window.tribe_data_table = null;

( function( $ ) {
	'use strict';

	$.fn.tribeDataTable = function( options ) {
		var $document = $( document );
		var settings = $.extend( {
			language: {
				lengthMenu   : tribe_l10n_datatables.length_menu,
				emptyTable   : tribe_l10n_datatables.emptyTable,
				info         : tribe_l10n_datatables.info,
				infoEmpty    : tribe_l10n_datatables.info_empty,
				infoFiltered : tribe_l10n_datatables.info_filtered,
				zeroRecords  : tribe_l10n_datatables.zero_records,
				search       : tribe_l10n_datatables.search,
				paginate     : {
					next     : tribe_l10n_datatables.pagination.next,
					previous : tribe_l10n_datatables.pagination.previous,
				},
				aria         : {
					sortAscending  : tribe_l10n_datatables.aria.sort_ascending,
					sortDescending : tribe_l10n_datatables.aria.sort_descending
				},
				select: {
					rows: {
						'0': tribe_l10n_datatables.select.rows[0],
						_: tribe_l10n_datatables.select.rows._,
						'1': tribe_l10n_datatables.select.rows[1]
					}
				}
			},
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, tribe_l10n_datatables.pagination.all ]
			],
		}, options );

		var only_data = false;
		if ( this.is( '.dataTable' ) ) {
			only_data = true;
		}

		var methods = {
			toggle_global_checkbox: function( $checkbox, table ) {
				var $table = $checkbox.closest( '.dataTable' );
				var $thead = $table.find( 'thead' );
				var $tfoot = $table.find( 'tfoot' );
				var $header_checkbox = $thead.find( '.column-cb input:checkbox' );
				var $footer_checkbox = $tfoot.find( '.column-cb input:checkbox' );

				if ( $checkbox.is( ':checked' ) ) {
					$table.find( 'tbody .check-column input:checkbox' ).prop( 'checked', true );
					$header_checkbox.prop( 'checked', true );
					$footer_checkbox.prop( 'checked', true );

					var $link = $( '<a>' ).attr( 'href', '#select-all' ).text( tribe_l10n_datatables.select_all_link );
					var $text = $( '<div>' ).css( 'text-align', 'center' ).text( tribe_l10n_datatables.all_selected_text ).append( $link );
					var $column = $( '<th>' ).attr( 'colspan', table.columns()[0].length ).append( $text );
					var $row = $( '<tr>' ).addClass( 'tribe-datatables-all-pages-checkbox' ).append( $column );

					$link.one( 'click', function( event ) {
						table.rows().select();

						$link.text( tribe_l10n_datatables.clear_selection ).one( 'click', function() {
							$row.remove();
							$table.find( 'tbody .check-column input:checkbox' ).prop( 'checked', false );
							$header_checkbox.prop( 'checked', false );
							$footer_checkbox.prop( 'checked', false );
							table.rows().deselect();

							event.preventDefault();
							return false;
						} );

						event.preventDefault();
						return false;
					} );

					$thead.append( $row );
					table.rows( { page: 'current' } ).select();
					return;
				}

				$table.find( '.tribe-datatables-all-pages-checkbox' ).remove();
				$table.find( 'tbody .check-column input:checkbox' ).prop( 'checked', false );
				$header_checkbox.prop( 'checked', false );
				$footer_checkbox.prop( 'checked', false );
				table.rows().deselect();
			},
			toggle_row_checkbox: function( $checkbox, table ) {
				var $row = $checkbox.closest( 'tr' );

				if ( $checkbox.is( ':checked' ) ) {
					table.row( $row ).select();
					return;
				}

				table.row( $row ).deselect();
				$checkbox.closest( '.dataTable' ).find( 'thead .column-cb input:checkbox, tfoot .column-cb input:checkbox' ).prop( 'checked', false );
			}
		};

		return this.each( function() {
			var $el = $( this );
			var table;

			if ( only_data ) {
				table = $el.DataTable();
			} else {
				table = $el.DataTable( settings );
			}

			window.tribe_data_table = table;

			if ( 'undefined' !== typeof settings.data ) {
				table.clear().draw();
				table.rows.add( settings.data );
				table.draw();
			}

			$el.on(
				'click',
				'thead .column-cb input:checkbox, tfoot .column-cb input:checkbox',
				function() {
					methods.toggle_global_checkbox( $( this ), table );
				}
			);

			$el.on(
				'click',
				'tbody .check-column input:checkbox',
				function() {
					methods.toggle_row_checkbox( $( this ), table );
				}
			);
		} );
	};
} )( jQuery );
