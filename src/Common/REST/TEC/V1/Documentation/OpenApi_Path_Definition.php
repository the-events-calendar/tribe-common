<?php
/**
 * OpenApi_Path_Definition class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Abstracts\Definition;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Class OpenApi_Path_Definition.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class OpenApi_Path_Definition extends Definition {
	/**
	 * Returns the type of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'OpenApiPath';
	}

	/**
	 * Returns the priority of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 10000000;
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
				'get' => [
					'type'        => 'object',
					'description' => __( 'The GET method for the path.', 'tribe-common' ),
					'properties'  => [
						'description' => [
							'type'        => 'string',
							'description' => __( 'The description of the GET method.', 'tribe-common' ),
						],
						'parameters'  => [
							'type'        => 'array',
							'description' => __( 'The parameters of the GET method.', 'tribe-common' ),
							'items'       => [
								'type'        => 'object',
								'description' => __( 'A parameter.', 'tribe-common' ),
								'properties'  => [
									'name'     => [
										'type'        => 'string',
										'description' => __( 'The name of the parameter.', 'tribe-common' ),
									],
									'in'       => [
										'type'        => 'string',
										'description' => __( 'The location of the parameter.', 'tribe-common' ),
									],
									'required' => [
										'type'        => 'boolean',
										'description' => __( 'Whether the parameter is required.', 'tribe-common' ),
									],
									'schema'   => [
										'type'        => 'object',
										'description' => __( 'The schema of the parameter.', 'tribe-common' ),
									],
								],
							],
						],
						'responses'   => [
							'type'        => 'object',
							'description' => __( 'The responses of the GET method.', 'tribe-common' ),
							'properties'  => [
								200 => [
									'type'        => 'object',
									'description' => __( 'The response for the GET method.', 'tribe-common' ),
									'properties'  => [
										'content' => [
											'type'        => 'object',
											'description' => __( 'The content of the response.', 'tribe-common' ),
											'properties'  => [
												'application/json' => [
													'type'        => 'object',
													'description' => __( 'The JSON content of the response.', 'tribe-common' ),
													'properties'  => [
														'schema' => [
															'type'        => 'object',
															'description' => __( 'The schema of the content.', 'tribe-common' ),
															'properties'  => [
																'type' => [
																	'type'        => 'string',
																	'description' => __( 'The type of the content.', 'tribe-common' ),
																],
															],
														],
													],
												],
											],
										],
									],
								],
							],
						],
					],
				],
			],
		];

		$type = strtolower( $this->get_type() );

		/**
		 * Filters the Swagger definition generated for an OpenAPI path in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array                   $documentation An associative PHP array in the format supported by Swagger.
		 * @param OpenApi_Path_Definition $this          The OpenApi_Path_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( "tec_rest_swagger_{$type}_definition", $documentation, $this );
	}
}
