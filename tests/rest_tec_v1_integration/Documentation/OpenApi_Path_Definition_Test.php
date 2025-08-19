<?php

namespace TEC\Common\Tests\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Path_Definition;
use Codeception\TestCase\WPTestCase;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class OpenApi_Path_Definition_Test extends WPTestCase {
	use SnapshotAssertions;

	/**
	 * Test the OpenApi_Path_Definition documentation output
	 */
	public function test_openapi_path_definition_json_snapshot() {
		$instance = new OpenApi_Path_Definition();
		$this->assertMatchesJsonSnapshot( wp_json_encode( $instance->get_documentation(), JSON_SNAPSHOT_OPTIONS ) );
	}
}