<?php
/**
 * OpenApi_Path_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Class OpenApi_Path_Definition.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class OpenApi_Path_Definition implements Definition_Interface {
	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'OpenApiPath';
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
				'get' => [
					'type'        => 'object',
					'description' => __( 'The GET method for the path.', 'the-events-calendar' ),
					'properties'  => [
						'description' => [
							'type'        => 'string',
							'description' => __( 'The description of the GET method.', 'the-events-calendar' ),
						],
						'parameters'  => [
							'type'        => 'array',
							'description' => __( 'The parameters of the GET method.', 'the-events-calendar' ),
							'items'       => [
								'type'        => 'object',
								'description' => __( 'A parameter.', 'the-events-calendar' ),
								'properties'  => [
									'name'     => [
										'type'        => 'string',
										'description' => __( 'The name of the parameter.', 'the-events-calendar' ),
									],
									'in'       => [
										'type'        => 'string',
										'description' => __( 'The location of the parameter.', 'the-events-calendar' ),
									],
									'required' => [
										'type'        => 'boolean',
										'description' => __( 'Whether the parameter is required.', 'the-events-calendar' ),
									],
									'schema'   => [
										'type'        => 'object',
										'description' => __( 'The schema of the parameter.', 'the-events-calendar' ),
									],
								],
							],
						],
						'responses'   => [
							'type'        => 'object',
							'description' => __( 'The responses of the GET method.', 'the-events-calendar' ),
							'properties'  => [
								200 => [
									'type'        => 'object',
									'description' => __( 'The response for the GET method.', 'the-events-calendar' ),
									'properties'  => [
										'content' => [
											'type'        => 'object',
											'description' => __( 'The content of the response.', 'the-events-calendar' ),
											'properties'  => [
												'application/json' => [
													'type'        => 'object',
													'description' => __( 'The JSON content of the response.', 'the-events-calendar' ),
													'properties'  => [
														'schema' => [
															'type'        => 'object',
															'description' => __( 'The schema of the content.', 'the-events-calendar' ),
															'properties'  => [
																'type' => [
																	'type'        => 'string',
																	'description' => __( 'The type of the content.', 'the-events-calendar' ),
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

		/**
		 * Filters the Swagger definition generated for an OpenAPI path in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array                   $documentation An associative PHP array in the format supported by Swagger.
		 * @param OpenApi_Path_Definition $this          The OpenApi_Path_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
