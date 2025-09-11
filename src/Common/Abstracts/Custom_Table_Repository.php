<?php
/**
 * The custom table repository.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\Abstracts;

use TEC\Common\Contracts\Custom_Table_Repository_Interface as Repository_Interface;
use TEC\Common\Contracts\Model;
use TEC\Common\StellarWP\DB\DB;
use Tribe__Promise as Promise;
use RuntimeException;

/**
 * The custom table repository.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */
abstract class Custom_Table_Repository implements Repository_Interface {
	/**
	 * The schema.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $schema = [];

	/**
	 * The update fields aliases.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $aliases = [];

	/**
	 * The default arguments.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $default_select_args = [];

	/**
	 * The default arguments used for create queries.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $default_create_args = [];

	/**
	 * The arguments used for select queries.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $select_args = [];

	/**
	 * The arguments used for upsert queries.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $upsert_args = [];

	/**
	 * The found rows.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	protected int $found_rows = 0;

	/**
	 * The page used for select queries.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	protected int $page = 1;

	/**
	 * The per page used for select queries.
	 *
	 * If not changed, we are selecting them all by default.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	protected int $per_page = 10000000000;

	/**
	 * Constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->order_by( $this->get_table_interface()::uid_column(), 'DESC' );
		foreach ( array_keys( $this->get_table_interface()::get_columns() ) as $column ) {
			$this->add_schema_entry(
				$column,
				function( $value ) use ( $column ) {
					return [
						'column' => $column,
						'value'  => $value,
					];
				}
			);

			$this->add_schema_entry(
				$column . '_in',
				function( $value ) use ( $column ) {
					return [
						'column'   => $column,
						'value'    => $value,
						'operator' => 'IN',
					];
				}
			);

			$this->add_schema_entry(
				$column . '_not_in',
				function( $value ) use ( $column ) {
					return [
						'column'   => $column,
						'value'    => $value,
						'operator' => 'NOT IN',
					];
				}
			);
		}

		$model = tribe( $this->get_model_class() );

		$relationships = $model->get_relationships();

		foreach ( $relationships as $key => $relationship ) {
			if ( $relationship['type'] !== Model_Abstract::RELATIONSHIP_TYPE_MANY_TO_MANY ) {
				continue;
			}

			$this->add_schema_entry(
				$key,
				function ( $value ) use ( $relationship ) {
					$callback = function ( array $where ) use ( $relationship, $value ) {
						$where[] = DB::prepare( " AND %i = %d", $relationship['columns']['other'], $value );
						return $where;
					};

					add_filter( 'tec_common_custom_table_query_where', $callback );
					add_action( 'tec_common_custom_table_query_pre_results', fn() => remove_filter( 'tec_common_custom_table_query_where', $callback ) );
					return [];
				}
			);

			$this->add_schema_entry(
				$key . '_in',
				function ( $value ) use ( $relationship ) {
					$callback = function ( array $where ) use ( $relationship, $value ) {
						$value = (array) $value;
						$placeholders = '(' . implode( ',', array_fill( 0, count( $value ), '%d' ) ) . ')';
						$where[] = DB::prepare( " AND %i IN {$placeholders}", $relationship['columns']['other'], ...$value );
						return $where;
					};

					add_filter( 'tec_common_custom_table_query_where', $callback );
					add_action( 'tec_common_custom_table_query_pre_results', fn() => remove_filter( 'tec_common_custom_table_query_where', $callback ) );
					return [];
				}
			);

			$this->add_schema_entry(
				$key . '_not_in',
				function ( $value ) use ( $relationship ) {
					$callback = function ( array $where ) use ( $relationship, $value ) {
						$value = (array) $value;
						$placeholders = '(' . implode( ',', array_fill( 0, count( $value ), '%d' ) ) . ')';
						$where[] = DB::prepare( " AND %i NOT IN {$placeholders}", $relationship['columns']['other'], ...$value );
						return $where;
					};

					add_filter( 'tec_common_custom_table_query_where', $callback );
					add_action( 'tec_common_custom_table_query_pre_results', fn() => remove_filter( 'tec_common_custom_table_query_where', $callback ) );
					return [];
				}
			);
		}

		$this->add_update_field_alias( 'id', $this->get_table_interface()::uid_column() );
	}

	/**
	 * Gets the default arguments.
	 *
	 * @since TBD
	 *
	 * @return array The default arguments used for select queries.
	 */
	public function get_default_args(): array {
		return $this->default_select_args;
	}

