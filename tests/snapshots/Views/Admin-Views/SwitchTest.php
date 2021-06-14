<?php

namespace Tribe\Tests\Snapshots\Views\V2\Admin_Views\Components;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Snapshots\Snapshot_Test_Case;

class SwitchTest extends Snapshot_Test_Case {
	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'components/switch.php';

	/**
	 * Test render without classes.
	 */
	public function test_render_without_classes() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes_wrap' => [] ] ) );
	}

	/**
	 * Test render with classes.
	 */
	public function test_render_with_classes() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes_wrap' => [ 'test-class-1', 'test-class-2' ] ] ) );
	}
}
