<?php
/**
 * The custom table repository.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\Abstracts;

use Generator;
use RuntimeException;
use TEC\Common\Contracts\Custom_Table_Repository_Interface as Repository_Interface;
use TEC\Common\StellarWP\DB\DB;
use TEC\Common\StellarWP\Schema\Tables\Contracts\Table as Table_Interface;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
use TEC\Common\StellarWP\SchemaModels\Relationships\ManyToManyWithPosts;
use TEC\Common\StellarWP\SchemaModels\Exceptions\BadMethodCallSchemaModelException;
use Tribe__Promise as Promise;
use WP_Post;

/**
 * The custom table repository.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\Abstracts
 */
abstract class Custom_Table_Repository implements Repository_Interface {
	/**
	 * The schema.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $schema = [];

	/**
	 * The update fields aliases.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $aliases = [];

	/**
	 * The default arguments.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $default_select_args = [];

	/**
	 * The default arguments used for create queries.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $default_create_args = [];

	/**
	 * The arguments used for select queries.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $select_args = [];

	/**
	 * The arguments used for upsert queries.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $upsert_args = [];

	/**
	 * The found rows.
	 *
	 * @since 6.10.0
	 *
	 * @var int
	 */
	private int $found_rows = 0;

	/**
	 * The page used for select queries.
	 *
	 * @since 6.10.0
	 *
	 * @var int
	 */
	private int $page = 1;

	/**
	 * The per page used for select queries.
	 *
	 * If not changed, we are selecting them all by default.
	 *
	 * @since 6.10.0
	 *
	 * @var int
	 */
	private int $per_page = 10000000000;

	/**
	 * The fields used for select queries.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $fields = [ '*' ];

	/**
	 * The schema callbacks.
	 *
	 * @since 6.10.0
	 *
	 * @var array
	 */
	private array $schema_callbacks = [];

	/**
	 * Constructor.
	 *
	 * @since 6.10.0
	 */
	public function __construct() {
		$this->order_by( $this->get_table_interface()::uid_column() );

		$this->add_columns_as_schema_entries();
		$this->add_relationships_as_schema_entries();

		$this->add_update_field_alias( 'id', $this->get_table_interface()::uid_column() );
	}

	/**
	 * Adds the columns as schema entries.
	 *
	 * @since 6.10.0
	 */
	private function add_columns_as_schema_entries(): void {
		$operators = $this->get_table_interface()::operators();

		$callback = function ( string $column, string $operator ) {
			return fn( $value ): array => [
				'column'   => $column,
				'value'    => $value,
				'operator' => $operator,
			];
		};

		foreach ( $this->get_table_interface()::get_columns()->get_names() as $column ) {
			foreach ( $operators as $operator_slug => $operator ) {
				if ( 'eq' === $operator_slug ) {
					$this->add_schema_entry(
						$column,
						$callback( $column, $operator )
					);
				}

				$this->add_schema_entry(
					"{$column}_{$operator_slug}",
					$callback( $column, $operator )
				);
			}
		}
	}

