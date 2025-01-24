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
 * @since TBD
 *
 * @package TEC\Common\Integrations
 */
abstract class Custom_Table_Abstract extends Table {
	use Traits\Custom_Table_Query_Methods;

	/**
	 * Helper method to check and add an index to a table.
	 *
	 * @since TBD
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

	/**
	 * Empties the custom table in a way that is not causing an implicit commit.
	 *
	 * Even though the method is called truncate it doesn't use TRUNCATE.
	 * Thats because we want to avoid implicit commits in the DB making this method suitable for using during a testcase.
	 * If you want to use TRUNCATE you can use the `empty_table` method instead.
	 *
	 * @since TBD
	 *
	 * @return bool|int Whether it was emptied or not.
	 */
	public function truncate() {
		DB::query( 'SET FOREIGN_KEY_CHECKS = 0;' );
		$deleted = DB::query(
			DB::prepare(
				'DELETE FROM %i',
				static::table_name( true )
			)
		);
		DB::query( 'SET FOREIGN_KEY_CHECKS = 1;' );

		return is_numeric( $deleted );
	}
}
