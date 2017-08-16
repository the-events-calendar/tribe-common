<?php


class Tribe__Events__Aggregator_Mocker__Service
	extends Tribe__Events__Aggregator__Service
	implements Tribe__Events__Aggregator_Mocker__Binding_Provider_Interface {

	/**
	 * Constructor!
	 */
	public function __construct( Tribe__Events__Aggregator__API__Requests $requests ) {
		parent::__construct( $requests );
	}


	/**
	 * Returns an array of options that should trigger the mocker as enabled.
	 *
	 * The options will be evaluated in a logic OR condition. Returning `true` in this method will always activate
	 * the provider.
	 *
	 * @return array|bool
	 */
	public static function enable_on() {
		return array(
			'ea_mocker-origins-mock_response',
			'ea_mocker-import-mock_response',
			'ea_mocker-import-post_import_mock_response',
		);
	}

	/**
	 * Fetch origins from service
	 *
	 * @param bool $return_error
	 *
	 * @return array
	 */
	public function get_origins( $return_error = true ) {
		$mocked_response = get_option( 'ea_mocker-origins-mock_response' );
		if ( ! empty( $mocked_response ) ) {
			$error = null;
			switch ( $mocked_response ) {
				case 'throttled':
					$response = $this->get_default_origins();
					$error    = array( 'response' => array( 'code' => 403 ) );
					break;
				case 'not-found':
					$response = $this->get_default_origins();
					$error    = array( 'response' => array( 'code' => 404 ) );
					break;
				case 'error':
					$response = $this->get_default_origins();
					$error    = new WP_Error( 'an-error-happened' );
					break;
				default:
					$response = json_decode( $mocked_response );
					break;
			}

			return $return_error
				? array( $response, $error )
				: $response;
		}

		return parent::get_origins();
	}

	/**
	 * Fetch import data from service
	 *
	 * @param string $import_id ID of the Import Record
	 *
	 * @return stdClass|WP_Error
	 */
	public function get_import( $import_id, $data = array() ) {
		$mocked_response = get_option( 'ea_mocker-import-mock_response' );
		if ( ! empty( $mocked_response ) ) {
			switch ( $mocked_response ) {
				case 'throttled':
					$response = array( 'response' => array( 'code' => 403 ) );
					break;
				case 'not-found':
					$response = array( 'response' => array( 'code' => 404 ) );
					break;
				case 'error':
					$response = new WP_Error( 'an-error-happened' );
					break;
				default:
					$response = json_decode( $mocked_response );
					break;
			}

			return $response;
		}

		return parent::get_import( $import_id );
	}

	/**
	 * Creates an import
	 *
	 * Note: This method exists because WordPress by default doesn't allow multipart/form-data
	 *       with boundaries to happen
	 *
	 * @param array $args        {
	 *                           Array of arguments. See REST docs for details. 1 exception listed below:
	 *
	 * @type array  $source_file Source file array using the $_FILES array values
	 * }
	 *
	 * @return string
	 */
	public function post_import( $args ) {
		$mocked_response = get_option( 'ea_mocker-import-post_import_mock_response' );
		if ( ! empty( $mocked_response ) ) {
			switch ( $mocked_response ) {
				case 'throttled':
					$response = array( 'response' => array( 'code' => 403 ) );
					break;
				case 'not-found':
					$response = array( 'response' => array( 'code' => 404 ) );
					break;
				case 'error':
					$response = new WP_Error( 'an-error-happened' );
					break;
				default:
					$response = json_decode( $mocked_response );
					break;
			}

			return $response;
		}

		return parent::post_import( $args );
	}


	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind() {
		$recorder_enabled = get_option( 'ea_mocker-recorder-enabled' );

		if ( $recorder_enabled ) {
			return;
		}

		tribe_singleton( 'events-aggregator.service', 'Tribe__Events__Aggregator_Mocker__Service' );
		delete_transient( 'tribe_aggregator_origins' );
	}

	/**
	 * Builds an endpoint URL
	 *
	 * @param string $endpoint Endpoint for the Event Aggregator service
	 * @param array  $data     Parameters to add to the URL
	 *
	 * @return string|WP_Error
	 */
	public function build_url( $endpoint, $data = array() ) {
		$mock_domain = get_option( 'ea_mocker-service_domain' );

		if ( ! empty( $mock_domain ) ) {
			$this->api = $this->api();
			if ( is_array( $this->api ) ) {
				$this->api['domain'] = trailingslashit( $mock_domain );
			} else {
				$this->api->domain = trailingslashit( $mock_domain );
			}
		}

		return parent::build_url( $endpoint, $data );
	}
}
