<?php
/**
 * OpenAPI docs endpoint.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Endpoints
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Endpoints;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

use TEC\Common\REST\TEC\V1\Abstracts\Endpoint;
use TEC\Common\REST\TEC\V1\Contracts\Readable_Endpoint;
use TEC\Common\REST\TEC\V1\Documentation;
use WP_REST_Request;
use WP_REST_Response;

/**
 * OpenAPI docs endpoint.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Endpoints
 */
class OpenApiDocs extends Endpoint implements Readable_Endpoint {
	/**
	 * Returns the arguments for the read method.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function read_args(): array {
		return [];
	}

	/**
	 * Returns the response for the read method.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	public function read( WP_REST_Request $request ): WP_REST_Response {
		/** @var Documentation $documentation */
		$documentation = tribe( Documentation::class );
		/**
		 * Filters the documentation for the OpenAPI docs endpoint.
		 *
		 * @since TBD
		 *
		 * @param array $documentation The documentation.
		 *
		 * @return array
		 */
		return new WP_REST_Response( (array) apply_filters( 'tec_rest_v1_openapi_docs', $documentation->get() ) );
	}

	/**
	 * Returns the schema for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_schema(): array {
		return [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'OpenApiDocumentation',
			'type'       => 'object',
			'properties' => [
				'openapi'    => [
					'type'        => 'string',
					'description' => __( 'The OpenAPI version.', 'the-events-calendar' ),
					'readonly'    => true,
				],
				'info'       => [
					'title'       => 'openapi_info',
					'type'        => 'object',
					'description' => __( 'The API info.', 'the-events-calendar' ),
					'readonly'    => true,
					'properties'  => [
						'title'       => [
							'type'        => 'string',
							'description' => __( 'The API title.', 'the-events-calendar' ),
							'readonly'    => true,
						],
						'version'     => [
							'type'        => 'string',
							'description' => __( 'The TEC REST API version.', 'the-events-calendar' ),
							'readonly'    => true,
						],
						'description' => [
							'type'        => 'string',
							'description' => __( 'The API description.', 'the-events-calendar' ),
							'readonly'    => true,
						],
					],
				],
				'components' => [
					'title'       => 'openapi_components',
					'type'        => 'object',
					'description' => __( 'The OpenAPI components.', 'the-events-calendar' ),
					'readonly'    => true,
					'properties'  => [
						'schemas' => [
							'title'       => 'openapi_schemas',
							'type'        => 'object',
							'description' => __( 'The OpenAPI schemas.', 'the-events-calendar' ),
							'readonly'    => true,
							'properties'  => [
								'openapi_schema' => [
									'type'        => 'object',
									'title'       => 'openapi_schema',
									'description' => __( 'An object\'s schema.', 'the-events-calendar' ),
									'readonly'    => true,
								],
							],
						],
					],
				],
				'servers'    => [
					'title'       => 'openapi_servers',
					'type'        => 'array',
					'description' => __( 'The OpenAPI servers.', 'the-events-calendar' ),
					'readonly'    => true,
					'items'       => [
						'type'        => 'object',
						'description' => __( 'A server.', 'the-events-calendar' ),
						'readonly'    => true,
						'properties'  => [
							'url' => [
								'type'        => 'string',
								'description' => __( 'The server URL.', 'the-events-calendar' ),
								'format'      => 'uri',
								'readonly'    => true,
							],
						],
					],
				],
				'paths'      => [
					'title'       => 'OpenApiPaths',
					'type'        => 'object',
					'description' => __( 'The OpenAPI paths.', 'the-events-calendar' ),
					'readonly'    => true,
					'properties'  => [
						'OpenApiPath' => [
							'title'       => 'OpenApiPath',
							'type'        => 'object',
							'description' => __( 'A path\'s documentation.', 'the-events-calendar' ),
							'readonly'    => true,
						],
					],
				],
			],
		];
	}

	/**
	 * Returns the documentation for the endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_documentation(): array {
		return [
			'get' => [
				'responses' => [
					'200' => [
						'description' => __( 'Returns the documentation for The Events Calendar REST API in Swagger consumable format.', 'the-events-calendar' ),
						'content'     => [
							'application/json' => [
								'schema' => [
									'$ref' => '#/components/schemas/OpenApiDocumentation',
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Returns the path of the endpoint.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_path(): string {
		return '/docs';
	}
}
