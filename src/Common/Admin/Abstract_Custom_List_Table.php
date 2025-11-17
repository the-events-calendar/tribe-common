<?php
/**
 * An abstract admin list table for custom tables.
 *
 * @since 6.5.3
 *
 * @package TEC\Admin
 */

namespace TEC\Common\Admin;

use RuntimeException;
use WP_List_Table;
use Tribe__Date_Utils as Dates;
use WP_Post;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/screen.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Abstract_Custom_List_Table class.
 *
 * @since 6.5.3
 */
abstract class Abstract_Custom_List_Table extends WP_List_Table {
	/**
	 * Plural name for the table. Non translatable.
	 *
	 * @since 6.5.3
	 *
	 * @var string
	 */
	protected const PLURAL = '';

	/**
	 * Table ID.
	 *
	 * @since 6.5.3
	 *
	 * @var string
	 */
	protected const TABLE_ID = '';

	/**
	 * Constructor.
	 *
	 * @since 6.5.3
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'plural' => static::PLURAL,
				'ajax'   => false,
				'screen' => null,
			]
		);

		parent::__construct( $args );

		$this->screen->add_option(
			'per_page',
			[
				'label'  => __( 'Number of entries per page:', 'tribe-common' ),
				'option' => str_replace( '-', '_', static::TABLE_ID . '_per_page' ),
			]
		);
	}

	/**
	 * Display the table
	 *
	 * @since 6.5.3
	 */
	public function display(): void {
		if ( $this->is_empty() ) {
			$this->empty_content();
			return;
		}

		parent::display();
	}

	/**
	 * Store the custom per page option.
	 *
	 * @since 6.5.3
	 *
	 * @param mixed  $screen_option The value to save instead of the option value.
	 *                              Default false (to skip saving the current option).
	 * @param string $option        The option name.
	 * @param int    $value         The option value.
	 *
	 * @return array An associative array in the format [ <slug> => <title> ]
	 */
	public static function store_custom_per_page_option( $screen_option, string $option, int $value ) {
		if ( str_replace( '-', '_', static::TABLE_ID . '_per_page' ) === $option ) {
			return $value;
		}

		return $screen_option;
	}

	/**
	 * Outputs the results of the filters above the table.
	 *
	 * It should echo the output.
	 *
	 * @since 6.5.3
	 */
	public function do_top_tablename_filters(): void {}

	/**
	 * Returns the total number of items in the table.
	 *
	 * @since 6.5.3
	 *
	 * @return int
	 */
	abstract protected function get_total_items(): int;

	/**
	 * Returns the list of items for the table.
	 *
	 * @since 6.5.3
	 *
	 * @param int $per_page The number of items to display per page.
	 *
	 * @return array
	 */
	abstract protected function get_items( int $per_page ): array;

	/**
	 * Returns whether the list is completely empty.
	 *
	 * @since 6.5.3
	 *
	 * @return bool
	 */
	public function is_empty(): bool {
		return false;
	}

	/**
	 * Outputs the content to display when the list is completely empty.
	 *
	 * @since 6.5.3
	 */
	public function empty_content(): void {}

	/**
	 * Prints the extra table controls.
	 *
	 * @since 6.5.3
	 *
	 * @param string $which The location of the extra controls: 'top' or 'bottom'.
	 */
	protected function extra_tablenav( $which ) {
		?>
		<div class="alignleft actions">
		<?php
		if ( 'top' === $which ) {
			ob_start();
			$this->do_top_tablename_filters();
			$output = ob_get_clean();

			if ( ! empty( $output ) ) {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, StellarWP.XSS.EscapeOutput.OutputNotEscaped
				submit_button(
					__( 'Apply Filters', 'tribe-common' ),
					'',
					'filter_action',
					false,
					[
						'id' => 'post-query-submit',
					]
				);
			}
		}
		?>
		</div>
		<?php
	}

	/**
	 * Returns the list of table classes.
	 *
	 * @since 6.5.3
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		return [
			'widefat',
			'fixed',
			'striped',
			'table-view-list',
			'posts',
		];
	}

	/**
	 * Handles the default column output.
	 *
	 * @since 6.5.3
	 *
	 * @param object $item        The current object.
	 * @param string $column_name The current column name.
	 */
	public function column_default( $item, $column_name ) {
		$value = null;

		if ( is_array( $item ) ) {
			// Ensures compatibility.
			$item = (object) $item;
		}

		if ( isset( $item->$column_name ) ) {
			$value = $item->$column_name;
		}

		if ( null === $value && is_callable( [ $item, 'get_' . strtolower( $column_name ) ] ) ) {
			$value = call_user_func( [ $item, 'get_' . strtolower( $column_name ) ] );
		}

		if ( null === $value ) {
			return;
		}
		?>
		<span><?php echo esc_html( $value ); ?></span>
		<?php
	}

