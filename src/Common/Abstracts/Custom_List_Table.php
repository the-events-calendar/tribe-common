<?php
/**
 * The Abstract Custom List Table.
 *
 * @since TBD
 *
 * @package TEC/Common/Abstracts
 */

namespace TEC\Common\Abstracts;

use TEC\Common\Admin\Abstract_Custom_List_Table as Base_Abstract;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
use TEC\Common\Contracts\Custom_Table_Repository_Interface as Repository;

/**
 * Class Abstract_Custom_List_Table
 *
 * @since TBD
 *
 * @package TEC/Common/Abstracts
 */
abstract class Custom_List_Table extends Base_Abstract {
	/**
	 * Returns the total number of items.
	 *
	 * @since TBD
	 *
	 * @return int The total number of items.
	 */
	protected function get_total_items(): int {
		$repo = $this->get_repository();

		$repo->set_use_default_args( false );
		$result = $repo->by_args( $this->get_arguments() )->found();
		$repo->set_use_default_args( true );

		return $result;
	}

	/**
	 * Returns the repository.
	 *
	 * @since TBD
	 *
	 * @return Repository The repository.
	 */
	abstract protected function get_repository(): Repository;

	/**
	 * Returns the items for the current page.
	 *
	 * @since TBD
	 *
	 * @param int $per_page The number of items to display per page.
	 *
	 * @return array The items for the current page.
	 */
	protected function get_items( int $per_page ): array {
		$repo = $this->get_repository();

		$repo->set_use_default_args( false );
		$results = $repo->by_args( $this->get_arguments() )->page( $this->get_pagenum() )->per_page( $per_page )->all();
		$repo->set_use_default_args( true );

		return $results;
	}

	/**
	 * Returns whether the list is completely empty.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_empty(): bool {
		$repo = $this->get_repository();
		$repo->set_use_default_args( false );
		$result = $repo->found();
		$repo->set_use_default_args( true );

		return 0 === $result;
	}

	/**
	 * Returns the arguments to query the items.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	private function get_arguments(): array {
		return array_merge( $this->get_args(), $this->get_instance_args() );
	}

	/**
	 * Returns the instance arguments.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	abstract protected function get_instance_args(): array;

	/**
	 * Returns the arguments to query the items.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$column_slugs = array_keys( $this->get_columns() );

		$column_slugs = array_diff( $column_slugs, [ 'cb' ] );

		/**
		 * Filters the list of sortable columns for the custom table.
		 *
		 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_bulk_actions(): array {
		return [ 'delete' => __( 'Delete', 'tribe-common' ) ];
	}
}
