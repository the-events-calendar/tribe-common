<?php
/**
 * Provides query methods common to all custom tables.
 *
 * @since TBD
 *
 * @package TEC\Common\Traits;
 */

namespace TEC\Common\Traits;

use TEC\Common\StellarWP\DB\DB;
use InvalidArgumentException;
use DateTimeInterface;
use DateTime;
use TEC\Common\Abstracts\Custom_Table_Abstract as Table;
use TEC\Common\Contracts\Model;
use TEC\Common\Exceptions\Not_Bound_Exception;

/**
 * Trait Custom_Table_Query_Methods.
 *
 * @since TBD
 *
 * @package TEC\Common\Traits;
 */
trait Custom_Table_Query_Methods {
	/**
	 * Updates multiple rows into the table.
	 *
	 * @since TBD
	 *
	 * @param array<mixed> $entries The entries to update.
	 *
	 * @return bool Whether the update was successful.
	 */
	public static function update_many( array $entries ): bool {
		$uid_column = static::uid_column();

		$queries = [];
		$columns = array_keys( static::get_columns() );
		foreach ( $entries as $entry ) {
			$uid = $entry[ $uid_column ] ?? '';

			if ( ! $uid ) {
				continue;
			}

			$set_statement = [];

			foreach ( $entry as $column => $value ) {
				if ( $column === $uid_column ) {
					continue;
				}

				if ( ! in_array( $column, $columns, true ) ) {
					continue;
				}

				if ( $value instanceof DateTimeInterface ) {
					$value = $value->format( 'Y-m-d H:i:s' );
				}

				$set_statement[] = DB::prepare( "`{$column}` = %s", $value );
			}

			$set_statement = implode( ', ', $set_statement );

			$queries[] = DB::prepare(
				"UPDATE %i SET {$set_statement} WHERE {$uid_column} = %s;",
				static::table_name( true ),
				$uid
			);
		}

		return (bool) DB::query( implode( '', $queries ) );
	}

	/**
	 * Method used to paginate the results of a query.
	 *
	 * Also supports joining another table.
	 *
	 * @since TBD
	 *
	 * @param array  $args                      The query arguments.
	 * @param int    $per_page                  The number of items to display per page.
	 * @param int    $page                      The current page number.
	 * @param array  $columns                   The columns to select.
	 * @param string $join_table                The table to join.
	 * @param string $join_condition            The condition to join on.
	 * @param array  $selectable_joined_columns The columns from the joined table to select.
	 * @param string $output                    The output type of the query, one of OBJECT, ARRAY_A, or ARRAY_N.
	 *
	 * @return array The items.
	 * @throws InvalidArgumentException If the table to join is the same as the current table.
	 *                                  If the join condition does not contain an equal sign.
	 *                                  If the join condition does not contain valid columns.
	 */
	public static function paginate( array $args, int $per_page = 20, int $page = 1, array $columns = [ '*' ], string $join_table = '', string $join_condition = '', array $selectable_joined_columns = [], string $output = OBJECT ): array {
		$is_join = (bool) $join_table;

		if ( $is_join && static::table_name( true ) === $join_table::table_name( true ) ) {
			throw new InvalidArgumentException( 'The table to join must be different from the current table.' );
		}

		$per_page = min( max( 1, $per_page ), 200 );
		$page     = max( 1, $page );

		$offset      = ( $page - 1 ) * $per_page;
		$args_offset = $args['offset'] ?? $offset;
		$offset      = 1 === $page ? $args_offset : $offset;

		$orderby = $args['orderby'] ?? static::uid_column();
		$order   = strtoupper( $args['order'] ?? 'ASC' );

		if ( ! in_array( $orderby, array_keys( static::get_columns() ), true ) ) {
			$orderby = static::uid_column();
		}

		if ( ! in_array( $order, [ 'ASC', 'DESC' ], true ) ) {
			$order = 'ASC';
		}

		$where = static::build_where_from_args( $args );

		[ $join, $secondary_columns ] = $is_join ? static::get_join_parts( $join_table, $join_condition, $selectable_joined_columns ) : [ '', '' ];

		$columns = implode( ', ', array_map( fn( $column ) => "a.{$column}", $columns ) );

		/**
		 * Fires before the results of the query are fetched.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $args  The query arguments.
		 * @param class-string        $class The class name.
		 */
		do_action( 'tec_common_custom_table_query_pre_results', $args, static::class );

		$results = DB::get_results(
			DB::prepare(
				"SELECT {$columns}{$secondary_columns} FROM %i a {$join} {$where} ORDER BY a.{$orderby} {$order} LIMIT %d, %d",
				static::table_name( true ),
				$offset,
				$per_page
			),
			$output
		);

		$results = array_map( fn( $result ) => self::amend_value_types( $result ), $results );

		/**
		 * Fires after the results of the query are fetched.
		 *
		 * @since TBD
		 *
		 * @param array<mixed>       $results The results of the query.
		 * @param array<string,mixed> $args  The query arguments.
		 * @param class-string        $class The class name.
		 */
		do_action( 'tec_common_custom_table_query_post_results', $results, $args, static::class );

		/**
		 * Filters the results of the query.
		 *
		 * @since TBD
		 *
		 * @param array<mixed>       $results The results of the query.
		 * @param array<string,mixed> $args  The query arguments.
		 * @param class-string        $class The class name.
		 */
		return apply_filters( 'tec_common_custom_table_query_results', $results, $args, static::class );
	}

