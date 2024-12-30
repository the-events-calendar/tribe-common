<?php
/**
 * The Integrations REST Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\REST\V1\Endpoints
 */

namespace TEC\Event_Automator\Integrations\REST\V1\Endpoints\Queue;

use TEC\Event_Automator\Integrations\REST\V1\Interfaces\REST_Endpoint_Interface;
use TEC\Event_Automator\Integrations\Trigger_Queue\Integration_Trigger_Queue;
use TEC\Event_Automator\Traits\Last_Access;
use Tribe__Documentation__Swagger__Provider_Interface as Swagger_Provider_Interface;
use Tribe__REST__Endpoints__READ_Endpoint_Interface as READ_Endpoint_Interface;
use Tribe__Utils__Array as Arr;
use WP_Error;
use WP_REST_Request;

/**
 * Integration_REST_Endpoint
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integration\REST\V1\Endpoints
 */
abstract class Integration_REST_Endpoint implements READ_Endpoint_Interface, Swagger_Provider_Interface, REST_Endpoint_Interface {
	use Last_Access;

	/**
	 * The REST API endpoint path.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * An instance of the Integration API handler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * An instance of the Swagger_Documentation handler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Swagger_Documentation
	 */
	protected $documentation;

	/**
	 * Endpoint details prefix.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $endpoint_details_prefix;

	/**
	 * Endpoint id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $endpoint_id = '';

	/**
	 * An array of details for the endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var array<string,array>
	 */
	protected $details;

	/**
	 * Whether the Endpoint is enabled or disabled.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var bool
	 */
	protected bool $enabled;

	/**
	 * Whether the Endpoint is missing a dependency.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var bool
	 */
	protected bool $missing_dependency;

	/**
	 * An array of dependent codes for endpoint [ 'et', 'tec' ].
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var array<string>
	 */
	protected array $dependents = [];

	/**
	 * The endpoint type( authorize or queue ).
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $type;

	/**
	 * The endpoint service id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected static $service_id;

	/**
	 * The trigger accessed with this endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var Integration_Trigger_Queue
	 */
	public $trigger;

	/**
	 * Register the actual endpoint on WP Rest API.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @link http://swagger.io/
	 *
	 * @return array<string|mixed> An array description of a Swagger supported component.
	 */
	abstract public function get_documentation();

	/**
	 * Provides the content of the `args` array to register the endpoint support for GET requests.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string|mixed> An array of read 'args'.
	 */
	abstract public function READ_args();

	/**
	 * Gets the Endpoint path for this route.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string
	 */
	public function get_endpoint_path() {
		return $this->path;
	}

