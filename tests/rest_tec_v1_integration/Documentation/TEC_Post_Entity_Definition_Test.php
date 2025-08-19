<?php

namespace TEC\Common\Tests\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Documentation\TEC_Post_Entity_Definition;
use Codeception\TestCase\WPTestCase;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class TEC_Post_Entity_Definition_Test extends WPTestCase {
	use SnapshotAssertions;

	/**
	 * Test the TEC_Post_Entity_Definition documentation output
	 */
	public function test_tec_post_entity_definition_json_snapshot() {
		$instance = new TEC_Post_Entity_Definition();
		$this->assertMatchesJsonSnapshot( wp_json_encode( $instance->get_documentation(), JSON_SNAPSHOT_OPTIONS ) );
	}
}