	/**
	 * Builds a WHERE clause from the provided arguments.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $args   The query arguments.
	 *
	 * @return string The WHERE clause.
	 */
	protected static function build_where_from_args( array $args = [] ): string {
		$query_operator = strtoupper( $args['query_operator'] ?? 'AND' );

		if ( ! in_array( $query_operator, [ 'AND', 'OR' ], true ) ) {
			$query_operator = 'AND';
		}

		unset( $args['order'], $args['orderby'], $args['query_operator'], $args['offset'] );

		$joined_prefix = 'a.';

		$where = [];

		$search = $args['term'] ?? '';
		if ( $search ) {
			$searchable_columns = static::get_searchable_columns();

			if ( ! empty( $searchable_columns ) ) {
				$search_where = [];

				foreach ( $searchable_columns as $column ) {
					$search_where[] = DB::prepare( "{$joined_prefix}{$column} LIKE %s", '%' . DB::esc_like( $search ) . '%' );
				}

				$where[] = '(' . implode( ' OR ', $search_where ) . ')';
			}
		}

		$columns = array_keys( static::get_columns() );

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
			if ( ! in_array( strtoupper( $arg['operator'] ), array_values( static::operators() ), true ) ) {
				$arg['operator'] = '=';
			}

			$column   = $arg['column'];
			$operator = strtoupper( $arg['operator'] );

			[ $value, $placeholder ] = self::prepare_value_for_query( $column, $arg['value'] );

			$query = "{$joined_prefix}{$column} {$operator} {$placeholder}";

			if ( is_array( $value ) ) {
				$where[] = DB::prepare( $query, ...$value );
				continue;
			}

			$where[] = DB::prepare( $query, $value );
		}

		/**
		 * Filters the WHERE clause.
		 *
		 * @since TBD
		 *
		 * @param array<string>       $where The WHERE clause parts.
		 * @param array<string,mixed> $args  The query arguments.
		 * @param class-string        $class The class name.
		 */
		$where = apply_filters( 'tec_common_custom_table_query_where', array_filter( $where ), $args, static::class );

		if ( empty( $where ) ) {
			return '';
		}

