<?php
/**
 * Trait to handle the response for read custom archive requests.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Traits;

use TEC\Common\Contracts\Repository_Interface;
use TEC\Common\StellarWP\SchemaModels\Contracts\SchemaModel;
use WP_REST_Response;

/**
 * Trait to handle the response for read custom archive requests.
 *
 * @since 6.10.0
 *
 * @package TEC\Common\REST\TEC\V1\Traits
 */
trait Read_Custom_Archive_Response {
	/**
	 * Handles the read request for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return WP_REST_Response The response object.
	 */
	public function read( array $params = [] ): WP_REST_Response {
		$page     = absint( $params['page'] ?? 1 );
		$per_page = absint( $params['per_page'] ?? $this->get_default_posts_per_page() );

		unset( $params['page'], $params['per_page'] );

		$query = $this->build_query( $params );

		$query->page( $page )->per_page( $per_page );

		$data  = $this->format_entity_collection( $query->all() );
		$total = $query->found();

		// Return 404 if no entities found and page > 1.
		if ( empty( $data ) && $page > 1 ) {
			return new WP_REST_Response(
				[
					'code'    => 'tec_rest_organizers_page_not_found',
					'message' => __( 'The requested page was not found.', 'tribe-common' ),
				],
				404
			);
		}

		/**
		 * Filters the data that will be returned for an entities archive request.
		 *
		 * @since 6.10.0
		 *
		 * @param array $data   The retrieved data.
		 * @param array $params The sanitized parameters to use for the request.
		 */
		$data = apply_filters( "tec_rest_{$this->get_model_class()}_archive", $data, $params );

		$total_pages = $per_page > 0 ? (int) ceil( $total / $per_page ) : 1;
		$current_url = $this->get_current_rest_url();

		$response = new WP_REST_Response( $data );

		$response->header( 'X-WP-Total', $total );
		$response->header( 'X-WP-TotalPages', $total_pages );

		if ( $page < $total_pages ) {
			$response->link_header( 'next', add_query_arg( 'page', $page + 1, $current_url ) );
		}

		if ( $page > 1 ) {
			$response->link_header( 'prev', add_query_arg( 'page', $page - 1, $current_url ) );
		}

		return $response;
	}

	/**
	 * Builds the entities query using the ORM.
	 *
	 * @since 6.10.0
	 *
	 * @param array $params The sanitized parameters to use for the request.
	 *
	 * @return Repository_Interface The entities query.
	 */
	protected function build_query( array $params = [] ): Repository_Interface {
		$query = $this->get_orm();

		$search  = $params['search'] ?? '';
		$orderby = $params['orderby'] ?? '';
		$order   = $params['order'] ?? '';

		unset(
			$params['orderby'],
			$params['order'],
			$params['search'],
		);

		if ( $search ) {
			$query->search( $search );
		}

		if ( $orderby && $order ) {
			$query->order_by( $orderby, $order );
		}

		$query->by_args( $params );

		/**
		 * Filters the query in the TEC REST API.
		 *
		 * @since 6.10.0
		 *
		 * @param Repository_Interface $query   The query.
		 * @param array                $params  The sanitized parameters to use for the request.
		 */
		return apply_filters( "tec_rest_{$this->get_model_class()}_query", $query, $params );
	}

	/**
	 * Returns the default number of posts per page.
	 *
	 * @since 6.10.0
	 *
	 * @return int
	 */
	abstract public function get_default_posts_per_page(): int;

	/**
	 * Returns the current REST URL.
	 *
	 * @since 6.10.0
	 *
	 * @return string
	 */
	abstract public function get_current_rest_url(): string;

	/**
	 * Formats the entity collection.
	 *
	 * @since 6.10.0
	 *
	 * @param array $entities The entities to format.
	 *
	 * @return array
	 */
	abstract public function format_entity_collection( array $entities ): array;

	/**
	 * Returns the ORM for the endpoint.
	 *
	 * @since 6.10.0
	 *
	 * @return Repository_Interface
	 */
	abstract public function get_orm(): Repository_Interface;

	/**
	 * Returns the model class.
	 *
	 * @since 6.10.0
	 *
	 * @return class-string<SchemaModel>
	 */
	abstract public function get_model_class(): string;
}
