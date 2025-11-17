<?php
/**
 * Post Entity Endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use WP_Post;
use TEC\Common\Contracts\Repository_Interface;

/**
 * Post Entity Endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Post_Entity_Endpoint_Interface extends Endpoint_Interface {
	/**
	 * Returns the post type of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_post_type(): string;

	/**
	 * Returns whether the guest can read the object.
	 *
	 * @since 6.9.0
	 *
	 * @return bool
	 */
	public function guest_can_read(): bool;

	/**
	 * Returns the model class.
	 *
	 * @since 6.9.0
	 *
	 * @return class-string
	 */
	public function get_model_class(): string;

	/**
	 * Validates the status parameter.
	 *
	 * @since 6.9.0
	 *
	 * @param mixed $value The value to validate.
	 *
	 * @return bool Whether the value is valid.
	 */
	public function validate_status( $value ): bool;

	/**
	 * Formats a post into a post entity.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_Post $post The post to format.
	 *
	 * @return array
	 */
	public function get_formatted_entity( WP_Post $post ): array;

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since 6.9.0
	 * @since 6.10.0 Updated to use the new Repository_Interface.
	 *
	 * @return Repository_Interface
	 */
	public function get_orm(): Repository_Interface;
}
