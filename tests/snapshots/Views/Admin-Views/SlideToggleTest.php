<?php

namespace Tribe\Tests\Snapshots\Views\V2\Admin_Views\Components;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Snapshots\Snapshot_Test_Case;

class SlideToggleTest extends Snapshot_Test_Case {

	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'admin-views/components/slide-toggle.php';

	/**
	 * @test
	 */
	public function should_display_closed_with_no_added_classes() {
		$context = [
			'label_id'       => 'toggle-test',
			'label'          => 'Toggle Default',
			'classes_wrap'   => [],
			'classes_button' => [],
			'classes_panel'  => [],
			'panel_id'       => 'toggle-test-panel',
			'panel'          => 'Test Content',
			'expanded'       => false,
		];
		$this->assertMatchesHtmlSnapshot( $this->render( $context ) );
	}

	/**
	 * @test
	 */
	public function should_display_closed_with_added_classes() {
		$context = [
			'label_id'       => 'toggle-test',
			'label'          => 'Toggle Default',
			'classes_wrap'   => [ 'toggle-wrap' ],
			'classes_button' => [ 'toggle-button' ],
			'classes_panel'  => [ 'toggle-panel' ],
			'panel_id'       => 'toggle-test-panel',
			'panel'          => 'Test Content',
			'expanded'       => false,
		];
		$this->assertMatchesHtmlSnapshot( $this->render( $context ) );
	}

	/**
	 * @test
	 */
	public function should_display_open_with_add_classes() {
		$context = [
			'label_id'       => 'toggle-test',
			'label'          => 'Toggle Default',
			'classes_wrap'   => [ 'toggle-wrap' ],
			'classes_button' => [ 'toggle-button' ],
			'classes_panel'  => [ 'toggle-panel' ],
			'panel_id'       => 'toggle-test-panel',
			'panel'          => 'Test Content',
			'expanded'       => true,
		];
		$this->assertMatchesHtmlSnapshot( $this->render( $context ) );
	}
}
