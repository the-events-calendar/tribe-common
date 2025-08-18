<?php
/**
 * Swagger documentation endpoint for the TEC REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface;

/**
 * Swagger documentation endpoint for the Events REST API.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1
 */
class Documentation {
	/**
	 * The OpenAPI version.
	 *
	 * @since 6.9.0
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md
	 *
	 * @var string
	 */
	protected const SWAGGER_VERSION = '3.0.4';

	/**
	 * The TEC REST API version.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	protected const TEC_REST_API_VERSION = '1.0.0';

	/**
	 * The registered endpoints.
	 *
	 * @since 6.9.0
	 *
	 * @var Endpoint_Interface[]
	 */
	protected $endpoints = [];

	/**
	 * The registered definitions.
	 *
	 * @since 6.9.0
	 *
	 * @var Definition_Interface[]
	 */
	protected $definitions = [];

	/**
	 * The registered tags.
	 *
	 * @since 6.9.0
	 *
	 * @var Tag_Interface[]
	 */
	protected $tags = [];

	/**
	 * Returns an array in the format used by Swagger.
	 *
	 * @since 6.9.0
	 *
	 * @return array An array description of a Swagger supported component.
	 */
	public function get(): array {
		$documentation = [
			'openapi'    => self::SWAGGER_VERSION,
			'info'       => $this->get_api_info(),
			'tags'       => $this->get_tags(),
			'components' => [
				'schemas'         => $this->get_definitions(),
				'securitySchemes' => $this->get_security_schemes(),
			],
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
	 * @since 6.9.0
	 *
	 * @return array
	 */
	protected function get_api_info(): array {
		return [
			'title'       => __( 'The Events Calendar REST API', 'tribe-common' ),
			'version'     => self::TEC_REST_API_VERSION,
			'description' => __( 'The Events Calendar REST API allows accessing upcoming events information easily and conveniently.', 'tribe-common' ),
			'contact'     => [
				'name'  => __( 'The Events Calendar', 'tribe-common' ),
				'email' => 'support@theeventscalendar.com',
			],
		];
	}

	/**
	 * Registers an endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @param Endpoint_Interface $endpoint The endpoint to register.
	 */
	public function register_endpoint( Endpoint_Interface $endpoint ): void {
		$this->endpoints[] = $endpoint;
	}

	/**
	 * Registers a tag.
	 *
	 * @since 6.9.0
	 *
	 * @param Tag_Interface $tag The tag to register.
	 */
	public function register_tag( Tag_Interface $tag ): void {
		$this->tags[] = $tag;
	}

	/**
	 * Registers a definition.
	 *
	 * @since 6.9.0
	 *
	 * @param Definition_Interface $definition The definition to register.
	 */
	public function register_definition( Definition_Interface $definition ): void {
		$this->definitions[] = $definition;
	}

	/**
	 * Returns the tags.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	protected function get_tags(): array {
		usort(
			$this->tags,
			function ( Tag_Interface $a, Tag_Interface $b ) {
				return $a->get_priority() - $b->get_priority();
			}
		);

		return $this->tags;
	}

	/**
	 * Returns the paths documentation for each endpoint.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	protected function get_paths(): array {
		$paths = [];
		foreach ( $this->endpoints as $endpoint ) {
			/** @var Endpoint_Interface $endpoint */
			$paths[ $endpoint->get_open_api_path() ] = $endpoint->get_documentation();
		}

		return $paths;
	}

	/**
	 * Returns the definitions documentation for each definition.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	protected function get_definitions(): array {
		$definitions = [];

		// Sort the definitions by priority.
		usort(
			$this->definitions,
			function ( Definition_Interface $a, Definition_Interface $b ) {
				return $a->get_priority() - $b->get_priority();
			}
		);

		/** @var Definition_Interface $definition */
		foreach ( $this->definitions as $definition ) {
			$definitions[ $definition->get_type() ] = $definition->get_documentation();
		}

		return $definitions;
	}

	/**
	 * Returns the security schemes documentation for each security scheme.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	protected function get_security_schemes(): array {
		/**
		 * Filters the security schemes documentation for the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array $security_schemes The security schemes documentation.
		 *
		 * @return array
		 */
		return (array) apply_filters(
			'tec_rest_swagger_security_schemes',
			[
				'BasicAuth' => [
					'type'   => 'http',
					'scheme' => 'basic',
				],
			]
		);
	}
}
