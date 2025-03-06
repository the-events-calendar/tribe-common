<?php
/**
 * Abstract for Custom Tables.
 *
 * @since TDB
 *
 * @package TEC\Common\Integrations
 */

namespace TEC\Common\Integrations;

use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;
use TEC\Common\StellarWP\DB\DB;

/**
 * Class Integration_Abstract
 *
 * @since 6.5.3
 *
 * @package TEC\Common\Integrations
 */
abstract class Custom_Table_Abstract extends Table {
	use Traits\Custom_Table_Query_Methods;

	/**
	 * An array of all the columns in the table.
	 *
	 * @since 6.5.3
	 *
	 * @var string[]
	 */
	abstract public static function get_columns(): array;

	/**
	 * An array of all the columns that are searchable.
	 *
	 * @since 6.5.3
	 *
	 * @return string[]
	 */
	public static function get_searchable_columns(): array {
		return [];
	}

	/**
	 * Helper method to check and add an index to a table.
	 *
	 * @since 6.5.3
	 *
	 * @param array  $results    The results array to track changes.
	 * @param string $index_name The name of the index.
	 * @param string $columns    The columns to index.
	 *
	 * @return array The updated results array.
	 */
	protected function check_and_add_index( array $results, string $index_name, string $columns ): array {
		$index_name = esc_sql( $index_name );

		// Add index only if it does not exist.
		if ( $this->has_index( $index_name ) ) {
			return $results;
		}

		$columns = esc_sql( $columns );

		DB::query(
			DB::prepare( "ALTER TABLE %i ADD INDEX `{$index_name}` ( {$columns} )", esc_sql( static::table_name( true ) ) )
		);

		return $results;
	}
}
