<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Endpoints
 */

namespace TEC\Common\Zapier\REST\V1\Endpoints;

use Tribe__REST__Endpoints__READ_Endpoint_Interface;
use Tribe__Documentation__Swagger__Provider_Interface;
use Tribe__Documentation__Swagger__Builder_Interface;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

class Swagger_Documentation
	implements Tribe__REST__Endpoints__READ_Endpoint_Interface,
	Tribe__Documentation__Swagger__Provider_Interface,
	Tribe__Documentation__Swagger__Builder_Interface {

	/**
	 * @var string
	 */
	protected $open_api_version = '3.0.0';

	/**
	 * @var string
	 */
	protected $tec_rest_api_version;

	/**
	 * @var Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	protected $documentation_providers = array();

	/**
	 * @var Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	protected $definition_providers = array();

	/**
	 * Tribe__Events__REST__V1__Endpoints__Swagger_Documentation constructor.
	 *
	 * @since TBD
	 *
	 * @param string $tec_rest_api_version
	 */
	public function __construct( $tec_rest_api_version ) {
		$this->tec_rest_api_version = $tec_rest_api_version;
	}

	/**
	 * Handles GET requests on the endpoint.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response An array containing the data on success or a WP_Error instance on failure.
	 */
	public function get( WP_REST_Request $request ) {
		$data = $this->get_documentation();

		return new WP_REST_Response( $data );
	}

	/**
	 * Returns an array in the format used by Swagger 2.0.
	 *
	 * @since TBD
	 *
	 * While the structure must conform to that used by v2.0 of Swagger the structure can be that of a full document
	 * or that of a document part.
	 * The intelligence lies in the "gatherer" of informations rather than in the single "providers" implementing this
	 * interface.
	 *
	 * @link http://swagger.io/
	 *
	 * @return array An array description of a Swagger supported component.
	 */
	public function get_documentation() {
		/** @var Tribe__Tickets__REST__V1__Main $main */
		//$main = tribe( 'tickets.rest-v1.main' );

		$documentation = array(
			'openapi'    => $this->open_api_version,
			'info'       => $this->get_api_info(),
			'servers'    => array(
				array(
					'url' => $main->get_url(),
				),
			),
			'paths'      => $this->get_paths(),
			'components' => array( 'schemas' => $this->get_definitions() ),
		);

		/**
		 * Filters the Swagger documentation generated for the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array<string|mixed>   $documentation An associative PHP array in the format supported by Swagger.
		 * @param Swagger_Documentation $this          This documentation endpoint instance.
		 *
		 * @link  http://swagger.io/
		 */
		$documentation = apply_filters( 'tribe_rest_swagger_documentation', $documentation, $this );

		return $documentation;
	}

	/**
	 * Get Zapier REST API Info
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_api_info() {
		return array(
			'title'       => __( 'TEC Zapier REST API', 'tribe-common' ),
			'description' => __( 'TEC Zapier REST API allows direct connections to making Zapier Zaps.', 'tribe-common' ),
			'version'     => $this->tec_rest_api_version,
		);
	}

	/**
	 * Get Zapier REST API Path
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_paths() {
		$paths = array();
		foreach ( $this->documentation_providers as $path => $endpoint ) {
			if ( $endpoint !== $this ) {
				/** @var Tribe__Documentation__Swagger__Provider_Interface $endpoint */
				$documentation = $endpoint->get_documentation();
			} else {
				$documentation = $this->get_own_documentation();
			}
			$paths[ $path ] = $documentation;
		}

		return $paths;
	}

	/**
	 * Registers a documentation provider for a path.
	 *
	 * @since TBD
	 *
	 * @param                                            $path
	 * @param Tribe__Documentation__Swagger__Provider_Interface $endpoint
	 */
	public function register_documentation_provider( $path, Tribe__Documentation__Swagger__Provider_Interface $endpoint ) {
		$this->documentation_providers[ $path ] = $endpoint;
	}

	/**
	 * Get REST API Documentation
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_own_documentation() {
		return array(
			'get' => array(
				'responses' => array(
					'200' => array(
						'description' => __( 'Returns the documentation for TEC Zapier REST API in Swagger consumable format.', 'tribe-common' ),
					),
				),
			),
		);
	}

	/**
	 * Get REST API Definitions
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	protected function get_definitions() {
		$definitions = array();
		/** @var Tribe__Documentation__Swagger__Provider_Interface $provider */
		foreach ( $this->definition_providers as $type => $provider ) {
			$definitions[ $type ] = $provider->get_documentation();
		}

		return $definitions;
	}

	/**
	 * Get REST API Registered Documentation Providers
	 *
	 * @since TBD
	 *
	 * @return Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	public function get_registered_documentation_providers() {
		return $this->documentation_providers;
	}

	/**
	 * Registers a documentation provider for a definition.
	 *
	 * @since TBD
	 *
	 * @param                                                  string $type
	 * @param Tribe__Documentation__Swagger__Provider_Interface       $provider
	 */
	public function register_definition_provider( $type, Tribe__Documentation__Swagger__Provider_Interface $provider ) {
		$this->definition_providers[ $type ] = $provider;
	}

	/**
	 * Get Documentation Provider Interface
	 *
	 * @since TBD
	 *
	 * @return Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	public function get_registered_definition_providers() {
		return $this->definition_providers;
	}

	/**
	 * Returns the content of the `args` array that should be used to register the endpoint
	 * with the `register_rest_route` function.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function READ_args() {
		return array();
	}
}
