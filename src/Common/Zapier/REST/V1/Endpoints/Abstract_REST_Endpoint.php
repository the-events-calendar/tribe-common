<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Endpoints
 */

namespace TEC\Common\Zapier\REST\V1\Endpoints;

use TEC\Common\Zapier\Api;
use Tribe__REST__Endpoints__READ_Endpoint_Interface as READ_Endpoint_Interface;
use Tribe__Documentation__Swagger__Provider_Interface as Swagger_Provider_Interface;
use Tribe__Utils__Array as Arr;

/**
 * Abstract REST Endpoint Zapier
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Endpoints
 */
abstract class Abstract_REST_Endpoint implements READ_Endpoint_Interface, Swagger_Provider_Interface {

	/**
	 * The REST API endpoint path.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $namespace = 'tribe';

	/**
	 * The REST API endpoint path.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * An instance of the Zapier API handler.
	 *
	 * @since TBD
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since TBD
	 *
	 * @param Api $api An instance of the Zapier API handler.
	 */
	public function __construct( Api $api ) {
		$this->api      = $api;
	}

	/**
	 * Returns the namespace of REST APIs.
	 *
	 * @return string
	 */
	public function get_namespace() {
		return $this->namespace;
	}

	/**
	 * Returns the string indicating the REST API version.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_version() {
		return 'v1';
	}

	/**
	 * Returns the events REST API namespace string that should be used to register a route.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_events_route_namespace() {
		return $this->get_namespace() . '/zapier/' . $this->get_version();
	}


	/**
	 * Gets the Endpoint path for this route.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_endpoint_path() {
		return $this->path;
	}

	/**
	 * Get the REST API route URL.
	 *
	 * @since TBD
	 *
	 * @return string The REST API route URL.
	 */
	public function get_route_url() {
		$namespace = $this->get_events_route_namespace();

		return rest_url( '/' . $namespace . $this->get_endpoint_path(), 'https' );
	}

	/**
	 * Sanitize a request argument based on details registered to the route.
	 *
	 * @since TBD
	 *
	 * @param mixed $value Value of the 'filter' argument.
	 *
	 * @return string|array<string|string> A text field sanitized string or array.
	 */
	public function sanitize_callback( $value ) {
		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		}

		return sanitize_text_field( $value );
	}

	/**
	 * Converts an array of arguments suitable for the WP REST API to the Swagger format.
	 *
	 * @since TBD
	 *
	 * @param array<string|mixed> $args An array of arguments to swaggerize.
	 * @param array<string|mixed> $defaults A default array of arguments.
	 *
	 * @return array<string|mixed> The converted arguments.
	 */
	public function swaggerize_args( array $args = [], array $defaults = [] ) {
		if ( empty( $args ) ) {
			return $args;
		}

		$no_description = __( 'No description provided', 'the-events-calendar' );
		$defaults = array_merge( [
			'in'          => 'body',
			'schema' => [
				'type'        => 'string',
			],
			'description' => $no_description,
			'required'    => false,
			'items'       => [
				'type' => 'integer',
			],
		], $defaults );


		$swaggerized = [];
		foreach ( $args as $name => $info ) {
			if ( isset( $info['swagger_type'] ) ) {
				$type = $info['swagger_type'];
			} else {
				$type = isset( $info['type'] ) ? $info['type'] : false;
			}

			$type = $this->convert_type( $type );

			$read = [
				'name'             => $name,
				'in'               => isset( $info['in'] ) ? $info['in'] : false,
				'description'      => isset( $info['description'] ) ? $info['description'] : false,
				'schema' => [
					'type'         => $type,
				],
				'required'         => isset( $info['required'] ) ? $info['required'] : false,
			];

			if ( isset( $info['items'] ) ) {
				$read['schema']['items'] = $info['items'];
			}

			if ( isset( $info['collectionFormat'] ) && $info['collectionFormat'] === 'csv' ) {
				$read['style']   = 'form';
				$read['explode'] = false;
			}

			if ( isset( $info['swagger_type'] ) ) {
				$read['schema']['type'] = $info['swagger_type'];
			}

			// Copy in case we need to mutate default values for this field in args
			$defaultsCopy = $defaults;
			unset( $defaultsCopy['default'] );
			unset( $defaultsCopy['items'] );
			unset( $defaultsCopy['type'] );

			$swaggerized[] = array_merge( $defaultsCopy, array_filter( $read ) );
		}

		return $swaggerized;
	}

	/**
	 * Converts REST format type argument to the corresponding Swagger.io definition.
	 *
	 * @since TBD
	 *
	 * @param string $type A type to convert to Swagger.
	 *
	 * @return string The converted type.
	 */
	protected function convert_type( $type ) {
		$rest_to_swagger_type_map = [
			'int'  => 'integer',
			'bool' => 'boolean',
		];

		return Arr::get( $rest_to_swagger_type_map, $type, $type );
	}
}