		return 'WHERE ' . implode( " {$query_operator} ", $where );
	}

	/**
	 * Gets the JOIN parts of the query.
	 *
	 * @since TBD
	 *
	 * @param string $join_table                The table to join.
	 * @param string $join_condition            The condition to join on.
	 * @param array  $selectable_joined_columns The columns from the joined table to select.
	 *
	 * @return array<string> The JOIN statement and the secondary columns to select.
	 * @throws InvalidArgumentException If the join condition does not contain an equal sign.
	 *                                  If the join condition does not contain valid columns.
	 */
	protected static function get_join_parts( string $join_table, string $join_condition, array $selectable_joined_columns = [] ): array {
		if ( ! strstr( $join_condition, '=' ) ) {
			throw new InvalidArgumentException( 'The join condition must contain an equal sign.' );
		}

		$join_condition = array_map( 'trim', explode( '=', $join_condition, 2 ) );

		$secondary_table_columns = array_keys( $join_table::get_columns() );

		$both_table_columns = array_merge( array_keys( static::get_columns() ), $secondary_table_columns );

		if ( ! in_array( $join_condition[0], $both_table_columns, true ) || ! in_array( $join_condition[1], $both_table_columns, true ) ) {
			throw new InvalidArgumentException( 'The join condition must contain valid columns.' );
		}

		$join_condition = 'a.' . str_replace( [ 'a.', 'b.' ], '', $join_condition[0] ) . ' = b.' . str_replace( [ 'a.', 'b.' ], '', $join_condition[1] );

		$clean_secondary_columns = [];

		foreach ( array_map( 'trim', $selectable_joined_columns ) as $column ) {
			if ( ! in_array( $column, $secondary_table_columns, true ) ) {
				continue;
			}

			$clean_secondary_columns[] = 'b.' . $column;
		}

		$clean_secondary_columns = $clean_secondary_columns ? ', ' . implode( ', ', $clean_secondary_columns ) : '';

		return [
			DB::prepare( "JOIN %i b ON {$join_condition}", $join_table::table_name( true ) ),
			$clean_secondary_columns,
		];
	}

	/**
	 * Gets all models by a column.
	 *
	 * @since TBD
	 *
	 * @param string $column The column to get the models by.
	 * @param mixed  $value  The value to get the models by.
	 * @param int    $limit  The limit of models to return.
	 *
	 * @return Model[] The models, or an empty array if no models are found.
	 *
	 * @throws InvalidArgumentException If the column does not exist.
	 */
	public static function get_all_by( string $column, $value, int $limit = 50 ): ?array {
		[ $value, $placeholder ] = self::prepare_value_for_query( $column, $value );

		$results = [];
		foreach ( static::fetch_all_where( DB::prepare( "WHERE {$column} = {$placeholder}", $value ), $limit, ARRAY_A ) as $task_array ) {
			if ( empty( $task_array[ static::uid_column() ] ) ) {
				continue;
			}

			$results[] = static::get_model_from_array( self::amend_value_types( $task_array ) );
		}

		return $results;
	}

	/**
	 * Gets the first model by a column.
	 *
	 * @since TBD
	 *
	 * @param string $column The column to get the model by.
	 * @param mixed  $value  The value to get the model by.
	 *
	 * @return ?Model The model, or `null` if no model is found.
	 *
	 * @throws InvalidArgumentException If the column does not exist.
	 */
	public static function get_first_by( string $column, $value ): ?Model {
		[ $value, $placeholder ] = self::prepare_value_for_query( $column, $value );

		$task_array = static::fetch_first_where( DB::prepare( "WHERE {$column} = {$placeholder}", $value ), ARRAY_A );

		if ( empty( $task_array[ static::uid_column() ] ) ) {
			return null;
		}

		return static::get_model_from_array( self::amend_value_types( $task_array ) );
	}

	/**
	 * Prepares a value for a query.
	 *
	 * @since TBD
	 *
	 * @param string $column   The column to prepare the value for.
	 * @param mixed  $value    The value to prepare.
	 *
	 * @return array<mixed, string> The prepared value and placeholder.
	 *
	 * @throws InvalidArgumentException If the column does not exist.
	 */
	private static function prepare_value_for_query( string $column, $value ): array {
		$columns = static::get_columns();

		if ( ! isset( $columns[ $column ] ) ) {
			throw new InvalidArgumentException( "Column $column does not exist." );
		}

		$column_type = $columns[ $column ]['php_type'];

		switch ( $column_type ) {
			case Table::PHP_TYPE_INT:
			case Table::PHP_TYPE_BOOL:
				$value       = is_array( $value ) ? array_map( fn( $v ) => (int) $v, $value ) : (int) $value;
				$placeholder = '%d';
				break;
			case Table::PHP_TYPE_STRING:
			case Table::PHP_TYPE_DATETIME:
				$value       = is_array( $value ) ?
					array_map( fn( $v ) => $v instanceof DateTimeInterface ? $v->format( 'Y-m-d H:i:s' ) : (string) $v, $value ) :
					( $value instanceof DateTimeInterface ? $value->format( 'Y-m-d H:i:s' ) : (string) $value );
				$placeholder = '%s';
				break;
			case Table::PHP_TYPE_FLOAT:
				$value       = is_array( $value ) ? array_map( fn( $v ) => (float) $v, $value ) : (float) $value;
				$placeholder = '%f';
				break;
			default:
				throw new InvalidArgumentException( "Unsupported column type: $column_type." );
		}

		return [ $value, is_array( $value ) ? '(' . implode( ',', array_fill( 0, count( $value ), $placeholder ) ) . ')' : $placeholder ];
	}

	/**
	 * Gets a model by its ID.
	 *
	 * @since TBD
	 *
	 * @param int|string $id The ID.
	 *
	 * @return ?Model The model, or null if not found.
	 *
	 * @throws InvalidArgumentException If the model class does not implement the Model interface.
	 */
	public static function get_by_id( $id ): ?Model {
		return static::get_first_by( static::uid_column(), $id );
	}


	/**
	 * Gets the operators supported by the table.
	 *
	 * @since TBD
	 *
	 * @return array<string, string> The operators supported by the table.
	 */
	public static function operators(): array {
		return [
			'eq'     => '=',
			'neq'    => '!=',
			'gt'     => '>',
			'lt'     => '<',
			'gte'    => '>=',
			'lte'    => '<=',
			'in'     => 'IN',
			'not_in' => 'NOT IN',
		];
	}

	/**
	 * Amends the value types of the data.
	 *
	 * @since TBD
	 *
	 * @param array<string, mixed> $data The data.
	 *
	 * @return array<string, mixed> The amended data.
	 */
	private static function amend_value_types( array $data ): array {
		$columns = static::get_columns();
		foreach ( $data as $column => $value ) {
			if ( ! isset( $columns[ $column ] ) ) {
				continue;
			}

			$column_data = $columns[ $column ];

			if ( ! empty( $column_data['nullable'] ) && null === $value ) {
				continue;
			}

			switch ( $column_data['php_type'] ) {
				case Table::PHP_TYPE_INT:
					$data[ $column ] = (int) $value;
					break;
				case Table::PHP_TYPE_STRING:
					$data[ $column ] = (string) $value;
					break;
				case Table::PHP_TYPE_FLOAT:
					$data[ $column ] = (float) $value;
					break;
				case Table::PHP_TYPE_BOOL:
					$data[ $column ] = (bool) $value;
					break;
				case Table::PHP_TYPE_DATETIME:
					try {
						$instance = tribe( DateTimeInterface::class );
					} catch ( Not_Bound_Exception $e ) {
						$instance = DateTime::class;
					}

					$data[ $column ] = $instance::createFromFormat( 'Y-m-d H:i:s', $value );
					break;
				default:
					throw new InvalidArgumentException( "Unsupported column type: {$column_data['php_type']}." );
			}
		}

		return $data;
	}

	// phpcs:disable Squiz.Commenting.FunctionComment.InvalidNoReturn, Generic.CodeAnalysis.UnusedFunctionParameter.Found

	/**
	 * Gets a model from an array.
	 *
	 * @since TBD
	 *
	 * @param array<string, mixed> $model_array The model array.
	 *
	 * @return Model The model.
	 */
	abstract protected static function get_model_from_array( array $model_array ): Model;

	// phpcs:enable Squiz.Commenting.FunctionComment.InvalidNoReturn, Generic.CodeAnalysis.UnusedFunctionParameter.Found
}
