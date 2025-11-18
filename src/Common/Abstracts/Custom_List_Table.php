<?php
/**
 * The Abstract Custom List Table.
 *
 * @since 6.10.0
 *
 * @package TEC/Common/Abstracts
 */

namespace TEC\Common\Abstracts;

use TEC\Common\Admin\Abstract_Custom_List_Table as Base_Abstract;
use TEC\Common\Contracts\Custom_Table_Repository_Interface as Repository;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;

/**
 * Class Abstract_Custom_List_Table
 *
 * @since 6.10.0
 *
 * @package TEC/Common/Abstracts
 */
abstract class Custom_List_Table extends Base_Abstract {
	/**
	 * Returns the total number of items.
	 *
	 * @since 6.10.0
	 *
	 * @return int The total number of items.
	 */
	protected function get_total_items(): int {
		return $this->get_cloned_repository()->by_args( $this->get_arguments() )->found();
	}

	/**
	 * Returns the repository.
	 *
	 * @since 6.10.0
	 *
	 * @return Repository The repository.
	 */
	abstract protected function get_repository(): Repository;

	/**
	 * Returns a cloned repository with the default arguments set to an empty array.
	 *
	 * @since 6.10.0
	 *
	 * @return Repository The cloned repository.
	 */
	private function get_cloned_repository(): Repository {
		$cloned = clone $this->get_repository();
		$cloned->set_default_args( [] );
		return $cloned;
	}

	/**
	 * Returns the items for the current page.
	 *
	 * @since 6.10.0
	 *
	 * @param int $per_page The number of items to display per page.
	 *
	 * @return array The items for the current page.
	 */
	protected function get_items( int $per_page ): array {
		return $this->get_cloned_repository()->by_args( $this->get_arguments() )->page( $this->get_pagenum() )->per_page( $per_page )->all();
	}

	/**
	 * Returns whether the list is completely empty.
	 *
	 * @since 6.10.0
	 *
	 * @return bool
	 */
	public function is_empty(): bool {
		return 0 === $this->get_cloned_repository()->found();
	}

	/**
	 * Returns the arguments to query the items.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	private function get_arguments(): array {
		return array_merge( $this->get_args(), $this->get_object_query_args() );
	}

	/**
	 * Returns the instance arguments.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	protected function get_object_query_args(): array {
		return [];
	}

	/**
	 * Returns the arguments to query the items.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	private function get_args(): array {
		$args = [
			'orderby' => $this->get_orderby( 'created' ),
			'order'   => $this->get_order( 'DESC' ),
			'term'    => tec_get_request_var( 's', '' ),
		];

		return array_filter( $args );
	}

	/**
	 * List of sortable columns.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$column_slugs = array_keys( $this->get_columns() );

		$column_slugs = array_diff( $column_slugs, [ 'cb' ] );

		/**
		 * Filters the list of sortable columns for the custom table.
		 *
		 * @since 6.10.0
		 *
		 * @param array $columns List of columns that can be sorted.
		 */
		return (array) apply_filters(
			'tec_common_custom_table_sortable_columns',
			array_combine( $column_slugs, $column_slugs )
		);
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @since 6.10.0
	 *
	 * @param Model $item The current object.
	 */
	public function column_cb( $item ): void {
		$show = current_user_can( 'manage_options' );

		if ( ! $show ) {
			return;
		}

		?>
		<input id="cb-select-<?php echo esc_attr( $item->getPrimaryValue() ); ?>" type="checkbox" name="item_id[]" value="<?php echo esc_attr( $item->getPrimaryValue() ); ?>" />
		<label for="cb-select-<?php echo esc_attr( $item->getPrimaryValue() ); ?>">
			<span class="screen-reader-text">
			<?php
				/* translators: %d: The id of the item. */
				printf( esc_html__( 'Select the item with id: %d', 'tribe-common' ), esc_html( $item->getPrimaryValue() ) );
			?>
			</span>
		</label>
		<?php
	}

	/**
	 * Returns the bulk actions.
	 *
	 * @since 6.10.0
	 *
	 * @return array
	 */
	public function get_bulk_actions(): array {
		return [ 'delete' => __( 'Delete', 'tribe-common' ) ];
	}
}
