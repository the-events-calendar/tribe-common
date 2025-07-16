<?php
/**
 * Swagger documentation endpoint for the TEC REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Swagger documentation endpoint for the Events REST API.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1
 */
class Documentation {
	/**
	 * The OpenAPI version.
	 *
	 * @since TBD
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md
	 *
	 * @var string
	 */
	protected const SWAGGER_VERSION = '3.0.4';

	/**
	 * The TEC REST API version.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected const TEC_REST_API_VERSION = '1.0.0';

	/**
	 * The registered endpoints.
	 *
	 * @since TBD
	 *
	 * @var Endpoint_Interface[]
	 */
	protected $endpoints = [];

	/**
	 * The registered definitions.
	 *
	 * @since TBD
	 *
	 * @var Definition_Interface[]
	 */
	protected $definitions = [];

	/**
	 * Returns an array in the format used by Swagger.
	 *
	 * @since TBD
	 *
	 * @return array An array description of a Swagger supported component.
	 */
	public function get(): array {
		$documentation = [
			'openapi'    => self::SWAGGER_VERSION,
			'info'       => $this->get_api_info(),
			'components' => [ 'schemas' => $this->get_definitions() ],
			'servers'    => [
				[
					'url' => rest_url( Controller::get_versioned_namespace() ),
				],
			],
			'paths'      => $this->get_paths(),
		];

		/**
		 * Filters the Swagger documentation generated for the TEC REST API.
		 *
		 * @param array         $documentation An associative PHP array in the format supported by Swagger.
		 * @param Documentation $this          This instance of the documentation.
		 *
		 * @link https://swagger.io/specification/
		 */
		return apply_filters( 'tec_rest_swagger_documentation', $documentation, $this );
	}

	/**
	 * Returns the API info.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_api_info(): array {
		return [
			'title'       => __( 'The Events Calendar REST API', 'the-events-calendar' ),
			'version'     => self::TEC_REST_API_VERSION,
			'description' => __( 'The Events Calendar REST API allows accessing upcoming events information easily and conveniently.', 'the-events-calendar' ),
		];
	}

	/**
	 * Registers an endpoint.
	 *
	 * @since TBD
	 *
	 * @param Endpoint_Interface $endpoint The endpoint to register.
	 */
	public function register_endpoint( Endpoint_Interface $endpoint ): void {
		$this->endpoints[] = $endpoint;
	}

	/**
	 * Registers a definition.
	 *
	 * @since TBD
	 *
	 * @param Definition_Interface $definition The definition to register.
	 */
	public function register_definition( Definition_Interface $definition ): void {
		$this->definitions[] = $definition;
	}

	/**
	 * Returns the paths documentation for each endpoint.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_paths(): array {
		$paths = [];
		foreach ( $this->endpoints as $endpoint ) {
			/** @var Endpoint_Interface $endpoint */
			$paths[ $endpoint->get_path() ] = $endpoint->get_documentation();
		}

		return $paths;
	}

	/**
	 * Returns the definitions documentation for each definition.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_definitions(): array {
		$definitions = [];
		/** @var Definition_Interface $definition */
		foreach ( $this->definitions as $definition ) {
			$definitions[ $definition->get_type() ] = $definition->get_documentation();
		}

		return $definitions;
	}
}
