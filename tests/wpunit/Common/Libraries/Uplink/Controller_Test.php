<?php

namespace TEC\Common\Libraries\Uplink;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use TEC\Common\StellarWP\Uplink\Register;
use Tribe__Main;

class Controller_Test extends \Codeception\TestCase\WPTestCase {
	use SnapshotAssertions;

	/**
	 * @before
	 */
	public function register_uplink() {
		tribe( Controller::class )->register_uplink();
		$this->register_plugin();
	}

	public function register_plugin() {
		Register::plugin(
			'common-test-slug',
			'common-test',
			'1.0.0',
			dirname( __FILE__ ),
			tribe( Tribe__Main::class )
		);
	}

	/**
	 * @test
	 */
	public function it_should_setup_license_fields() {
		$fields         = [
			'tribe-form-content-start' => [],
		];
		$license_fields = tribe( Controller::class )->register_license_fields( $fields );
		$this->assertMatchesHtmlSnapshot( $license_fields );
	}
}
