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
use TEC\Common\Zapier\REST\V1\Traits\REST_Namespace;
use Tribe__REST__Endpoints__READ_Endpoint_Interface as READ_Endpoint_Interface;
use Tribe__Documentation__Swagger__Provider_Interface as Swagger_Provider_Interface;
use TEC\Common\Zapier\REST\V1\Documentation\Swagger_Documentation;
use Tribe__Utils__Array as Arr;
use WP_REST_Request;
use WP_Error;

/**
 * Abstract REST Endpoint Zapier
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Endpoints
 */
abstract class Abstract_REST_Endpoint implements READ_Endpoint_Interface, Swagger_Provider_Interface {
	use REST_Namespace;

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
	 * An instance of the Zapier Swagger_Documentation handler.
	 *
	 * @since TBD
	 *
	 * @var Api
	 */
	protected $documentation;

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since TBD
	 *
	 * @param Api $api An instance of the Zapier API handler.
	 * @param Swagger_Documentation $documentation An instance of the Zapier Swagger_Documentation handler.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation ) {
		$this->api           = $api;
		$this->documentation = $documentation;
	}

	/**
	 * Register the actual endpoint on WP Rest API.
	 *
	 * @since TBD
	 */
	abstract public function register();

	/**
	 * Returns an array in the format used by Swagger 2.0.
	 *
	 * While the structure must conform to that used by v2.0 of Swagger the structure can be that of a full document
	 * or that of a document part.
	 * The intelligence lies in the "gatherer" of informations rather than in the single "providers" implementing this
	 * interface.
	 *
	 * @since TBD
	 *
	 * @link http://swagger.io/
	 *
	 * @return array<string|mixed> An array description of a Swagger supported component.
	 */
	abstract public function get_documentation();

	/**
	 * Provides the content of the `args` array to register the endpoint support for GET requests.
	 *
	 * @since TBD
	 *
	 * @return array<string|mixed> An array of read 'args'.
	 */
	abstract public function READ_args();

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

	/**
	 * Load the API Key Pair using the consumer id and secret.
	 *
	 * @since TBD
	 *
	 * @param string $consumer_id     The consumer id to get and load an API Key pair.
	 * @param string $consumer_secret The consumer secret used to verify an API Key pair.
	 *
	 * @return bool|WP_Error Whether the API Key pair could load or WP_Error.
	 */
	protected function load_api_key_pair( $consumer_id, $consumer_secret ) {
		$loaded = $this->api->load_api_key_by_id( $consumer_id, $consumer_secret );
		if ( is_wp_error( $loaded ) ) {
			return $loaded;
		}

		return true;
	}

	/**
	 * Verify the access_token for the Zapier request.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return array<string|string>|WP_Error The decoded access token or WP_Error.
	 */
	protected function verify_token( $request ) {
		$access_token = $request->get_param( 'access_token' );
		$key_pair     = $this->api->decode_jwt( $access_token );

		return $key_pair;
	}
}