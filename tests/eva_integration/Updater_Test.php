<?php

namespace Tribe\tests\eva_integration;


use TEC\Event_Automator\Updater;

class Updater_Test extends \Codeception\TestCase\WPTestCase {

	public function license_key_values() {
		return [
			'has-license'           => [ '9999261eb16f7ddf4fe24b69918c8804c82ea817', '9999261eb16f7ddf4fe24b69918c8804c82ea817' ],
			'no-license'            => [ '', '5f53261eb16f7ddf4fe24b69918c8804c82ea817' ],
			'invalid-license-true'  => [ true, true ],
			'invalid-license-false' => [ false, '5f53261eb16f7ddf4fe24b69918c8804c82ea817' ],
		];
	}

	/**
	 * @test
	 * @dataProvider license_key_values
	 */
	public function should_delete_pue_license_when_empty( $license, $result ) {
		update_option( 'pue_install_key_event_automator', $license );

		$updater = new Updater( '1.2' );
		$updater->update_pue_license_key();
		$pue_license_key = get_option( 'pue_install_key_event_automator' );

		$this->assertEquals( $pue_license_key, $result );
	}
}
