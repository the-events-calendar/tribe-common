<?php
/**
 * The model contract.
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts;
 */

declare( strict_types=1 );

namespace TEC\Common\Contracts;

use TEC\Common\Abstracts\Custom_Table_Abstract;

/**
 * The Shepherd model contract.
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts;
 */
interface Model {
	/**
	 * Gets the model's ID.
	 *
	 * @since TBD
	 *
	 * @return int The model's ID.
	 */
	public function get_id(): int;

	/**
	 * Sets the model's ID.
	 *
	 * @since TBD
	 *
	 * @param int $id The model's ID.
	 */
	public function set_id( int $id ): void;

	/**
	 * Saves the model.
	 *
	 * @since TBD
	 *
	 * @return int The id of the saved model.
	 */
	public function save(): int;

	/**
	 * Deletes the model.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the model was deleted.
	 */
	public function delete(): bool;

	/**
	 * Gets the table interface for the model.
	 *
	 * @since TBD
	 *
	 * @return Custom_Table_Abstract The table interface.
	 */
	public function get_table_interface(): Custom_Table_Abstract;

	/**
	 * Converts the model to an array.
	 *
	 * @since TBD
	 *
	 * @return array The model as an array.
	 */
	public function to_array(): array;

	/**
	 * Gets the relationships of the model.
	 *
	 * @since TBD
	 *
	 * @return array The relationships of the model.
	 */
	public function get_relationships(): array;

	/**
	 * Adds an ID to a relationship.
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the relationship.
	 * @param int    $id  The ID to add.
	 */
	public function add_id_to_relationship( string $key, int $id ): void;

	/**
	 * Removes an ID from a relationship.
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the relationship.
	 * @param int    $id  The ID to remove.
	 */
	public function remove_id_from_relationship( string $key, int $id ): void;

	/**
	 * Deletes the relationship data for a given key.
	 *
	 * @since TBD
	 *
	 * @param string $key The key of the relationship.
	 *
	 * @return void
	 */
	public function delete_relationship_data( string $key ): void;

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
	public static function from_array( array $data ): self;
}
