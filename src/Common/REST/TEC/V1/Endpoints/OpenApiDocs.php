<?php
/**
 * OpenAPI docs endpoint.
 *
 * @since 6.9.0
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
use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface as Tag;
use TEC\Common\REST\TEC\V1\Tags\Common_Tag;
use TEC\Common\REST\TEC\V1\Collections\QueryArgumentCollection;
use TEC\Common\REST\TEC\V1\Documentation\OpenAPI_Schema;
use TEC\Common\REST\TEC\V1\Parameter_Types\Definition_Parameter;
use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Definition;
use InvalidArgumentException;

/**
 * OpenAPI docs endpoint.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Endpoints
 */
class OpenApiDocs extends Endpoint implements Readable_Endpoint {
	/**
	 * Returns the arguments for the read method.
	 *
	 * @since 6.9.0
	 *
	 * @return QueryArgumentCollection
	 */
	public function read_params(): QueryArgumentCollection {
		return new QueryArgumentCollection();
	}

	/**
	 * Returns whether the user can read the object.
	 *
	 * @since 6.9.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return bool
	 */
	public function can_read( WP_REST_Request $request ): bool {
		return true;
	}

	/**
	 * Returns the response for the read method.
	 *
	 * @since 6.9.0
	 *
	 * @param array $params The parameters to use for the request.
	 *
	 * @return WP_REST_Response
	 */
	public function read( array $params = [] ): WP_REST_Response {
		/** @var Documentation $documentation */
		$documentation = tribe( Documentation::class );

		/**
		 * Filters the documentation for the OpenAPI docs endpoint.
		 *
		 * @since 6.9.0
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
	 * @since 6.9.0
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
					'description' => __( 'The OpenAPI version.', 'tribe-common' ),
					'readonly'    => true,
				],
				'info'       => [
					'title'       => 'openapi_info',
					'type'        => 'object',
					'description' => __( 'The API info.', 'tribe-common' ),
					'readonly'    => true,
					'properties'  => [
						'title'       => [
							'type'        => 'string',
							'description' => __( 'The API title.', 'tribe-common' ),
							'readonly'    => true,
						],
						'version'     => [
							'type'        => 'string',
							'description' => __( 'The TEC REST API version.', 'tribe-common' ),
							'readonly'    => true,
						],
						'description' => [
							'type'        => 'string',
							'description' => __( 'The API description.', 'tribe-common' ),
							'readonly'    => true,
						],
						'contact'     => [
							'type'        => 'object',
							'description' => __( 'The API contact.', 'tribe-common' ),
							'readonly'    => true,
							'properties'  => [
								'name'  => [
									'type'        => 'string',
									'description' => __( 'The name of the contact.', 'tribe-common' ),
									'readonly'    => true,
								],
								'email' => [
									'type'        => 'string',
									'description' => __( 'The email of the contact.', 'tribe-common' ),
									'readonly'    => true,
								],
							],
						],
					],
				],
				'components' => [
					'title'       => 'openapi_components',
					'type'        => 'object',
					'description' => __( 'The OpenAPI components.', 'tribe-common' ),
					'readonly'    => true,
					'properties'  => [
						'schemas' => [
							'title'       => 'openapi_schemas',
							'type'        => 'object',
							'description' => __( 'The OpenAPI schemas.', 'tribe-common' ),
							'readonly'    => true,
							'properties'  => [
								'openapi_schema' => [
									'type'        => 'object',
									'title'       => 'openapi_schema',
									'description' => __( 'An object\'s schema.', 'tribe-common' ),
									'readonly'    => true,
								],
							],
						],
					],
				],
				'servers'    => [
					'title'       => 'openapi_servers',
					'type'        => 'array',
					'description' => __( 'The OpenAPI servers.', 'tribe-common' ),
					'readonly'    => true,
					'items'       => [
						'type'        => 'object',
						'description' => __( 'A server.', 'tribe-common' ),
						'readonly'    => true,
						'properties'  => [
							'url' => [
								'type'        => 'string',
								'description' => __( 'The server URL.', 'tribe-common' ),
								'format'      => 'uri',
								'readonly'    => true,
							],
						],
					],
				],
				'paths'      => [
					'title'       => 'OpenApiPaths',
					'type'        => 'object',
					'description' => __( 'The OpenAPI paths.', 'tribe-common' ),
					'readonly'    => true,
					'properties'  => [
						'OpenApiPath' => [
							'title'       => 'OpenApiPath',
							'type'        => 'object',
							'description' => __( 'A path\'s documentation.', 'tribe-common' ),
							'readonly'    => true,
						],
					],
				],
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function read_schema(): OpenAPI_Schema {
		$schema = new OpenAPI_Schema(
			fn() => __( 'Get the documentation for the TEC REST API', 'tribe-common' ),
			fn() => __( 'Returns the documentation for The Events Calendar REST API in Swagger consumable format.', 'tribe-common' ),
			$this->get_operation_id( 'read' ),
			$this->get_tags(),
			null,
			$this->read_params(),
		);

		$schema->add_response(
			200,
			fn() => __( 'Returns the documentation for The Events Calendar REST API in Swagger consumable format.', 'tribe-common' ),
			null,
			'application/json',
			new Definition_Parameter( tribe( OpenApi_Definition::class ) ),
		);

		return $schema;
	}

	/**
	 * Returns the base path of the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_base_path(): string {
		return '/docs';
	}

	/**
	 * Returns the tags for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return Tag[]
	 */
	public function get_tags(): array {
		return [ tribe( Common_Tag::class ) ];
	}

	/**
	 * Returns the operation ID for the endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @param string $operation The operation to get the operation ID for.
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException If the operation is invalid.
	 */
	public function get_operation_id( string $operation ): string {
		switch ( $operation ) {
			case 'read':
				return 'getOpenApiDocs';
		}

		throw new InvalidArgumentException( sprintf( 'Invalid operation: %s', $operation ) );
	}

	/**
	 * Returns whether the endpoint is experimental.
	 *
	 * This endpoint is not experimental.
	 *
	 * @since 6.9.0
	 *
	 * @return bool
	 */
	public function is_experimental(): bool {
		return false;
	}
}
