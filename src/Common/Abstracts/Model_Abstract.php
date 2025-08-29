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

		return $id;
	}

	/**
	 * Deletes the model.
	 *
	 * @since TBD
	 *
	 * @return void
	 *
	 * @throws RuntimeException If the model ID required to delete the model is not set.
	 * @throws RuntimeException If the model fails to delete.
	 */
	public function delete(): void {
		$uid = $this->get_id();

		if ( ! $uid ) {
			throw new RuntimeException( __( 'Model ID is required to delete the model.', 'tribe-common' ) );
		}

		$result = $this->get_table_interface()::delete( $uid );

		if ( ! $result ) {
			throw new RuntimeException( __( 'Failed to delete the model.', 'tribe-common' ) );
		}
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

			if ( ! method_exists( $this, $method ) ) {
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
}