	/**
	 * Sets the default arguments.
	 *
	 * @since TBD
	 *
	 * @param array $default_select_args The default arguments used for select queries.
	 */
	public function set_default_args( array $default_select_args ): void {
		$this->default_select_args = $default_select_args;
	}

	/**
	 * Gets the select arguments.
	 *
	 * @since TBD
	 *
	 * @return array The select arguments.
	 */
	protected function get_select_args(): array {
		$args = array_merge( $this->get_default_args(), $this->select_args );

		$new_args = [];

		foreach ( $args as $key => $value ) {
			if ( in_array( $key, [ 'order', 'order_by', 'term', 'offset' ], true ) ) {
				$new_args[ $key ] = $value;
				continue;
			}

			if ( ! isset( $this->get_schema()[ $key ] ) ) {
				throw new RuntimeException( "Filter {$key} is not supported for custom table repositories." );
			}

			$new_args[] = array_merge( $new_args, $this->get_schema()[ $key ]( $value ) );
		}

		return $args;
	}

	/**
	 * Sets the create args.
	 *
	 * @since TBD
	 *
	 * @param array $default_create_args The create args.
	 */
	public function set_create_args( array $default_create_args ): void {
		$this->default_create_args = $default_create_args;
	}

	/**
	 * Gets the create args.
	 *
	 * @since TBD
	 *
	 * @return array The create args.
	 */
	public function get_create_args(): array {
		return array_merge( $this->default_create_args, $this->upsert_args );
	}

	/**
	 * Sets the found rows.
	 *
	 * @since TBD
	 *
	 * @param bool $found_rows The found rows.
	 */
	public function set_found_rows( $found_rows ): self {
		$this->found_rows = (int) $found_rows;
		return $this;
	}

	/**
	 * Gets a model by its primary key.
	 *
	 * @since TBD
	 *
	 * @param int|string $primary_key The primary key's value.
	 *
	 * @return Model|null|WP_Post The model.
	 */
	public function by_primary_key( $primary_key ) {
		$model = $this->get_table_interface()::get_by_id( $primary_key );

		if ( ! $model ) {
			return null;
		}

		return $model;
	}

	/**
	 * Deletes the models.
	 *
	 * @since TBD
	 *
	 * @param bool $return_promise Whether to return a promise.
	 *
	 * @return Promise|array The deleted IDs.
	 */
	public function delete( bool $return_promise = false ) {
		$callback = function () {
			$deleted_ids = [];
			foreach( $this->all( true ) as $model ) {
				$deleted_ids[ $model->get_id() ] = $model->delete();
			}

			return $deleted_ids;
		};

		return $return_promise ? new Promise( $callback ) : $callback();
	}

	/**
	 * Sets an argument used for upsert queries.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to set.
	 * @param mixed $value The value to set.
	 *
	 * @return self The repository instance.
	 */
	public function set( $key, $value ): self {
		$this->upsert_args[ $key ] = $value;
		return $this;
	}

	/**
	 * Sets arguments used for upsert queries.
	 *
	 * @since TBD
	 *
	 * @param array $update_map The arguments to set.
	 *
	 * @return self The repository instance.
	 */
	public function set_args( array $update_map ): self {
		$this->upsert_args = array_merge( $this->upsert_args, $update_map );
		return $this;
	}

	/**
	 * Sets arguments used for select queries.
	 *
	 * @since TBD
	 *
	 * @param array $args The arguments to set.
	 *
	 * @return self The repository instance.
	 */
	public function by_args( array $args ): self {
		$this->select_args = array_merge( $this->select_args, $args );
		return $this;
	}

	/**
	 * Sets an argument used for select queries.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to set.
	 * @param mixed $value The value to set.
	 *
	 * @return self The repository instance.
	 */
	public function by( $key, $value = null ): self {
		$this->select_args[ $key ] = $value;
		return $this;
	}

	/**
	 * Sets an argument used for select queries.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to set.
	 * @param mixed $value The value to set.
	 *
	 * @return self The repository instance.
	 */
	public function where( $key, $value = null ): self {
		return $this->by( $key, $value );
	}