	/**
	 * Prepares the list of items for displaying.
	 *
	 * You should be initializing and preparing items in the `current_screen` hook. So that the screen is set up properly.
	 *
	 * The screen is set up in the constructor!!
	 *
	 * @since 6.5.3
	 * @throws RuntimeException If the table is being prepared too late.
	 */
	public function prepare_items(): void {
		if ( did_action( 'tec_admin_headers_about_to_be_sent' ) ) {
			throw new RuntimeException( 'You are not Prepared! You need to prepare before any headers have been sent!' );
		}

		/**
		 * Filters the number of items per page to show in the list table.
		 *
		 * Core Filter.
		 *
		 * @since 6.5.3
		 *
		 * @param int    $per_page Number of items to be displayed.
		 * @param string $table_id The table ID.
		 */
		$per_page = (int) apply_filters(
			'edit_posts_per_page',
			$this->get_items_per_page( str_replace( '-', '_', static::TABLE_ID . '_per_page' ) ),
			static::TABLE_ID
		);

		$this->items = $this->get_items( $per_page );
		$total_items = $per_page > count( $this->items ) && 2 > $this->get_pagenum() ? count( $this->items ) : $this->get_total_items();

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			]
		);
	}

	/**
	 * Generates the columns for a single row of the table.
	 *
	 * @since 6.5.3
	 *
	 * @param object $item The current item.
	 */
	public function single_row( $item ): void {
		?>
		<tr class="iedit level-0">
			<?php $this->single_row_columns( $item ); ?>
		</tr>
		<?php
	}

	/**
	 * Display the search box.
	 *
	 * @since 6.5.3
	 *
	 * @param string $text     The search button text.
	 * @param string $input_id The search input id.
	 */
	public function search_box( $text, $input_id ) {
		if ( ! tec_get_request_var_raw( 's' ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( tec_get_request_var( 'orderby' ) ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( tec_get_request_var( 'orderby' ) ) . '" />';
		}

		if ( ! empty( tec_get_request_var( 'order' ) ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( tec_get_request_var( 'order' ) ) . '" />';
		}

		if ( ! empty( tec_get_request_var( 'page' ) ) ) {
			echo '<input type="hidden" name="page" value="' . esc_attr( tec_get_request_var( 'page' ) ) . '" />';
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
			<input placeholder="<?php echo esc_attr( $this->get_search_placeholder() ); ?>" type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', false, false, [ 'id' => 'search-submit' ] ); ?>
		</p>
		<?php
	}

	/**
	 * Returns the search placeholder.
	 *
	 * @since 6.5.3
	 *
	 * @return string
	 */
	protected function get_search_placeholder(): string {
		return '';
	}

	/**
	 * Displays a dropdown for filtering items in the list table by date range.
	 *
	 * @since 6.5.3
	 *
	 * @return void
	 */
	protected function date_range_dropdown() {
		$date_from = sanitize_text_field( tribe_get_request_var( 'tec_tc_date_range_from', '' ) );
		$date_to   = sanitize_text_field( tribe_get_request_var( 'tec_tc_date_range_to', '' ) );

		$date_from = Dates::is_valid_date( $date_from ) ? $date_from : '';
		$date_to   = Dates::is_valid_date( $date_to ) ? $date_to : '';
		?>
		<label class="screen-reader-text" for="tec_tc_data-range-from">
			<?php esc_html_e( 'From', 'tribe-common' ); ?>
		</label>
		<input
			autocomplete="off"
			type="text"
			class="tribe-datepicker"
			name="tec_tc_date_range_from"
			id="tec_tc_data-range-from"
			size="10"
			value="<?php echo esc_attr( $date_from ); ?>"
			placeholder="<?php esc_attr_e( 'YYYY-MM-DD', 'tribe-common' ); ?>"
			data-validation-type="datepicker"
		/>
		<label for="tec_tc_data-range-to">
			<?php esc_html_e( 'to', 'tribe-common' ); ?>
		</label>
		<input
			autocomplete="off"
			type="text"
			class="tribe-datepicker"
			name="tec_tc_date_range_to"
			id="tec_tc_data-range-to"
			size="10"
			value="<?php echo esc_attr( $date_to ); ?>"
			placeholder="<?php esc_attr_e( 'YYYY-MM-DD', 'tribe-common' ); ?>"
			data-validation-type="datepicker"
		/>
		<?php
	}

	/**
	 * Returns the selected date range.
	 *
	 * @since 6.5.3
	 *
	 * @return array<string,string>
	 */
	public function get_date_range(): array {
		$date_from = sanitize_text_field( tec_get_request_var( 'tec_tc_date_range_from', '' ) );
		$date_to   = sanitize_text_field( tec_get_request_var( 'tec_tc_date_range_to', '' ) );

		$date_from = Dates::is_valid_date( $date_from ) ? $date_from : '';
		$date_to   = Dates::is_valid_date( $date_to ) ? $date_to : '';

		return [
			'date_from' => $date_from,
			'date_to'   => $date_to,
		];
	}

	/**
	 * Displays a dropdown for filtering items in the list table by month.
	 *
	 * @since 6.5.3
	 *
	 * @return void
	 */
	protected function ticket_able_post_dropdown() {
		// Event options are being filtered in the Frontend after the user starts typing in the search box.
		// Except for when the user has already filtered by an event. We take the event ID from the URL and add it to the dropdown.

		$e = absint( tribe_get_request_var( 'tec_tc_events', 0 ) );

		$event = $e ? get_post( $e ) : null;

		$event = $event instanceof WP_Post ? $event : null;

		$events_formatted = [
			'' => esc_html__( 'All Events', 'tribe-common' ),
		];

		$events_formatted += $event ? [ (string) $event->ID => get_the_title( $event->ID ) ] : [];
		?>
		<label for="tec_tc_events-select" class="screen-reader-text"><?php esc_html_e( 'Filter By Event', 'tribe-common' ); ?></label>
		<select
			name="tec_tc_events"
			id='tec_tc_events-select'
			class='tribe-dropdown'
			data-freeform="1"
			data-force-search="1"
			data-searching-placeholder="<?php esc_attr_e( 'Searching...', 'tribe-common' ); ?>"
			data-source="tec_tickets_list_ticketables_ajax"
			data-source-nonce="<?php echo esc_attr( wp_create_nonce( 'tribe_dropdown' ) ); ?>"
			data-ajax-delay="400"
			data-ajax-cache="1"
			data-minimum-input-length="3"
			data-tags="0"
		>
			<?php foreach ( $events_formatted as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $e, $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Formats a date for display.
	 *
	 * @since 6.5.3
	 *
	 * @param string $dt The date to format in format accepted by `strtotime` or EPOCH.
	 *
	 * @return string
	 */
	public function format_date( string $dt = '' ) {
		if ( ! $dt ) {
			return '&ndash;';
		}

		$ts = is_numeric( $dt ) ? $dt : strtotime( $dt );

		if ( ! $ts ) {
			return '&ndash;';
		}

		// Check if the order was created within the last 24 hours, and not in the future.
		if ( $ts > strtotime( '-1 day', time() ) && $ts <= time() ) {
			$show_date = sprintf(
				/* translators: %s: human-readable time difference */
				_x( '%s ago', '%s = human-readable time difference', 'woocommerce' ),
				human_time_diff( $ts, time() )
			);
		} else {
			$show_date = Dates::reformat( $ts, Dates::DATEONLYFORMAT );
		}

		return sprintf(
			'<time datetime="%1$s" title="%2$s">%3$s</time>',
			esc_attr( Dates::reformat( $ts, 'c' ) ),
			esc_html( Dates::reformat( $ts, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ),
			esc_html( $show_date )
		);
	}

	/**
	 * Returns the selected from the events dropdown.
	 *
	 * @since 6.5.3
	 *
	 * @return int
	 */
	public function get_event_id(): int {
		$event_id = (int) tec_get_request_var_raw( 'tec_tc_events', 0 );

		return $event_id > 0 ? $event_id : 0;
	}

	/**
	 * Returns the selected search term.
	 *
	 * @since 6.5.3
	 * @since 6.10.0 Added the $by_default parameter.
	 *
	 * @param string $by_default The default order.
	 *
	 * @return string
	 */
	public function get_order( $by_default = 'ASC' ): string {
		$order = strtoupper( tec_get_request_var_raw( 'order', $by_default ) );

		return in_array( $order, [ 'ASC', 'DESC' ], true ) ? $order : $by_default;
	}

	/**
	 * Returns the selected orderby.
	 *
	 * @since 6.5.3
	 * @since 6.10.0 Added the $by_default parameter.
	 *
	 * @param string $by_default The default orderby.
	 *
	 * @return string
	 */
	public function get_orderby( $by_default = '' ): string {
		$orderby = tec_get_request_var( 'orderby', $by_default );

		return in_array( $orderby, array_values( $this->get_sortable_columns() ), true ) ? $orderby : $by_default;
	}
}
