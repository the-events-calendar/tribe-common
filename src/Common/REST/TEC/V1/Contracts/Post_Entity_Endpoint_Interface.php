<?php
/**
 * Post Entity Endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_Post;
use Tribe__Repository__Interface;

/**
 * Post Entity Endpoint interface.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Post_Entity_Endpoint_Interface extends Endpoint_Interface {
	/**
	 * Returns the post type of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_post_type(): string;

	/**
	 * Returns whether the guest can read the object.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function guest_can_read(): bool;

	/**
	 * Returns the model class.
	 *
	 * @since TBD
	 *
	 * @return class-string
	 */
	public function get_model_class(): string;

	/**
	 * Validates the status parameter.
	 *
	 * @since TBD
	 *
	 * @param mixed $value The value to validate.
	 *
	 * @return bool Whether the value is valid.
	 */
	public function validate_status( $value ): bool;

	/**
	 * Formats a post into a post entity.
	 *
	 * @since TBD
	 *
	 * @param WP_Post $post The post to format.
	 *
	 * @return array
	 */
	public function get_formatted_entity( WP_Post $post ): array;

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return Tribe__Repository__Interface
	 */
	public function get_orm(): Tribe__Repository__Interface;
}