	/**
	 * Sets the page used for select queries.
	 *
	 * @since TBD
	 *
	 * @param int $page The page to set.
	 *
	 * @return self The repository instance.
	 */
	public function page( $page ): self {
		$this->page = (int) $page;
		return $this;
	}

	/**
	 * Sets the per page used for select queries.
	 *
	 * @since TBD
	 *
	 * @param int $per_page The per page to set.
	 *
	 * @return self The repository instance.
	 */
	public function per_page( $per_page ): self {
		$this->per_page = (int) $per_page;
		return $this;
	}

	/**
	 * Gets the found rows.
	 *
	 * @since TBD
	 *
	 * @return int The found rows.
	 */
	public function found(): int {
		$this->set_found_rows( $this->get_table_interface()::get_total_items( $this->get_select_args() ) );
		return $this->found_rows;
	}

	/**
	 * Gets all the models.
	 *
	 * @since TBD
	 *
	 * @param bool $return_generator Whether to return a generator of models instead of an array of models.
	 * @param int $batch_size The batch size to set.
	 *
	 * @return Model[]|Generator<Model> The models.
	 */
	public function all( $return_generator = false, int $batch_size = 50 ) {
		$all = [];
		do {
			$i = 1;
			$results = $this->get_table_interface()::paginate( $this->get_select_args(), $batch_size, $i );
			$i++;

			$results = array_map( fn( $result ) => $this->get_model_class()::from_array( $result ), $results );

			if ( $return_generator ) {
				yield from $results;
			} else {
				$all = array_merge( $all, $results );
			}
		} while( ! empty( $results ) && $i * $batch_size < $this->per_page );

		if ( $return_generator ) {
			return [];
		}

		return $all;
	}

	public function offset( $offset, $increment = false ): self {
		return $this->by( 'offset', $offset );
	}

	/**
	 * Sets the order on the query.
	 *
	 * @since TBD
	 *
	 * @param string $order The order to set.
	 *
	 * @return self The repository instance.
	 */
	public function order( $order = 'ASC' ): self {
		return $this->by( 'order', $order );
	}

	/**
	 * Sets the order by on the query.
	 *
	 * @since TBD
	 *
	 * @param string $order_by The order by to set.
	 * @param string $order The order to set.
	 *
	 * @return self The repository instance.
	 */
	public function order_by( $order_by, $order = 'DESC' ): self {
		$this->order( $order );
		return $this->by( 'order_by', $order_by );
	}

	/**
	 * Sets the fields on the query.
	 *
	 * @since TBD
	 *
	 * @param string $fields The fields to set.
	 *
	 * @return self The repository instance.
	 *
	 * @throws RuntimeException Fields are not supported for custom table repositories.
	 */
	public function fields( $fields ): self {
		throw new RuntimeException( 'Fields are not supported for custom table repositories.' );
	}

	/**
	 * Sets the in on the query.
	 *
	 * @since TBD
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @return self The repository instance.
	 *
	 * @throws RuntimeException In is not supported for custom table repositories.
	 */
	public function in( $post_ids ): self {
		throw new RuntimeException( 'In is not supported for custom table repositories.' );
	}

	/**
	 * Sets the not in on the query.
	 *
	 * @since TBD
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @return self The repository instance.
	 *
	 * @throws RuntimeException Not in is not supported for custom table repositories.
	 */
	public function not_in( $post_ids ): self {
		throw new RuntimeException( 'Not in is not supported for custom table repositories.' );
	}

	/**
	 * Sets the parent on the query.
	 *
	 * @since TBD
	 *
	 * @param int $post_id The post id to set.
	 *
	 * @return self The repository instance.
	 *
	 * @throws RuntimeException Parent is not supported for custom table repositories.
	 */
	public function parent( $post_id ): self {
		throw new RuntimeException( 'Parent is not supported for custom table repositories.' );
	}

	/**
	 * Sets the parent in on the query.
	 *
	 * @since TBD
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @return self The repository instance.
	 *
	 * @throws RuntimeException Parent in is not supported for custom table repositories.
	 */
	public function parent_in( $post_ids ): self {
		throw new RuntimeException( 'Parent in is not supported for custom table repositories.' );
	}

	/**
	 * Sets the parent not in on the query.
	 *
	 * @since TBD
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @return self The repository instance.
	 *
	 * @throws RuntimeException Parent not in is not supported for custom table repositories.
	 */
	public function parent_not_in( $post_ids ): self {
		throw new RuntimeException( 'Parent not in is not supported for custom table repositories.' );
	}

