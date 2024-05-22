<?php

class Tribe__Events__Aggregator_Mocker__Recorder
	extends Tribe__Events__Aggregator__Service
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
		tribe_singleton( 'events-aggregator.service', 'Tribe__Events__Aggregator_Mocker__Recorder' );
	}

	/**
	 * Performs a GET request against the Event Aggregator service.
	 *
	 * Decorates the GET request recording the response.
	 *
	 * @param string $endpoint Endpoint for the Event Aggregator service
	 * @param array $data Parameters to send to the endpoint
	 *
	 * @return stdClass|WP_Error
	 */
	public function get( $endpoint, $data = array() ) {
		$response = parent::get( $endpoint, $data );

		$this->record( $endpoint, $data, 'GET', $response );

		return $response;
	}

	/**
	 * Performs a POST request against the Event Aggregator service
	 *
	 * @param string $endpoint Endpoint for the Event Aggregator service
	 * @param array $data Parameters to send to the endpoint
	 *
	 * @return stdClass|WP_Error
	 */
	public function post( $endpoint, $data = array() ) {
		$response = parent::post( $endpoint, $data );

		$this->record( $endpoint, $data, 'POST', $response );

		return $response;
	}

	/**
	 * @param $endpoint
	 * @param $data
	 * @param $method
	 * @param $response
	 */
	protected function record( $endpoint, $data, $method, $response ) {
		$logged = get_option( $this->responses, false );

		if ( ! empty( $logged ) ) {
			$logged = unserialize( $logged );
		} else {
			$logged = array();
		}

		$date   = date( 'Y-m-d H:i:s' );
		$pretty = array(
			'method'       => $method,
			'endpoint'     => $endpoint,
			'request_data' => $data,
			'response'     => $response
		);

		$logged[ $date ] = $pretty;

		update_option( $this->responses, serialize( $logged ) );
	}
}