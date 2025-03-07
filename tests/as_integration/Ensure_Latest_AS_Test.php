<?php

namespace Tribe\Test\AS_Integration;

use Codeception\TestCase\WPTestCase;
use ActionScheduler_Versions;

class Ensure_Latest_AS_Test extends WPTestCase {
	public function test_latest_as() {
		$current_version = '3.9.0';

		$as_versions = ActionScheduler_Versions::instance();
		$loaded_version = $as_versions->latest_version();

		$this->assertEquals( $current_version, $loaded_version );
	}
}