	/**
	 * Sets the search on the query.
	 *
	 * @since TBD
	 *
	 * @param string $search The search to set.
	 *
	 * @return self The repository instance.
	 */
	public function search( $search ): self {
		return $this->by( 'term', $search );
	}

	/**
	 * Gets the count of the models.
	 *
	 * @since TBD
	 *
	 * @return int The count of the models.
	 */
	public function count(): int {
		$max = $this->found();

		return min( $max, $this->per_page );
	}

	/**
	 * Gets the first model.
	 *
	 * @since TBD
	 *
	 * @return ?Model The first model.
	 */
	public function first(): ?Model {
		$per_page = $this->per_page;
		$this->per_page( 1 );
		$models = $this->all( false, 1 );
		$this->per_page( $per_page );
		return $models[0] ?? null;
	}

	/**
	 * Gets the last model.
	 *
	 * @since TBD
	 *
	 * @return ?Model The last model.
	 */
	public function last(): ?Model {
		$select_args = $this->get_select_args();
		$order = $select_args['order'] ?? 'DESC';
		$this->order( 'DESC' === $order ? 'ASC' : 'DESC' );
		$model = $this->first();
		$this->order( $order );
		return $model;
	}

	/**
	 * Gets the nth model.
	 *
	 * @since TBD
	 *
	 * @param int $n The nth model to get.
	 *
	 * @return ?Model The nth model.
	 */
	public function nth( $n ): ?Model {
		$select_args = $this->get_select_args();
		$offset = $select_args['offset'] ?? 0;
		$this->offset( $n );
		$model = $this->first();
		$this->offset( $offset );
		return $model;
	}

	/**
	 * Gets the first n models.
	 *
	 * @since TBD
	 *
	 * @param int $n The number of models to get.
	 *
	 * @return array The first n models.
	 */
	public function take( $n ): array {
		$per_page = $this->per_page;
		$this->per_page( $n );
		$models = $this->all( false, $n );
		$this->per_page( $per_page );
		return $models;
	}

	/**
	 * Gets the first n models.
	 *
	 * @since TBD
	 *
	 * @param int $n The number of models to get.
	 *
	 * @return array The first n models.
	 */
	public function pluck( $field ): array {
		$method = 'get_' . $field;
		$results = [];
		foreach ( $this->all( true ) as $model ) {
			$results[] = $model->$method();
		}

		return $results;
	}

	/**
	 * Filters the models.
	 *
	 * @since TBD
	 *
	 * @param array $args The arguments to filter by.
	 * @param string $operator The operator to filter by.
	 *
	 * @return array The filtered models.
	 */
	public function filter( $args = [], $operator = 'AND' ): array {
		$results = [];
		foreach ( $this->all( true ) as $model ) {
			foreach ( $args as $key => $value ) {
				$method = 'get_' . $key;
				if ( $model->$method() !== $value ) {
					if ( $operator === 'AND' ) {
						continue 2;
					}

					continue;
				}

				$results[] = $model;
				break;
			}
		}

		return $results;
	}

	/**
	 * Sorts the models.
	 *
	 * @since TBD
	 *
	 * @param array $orderby The orderby to set.
	 * @param string $order The order to set.
	 * @param bool $preserve_keys The preserve keys to set.
	 *
	 * @return array The sorted models.
	 */
	public function sort( $orderby = [], $order = 'ASC', $preserve_keys = false ): array {
		throw new RuntimeException( 'Sort is not supported for custom table repositories.' );
	}

	/**
	 * Collects the models.
	 *
	 * @since TBD
	 *
	 * @return array The collected models.
	 *
	 * @throws RuntimeException Collect is not supported for custom table repositories.
	 */
	public function collect(): array {
		throw new RuntimeException( 'Collect is not supported for custom table repositories.' );
	}

