<?php
/**
 * The Integration Abstract Swagger Documentation Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\REST\V1\Documentation
 */

namespace TEC\Event_Automator\Integrations\REST\V1\Documentation;

use Tribe__REST__Endpoints__READ_Endpoint_Interface;
use Tribe__Documentation__Swagger__Provider_Interface;
use Tribe__Documentation__Swagger__Builder_Interface;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

/**
 * Class Swagger_Documentation
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\REST\V1\Documentation
 */
abstract class Integration_Swagger_Documentation
	implements Tribe__REST__Endpoints__READ_Endpoint_Interface,
	Tribe__Documentation__Swagger__Provider_Interface,
	Tribe__Documentation__Swagger__Builder_Interface {

	/**
	 * Open API Version.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $open_api_version = '3.0.0';

	/**
	 * Integration REST API Version.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $rest_api_version = '1.0.0';

	/**
	 * REST Documentation Definition Providers.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	protected $documentation_providers = [];

	/**
	 * REST Definition Definition Providers.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	protected $definition_providers = [];

	/**
	 * Register the actual endpoint on WP Rest API.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function register() {
		tribe_register_rest_route( $this->get_events_route_namespace(), '/doc', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [ $this, 'get' ],
		] );
		$this->register_documentation_provider( '/doc', $this );
	}

	/**
	 * Handles GET requests on the endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * @since 6.0.0 Migrated to Common from Event Automator
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
		$url = '';
		if ( function_exists( 'tribe_events_rest_url' ) ) {
			$url = tribe_events_rest_url();
		} else if ( function_exists( 'tribe_tickets_rest_url' )  ) {
			$url = tribe_tickets_rest_url();
		}

		$documentation = [
			'openapi'    => $this->open_api_version,
			'info'       => $this->get_api_info(),
			'servers'    => [
				[
					'url' => $url,
				],
			],
			'paths'      => $this->get_paths(),
			'components' => [ 'schemas' => $this->get_definitions() ],
		];

		/**
		 * Filters the Swagger documentation generated for the TEC REST API.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string|mixed>   $documentation An associative PHP array in the format supported by Swagger.
		 * @param Swagger_Documentation $this          This documentation endpoint instance.
		 *
		 * @link  http://swagger.io/
		 */
		$documentation = apply_filters( 'tec_event_automator_rest_swagger_documentation', $documentation, $this );

		return $documentation;
	}

	/**
	 * Get REST API Info
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array
	 */
	protected abstract function get_api_info();

	/**
	 * Get REST API Path
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array
	 */
	protected function get_paths() {
		$paths = [];
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
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array
	 */
	protected function get_own_documentation() {
		return [
			'get' => [
				'responses' =>[
					'200' => [
						'description' => __( 'Returns the documentation for TEC REST API in Swagger consumable format.', 'tribe-common' ),
					],
				],
			],
		];
	}

	/**
	 * Get REST API Definitions
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array
	 */
	protected function get_definitions() {
		$definitions = [];
		/** @var Tribe__Documentation__Swagger__Provider_Interface $provider */
		foreach ( $this->definition_providers as $type => $provider ) {
			$definitions[ $type ] = $provider->get_documentation();
		}

		return $definitions;
	}

	/**
	 * Get REST API Registered Documentation Providers
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return Tribe__Documentation__Swagger__Provider_Interface[]
	 */
	public function get_registered_documentation_providers() {
		return $this->documentation_providers;
	}

	/**
	 * Registers a documentation provider for a definition.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string                                            $type
	 * @param Tribe__Documentation__Swagger__Provider_Interface $provider
	 */
	public function register_definition_provider( $type, Tribe__Documentation__Swagger__Provider_Interface $provider ) {
		$this->definition_providers[ $type ] = $provider;
	}

	/**
	 * Get Documentation Provider Interface
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array
	 */
	public function READ_args() {
		return [];
	}
}
