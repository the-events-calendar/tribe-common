<?php
/**
 * Trait to handle the response for read archive requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use WP_REST_Request;
use WP_REST_Response;
use Tribe__Repository__Interface;

/**
 * Trait to handle the response for read archive requests.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Read_Archive_Response {
	/**
	 * Handles the read request for the endpoint.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function read( WP_REST_Request $request ): WP_REST_Response {
		$page     = absint( $request['page'] ?? 1 );
		$per_page = absint( $request['per_page'] ?? $this->get_default_posts_per_page() );

		/** @var Tribe__Repository__Interface $query */
		$query = $this->build_query( $request );

		// Set pagination.
		$query->page( $page )->per_page( $per_page );

		$data  = $this->format_post_entity_collection( $query->all() );
		$total = $query->found();

		// Return 404 if no events found and page > 1.
		if ( empty( $data ) && $page > 1 ) {
			return new WP_REST_Response(
				[
					'code'    => 'tec_rest_organizers_page_not_found',
					'message' => __( 'The requested page was not found.', 'the-events-calendar' ),
				],
				404
			);
		}

		/**
		 * Filters the data that will be returned for an entities archive request.
		 *
		 * @since TBD
		 *
		 * @param array           $data    The retrieved data.
		 * @param WP_REST_Request $request The original request.
		 */
		$data = apply_filters( 'tec_rest_' . $this->get_post_type() . '_archive', $data, $request );

		$total_pages = $per_page > 0 ? (int) ceil( $total / $per_page ) : 1;
		$current_url = $this->get_current_rest_url( $request );

		$response = new WP_REST_Response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $total_pages );

		if ( $page < $total_pages ) {
			$response->link_header( 'next', add_query_arg( 'page', $page + 1, $current_url ) );
		}

		if ( $page > 1 ) {
			$response->link_header( 'prev', add_query_arg( 'page', $page - 1, $current_url ) );
		}

		return $response;
	}
}
