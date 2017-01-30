<?php

class Tribe__Events__Aggregator_Mocker__Recorder
	implements Tribe__Events__Aggregator_Mocker__Binding_Provider_Interface {

	/**
	 * @var Tribe__Events__Aggregator__Service
	 */
	protected $service;

	/**
	 * The option that will store recorded Event Aggregator API responses.
	 * @var string
	 */
	protected $responses = 'ea_mocker-recorder-recorded-responses';

	/**
	 * Tribe__Events__Aggregator_Mocker__Recorder constructor.
	 *
	 * @param Tribe__Events__Aggregator__Service $service
	 */
	public function __construct( Tribe__Events__Aggregator__Service $service ) {
		$this->service = $service;
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
			'ea_mocker-recorder-enabled',
		);
	}

	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind() {
		$service = tribe( 'events-aggregator.service', 'Tribe__Events__Aggregator_Mocker__Service' );
		$recorder = new self( $service );
		tribe_singleton( 'events-aggregator.service', $recorder );
	}

	public function __call( $name, $arguments ) {
		return call_user_func_array( array( $this->service, $name ), $arguments );
	}

	/**
	 * Performs a GET request against the Event Aggregator service.
	 *
	 * Decorates the GET request recording the response.
	 *
	 * @param string $endpoint Endpoint for the Event Aggregator service
	 * @param array  $data     Parameters to send to the endpoint
	 *
	 * @return stdClass|WP_Error
	 */
	public function get( $endpoint, $data = array() ) {
		$response = $this->service->get( $endpoint, $data );

		$logged = get_option( $this->responses );

		$pretty = json_encode( array(
			'date'         => date( 'Y-m-d H:i:s' ),
			'method'       => 'GET',
			'endpoint'     => $endpoint,
			'request_data' => $data,
			'response'     => $response
		) );

		update_option( $this->responses, $logged . "\n\n" . $pretty );

		return $response;
	}
}