	/**
	 * Gets the ids of the models.
	 *
	 * @since TBD
	 *
	 * @param bool $return_generator Whether to return a generator of ids instead of an array of ids.
	 * @param int $batch_size The batch size to set.
	 *
	 * @return Generator<int>|int[] The ids of the models.
	 */
	public function get_ids( $return_generator = false, int $batch_size = 50 ) {
		$all = [];
		do {
			$i = 1;
			$results = $this->get_table_interface()::paginate( $this->get_select_args(), $batch_size, $i, [ $this->get_table_interface()::uid_column() ] );
			$i++;

			if ( $return_generator ) {
				yield from array_column( $results, $this->get_table_interface()::uid_column() );
			} else {
				$all = array_merge( $all, array_column( $results, $this->get_table_interface()::uid_column() ) );
			}
		} while( ! empty( $results ) && $i * $batch_size < $this->per_page );

		if ( $return_generator ) {
			return [];
		}

		return $all;
	}

	/**
	 * Saves the models.
	 *
	 * @since TBD
	 *
	 * @param bool $return_promise Whether to return a promise.
	 *
	 * @return Promise|array The saved models.
	 */
	public function save( $return_promise = false ) {
		$callback = function () {
			$all = [];
			foreach ( $this->all( true ) as $model ) {
				$relationships = $model->get_relationships();
				foreach ( $this->upsert_args as $key => $value ) {
					$property = $this->get_property_name( $key );

					$method = 'set_' . $property;

					if ( ! method_exists( $model, $method ) && ! isset( $relationships[ $key ] ) ) {
						throw new RuntimeException( "Method {$method} does not exist on the model." );
					}

					$model->$method( $value );
				}

				$all[ $model->get_id() ] = $model->save();
			}

			return $all;
		};

		return $return_promise ? new Promise( $callback ) : $callback();
	}

	/**
	 * Creates a model.
	 *
	 * @since TBD
	 *
	 * @return ?Model The created model.
	 *
	 * @throws RuntimeException If a method does not exist on the model.
	 */
	public function create(): ?Model {
		$model_class = $this->get_model_class();
		$model = new $model_class();
		$relationships = $model->get_relationships();
		foreach ( $this->get_create_args() as $key => $value ) {
			$property = $this->get_property_name( $key );
			$method = 'set_' . $property;

			if ( ! method_exists( $model, $method ) && ! isset( $relationships[ $key ] ) ) {
				throw new RuntimeException( "Method {$method} does not exist on the model." );
			}

			if ( isset( $relationships[ $key ] ) ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						if ( ! is_int( $v ) ) {
							throw new RuntimeException( "Relationship {$key} must be an array of integers." );
						}

						$model->add_id_to_relationship( $key, $v );
					}
				} elseif ( is_int( $value ) ) {
					$model->add_id_to_relationship( $key, $value );
				} else {
					throw new RuntimeException( "Relationship {$key} must be an array of integers or an integer." );
				}

				continue;
			}

			$model->$method( $value );
		}

		$model->save();

		return $model;
	}

	/**
	 * Adds an update field alias.
	 *
	 * @since TBD
	 *
	 * @param string $alias The alias to add.
	 * @param string $field_name The field name to add.
	 */
	public function add_update_field_alias( $alias, $field_name ): void {
		$this->aliases[ $alias ] = $field_name;
	}

	/**
	 * Gets the update fields aliases.
	 *
	 * @since TBD
	 *
	 * @return array The update fields aliases.
	 */
	public function get_update_fields_aliases(): array {
		return $this->aliases;
	}

	/**
	 * Sets the update fields aliases.
	 *
	 * @since TBD
	 *
	 * @param array $update_fields_aliases The update fields aliases.
	 */
	public function set_update_fields_aliases( array $update_fields_aliases ): void {
		$this->aliases = $update_fields_aliases;
	}

	/**
	 * Gets the schema.
	 *
	 * @since TBD
	 *
	 * @return array The schema.
	 */
	public function get_schema(): array {
		return $this->schema;
	}

	/**
	 * Adds an entry to the schema.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to add.
	 * @param callable $callback The callback to add.
	 */
	public function add_schema_entry( $key, $callback ): void {
		$this->schema[ $key ] = $callback;
	}

	/**
	 * Gets the table interface.
	 *
	 * @since TBD
	 *
	 * @return Custom_Table_Abstract The table interface.
	 */
	protected function get_table_interface(): Custom_Table_Abstract {
		return tribe( $this->get_model_class() )->get_table_interface();
	}

	/**
	 * Gets the property name.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to get.
	 *
	 * @return string The property name.
	 */
	protected function get_property_name( $key ): string {
		return $this->aliases[ $key ] ?? $key;
	}
}