	/**
	 * Adds the relationships as schema entries.
	 *
	 * @since 6.10.0
	 */
	private function add_relationships_as_schema_entries(): void {
		$relationships = tribe( $this->get_model_class() )->getRelationshipCollection();

		foreach ( $relationships->getAll() as $key => $relationship ) {
			$definition = $relationship->getDefinition();
			if ( ! $definition instanceof ManyToManyWithPosts ) {
				continue;
			}

			$this->add_schema_entry(
				$key,
				function ( $value ) use ( $definition ) {
					$callback = function ( array $where ) use ( $definition, $value ) {
						$value        = (array) $value;
						$placeholders = '(' . implode( ',', array_fill( 0, count( $value ), '%d' ) ) . ')';
						$where[]      = DB::prepare(
							"%i IN (SELECT %i FROM %i WHERE %i IN {$placeholders})",
							$this->get_table_interface()::uid_column(),
							$definition->getThisEntityColumn(),
							$definition->getTableInterface()::table_name(),
							$definition->getOtherEntityColumn(),
							...$value
						);
						return $where;
					};

					$this->schema_callbacks[] = $callback;

					if ( ! has_filter( 'stellarwp_schema_custom_table_query_where', [ $this, 'apply_schema_callbacks' ] ) ) {
						add_filter( 'stellarwp_schema_custom_table_query_where', [ $this, 'apply_schema_callbacks' ] );
					}

					return [];
				}
			);

			$this->add_schema_entry(
				$key . '_in',
				function ( $value ) use ( $relationship ) {
					$callback = function ( array $where ) use ( $relationship, $value ) {
						$value        = (array) $value;
						$placeholders = '(' . implode( ',', array_fill( 0, count( $value ), '%d' ) ) . ')';
						$where[]      = DB::prepare(
							"%i IN (SELECT %i FROM %i WHERE %i IN {$placeholders})",
							$this->get_table_interface()::uid_column(),
							$relationship['columns']['this'],
							$relationship['through']::table_name(),
							$relationship['columns']['other'],
							...$value
						);
						return $where;
					};

					$this->schema_callbacks[] = $callback;

					if ( ! has_filter( 'stellarwp_schema_custom_table_query_where', [ $this, 'apply_schema_callbacks' ] ) ) {
						add_filter( 'stellarwp_schema_custom_table_query_where', [ $this, 'apply_schema_callbacks' ] );
					}

					return [];
				}
			);

			$this->add_schema_entry(
				$key . '_not_in',
				function ( $value ) use ( $relationship ) {
					$callback = function ( array $where ) use ( $relationship, $value ) {
						$value        = (array) $value;
						$placeholders = '(' . implode( ',', array_fill( 0, count( $value ), '%d' ) ) . ')';
						$where[]      = DB::prepare(
							"%i NOT IN (SELECT %i FROM %i WHERE %i IN {$placeholders})",
							$this->get_table_interface()::uid_column(),
							$relationship['columns']['this'],
							$relationship['through']::table_name(),
							$relationship['columns']['other'],
							...$value
						);
						return $where;
					};

					$this->schema_callbacks[] = $callback;

					if ( ! has_filter( 'stellarwp_schema_custom_table_query_where', [ $this, 'apply_schema_callbacks' ] ) ) {
						add_filter( 'stellarwp_schema_custom_table_query_where', [ $this, 'apply_schema_callbacks' ] );
					}

					return [];
				}
			);
		}
	}

	/**
	 * Applies the schema callbacks.
	 *
	 * @since 6.10.0
	 *
	 * @param array $where The where clause.
	 *
	 * @return array The where clause.
	 */
	public function apply_schema_callbacks( array $where ): array {
		foreach ( $this->schema_callbacks as $key => $callback ) {
			$where = $callback( $where );
			unset( $this->schema_callbacks[ $key ] );
		}
		return $where;
	}

	/**
	 * Gets the default arguments.
	 *
	 * @since 6.10.0
	 *
	 * @return array The default arguments used for select queries.
	 */
	public function get_default_args() {
		return $this->default_select_args;
	}

	/**
	 * Sets the default arguments.
	 *
	 * @since 6.10.0
	 *
	 * @param array $default_select_args The default arguments used for select queries.
	 */
	public function set_default_args( array $default_select_args ) {
		$this->default_select_args = $default_select_args;
	}

	/**
	 * Gets the select arguments.
	 *
	 * @since 6.10.0
	 *
	 * @return array The select arguments.
	 *
	 * @throws RuntimeException If the filter is not supported for custom table repositories.
	 */
	private function get_select_args(): array {
		$args = array_merge( $this->get_default_args(), $this->select_args );

		$new_args = [];

		$schema_args = [];

		foreach ( $args as $key => $value ) {
			if ( in_array( $key, [ 'order', 'orderby', 'term', 'offset' ], true ) ) {
				$new_args[ $key ] = $value;
				continue;
			}

			if ( ! isset( $this->get_schema()[ $key ] ) ) {
				throw new RuntimeException( "Filter {$key} is not supported for custom table repositories." );
			}

			$schema_args[] = $this->get_schema()[ $key ]( $value );
		}

		return array_merge( $new_args, array_filter( $schema_args ) );
	}

	/**
	 * Sets the create args.
	 *
	 * @since 6.10.0
	 *
	 * @param array $default_create_args The create args.
	 */
	public function set_create_args( array $default_create_args ) {
		$this->default_create_args = $default_create_args;
	}

	/**
	 * Gets the create args.
	 *
	 * @since 6.10.0
	 *
	 * @return array The create args.
	 */
	public function get_create_args() {
		return array_merge( $this->default_create_args, $this->upsert_args );
	}

