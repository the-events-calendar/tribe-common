<?php
/**
 * Abstract for Custom Tables.
 *
 * @since TDB
 *
 * @package TEC\Common\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\Abstracts;

use TEC\Common\StellarWP\DB\DB;
use TEC\Common\Traits\Custom_Table_Query_Methods;
use DateTimeInterface;
use TEC\Common\Integrations\Custom_Table_Abstract as Base_Table;

/**
 * Class Custom_Table_Abstract
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */
abstract class Custom_Table_Abstract extends Base_Table {
	use Custom_Table_Query_Methods;

	/**
	 * The PHP type for an integer.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const PHP_TYPE_INT = 'int';

	/**
	 * The PHP type for a string.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const PHP_TYPE_STRING = 'string';

	/**
	 * The PHP type for a json.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const PHP_TYPE_JSON = 'json';

	/**
	 * The PHP type for a float.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const PHP_TYPE_FLOAT = 'float';

	/**
	 * The PHP type for a boolean.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const PHP_TYPE_BOOL = 'bool';

	/**
	 * The PHP type for a datetime.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const PHP_TYPE_DATETIME = DateTimeInterface::class;

	/**
	 * The column type for a bigint.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const COLUMN_TYPE_BIGINT = 'bigint';

	/**
	 * The column type for a varchar.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const COLUMN_TYPE_VARCHAR = 'varchar';

	/**
	 * The column type for a text.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const COLUMN_TYPE_TEXT = 'text';

	/**
	 * The column type for a longtext.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const COLUMN_TYPE_LONGTEXT = 'longtext';

	/**
	 * The column type for a boolean.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const COLUMN_TYPE_BOOLEAN = 'boolean';

	/**
	 * The column type for a timestamp.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const COLUMN_TYPE_TIMESTAMP = 'timestamp';

	public const SQL_RESERVED_DEFAULTS = [
		'CURRENT_TIMESTAMP',
		'CURRENT_DATE',
		'CURRENT_TIME',
	];

	/**
	 * Constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->db        = DB::class;
		$this->container = tribe();
	}

	/**
	 * Returns the table creation SQL in the format supported
	 * by the `dbDelta` function.
	 *
	 * @since TBD
	 *
	 * @return string The table creation SQL, in the format supported
	 *                by the `dbDelta` function.
	 */
	public function get_definition() {
		global $wpdb;
		$table_name      = static::table_name( true );
		$charset_collate = $wpdb->get_charset_collate();
		$uid_column      = static::uid_column();

		$columns = static::get_columns();

		$columns_definitions = [];
		foreach ( $columns as $column => $definition ) {
			$column_sql = "`{$column}` {$definition['type']}";

			if ( ! empty( $definition['length'] ) ) {
				$column_sql .= "({$definition['length']})";
			}

			if ( ! empty( $definition['unsigned'] ) ) {
				$column_sql .= ' UNSIGNED';
			}

			$column_sql .= ! empty( $definition['nullable'] ) ? ' NULL' : ' NOT NULL';

			if ( ! empty( $definition['auto_increment'] ) ) {
				$column_sql .= ' AUTO_INCREMENT';
			}

			if ( ! empty( $definition['default'] ) ) {
				$column_sql .= ' DEFAULT ' . ( in_array( $definition['default'], self::SQL_RESERVED_DEFAULTS, true ) || in_array( $definition['php_type'], [ self::PHP_TYPE_INT, self::PHP_TYPE_BOOL, self::PHP_TYPE_FLOAT ], true ) ? $definition['default'] : "'{$definition['default']}'" );
			}

			$columns_definitions[] = $column_sql;
		}

		$columns_sql = implode( ',' . PHP_EOL, $columns_definitions );

		return "
			CREATE TABLE `{$table_name}` (
				{$columns_sql},
				PRIMARY KEY (`{$uid_column}`)
			) {$charset_collate};
		";
	}

	/**
	 * Add indexes after table creation.
	 *
	 * @since TBD
	 *
	 * @param array<string,string> $results A map of results in the format
	 *                                      returned by the `dbDelta` function.
	 *
	 * @return array<string,string> A map of results in the format returned by
	 *                              the `dbDelta` function.
	 */
	protected function after_update( array $results ) {
		foreach ( static::get_columns() as $column => $definition ) {
			if ( empty( $definition['index'] ) ) {
				continue;
			}

			$this->add_index( $column );
		}

		return $results;
	}

	/**
	 * Helper method to check and add an index to a table.
	 *
	 * @since TBD
	 *
	 * @param string $index_name The name of the index.
	 * @param string $columns    The columns to index. Defaults to the index name.
	 *
	 * @return void
	 */
	public function add_index( string $index_name, string $columns = '' ): void {
		$this->check_and_add_index( [], $index_name, $columns ? $columns : $index_name );
	}
}
