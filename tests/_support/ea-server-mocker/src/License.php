<?php

class Tribe__Events__Aggregator_Mocker__License
	implements Tribe__Events__Aggregator_Mocker__Binding_Provider_Interface {

	/**
	 * Returns an array of options that should trigger the mocker as enabled.
	 *
	 * The options will be evaluated in a logic OR condition. Returning `true` in this method will always activate
	 * the provider.
	 *
	 * @return array|bool
	 */
	public static function enable_on() {
		return true;
	}

	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind() {
		$should_mock = get_option( 'ea_mocker-license-mock_enabled' );

		$mock_key     = 'mock-key-mock-key-mock-key-mock-key-mock-key-mock-key';

		if ( empty( $should_mock ) ) {
			if ( get_option( 'pue_install_key_event_aggregator' ) === $mock_key ) {
				delete_option( 'pue_install_key_event_aggregator' );
				update_option( 'pue_install_key_event_aggregator', get_option( 'pue_install_key_event_aggregator-backup' ) );
				delete_option( 'pue_install_key_event_aggregator-backup' );
			}

			return;
		}

		$existing_key = get_option( 'pue_install_key_event_aggregator' );

		if ( $existing_key === $mock_key ) {
			return;
		}

		update_option( 'pue_install_key_event_aggregator-backup', $existing_key );
		update_option( 'pue_install_key_event_aggregator', $mock_key );
	}
}