	/**
	 * Sets the found rows.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $found_rows The found rows.
	 */
	public function set_found_rows( $found_rows ) {
		$this->found_rows = (int) $found_rows;
		return $this;
	}

	/**
	 * Gets a model by its primary key.
	 *
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @param bool $return_promise Whether to return a promise.
	 *
	 * @return Promise|array The deleted IDs.
	 */
	public function delete( bool $return_promise = false ) {
		$callback = function () {
			$deleted_ids = [];
			/** @var Model $model */
			foreach ( $this->all( true ) as $model ) {
				$deleted_ids[ $model->getPrimaryValue() ] = $model->delete();
			}

			return $deleted_ids;
		};

		return $return_promise ? new Promise( $callback ) : $callback();
	}

	/**
	 * Sets an argument used for upsert queries.
	 *
	 * @since 6.10.0
	 *
	 * @param string $key   The key to set.
	 * @param mixed  $value The value to set.
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
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @param array $args The arguments to set.
	 *
	 * @return self The repository instance.
	 */
	public function by_args( array $args ): self {
		$this->select_args = array_merge( $this->select_args, $args );
		$this->set_found_rows( 0 );
		return $this;
	}

	/**
	 * Sets an argument used for select queries.
	 *
	 * @since 6.10.0
	 *
	 * @param string $key   The key to set.
	 * @param mixed  $value The value to set.
	 *
	 * @return self The repository instance.
	 */
	public function by( $key, $value = null ): self {
		$this->select_args[ $key ] = $value;
		$this->set_found_rows( 0 );
		return $this;
	}

	/**
	 * Sets an argument used for select queries.
	 *
	 * @since 6.10.0
	 *
	 * @param string $key   The key to set.
	 * @param mixed  $value The value to set.
	 *
	 * @return self The repository instance.
	 */
	public function where( $key, $value = null ): self {
		return $this->by( $key, $value );
	}

	/**
	 * Sets the page used for select queries.
	 *
	 * @since 6.10.0
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
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @return int The found rows.
	 */
	public function found(): int {
		if ( ! $this->found_rows && ! empty( $this->select_args ) ) {
			$this->set_found_rows( $this->get_table_interface()::get_total_items( $this->get_select_args() ) );
		}

		return $this->found_rows;
	}

	/**
	 * Gets all the models.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $return_generator Whether to return a generator of models instead of an array of models.
	 * @param int  $batch_size       The batch size to set.
	 *
	 * @return Model[]|Generator<Model> The models.
	 */
	public function all( $return_generator = false, int $batch_size = 50 ) {
		if ( $return_generator ) {
			return $this->get_all_generator( $batch_size );
		}

		return iterator_to_array( $this->get_all_generator( $batch_size ) );
	}

	/**
	 * Gets the all generator.
	 *
	 * @since 6.10.0
	 *
	 * @param int $batch_size The batch size to set.
	 *
	 * @return Generator The all generator.
	 */
	private function get_all_generator( int $batch_size = 50 ): Generator {
		$i          = 1;
		$batch_size = min( $batch_size, $this->per_page );

		if ( $this->page > 1 ) {
			$this->offset( ( $this->page - 1 ) * $batch_size );
		}

		do {
			$results = $this->get_table_interface()::paginate( $this->get_select_args(), $batch_size, $i, $this->fields, '', '', [], ARRAY_A );
			if ( empty( $results ) ) {
				break;
			}

			if ( 1 === count( $this->fields ) && $this->fields !== [ '*' ] ) {
				$field   = array_values( $this->fields )[0];
				$results = array_map( fn( $result ) => $result[ $field ], $results );
			}

			yield from $results;

			++$i;
			$result_count = count( $results );
		} while ( $result_count === $batch_size && $i * $batch_size <= $this->per_page );

		$this->set_found_rows( $this->get_table_interface()::get_total_items( $this->get_select_args() ) );

		$this->select_args = [];
		$this->fields      = [ '*' ];
	}

	/**
	 * Sets the offset on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param int  $offset    The offset to set.
	 * @param bool $increment Whether to increment the offset.
	 *
	 * @return self The repository instance.
	 */
	public function offset( $offset, $increment = false ): self {
		return $this->by( 'offset', $offset );
	}

