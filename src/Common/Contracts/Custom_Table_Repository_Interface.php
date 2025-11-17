<?php
/**
 * Interface Custom_Table_Repository_Interface
 *
 * @since 6.10.0
 *
 * @package TEC\Common\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\Contracts;

use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;

/**
 * Interface Custom_Table_Repository_Interface
 *
 * @since 6.10.0
 *
 * @package TEC\Common\Contracts
 */
interface Custom_Table_Repository_Interface extends Repository_Interface {
	/**
	 * Gets the model class.
	 *
	 * @since 6.10.0
	 *
	 * @return class-string<Model> The model class.
	 */
	public function get_model_class(): string;

	/**
	 * Gets the schema.
	 *
	 * @since 6.10.0
	 *
	 * @return array The schema.
	 */
	public function get_schema(): array;
}
