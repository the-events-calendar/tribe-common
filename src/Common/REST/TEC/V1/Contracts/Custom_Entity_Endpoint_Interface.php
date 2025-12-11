<?php
/**
 * Custom Entity Endpoint interface.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel as Model;
use TEC\Common\Contracts\Repository_Interface;

/**
 * Custom Entity Endpoint interface.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Custom_Entity_Endpoint_Interface extends Endpoint_Interface {
	/**
	 * Returns whether the guest can read the object.
	 *
	 * @since 6.10.0
	 *
	 * @return bool
	 */
	public function guest_can_read(): bool;

	/**
	 * Returns the minimum capability required to access the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return string
	 */
	public function get_minimum_capability(): string;

	/**
	 * Returns the model class.
	 *
	 * @since 6.10.0
	 *
	 * @return class-string
	 */
	public function get_model_class(): string;

	/**
	 * Formats a model into a model entity.
	 *
	 * @since 6.10.0
	 *
	 * @param Model $model The model to format.
	 *
	 * @return array
	 */
	public function get_formatted_entity( Model $model ): array;

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return Repository_Interface
	 */
	public function get_orm(): Repository_Interface;
}
