<?php
namespace TEC\Common\Fields\Field;

class HTML_Test extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 */
	public function it_should_fail_if_no_content_or_html_in_args() {
		$args =  [
			'type'   => 'html',
		];

		$valid = HTML::normalize_content( $args );

		// normalize_content() logs an error and returns null on a failure.
		$this->assertNull( $valid );
	}

	/**
	 * @test
	 */
	public function it_should_fail_if_both_content_and_html_in_args() {
		$args =  [
			'type'    => 'html',
			'html'    => '<span class="test-span>Test</span>',
			'content' => '<span class="test-span>Test</span>',
		];

		$valid = HTML::normalize_content( $args );

		// normalize_content() logs an error and returns null on a failure.
		$this->assertNull( $valid );
	}

	/**
	 * @test
	 */
	public function it_should_pass_if_given_content_only() {
		$args =  [
			'type'    => 'html',
			'content' => '<span class="test-span>Test</span>',
		];

		$valid = HTML::normalize_content( $args );

		$this->assertNotNull( $valid );

		$this->assertEquals( $args['content'], $valid );
	}

	/**
	 * @test
	 */
	public function it_should_pass_if_given_html_only() {
		$args =  [
			'type'   => 'html',
			'html'   => '<span class="test-span>Test</span>',
		];

		$valid = HTML::normalize_content( $args );

		$this->assertNotNull( $valid );

		$this->assertEquals( $args['html'], $valid );
	}

	/**
	 * @test
	 */
	public function it_should_render_given_content() {
		$args =  [
			'type'    => 'html',
			'content' => '<span class="test-span>Test</span>',
		];

		$field = new HTML( 'test', $args );

		$html = $field->render( false );

		$this->assertEquals( $args['content'], $html );
	}
}
