<?php
/**
 * The model abstract.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts;
 */

declare( strict_types=1 );

namespace TEC\Common\Abstracts;

use TEC\Common\Contracts\Model;
use TEC\Common\StellarWP\DB\DB;
use RuntimeException;
use InvalidArgumentException;

/**
 * The model abstract.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts;
 */
abstract class Model_Abstract implements Model {
	/**
	 * The model's ID.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	private int $id = 0;

	/**
	 * The model's data from the database.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $db_data = [];

	/**
	 * The relationships of the model.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $relationships = [];

	/**
	 * The relationship data of the model.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected array $relationship_data = [];

	/**
	 * The types of relationships.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public const RELATIONSHIP_TYPE_ONE_TO_ONE = 'one_to_one';

	/**
	 * The type of relationship that is a one to many.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const RELATIONSHIP_TYPE_ONE_TO_MANY = 'one_to_many';

	/**
	 * The type of relationship that is a many to one.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const RELATIONSHIP_TYPE_MANY_TO_ONE = 'many_to_one';

	/**
	 * The type of relationship that is a many to many.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const RELATIONSHIP_TYPE_MANY_TO_MANY = 'many_to_many';

	/**
	 * Gets the model's ID.
	 *
	 * @since TBD
	 *
	 * @return int The model's ID.
	 */
	public function get_id(): int {
		return $this->id;
	}

	/**
	 * Sets the model's ID.
	 *
	 * @since TBD
	 *
	 * @param int $id The model's ID.
	 */
	public function set_id( int $id ): void {
		$this->id = $id;
	}

	/**
	 * Saves the model.
	 *
	 * @since TBD
	 *
	 * @return int The id of the saved model.
	 *
	 * @throws RuntimeException If the model fails to save.
	 */
	public function save(): int {
		if ( ! $this->has_changes() ) {
			$this->save_relationship_data();
			return $this->get_id();
		}

		$table_interface = $this->get_table_interface();
		$result          = $table_interface::upsert( $this->to_array() );

		if ( ! $result ) {
			throw new RuntimeException( __( 'Failed to save the model.', 'tribe-common' ) );
		}

		$id = $this->get_id();

		if ( ! $id ) {
			$id = DB::last_insert_id();
			$this->set_id( $id );
		}

		$this->mark_saved();

		$this->save_relationship_data();

		return $id;
	}

	/**
	 * Deletes the model.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the model was deleted.
	 *
	 * @throws RuntimeException If the model ID required to delete the model is not set.
	 */
	public function delete(): bool {
		$uid = $this->get_id();

		if ( ! $uid ) {
			throw new RuntimeException( __( 'Model ID is required to delete the model.', 'tribe-common' ) );
		}

		$this->delete_all_relationship_data();

		return $this->get_table_interface()::delete( $uid );
	}

	/**
	 * Converts the model to an array.
	 *
	 * @since TBD
	 *
	 * @return array The model as an array.
	 *
	 * @throws RuntimeException If a method does not exist on the model.
	 */
	public function to_array(): array {
		$table_interface = $this->get_table_interface();
		$columns         = array_keys( $table_interface::get_columns() );

		$model = [];
		foreach ( $columns as $column ) {
			$method = 'get_' . $column;

			if ( ! is_callable( [ $this, $method ] ) ) {
				throw new RuntimeException( "Method {$method} does not exist on the model." );
			}

			$model[ $column ] = $this->$method();
		}

		$uid_column = $table_interface::uid_column();

		if ( empty( $model[ $uid_column ] ) ) {
			unset( $model[ $uid_column ] );
		}

		return $model;
	}

	/**
	 * Creates a model from an array.
	 *
	 * @since TBD
	 *
	 * @param array $data The model data.
	 * @return self The model.
	 *
	 * @throws InvalidArgumentException If a method does not exist on the model.
	 */
	public static function from_array( array $data ): self {
		$model = new static();

		foreach ( $data as $key => $value ) {
			$method = 'set_' . $key;
			if ( ! is_callable( [ $model, $method ] ) ) {
				throw new InvalidArgumentException( "Method {$method} does not exist on the model." );
			}

			$model->$method( $value );
		}

		$model->mark_saved();

		return $model;
	}

	/**
	 * Checks if the model has changes.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the model has changes.
	 *
	 * @throws RuntimeException If a method does not exist on the model.
	 */
	protected function has_changes(): bool {
		return $this->db_data !== $this->to_array();
	}

	/**
	 * Marks the model as saved.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function mark_saved(): void {
		$this->db_data = $this->to_array();
	}

	/**
	 * Adds an ID to a relationship.
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the relationship.
	 * @param int    $id  The ID to add.
	 *
	 * @throws InvalidArgumentException If the relationship does not exist.
	 */
	public function add_id_to_relationship( string $key, int $id ): void {
		if ( ! isset( $this->get_relationships()[ $key ] ) ) {
			throw new InvalidArgumentException( "Relationship {$key} does not exist." );
		}

		if ( ! isset( $this->relationship_data[ $key ] ) ) {
			$this->relationship_data[ $key ] = [];
		}

		if ( ! isset( $this->relationship_data[ $key ]['insert'] ) ) {
			$this->relationship_data[ $key ]['insert'] = [];
		}

		$this->relationship_data[ $key ]['insert'][] = $id;

		if ( ! empty( $this->relationship_data[ $key ]['delete'] ) ) {
			$this->relationship_data[ $key ]['delete'] = array_diff( $this->relationship_data[ $key ]['delete'], [ $id ] );
		}
	}

