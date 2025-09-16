<?php

namespace Tribe\tests\eva_integration\Views\V2\Components\Icons;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\tests\eva_integration\Snapshot_Test_Case;

class VideoTest extends Snapshot_Test_Case {
	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'views/v2/components/icons/video.php';

	/**
	 * Test render without classes.
	 */
	public function test_render_without_classes() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes' => [] ] ) );
	}

	/**
	 * Test render with classes.
	 */
	public function test_render_with_classes() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes' => [ 'test-class-1', 'test-class-2' ] ] ) );
	}
}