	/**
	 * Whether the current request can access the endpoint.
	 *
	 * @return bool Whether the current request can access the endpoint.
	 */
	public function can_access( $request ) {
		$verified_token = $this->verify_token( $request );

		if ( is_wp_error( $verified_token ) ) {
			return false;
		}

		$app_header_id = $request->get_header( 'eva_app_name' );

		$loaded = $this->load_api_key_pair( $verified_token['consumer_id'], $verified_token['consumer_secret'], $verified_token, $app_header_id );
		if ( is_wp_error( $loaded ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sanitize a request argument based on details registered to the route.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
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
	 * @since 6.0.0 Migrated to Common from Event Automator
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

		$no_description = _x( 'No description provided', 'Default description for integration endpoint.', 'tribe-common' );
		$defaults       = array_merge(
			[
				'in'          => 'body',
				'schema'      => [
					'type' => 'string',
				],
				'description' => $no_description,
				'required'    => false,
				'items'       => [
					'type' => 'integer',
				],
			],
			$defaults
		);

		$swaggerized = [];
		foreach ( $args as $name => $info ) {
			if ( isset( $info['swagger_type'] ) ) {
				$type = $info['swagger_type'];
			} else {
				$type = $info['type'] ?? false;
			}

			$type = $this->convert_type( $type );

			$read = [
				'name'        => $name,
				'in'          => $info['in'] ?? false,
				'description' => $info['description'] ?? false,
				'schema'      => [
					'type' => $type,
				],
				'required'    => $info['required'] ?? false,
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
			$defaults_copy = $defaults;
			unset( $defaults_copy['default'] );
			unset( $defaults_copy['items'] );
			unset( $defaults_copy['type'] );

			$swaggerized[] = array_merge( $defaults_copy, array_filter( $read ) );
		}

		return $swaggerized;
	}

	/**
	 * Converts REST format type argument to the corresponding Swagger.io definition.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $type A type to convert to Swagger.
	 *
	 * @return string|array<string> The converted type, maintaining structure if it's an array.
	 */
	protected function convert_type( $type ) {
		$rest_to_swagger_type_map = [
			'int'  => 'integer',
			'bool' => 'boolean',
		];

		// Check if type is scalar and directly map it.
		if ( is_scalar( $type ) ) {
			return Arr::get( $rest_to_swagger_type_map, $type, $type );
		}

		// If type is an array, recursively convert its elements.
		if ( is_array( $type ) ) {
			foreach ( $type as $key => $value ) {
				$type[ $key ] = $this->convert_type( $value );
			}

			return $type;
		}

		// Return the type unmodified if it's neither scalar nor array.
		return $type;
	}

	/**
	 * Load the API Key Pair using the consumer id and secret.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string               $consumer_id     The consumer id to get and load an API Key pair.
	 * @param string               $consumer_secret The consumer secret used to verify an API Key pair.
	 * @param string|array<string> $token           The decoded access token or an empty string.
	 * @param string               $app_header_id   The app header id sent from the integration.
	 *
	 * @return bool|WP_Error Whether the API Key pair could load or WP_Error.
	 */
	protected function load_api_key_pair( $consumer_id, $consumer_secret, $token = '', $app_header_id = '' ) {
		$loaded = $this->api->load_api_key_by_id( $consumer_id, $consumer_secret );
		if ( is_wp_error( $loaded ) ) {
			return $loaded;
		}

		$app_name = empty( $token['app_name'] ) ? '' : $token['app_name'];
		$app_name = $app_header_id ?: $app_name;
		$this->api->set_api_key_last_access( $consumer_id, $app_name );
		$this->set_endpoint_last_access( $app_name );

		return true;
	}

	/**
	 * Verify the access_token for the integration request.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return array<string|string>|WP_Error The decoded access token or WP_Error.
	 */
	protected function verify_token( $request ) {
		$access_token = $request->get_param( 'access_token' );
		if ( empty( $access_token ) ) {
			$access_token = $request->get_param( 'tec_access_token' );
		}
		if ( empty( $access_token ) ) {
			return new WP_Error( 'missing_access_token', __( 'Missing access token.', 'tribe-common' ), [ 'status' => 401 ] );
		}

		return $this->api->decode_jwt( $access_token );
	}

	/**
	 * Verify and load the access_token for the request.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return array<string|string>|WP_Error The decoded access token or WP_Error.
	 */
	protected function verify_and_load_key( $request ) {
		$verified_token = $this->verify_token( $request );
		if ( is_wp_error( $verified_token ) ) {
			return $verified_token;
		}

		return $this->load_api_key_pair( $verified_token['consumer_id'], $verified_token['consumer_secret'], $verified_token );
	}

	/**
	 * Get the endpoint type.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The endpoint type.
	 */
	public function get_endpoint_type() {
		return static::$type;
	}

	/**
	 * Get the endpoint id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The endpoint details id with prefix and endpoint combined.
	 */
	public function get_id() {
		return static::$endpoint_id;
	}

	/**
	 * Get the endpoint option id.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The endpoint details id with prefix and endpoint combined.
	 */
	public function get_option_id() {
		return static::$endpoint_details_prefix . static::$endpoint_id;
	}

	/**
	 * Get the translatable display name for the integration endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string The display name for the integration endpoint.
	 */
	abstract protected function get_display_name(): string;

	/**
	 * Adds the endpoint to the endpoint dashboard fitler.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function add_to_dashboard() {
		$api_id = $this->api::get_api_id();

		add_filter( "tec_event_automator_{$api_id}_endpoints", [ $this, 'add_endpoint_details' ], 10, 2 );
	}

	/**
	 * Add the endpoint details to the endpoint array for the dashboard.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string,array> $endpoints An array of the integration endpoints to display.
	 *
	 * @return array<string,array> An array of the integration endpoints to display with current endpoint added.
	 */
	public function add_endpoint_details( $endpoints ) {
		$endpoints[ get_class( $this ) ] = $this->get_endpoint_details();

		return $endpoints;
	}

	/**
	 * Get details for the current endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,array> An array of the details for an endpoint.
	 */
	public function get_endpoint_details() {
		$endpoint_details = $this->get_saved_details();
		$api_id           = $this->api::get_api_id();

		$endpoint = [
			'id'                 => static::$endpoint_id,
			'display_name'       => $this->get_display_name(),
			'type'               => static::$type,
			'last_access'        => $endpoint_details['last_access'],
			'count'              => 0,
			'enabled'            => $endpoint_details['enabled'],
			'missing_dependency' => false,
			'dependents'         => [],
		];

		// Setup queue counts only on that endpoint type.
		if ( static::$type === 'queue' && isset( $this->trigger ) ) {
			$endpoint_queue    = (array) $this->trigger->get_queue();
			$endpoint['count'] = empty( $endpoint_queue ) ? 0 : count( $endpoint_queue );
		}

		/**
		 * Filters the integration endpoint details.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<string,array>    $endpoint An array of the integration endpoint details.
		 * @param Abstract_REST_Endpoint $this     An instance of the endpoint.
		 */
		return apply_filters( "tec_event_automator_{$api_id}_endpoint_details", $endpoint, $this );
	}

	/**
	 * Get the endpoint saved details ( last access and enabled ).
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string,array> An array of saved details for an endpoint.
	 */
	public function get_saved_details() {
		return get_option(
			$this->get_option_id(),
			[
				'last_access' => '',
				'enabled'     => true,
			]
		);
	}

	/**
	 * Set the endpoint details ( last access and enabled ).
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array<string|integer> $details An array of saved details for an endpoint ( last access and enabled ).
	 *
	 * @return bool
	 */
	public function set_endpoint_details( array $details ) {
		return update_option( $this->get_option_id(), $details );
	}

	/**
	 * Updates the last access valid access of an endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $app_name The optional app name used with this API key pair.
	 */
	public function set_endpoint_last_access( $app_name = '' ) {
		$endpoint_details                = $this->get_saved_details();
		$endpoint_details['last_access'] = $this->get_last_access( $app_name );

		$this->set_endpoint_details( $endpoint_details );
	}

	/**
	 * Clears last access of an endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function clear_endpoint_last_access() {
		$endpoint_details                = $this->get_saved_details();
		$endpoint_details['last_access'] = '-';

		$this->set_endpoint_details( $endpoint_details );
	}

	/**
	 * Disables or enables the endpoint.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param bool $enabled The enabled to change the endpoint too.
	 */
	public function set_endpoint_enabled( bool $enabled ) {
		$endpoint_details            = $this->get_saved_details();
		$endpoint_details['enabled'] = $enabled;

		$this->set_endpoint_details( $endpoint_details );
	}

	/**
	 * Add a custom post id to a trigger queue.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param integer            $post_id A WordPress custom post id.
	 * @param array<mixed|mixed> $data    An array of data specific to the trigger and used for validation.
	 */
	public function add_to_queue( $post_id, $data ) {
		// If disabled, then do not add to the queue.
		if ( ! $this->enabled || $this->missing_dependency ) {
			return;
		}

		$api_id = $this->api::get_api_id();

		/**
		 * Filters data passed to the trigger queue for an endpoint.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<mixed|mixed>     $data    An array of data specific to the trigger and used for validation.
		 * @param integer                $post_id A WordPress custom post id.
		 * @param Integration_REST_Endpoint $this    An instance of the endpoint.
		 */
		$data = (array) apply_filters( "tec_event_automator_{$api_id}_add_to_queue_data", $data, $post_id, $this );

		$endpoint_id = static::$endpoint_id;

		/**
		 * Filters data passed to the trigger queue for an endpoint by endpoint id.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param array<mixed|mixed>     $data    An array of data specific to the trigger and used for validation.
		 * @param integer                $post_id A WordPress custom post id.
		 * @param Integration_REST_Endpoint $this    An instance of the endpoint.
		 */
		$data = (array) apply_filters( "tec_event_automator_{$api_id}_add_to_queue_data_{$endpoint_id}", $data, $post_id, $this );

		$this->trigger->add_to_queue( $post_id, $data );
	}

	/**
	 * Check if it's a REST request.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return bool True if it's a REST request, false otherwise.
	 */
	protected function is_rest_request(): bool {
		$is_rest_request = defined( 'REST_REQUEST' ) && REST_REQUEST;
		$api_id          = $this->api::get_api_id();

		/**
		 * Filter to change the value of $is_rest_request.
		 *
		 * @since 6.0.0 Migrated to Common from Event Automator
		 *
		 * @param bool $is_rest_request True if it's a REST request, false otherwise.
		 */
		return apply_filters( "tec_event_automator_{$api_id}_is_rest_request", $is_rest_request, static::$endpoint_id, $this );
	}

	/**
	 * Get the endpoint dependents.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return array<string> The endpoint dependents array.
	 */
	public function get_dependents() {
		return $this->dependents;
	}

	/**
	 * Verify token and login user before dispatching the request.
	 * Done on `rest_pre_dispatch` to be able to set current user to pass validation capability checks.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param mixed           $result  Response to replace the requested version with. Can be anything
	 *                                 a normal endpoint can return, or null to not hijack the request.
	 * @param WP_REST_Server  $server  Server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 *
	 * @return null With always return null, failure will happen on the can_create permission check.
	 */
	public function pre_dispatch_verification( $result, $server, $request ) {
		if ( $request->get_route() !== '/' . $this->get_events_route_namespace() . $this->get_endpoint_path() ) {
			return $result;
		}

		$verified_token = $this->verify_token( $request );
		if ( is_wp_error( $verified_token ) ) {
			return $result;
		}

		$app_header_id = $request->get_header( 'eva_app_name' );

		$loaded = $this->load_api_key_pair( $verified_token['consumer_id'], $verified_token['consumer_secret'], $verified_token, $app_header_id );
		if ( is_wp_error( $loaded ) ) {
			return $result;
		}

		// Check if user connected to access token can create events.
		$cap      = get_post_type_object( 'tribe_events' )->cap->edit_posts;
		$user     = $this->api->get_user();
		$can_edit = user_can( $user, $cap );
		if ( ! $can_edit ) {
			return $result;
		}

		// Load user to get correct capabilities to create events.
		wp_set_current_user( $user->ID );

		return $result;
	}

	/**
	 * Modifies REST API comma seperated parameters before validation.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param WP_REST_Response|WP_Error $response Response to replace the requested version with. Can be anything
	 *                                            a normal endpoint can return, or a WP_Error if replacing the
	 *                                            response with an error.
	 * @param WP_REST_Server            $handler  ResponseHandler instance (usually WP_REST_Server).
	 * @param WP_REST_Request           $request Request used to generate the response.
	 *
	 * @return WP_REST_Response|WP_Error The response.
	 */
	public function modify_rest_api_params_before_validation( $response, $handler, $request ) {
		if ( $request->get_method() !== 'POST' ) {
			return $response;
		}

		if ( $request->get_route() !== '/' . $this->get_events_route_namespace() . $this->get_endpoint_path() ) {
			return $response;
		}

		$organizer = $request->get_param( 'organizer' );
		if ( $organizer && is_string( $organizer ) ) {
			$organizer_array = explode( ',', $organizer );
			$organizer_array = array_map( 'absint', $organizer_array );
			$request->set_param( 'organizer', $organizer_array );
		}

		return $response;
	}
}
