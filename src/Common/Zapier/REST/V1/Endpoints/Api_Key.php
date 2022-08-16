<?php
/**
 * The Zapier API Key Endpoint.
 *
 * @package TEC\Common\ZapierREST\V1\Endpoints;
 * @since   TBD
 */

namespace TEC\Common\Zapier\REST\V1\Endpoints;

use Tribe__Events__REST__V1__Endpoints__Base as REST_V1_Endpoints_Base;
use Tribe__REST__Endpoints__READ_Endpoint_Interface as READ_Endpoint_Interface;
use Tribe__Documentation__Swagger__Provider_Interface as Swagger_Provider_Interface;
use Tribe__REST__Messages_Interface;
use Tribe__Events__REST__Interfaces__Post_Repository;
use Tribe__Events__Validator__Interface;
use Tribe__Events__Main;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

class Api_Key
	extends REST_V1_Endpoints_Base
	implements READ_Endpoint_Interface, Swagger_Provider_Interface {

	/**
	 * @var Tribe__REST__Main
	 */
	protected $main;

	/**
	 * @var WP_REST_Request
	 */
	protected $serving;

	/**
	 * @var Tribe__Events__REST__Interfaces__Post_Repository
	 */
	protected $post_repository;

	/**
	 * @var Tribe__Events__Validator__Interface
	 */
	protected $validator;

	/**
	 * Tribe__Events__REST__V1__Endpoints__Archive_Event constructor.
	 *
	 * @since 4.6
	 *
	 * @param Tribe__REST__Messages_Interface                  $messages
	 * @param Tribe__Events__REST__Interfaces__Post_Repository $repository
	 * @param Tribe__Events__Validator__Interface              $validator
	 */
	public function __construct(
		Tribe__REST__Messages_Interface $messages,
		Tribe__Events__REST__Interfaces__Post_Repository $repository,
		Tribe__Events__Validator__Interface $validator
	) {
		parent::__construct( $messages );
		$this->post_type = Tribe__Events__Main::POSTTYPE;
	}

	/**
	 * Get new events from queue.
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|void|WP_Error|WP_REST_Response
	 */
	public function get( WP_REST_Request $request ) {
		$secret_key = get_option( 'zapier_secret' );
		$encoded = urlencode ( 'H*ZbPs3^1rw#YdLt*5' );
		$username   = $request->get_param( 'username' );
		$password   = $request->get_param( 'password' );
		$user       = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();

			return new WP_Error( $error_code, $user->get_error_message( $error_code ), array(
					'status' => 401,
				) );
		}

		return array(
			'api_key' => '252323f3',
		);

		$issuedAt = time();
		$token    = array(
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $issuedAt,
			'exp'  => $issuedAt + 300,
			'data' => array(
				'user_id' => $user->data->ID,
			),
		);

		return array(
			'token' => JWT::encode( $token, $secret_key ),
		);
	}

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
	 * @return array An array description of a Swagger supported component.
	 */
	public function get_documentation() {
		$POST_defaults = [
			'in'      => 'formData',
			'default' => '',
			'type'    => 'string',
		];
		$post_args     = array_merge( $this->READ_args(), $this->CHECK_IN_args() );

		return [
			'post' => [
				'consumes'   => [ 'application/x-www-form-urlencoded' ],
				'parameters' => $this->swaggerize_args( $post_args, $POST_defaults ),
				'responses'  => [
					'201' => [
						'description' => __( 'Returns successful check in', 'tribe-common' ),
						'schema'      => [
							'$ref' => '#/definitions/Ticket',
						],
					],
					'400' => [
						'description' => __( 'A required parameter is missing or an input parameter is in the wrong format', 'tribe-common' ),
					],
					'403' => [
						'description' => esc_html( sprintf( __( 'The %s is already checked in', 'tribe-common' ), tribe_get_ticket_label_singular_lowercase( 'rest_qr' ) ) ),
					],
				],
			],
		];
	}

	/**
	 * Provides the content of the `args` array to register the endpoint support for GET requests.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function READ_args() {
		return [
			'username'       => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_string' ],
				'type'              => 'string',
				'description'       => __( 'The API Key to authorize Zapier connection.', 'tribe-common' ),
			],
			'password'       => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_string' ],
				'type'              => 'string',
				'description'       => __( 'The API Key to authorize Zapier connection.', 'tribe-common' ),
			],
		];
	}

	/**
	 * Returns the content of the `args` array that should be used to register the endpoint
	 * with the `register_rest_route` function.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function CHECK_IN_args() {
		$ticket_label_singular_lower = esc_html( tribe_get_ticket_label_singular_lowercase( 'rest_qr' ) );

		return [
			// QR fields.
			'api_key'       => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_string' ],
				'type'              => 'string',
				'description'       => __( 'The API Key to authorize check in.', 'tribe-common' ),
			],
			'ticket_id'     => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_numeric' ],
				'type'              => 'string',
				'description'       => esc_html( sprintf( __( 'The ID of the %s to check in.', 'tribe-common' ), $ticket_label_singular_lower ) ),
			],
			'security_code' => [
				'required'          => true,
				'validate_callback' => [ $this->validator, 'is_string' ],
				'type'              => 'string',
				'description'       => esc_html( sprintf( __( 'The security code of the %s to verify for check in.', 'tribe-common' ), $ticket_label_singular_lower ) ),
			],
		];
	}

	/**
	 * Check in attendee
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function check_in( WP_REST_Request $request ) {

		$this->serving = $request;

		$qr_arr = $this->prepare_qr_arr( $request );

		if ( is_wp_error( $qr_arr ) ) {
			$response = new WP_REST_Response( $qr_arr );
			$response->set_status( 400 );

			return $response;
		}

		/**
		 * Allow filtering the API Key validation status.
		 *
		 * @since 5.2.5
		 *
		 * @param bool  $is_valid Whether the provided API Key is valid or not.
		 * @param array $qr_arr The request data for Check in.
		 */
		$api_check = apply_filters( 'event_tickets_plus_requested_api_is_valid', $this->has_api( $qr_arr ), $qr_arr );

		// Check all the data we need is there
		if ( empty( $api_check ) || empty( $qr_arr['ticket_id'] ) ) {
			$response = new WP_REST_Response( $qr_arr );
			$response->set_status( 400 );

			return $response;
		}

		$ticket_id     = (int) $qr_arr['ticket_id'];
		$security_code = (string) $qr_arr['security_code'];

		/** @var Tribe__Tickets__Data_API $data_api */
		$data_api = tribe( 'tickets.data_api' );

		$service_provider = $data_api->get_ticket_provider( $ticket_id );
		if (
			empty( $service_provider->security_code )
			|| get_post_meta( $ticket_id, $service_provider->security_code, true ) !== $security_code
		) {
			$response = new WP_REST_Response( [ 'msg' => __( 'Security code is not valid!', 'tribe-common' ) ] );
			$response->set_status( 403 );

			return $response;
		}

		// Add check for attendee data.
		$attendee = $service_provider->get_attendees_by_id( $ticket_id );
		$attendee = reset( $attendee );
		if ( ! is_array( $attendee ) ) {
			$response = new WP_REST_Response( [ 'msg' => __( 'An attendee is not found with this ID.', 'tribe-common' ) ] );
			$response->set_status( 403 );

			return $response;
		}

		// Get the attendee data to populate the response.
		$attendee_id   = (int) $attendee['attendee_id'];
		$attendee_data = tribe( 'tickets.rest-v1.attendee-repository' )->format_item( $attendee_id );

		/** @var Tribe__Tickets__Status__Manager $status */
		$status = tribe( 'tickets.status' );

		$complete_statuses = (array) $status->get_completed_status_by_provider_name( $service_provider );

		if ( ! in_array( $attendee['order_status'], $complete_statuses, true ) ) {
			$response = new WP_REST_Response(
				[
					'msg' => esc_html(
						// Translators: %s: 'ticket' label (singular, lowercase).
						sprintf(
							__( "This attendee's %s is not authorized to be Checked in", 'tribe-common' ),
							tribe_get_ticket_label_singular_lowercase( 'rest_qr' )
						)
					),
					'attendee' => $attendee_data,
				]
			);

			$response->set_status( 403 );

			return $response;
		}

		// Check if the attendee is checked in.
		$checked_status = get_post_meta( $ticket_id, '_tribe_qr_status', true );
		if ( $checked_status ) {
			$response = new WP_REST_Response(
				[
					'msg'      => __( 'Already checked in!', 'tribe-common' ),
					'attendee' => $attendee_data,
				]
			);
			$response->set_status( 403 );

			return $response;
		}

		$checked = $this->_check_in( $ticket_id, $service_provider );
		if ( ! $checked ) {
			$msg_arr = [
				'msg'             => esc_html( sprintf( __( '%s not checked in!', 'tribe-common' ), tribe_get_ticket_label_singular( 'rest_qr' ) ) ),
				'tribe_qr_status' => get_post_meta( $ticket_id, '_tribe_qr_status', 1 ),
				'attendee'        => $attendee_data,
			];
			$result  = array_merge( $msg_arr, $qr_arr );

			$response = new WP_REST_Response( $result );
			$response->set_status( 403 );

			return $response;
		}

		$response = new WP_REST_Response(
			[
				'msg'      => __( 'Checked In!', 'tribe-common' ),
				'attendee' => $attendee_data,
			]
		);
		$response->set_status( 201 );

		return $response;
	}

	/**
	 * Check if API is present and matches key is settings
	 *
	 * @since TBD
	 *
	 * @param $qr_arr
	 *
	 * @return bool
	 */
	public function has_api( $qr_arr ) {

		if ( empty( $qr_arr['api_key'] ) ) {
			return false;
		}

		$tec_options = Tribe__Settings_Manager::get_options();
		if ( ! is_array( $tec_options ) ) {
			return false;
		}

		if ( $tec_options['tickets-plus-qr-options-api-key'] !== esc_attr( $qr_arr['api_key'] ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Setup array of variables for check in
	 *
	 * @since TBD
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array|mixed|void
	 */
	protected function prepare_qr_arr( WP_REST_Request $request ) {

		$qr_arr = [
			'api_key'       => $request['api_key'],
			'ticket_id'     => $request['ticket_id'],
			'event_id'      => $request['event_id'],
			'security_code' => $request['security_code'],
		];

		/**
		 * Allow filtering of $postarr data with additional $request arguments.
		 *
		 * @param array           $qr_arr  Post array used for check in
		 * @param WP_REST_Request $request REST request object
		 *
		 * @since TBD
		 */
		$qr_arr = apply_filters( 'tribe_tickets_plus_rest_qr_prepare_qr_arr', $qr_arr, $request );

		return $qr_arr;
	}

	/**
	 * Check in attendee and on first success return
	 *
	 * @since TBD
	 *
	 * @param $ticket_id
	 *
	 * @return boolean
	 */
	private function _check_in( $attendee_id, $service_provider ) {

		if ( empty( $service_provider ) ) {
			return false;
		}

		// set parameter to true for the QR app - it is false for the original url so that the message displays
		$success = $service_provider->checkin( $attendee_id, true );
		if ( $success ) {
			return $success;
		}

		return false;
	}
}
