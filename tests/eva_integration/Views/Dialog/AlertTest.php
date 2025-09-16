<?php

namespace Tribe\tests\eva_integration\Views\Dialog;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\tests\eva_integration\Snapshot_Test_Case;

class AlertTest extends Snapshot_Test_Case {
	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = '/views/dialog/alert.php';

	public function test_w_title() {
		$html = $this->render( [
			'title'             => 'A test alert',
			'title_classes'     => 'test-alert__title',
			'id'                => 'test-alert',
			'button_display'    => true,
			'button_attributes' => [],
			'button_classes'    => 'test-alert__button test-alert__button-red',
			'button_text'       => 'Understood',
			'button_display'    => true,
			'button_attributes' => [],
			'content_classes'   => 'test-alert__content',
			'content'           => '<p>Lorem alert</p>',
			'alert_button_text' => 'Alert',
		] );

		$this->assertMatchesHtmlSnapshot( $html );
	}

	public function test_wo_title() {
		$html = $this->render( [
			'id'                => 'test-alert',
			'button_classes'    => 'test-alert__button test-alert__button-red',
			'button_text'       => 'Understood',
			'button_display'    => true,
			'button_attributes' => [],
			'content_classes'   => 'test-alert__content',
			'button_display'    => true,
			'button_attributes' => [],
			'content'           => '<p>Lorem alert</p>',
			'alert_button_text' => 'Alert',
		] );

		$this->assertMatchesHtmlSnapshot( $html );
	}
}
