<?php
/**
 * An abstract admin list table for custom tables.
 *
 * @since TBD
 *
 * @package TEC\Admin
 */

namespace TEC\Common\Admin;

use RuntimeException;
use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/screen.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Abstract_Custom_List_Table class.
 *
 * @since TBD
 */
abstract class Abstract_Custom_List_Table extends WP_List_Table {
	/**
	 * Singular name for the table. Non translatable.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected const SINGULAR = '';

	/**
	 * Plural name for the table. Non translatable.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected const PLURAL = '';

	/**
	 * Table ID.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected const TABLE_ID = '';

	/**
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 *
	 * @param array $args An associative array of arguments.
	 */
	public function __construct( $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'singular' => static::SINGULAR,
				'plural'   => static::PLURAL,
				'ajax'     => false,
				'screen'   => null,
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
	 * Store the custom per page option.
	 *
	 * @since TBD
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
	 * @since TBD
	 */
	public function do_top_tablename_filters(): void {}

	/**
	 * Returns the total number of items in the table.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	abstract protected function get_total_items(): int;

	/**
	 * Returns the list of items for the table.
	 *
	 * @since TBD
	 *
	 * @param int $per_page The number of items to display per page.
	 *
	 * @return array
	 */
	abstract protected function get_items( int $per_page ): array;

	/**
	 * Prints the extra table controls.
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
	 *
	 * @param object $item        The current object.
	 * @param string $column_name The current column name.
	 */
	public function column_default( $item, $column_name ) {
		$value = null;

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
		<strong><span><?php echo esc_html( $value ); ?></span></strong>
		<?php
	}

	/**
	 * Prepares the list of items for displaying.
	 *
	 * You should be initializing and preparing items in the `current_screen` hook. So that the screen is set up properly.
	 *
	 * The screen is set up in the constructor!!
	 *
	 * @since TBD
	 * @throws RuntimeException If the table is being prepared too late.
	 */
	public function prepare_items(): void {
		if ( did_action( 'tribe_admin_headers_about_to_be_sent' ) ) {
			throw new RuntimeException( 'You are not Prepared! You need to prepare before any headers have been sent!' );
		}

		/**
		 * Filters the number of items per page to show in the list table.
		 *
		 * Core Filter.
		 *
		 * @since TBD
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
		$total_items = $per_page > count( $this->items ) ? count( $this->items ) : $this->get_total_items();

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
	 * @since TBD
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
	 * @since TBD
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
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', false, false, [ 'id' => 'search-submit' ] ); ?>
		</p>
		<?php
	}
}
