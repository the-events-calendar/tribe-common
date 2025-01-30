<?php
/**
 * An abstract admin list table for custom tables.
 *
 * @since TBD
 *
 * @package TEC\Admin
 */

declare( strict_types=1 );

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
	protected const PLURAL   = '';

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
				'screen'   => get_current_screen(),
			]
		);

		parent::__construct( $args );
		add_filter( 'manage_' . $this->screen->id . '_columns', [ $this, 'get_columns' ], PHP_INT_MAX );
	}

	/**
	 * Outputs the results of the filters above the table.
	 *
	 * It should echo the output.
	 *
	 * @since TBD
	 *
	 * @return string
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

	// public function no_items() {
	// }

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
	 * @param object $item         The current object.
	 * @param string  $column_name The current column name.
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
	 * @since TBD
	 */
	public function prepare_items(): void {
		if ( did_action( 'wp_loaded' ) ) {
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
			$this->get_items_per_page( 'edit_' . static::TABLE_ID . '_per_page' ),
			static::TABLE_ID
		);

		$this->items = $this->get_items( $per_page );
		$total_items = $per_page > count( $this->items ) ? count( $this->items ) : $this->get_total_items();

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
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
	 * @param string $text The search button text
	 * @param string $input_id The search input id
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && !$this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		}

		if ( ! empty( $_REQUEST['page'] ) ) {
			echo '<input type="hidden" name="page" value="' . esc_attr( $_REQUEST['page'] ) . '" />';
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
			<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', false, false, array('id' => 'search-submit') ); ?>
		</p>
		<?php
	}
}
