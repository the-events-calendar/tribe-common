<?php
/**
 * Custom Entity Endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use TEC\Common\Contracts\Model;
use TEC\Common\Contracts\Repository_Interface;

/**
 * Custom Entity Endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Custom_Entity_Endpoint_Interface extends Endpoint_Interface {
	/**
	 * Returns whether the guest can read the object.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function guest_can_read(): bool;

	/**
	 * Returns the minimum capability required to access the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */

	public function get_minimum_capability(): string;

	/**
	 * Returns the model class.
	 *
	 * @since TBD
	 *
	 * @return class-string
	 */
	public function get_model_class(): string;

	/**
	 * Formats a model into a model entity.
	 *
	 * @since TBD
	 *
	 * @param Model $model The model to format.
	 *
	 * @return array
	 */
	public function get_formatted_entity( Model $model ): array;

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return Repository_Interface
	 */
	public function get_orm(): Repository_Interface;
}