	/**
	 * Sets the order on the query.
	 *
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @param string $order_by The order by to set.
	 * @param string $order The order to set.
	 *
	 * @return self The repository instance.
	 */
	public function order_by( $order_by, $order = 'DESC' ): self {
		$this->order( $order );
		return $this->by( 'orderby', $order_by );
	}

	/**
	 * Sets the fields on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param string|string[] $fields The field or fields to set.
	 *
	 * @return self The repository instance.
	 */
	public function fields( $fields ): self {
		$this->fields = (array) $fields;
		return $this;
	}

	/**
	 * Sets the in on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @throws RuntimeException In is not supported for custom table repositories.
	 */
	public function in( $post_ids ): self {
		throw new RuntimeException( 'In is not supported for custom table repositories.' );
	}

	/**
	 * Sets the not in on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @throws RuntimeException Not in is not supported for custom table repositories.
	 */
	public function not_in( $post_ids ): self {
		throw new RuntimeException( 'Not in is not supported for custom table repositories.' );
	}

	/**
	 * Sets the parent on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param int $post_id The post id to set.
	 *
	 * @throws RuntimeException Parent is not supported for custom table repositories.
	 */
	public function parent( $post_id ): self {
		throw new RuntimeException( 'Parent is not supported for custom table repositories.' );
	}

	/**
	 * Sets the parent in on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @throws RuntimeException Parent in is not supported for custom table repositories.
	 */
	public function parent_in( $post_ids ): self {
		throw new RuntimeException( 'Parent in is not supported for custom table repositories.' );
	}

	/**
	 * Sets the parent not in on the query.
	 *
	 * @since 6.10.0
	 *
	 * @param array $post_ids The post ids to set.
	 *
	 * @throws RuntimeException Parent not in is not supported for custom table repositories.
	 */
	public function parent_not_in( $post_ids ): self {
		throw new RuntimeException( 'Parent not in is not supported for custom table repositories.' );
	}

	/**
	 * Sets the search on the query.
	 *
	 * @since 6.10.0
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
	 * @since 6.10.0
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
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @return ?Model The last model.
	 */
	public function last(): ?Model {
		$select_args = $this->get_select_args();
		$order       = $select_args['order'] ?? 'DESC';

		$this->order( 'DESC' === $order ? 'ASC' : 'DESC' );
		$model = $this->first();
		$this->order( $order );

		return $model;
	}

	/**
	 * Gets the nth model.
	 *
	 * @since 6.10.0
	 *
	 * @param int $n The nth model to get.
	 *
	 * @return ?Model The nth model.
	 */
	public function nth( $n ): ?Model {
		$select_args = $this->get_select_args();
		$offset      = $select_args['offset'] ?? 0;

		$this->offset( $n );
		$model = $this->first();
		$this->offset( $offset );

		return $model;
	}

	/**
	 * Gets the first n models.
	 *
	 * @since 6.10.0
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
	 * @since 6.10.0
	 *
	 * @param string $field The field to pluck from each result.
	 *
	 * @return array The plucked models.
	 *
	 * @throws BadMethodCallSchemaModelException If the method does not exist on the model.
	 */
	public function pluck( $field ): array {
		$method  = 'get_' . $field;
		$results = [];
		foreach ( $this->all( true ) as $model ) {
			$results[] = $model->$method();
		}

		return $results;
	}

	/**
	 * Filters the models.
	 *
	 * @since 6.10.0
	 *
	 * @param array  $args     The arguments to filter by.
	 * @param string $operator The operator to filter by.
	 *
	 * @return array The filtered models.
	 *
	 * @throws BadMethodCallSchemaModelException If the method does not exist on the model.
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
	 * @since 6.10.0
	 *
	 * @param array  $orderby       The orderby to set.
	 * @param string $order         The order to set.
	 * @param bool   $preserve_keys The preserve keys to set.
	 *
	 * @throws RuntimeException Sort is not supported for custom table repositories.
	 */
	public function sort( $orderby = [], $order = 'ASC', $preserve_keys = false ): array {
		throw new RuntimeException( 'Sort is not supported for custom table repositories.' );
	}

	/**
	 * Collects the models.
	 *
	 * @since 6.10.0
	 *
	 * @throws RuntimeException Collect is not supported for custom table repositories.
	 */
	public function collect(): array {
		throw new RuntimeException( 'Collect is not supported for custom table repositories.' );
	}

