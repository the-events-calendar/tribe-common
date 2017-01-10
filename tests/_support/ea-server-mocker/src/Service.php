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
		);
	}

	/**
	 * Fetch origins from service
	 *
	 * @return array
	 */
	public function get_origins() {
		return json_decode( get_option( 'ea_mocker-origins-mock_response' ) );
	}

	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind() {
		tribe_singleton( 'events-aggregator.service', 'Tribe__Events__Aggregator_Mocker__Service' );
	}
}