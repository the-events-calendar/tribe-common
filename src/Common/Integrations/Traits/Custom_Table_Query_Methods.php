<?php
/**
 * Provides query methods common to all custom tables.
 *
 * @since TBD
 *
 * @package TEC\Controller\Tables;
 */

namespace TEC\Common\Integrations\Traits;

use Generator;
use TEC\Common\StellarWP\DB\DB;

/**
 * Trait Custom_Table_Query_Methods.
 *
 * @since TBD
 *
 * @package TEC\Controller\Tables;
 */
trait Custom_Table_Query_Methods {
	/**
	 * Fetches all the rows from the table using a batched query.
	 *
	 * @since TBD
	 *
	 * @param int    $batch_size   The number of rows to fetch per batch.
	 * @param string $output       The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
	 * @param string $where_clause The optional WHERE clause to use.
	 *
	 * @return Generator<array<string, mixed>> The rows from the table.
	 */
	public static function fetch_all( int $batch_size = 50, string $output = OBJECT, string $where_clause = '' ): Generator {
		$fetched = 0;
		$total   = null;
		$offset  = 0;

		do {
			// On first iteration, we need to set the SQL_CALC_FOUND_ROWS flag.
			$sql_calc_found_rows = 0 === $fetched ? 'SQL_CALC_FOUND_ROWS' : '';

			$batch = DB::get_results(
				DB::prepare(
					"SELECT {$sql_calc_found_rows} * FROM %i {$where_clause} ORDER BY id LIMIT %d, %d",
					static::table_name( true ),
					$offset,
					$batch_size
				),
				$output
			);

			// We need to get the total number of rows, only after the first batch.
			$total  ??= DB::get_var( 'SELECT FOUND_ROWS()' );
			$fetched += count( $batch );

			yield from $batch;
		} while ( $fetched < $total );
	}

	/**
	 * Inserts multiple rows into the table.
	 *
	 * @since TBD
	 *
	 * @param array<mixed> $entries The entries to insert.
	 *
	 * @return bool|int The number of rows affected, or `false` on failure.
	 */
	public static function insert_many( array $entries ) {
		$columns          = array_keys( $entries[0] );
		$prepared_columns = implode(
			', ',
			array_map(
				static fn( string $column ) => "`$column`",
				$columns
			)
		);
		$prepared_values  = implode(
			', ',
			array_map(
				static function ( array $entry ) use ( $columns ) {
					return '(' . implode( ', ', array_map( static fn( $e ) => DB::prepare( '%s', $e ), $entry ) ) . ')';
				},
				$entries
			)
		);

		return DB::query(
			DB::prepare(
				"INSERT INTO %i ({$prepared_columns}) VALUES {$prepared_values}",
				static::table_name( true ),
			)
		);
	}

	/**
	 * Fetches all the rows from the table using a batched query and a WHERE clause.
	 *
	 * @since TBD
	 *
	 * @param string $where_clause The WHERE clause to use.
	 * @param int    $batch_size   The number of rows to fetch per batch.
	 * @param string $output       The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
	 *
	 * @return Generator<array<string, mixed>> The rows from the table.
	 */
	public static function fetch_all_where( string $where_clause, int $batch_size = 50, string $output = OBJECT ): Generator {
		return static::fetch_all( $batch_size, $output, $where_clause );
	}

	/**
	 * Fetches the first row from the table using a WHERE clause.
	 *
	 * @since TBD
	 *
	 * @param string $where_clause The prepared WHERE clause to use.
	 * @param string $output       The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
	 *
	 * @return array|object|null The row from the table, or `null` if no row was found.
	 */
	public static function fetch_first_where( string $where_clause, string $output = OBJECT ) {
		return DB::get_row(
			DB::prepare(
				"SELECT * FROM %i {$where_clause} LIMIT 1",
				static::table_name( true )
			),
			$output
		);
	}

	/**
	 * Method used to paginate the results of a query.
	 *
	 * @since TBD
	 *
	 * @param array  $args     The query arguments.
	 * @param int    $per_page The number of items to display per page.
	 * @param int    $page     The current page number.
	 * @param string $output   The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
	 *
	 * @return array The items.
	 */
	public static function paginate( array $args, int $per_page = 20, int $page = 1, string $output = OBJECT ): array {
		$per_page = min( max( 1, $per_page ), 200 );
		$page     = max( 1, $page );

		$offset = ( $page - 1 ) * $per_page;

		$orderby = $args['orderby'] ?? self::uid_column();
		$order   = strtoupper( $args['order'] ?? 'ASC' );

		if ( ! in_array( $orderby, static::get_columns(), true ) ) {
			$orderby = self::uid_column();
		}

		if ( ! in_array( $order, [ 'ASC', 'DESC' ], true ) ) {
			$order = 'ASC';
		}

		$where = self::build_where_from_args( $args );

		return DB::get_results(
			DB::prepare(
				"SELECT * FROM %i {$where} ORDER BY {$orderby} {$order} LIMIT %d, %d",
				static::table_name( true ),
				$offset,
				$per_page
			),
			$output
		);
	}

	/**
	 * Gets the total number of items in the table.
	 *
	 * @since TBD
	 *
	 * @param array $args The query arguments.
	 *
	 * @return int The total number of items in the table.
	 */
	public static function get_total_items( array $args = [] ): int {
		$where = self::build_where_from_args( $args );

		return (int) DB::get_var(
			DB::prepare(
				"SELECT COUNT(*) FROM %i {$where}",
				static::table_name( true )
			)
		);
	}

	/**
	 * Builds a WHERE clause from the provided arguments.
	 *
	 * @since TBD
	 *
	 * @param array $args The query arguments.
	 *
	 * @return string The WHERE clause.
	 */
	protected static function build_where_from_args( array $args = [] ): string {
		$query_operator = $args['query_operator'] ?? 'AND';

		unset( $args['order'], $args['orderby'], $args['query_operator'] );

		if ( empty( $args ) ) {
			return '';
		}

		$where = [];

		$columns = static::get_columns();

		foreach ( $args as $arg ) {
			if ( ! is_array( $arg ) ) {
				continue;
			}

			if ( empty( $arg['column'] ) ) {
				continue;
			}

			if ( ! in_array( $arg['column'], $columns, true ) ) {
				continue;
			}

			if ( empty( $arg['value'] ) ) {
				// We check that the column has any value then.
				$arg['value']    = '';
				$arg['operator'] = '!=';
			}

			if ( empty( $arg['operator'] ) ) {
				$arg['operator'] = '=';
			}

			// For anything else, you should build your own query!
			if ( ! in_array( $arg['operator'], [ '=', '!=', '>', '<', '>=', '<=' ], true ) ) {
				$arg['operator'] = '=';
			}

			$column      = $arg['column'];
			$operator    = $arg['operator'];
			$value       = $arg['value'];
			$placeholder = is_numeric( $value ) ? '%d' : '%s'; // Only integers and strings are supported currently.

			$where[] = DB::prepare( "{$column} {$operator} {$placeholder}", $value );
		}

		if ( empty( $where ) ) {
			return '';
		}

		return 'WHERE ' . implode( " {$query_operator} ", $where );
	}
}
