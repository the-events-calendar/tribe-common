<?php

namespace Tribe\tests\eva_integration\Views\Admin_Views;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\tests\eva_integration\Snapshot_Test_Case;

class LoaderTest extends Snapshot_Test_Case {

	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'admin-views/components/loader.php';

	/**
	 * @test
	 */
	public function should_render_loader() {
		$this->assertMatchesHtmlSnapshot( $this->render( [] ) );
	}

	/**
	 * @test
	 */
	public function should_render_loader_with_custom_text() {
		$context = [
			'text'            => 'loading',
		];
		$this->assertMatchesHtmlSnapshot( $this->render( $context ) );
	}
}
