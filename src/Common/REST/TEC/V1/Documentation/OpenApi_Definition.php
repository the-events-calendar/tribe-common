<?php
/**
 * OpenApi_Definition class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Abstracts\Definition;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Class OpenApi_Definition.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class OpenApi_Definition extends Definition {
	/**
	 * Returns the type of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'OpenApiDocumentation';
	}

	/**
	 * Returns the priority of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 1000000;
	}

	/**
	 * Returns the documentation for the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_documentation(): array {
		$documentation = [
			'type'       => 'object',
			'properties' => [
				'openapi'    => [
					'type'        => 'string',
					'description' => __( 'The OpenAPI version.', 'tribe-common' ),
				],
				'info'       => [
					'type'        => 'object',
					'description' => __( 'The API info.', 'tribe-common' ),
					'properties'  => [
						'title'       => [
							'type'        => 'string',
							'description' => __( 'The API title.', 'tribe-common' ),
						],
						'version'     => [
							'type'        => 'string',
							'description' => __( 'The TEC REST API version.', 'tribe-common' ),
						],
						'description' => [
							'type'        => 'string',
							'description' => __( 'The API description.', 'tribe-common' ),
						],
						'contact'     => [
							'type'        => 'object',
							'description' => __( 'The API contact.', 'tribe-common' ),
							'properties'  => [
								'name'  => [
									'type'        => 'string',
									'description' => __( 'The name of the contact.', 'tribe-common' ),
								],
								'email' => [
									'type'        => 'string',
									'description' => __( 'The email of the contact.', 'tribe-common' ),
									'format'      => 'email',
								],
							],
						],
					],
				],
				'components' => [
					'type'        => 'object',
					'description' => __( 'The OpenAPI components.', 'tribe-common' ),
					'properties'  => [
						'schemas' => [
							'type'        => 'object',
							'description' => __( 'The OpenAPI schemas.', 'tribe-common' ),
						],
					],
				],
				'servers'    => [
					'type'        => 'array',
					'description' => __( 'The OpenAPI servers.', 'tribe-common' ),
					'items'       => [
						'type'        => 'object',
						'description' => __( 'A server.', 'tribe-common' ),
						'properties'  => [
							'url' => [
								'type'        => 'string',
								'description' => __( 'The server URL.', 'tribe-common' ),
								'format'      => 'uri',
							],
						],
					],
				],
				'paths'      => [
					'type'        => 'object',
					'description' => __( 'The OpenAPI paths.', 'tribe-common' ),
					'properties'  => [
						'OpenApiPath' => [
							'$ref' => '#/components/schemas/OpenApiPath',
						],
					],
				],
			],
		];

		$type = strtolower( $this->get_type() );

		/**
		 * Filters the Swagger definition generated for the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array              $documentation An associative PHP array in the format supported by Swagger.
		 * @param OpenApi_Definition $this          The OpenApi_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( "tec_rest_swagger_{$type}_definition", $documentation, $this );
	}
}
