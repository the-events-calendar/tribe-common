<?php

namespace TEC\Common\Tests\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Documentation\Date_Details_Definition;
use Codeception\TestCase\WPTestCase;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class Date_Details_Definition_Test extends WPTestCase {
	use SnapshotAssertions;

	/**
	 * Test the Date_Details_Definition documentation output
	 */
	public function test_date_details_definition_json_snapshot() {
		$instance = new Date_Details_Definition();
		$this->assertMatchesJsonSnapshot( wp_json_encode( $instance->get_documentation(), JSON_SNAPSHOT_OPTIONS ) );
	}
}