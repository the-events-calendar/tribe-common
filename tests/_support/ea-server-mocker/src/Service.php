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
			'ea_mocker-origins-mock_import_response',
		);
	}

	/**
	 * Fetch origins from service
	 *
	 * @return array
	 */
	public function get_origins() {
		if ( ! empty( get_option( 'ea_mocker-origins-mock_response' ) ) ) {
			return json_decode( get_option( 'ea_mocker-origins-mock_response' ) );
		}
	}

	/**
	 * Fetch import data from service
	 *
	 * @param string $import_id ID of the Import Record
	 *
	 * @return stdClass|WP_Error
	 */
	public function get_import( $import_id ) {
		if ( ! empty( json_decode( get_option( 'ea_mocker-origins-mock_import_response' ) ) ) ) {
			return json_decode( get_option( 'ea_mocker-origins-mock_import_response' ) );
		}
	}

	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind() {
		tribe_singleton( 'events-aggregator.service', 'Tribe__Events__Aggregator_Mocker__Service' );
	}
}