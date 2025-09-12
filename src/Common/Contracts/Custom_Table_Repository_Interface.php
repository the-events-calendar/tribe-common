<?php
/**
 * Interface Custom_Table_Repository_Interface
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\Contracts;

/**
 * Interface Custom_Table_Repository_Interface
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts
 */
interface Custom_Table_Repository_Interface extends Repository_Interface {
	/**
	 * Gets the model class.
	 *
	 * @since TBD
	 *
	 * @return class-string<Model> The model class.
	 */
	public function get_model_class(): string;

	/**
	 * Gets the schema.
	 *
	 * @since TBD
	 *
	 * @return array The schema.
	 */
	public function get_schema(): array;
}
