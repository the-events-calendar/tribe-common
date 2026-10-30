<?php
/**
 * Trait to handle the response for read entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use TEC\Common\Contracts\Repository_Interface;
use WP_Post;
use WP_REST_Posts_Controller;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Trait to handle the response for read entity requests.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Read_Entity_Response {
	/**
	 * Reads an existing entity.
	 *
	 * @since 6.9.0
	 * @since 6.13.3 Scoped the password-required bypass to this entity's own ID, so a linked
	 *            post with a different password is no longer disclosed.
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function read( array $params = [] ): WP_REST_Response {
		$id = $params['id'] ?? null;

		if ( ! $id ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be read.', 'tribe-common' ),
				],
				404
			);
		}

		if ( get_post_type( $id ) !== $this->get_post_type() ) {
			return new WP_REST_Response(
				[
					'error' => __( 'The entity could not be read.', 'tribe-common' ),
				],
				404
			);
		}

		$rest_controller = new WP_REST_Posts_Controller( $this->get_post_type() );

		$bypass = null;

		$request = $this->get_request();

		if ( post_password_required( $id ) && $rest_controller->can_access_password_content( get_post( $id ), $request ) ) {
			/*
			 * Re-check per post rather than bypassing globally: `get_formatted_entity()` below
			 * may serialize linked posts (e.g. an Event's Organizer or Venue) that carry their
			 * own, possibly different, password - access to those still has to be earned on
			 * their own terms, not inherited from the primary entity's.
			 */
			$bypass = static function ( $required, $post ) use ( $rest_controller, $request ) {
				if ( ! $required || ! $post instanceof WP_Post ) {
					return $required;
				}

				return $rest_controller->can_access_password_content( $post, $request ) ? false : $required;
			};
			add_filter( 'post_password_required', $bypass, 10, 2 );
		}

		try {
			$entity = $this->get_orm()->by_args(
				[
					'id'                => $id,
					'status'            => 'any',
					'tec_events_ignore' => true,
				]
			)->first();

			if ( ! $entity ) {
				return new WP_REST_Response(
					[
						'error' => __( 'The entity could not be read.', 'tribe-common' ),
					],
					404
				);
			}

			return new WP_REST_Response( $this->get_formatted_entity( $entity ), 200 );
		} finally {
			if ( $bypass ) {
				remove_filter( 'post_password_required', $bypass, 10 );
			}
		}
	}

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return Repository_Interface
	 */
	abstract public function get_orm(): Repository_Interface;

	/**
	 * Formats a model into a model entity.
	 *
	 * @since 6.10.0
	 *
	 * @param WP_Post $post The post to format.
	 *
	 * @return array
	 */
	abstract public function get_formatted_entity( WP_Post $post ): array;

	/**
	 * Returns the post type for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return string
	 */
	abstract public function get_post_type(): string;

	/**
	 * Returns the request.
	 *
	 * @since 6.10.0
	 *
	 * @return WP_REST_Request
	 */
	abstract public function get_request(): WP_REST_Request;
}
