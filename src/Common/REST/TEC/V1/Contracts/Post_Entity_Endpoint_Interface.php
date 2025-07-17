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
}
