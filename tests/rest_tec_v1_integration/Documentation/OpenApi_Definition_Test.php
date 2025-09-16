<?php

namespace TEC\Common\Tests\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Definition;
use Codeception\TestCase\WPTestCase;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class OpenApi_Definition_Test extends WPTestCase {
	use SnapshotAssertions;

	/**
	 * Test the OpenApi_Definition documentation output
	 */
	public function test_openapi_definition_json_snapshot() {
		$instance = new OpenApi_Definition();
		$this->assertMatchesJsonSnapshot( wp_json_encode( $instance->get_documentation(), JSON_SNAPSHOT_OPTIONS ) );
	}
}