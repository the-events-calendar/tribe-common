<?php

namespace Tribe\tests\eva_integration\Views\V2\Components\Icons;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\tests\eva_integration\Snapshot_Test_Case;

class FeaturedTest extends Snapshot_Test_Case {
	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'views/v2/components/icons/featured.php';

	/**
	 * Test render without classes or label.
	 */
	public function test_render_without_classes_or_label() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes' => [] ] ) );
	}

	/**
	 * Test render with classes, no label.
	 */
	public function test_render_with_classes_no_label() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes' => [ 'test-class-1', 'test-class-2' ] ] ) );
	}

	/**
	 * Test render with label, no classes.
	 */
	public function test_render_with_label_no_classes() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'icon_label' => 'Noted Event' ] ) );
	}

	/**
	 * Test render with classes and label.
	 */
	public function test_render_with_classes_and_label() {
		$this->assertMatchesHtmlSnapshot( $this->render( [ 'classes' => [ 'test-class-1', 'test-class-2' ], 'icon_label' => 'Noted Event' ] ) );
	}
}