	/**
	 * Removes an ID from a relationship.
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the relationship.
	 * @param int    $id  The ID to remove.
	 *
	 * @throws InvalidArgumentException If the relationship does not exist.
	 */
	public function remove_id_from_relationship( string $key, int $id ): void {
		if ( ! isset( $this->get_relationships()[ $key ] ) ) {
			throw new InvalidArgumentException( "Relationship {$key} does not exist." );
		}

		if ( ! isset( $this->relationship_data[ $key ] ) ) {
			$this->relationship_data[ $key ] = [];
		}

		if ( ! isset( $this->relationship_data[ $key ]['delete'] ) ) {
			$this->relationship_data[ $key ]['delete'] = [];
		}

		$this->relationship_data[ $key ]['delete'][] = $id;

		if ( ! empty( $this->relationship_data[ $key ]['insert'] ) ) {
			$this->relationship_data[ $key ]['insert'] = array_diff( $this->relationship_data[ $key ]['insert'], [ $id ] );
		}
	}

	/**
	 * Gets the relationships of the model.
	 *
	 * @since TBD
	 *
	 * @return array The relationships of the model.
	 */
	public function get_relationships(): array {
		return $this->relationships;
	}

	/**
	 * Deletes the relationship data for a given key.
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the relationship.
	 *
	 * @throws InvalidArgumentException If the relationship does not exist.
	 */
	public function delete_relationship_data( string $key ): void {
		if ( ! isset( $this->get_relationships()[ $key ] ) ) {
			throw new InvalidArgumentException( "Relationship {$key} does not exist." );
		}

		if ( $this->get_relationships()['type'] === self::RELATIONSHIP_TYPE_MANY_TO_MANY ) {
			$this->get_relationships()['through']::delete( $this->get_id(), $this->get_relationships()[ $key ]['columns']['this'] );
		}
	}

	/**
	 * Saves the relationship data.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function save_relationship_data(): void {
		foreach ( $this->get_relationships() as $key => $relationship ) {
			if ( self::RELATIONSHIP_TYPE_MANY_TO_MANY !== $relationship['type'] ) {
				continue;
			}

			if ( ! empty( $this->relationship_data[ $key ]['insert'] ) ) {
				$insert_data = [];
				foreach ( $this->relationship_data[ $key ]['insert'] as $insert_id ) {
					$insert_data[] = [
						$this->get_relationships()[ $key ]['columns']['this']  => $this->get_id(),
						$this->get_relationships()[ $key ]['columns']['other'] => $insert_id,
					];
				}

				// First delete them to avoid duplicates.
				$relationship['through']::delete_many(
					$this->relationship_data[ $key ]['insert'],
					$this->get_relationships()[ $key ]['columns']['other'],
					DB::prepare( ' AND %i = %d', $this->get_relationships()[ $key ]['columns']['this'], $this->get_id() )
				);

				$relationship['through']::insert_many( $insert_data );
			}

			if ( ! empty( $this->relationship_data[ $key ]['delete'] ) ) {
				$relationship['through']::delete_many(
					$this->relationship_data[ $key ]['delete'],
					$this->get_relationships()[ $key ]['columns']['other'],
					DB::prepare( ' AND %i = %d', $this->get_relationships()[ $key ]['columns']['this'], $this->get_id() )
				);
			}
		}
	}

	/**
	 * Deletes all the relationship data.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function delete_all_relationship_data(): void {
		if ( empty( $this->get_relationships() ) ) {
			return;
		}

		foreach ( array_keys( $this->get_relationships() ) as $key ) {
			$this->delete_relationship_data( $key );
		}
	}

	/**
	 * Sets a relationship for the model.
	 *
	 * @since TBD
	 *
	 * @param string        $key                   The key of the relationship.
	 * @param string        $type                  The type of the relationship.
	 * @param class-string  $through               A table interface that provides the relationship.
	 * @param string        $relationship_entity   The entity of the relationship.
	 */
	protected function set_relationship( string $key, string $type, ?string $through = null, string $relationship_entity = 'post' ): void {
		$this->relationships[ $key ] = [
			'type'    => $type,
			'through' => $through,
			'entity'  => $relationship_entity,
		];
	}

	/**
	 * Sets the relationship columns for the model.
	 *
	 * @since TBD
	 *
	 * @param string $key                 The key of the relationship.
	 * @param string $this_entity_column  The column of the relationship.
	 * @param string $other_entity_column The other entity column.
	 *
	 * @throws InvalidArgumentException If the relationship does not exist.
	 */
	protected function set_relationship_columns( string $key, string $this_entity_column, string $other_entity_column ): void {
		if ( ! isset( $this->get_relationships()[ $key ] ) ) {
			throw new InvalidArgumentException( "Relationship {$key} does not exist." );
		}

		$this->get_relationships()[ $key ]['columns'] = [
			'this'  => $this_entity_column,
			'other' => $other_entity_column,
		];
	}
}