	/**
	 * Gets the ids of the models.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $return_generator Whether to return a generator of ids instead of an array of ids.
	 * @param int  $batch_size       The batch size to set.
	 *
	 * @return Generator<int>|int[] The ids of the models.
	 */
	public function get_ids( $return_generator = false, int $batch_size = 50 ) {
		$this->fields( [ $this->get_table_interface()::uid_column() ] );
		return $this->all( $return_generator, $batch_size );
	}

	/**
	 * Saves the models.
	 *
	 * @since 6.10.0
	 *
	 * @param bool $return_promise Whether to return a promise.
	 *
	 * @return Promise|array The saved models.
	 *
	 * @throws BadMethodCallSchemaModelException If the method does not exist on the model.
	 */
	public function save( $return_promise = false ) {
		$callback = function () {
			$all = [];
			foreach ( $this->all( true ) as $model ) {
				foreach ( $this->upsert_args as $key => $value ) {
					$property = $this->get_property_name( $key );

					$method = 'set_' . $property;

					$model->$method( $value );
				}

				$all[ $model->getPrimaryValue() ] = $model->save();
			}

			$this->select_args = [];
			$this->upsert_args = [];

			return $all;
		};

		return $return_promise ? new Promise( $callback ) : $callback();
	}

	/**
	 * Creates a model.
	 *
	 * @since 6.10.0
	 *
	 * @return Model The created model.
	 *
	 * @phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
	 *
	 * @throws RuntimeException                  If a relationship is not an array of integers or an integer.
	 * @throws BadMethodCallSchemaModelException If the method does not exist on the model.
	 */
	public function create(): Model {
		$model_class = $this->get_model_class();

		/** @var Model $model */
		$model         = new $model_class();
		$relationships = $model->getRelationshipCollection()->getAll();
		foreach ( $this->get_create_args() as $key => $value ) {
			$property = $this->get_property_name( $key );
			$method   = 'set_' . $property;

			if ( isset( $relationships[ $key ] ) ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $v ) {
						if ( ! is_int( $v ) ) {
							throw new RuntimeException( "Relationship {$key} must be an array of integers." );
						}

						$model->addToRelationship( $key, $v );
					}
				} elseif ( is_int( $value ) ) {
					$model->addToRelationship( $key, $value );
				} else {
					throw new RuntimeException( "Relationship {$key} must be an array of integers or an integer." );
				}

				continue;
			}

			$model->$method( $value );
		}

		$model->save();

		$this->upsert_args = [];

		return $model;
	}

	// phpcs:enable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber

	/**
	 * Adds an update field alias.
	 *
	 * @since 6.10.0
	 *
	 * @param string $alias The alias to add.
	 * @param string $field_name The field name to add.
	 */
	public function add_update_field_alias( $alias, $field_name ) {
		$this->aliases[ $alias ] = $field_name;
	}

	/**
	 * Gets the update fields aliases.
	 *
	 * @since 6.10.0
	 *
	 * @return array The update fields aliases.
	 */
	public function get_update_fields_aliases() {
		return $this->aliases;
	}

	/**
	 * Sets the update fields aliases.
	 *
	 * @since 6.10.0
	 *
	 * @param array $update_fields_aliases The update fields aliases.
	 */
	public function set_update_fields_aliases( array $update_fields_aliases ) {
		$this->aliases = $update_fields_aliases;
	}

	/**
	 * Gets the schema.
	 *
	 * @since 6.10.0
	 *
	 * @return array The schema.
	 */
	public function get_schema(): array {
		return $this->schema;
	}

	/**
	 * Adds an entry to the schema.
	 *
	 * @since 6.10.0
	 *
	 * @param string   $key      The key to add.
	 * @param callable $callback The callback to add.
	 */
	public function add_schema_entry( $key, $callback ) {
		$this->schema[ $key ] = $callback;
	}

	/**
	 * Gets the table interface.
	 *
	 * @since 6.10.0
	 *
	 * @return Table_Interface The table interface.
	 */
	private function get_table_interface(): Table_Interface {
		return tribe( $this->get_model_class() )->getTableInterface();
	}

	/**
	 * Gets the property name.
	 *
	 * @since 6.10.0
	 *
	 * @param string $key The key to get.
	 *
	 * @return string The property name.
	 */
	private function get_property_name( $key ): string {
		return $this->aliases[ $key ] ?? $key;
	}
}
