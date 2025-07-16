<?php
/**
 * OpenApi_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Class OpenApi_Definition.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class OpenApi_Definition implements Definition_Interface {
	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'openapi_documentation';
	}

	/**
	 * Returns the documentation for the definition.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_documentation(): array {
		$documentation = [
			'type'       => 'object',
			'properties' => [
				'openapi'    => [
					'type'        => 'string',
					'description' => __( 'The OpenAPI version.', 'the-events-calendar' ),
				],
				'info'       => [
					'type'        => 'object',
					'description' => __( 'The API info.', 'the-events-calendar' ),
					'properties'  => [
						'title'       => [
							'type'        => 'string',
							'description' => __( 'The API title.', 'the-events-calendar' ),
						],
						'version'     => [
							'type'        => 'string',
							'description' => __( 'The TEC REST API version.', 'the-events-calendar' ),
						],
						'description' => [
							'type'        => 'string',
							'description' => __( 'The API description.', 'the-events-calendar' ),
						],
					],
				],
				'components' => [
					'type'        => 'object',
					'description' => __( 'The OpenAPI components.', 'the-events-calendar' ),
					'properties'  => [
						'schemas' => [
							'type'        => 'object',
							'description' => __( 'The OpenAPI schemas.', 'the-events-calendar' ),
						],
					],
				],
				'servers'    => [
					'type'        => 'array',
					'description' => __( 'The OpenAPI servers.', 'the-events-calendar' ),
					'items'       => [
						'type'        => 'object',
						'description' => __( 'A server.', 'the-events-calendar' ),
						'properties'  => [
							'url' => [
								'type'        => 'string',
								'description' => __( 'The server URL.', 'the-events-calendar' ),
								'format'      => 'uri',
							],
						],
					],
				],
				'paths'      => [
					'type'        => 'object',
					'description' => __( 'The OpenAPI paths.', 'the-events-calendar' ),
					'properties'  => [
						'openapi_path' => [
							'$ref' => '#/components/schemas/openapi_path',
						],
					],
				],
			],
		];

		/**
		 * Filters the Swagger definition generated for the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array              $documentation An associative PHP array in the format supported by Swagger.
		 * @param OpenApi_Definition $this          The OpenApi_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
