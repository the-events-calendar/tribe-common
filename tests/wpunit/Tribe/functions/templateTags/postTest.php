<?php

namespace Tribe\functions\templateTags;

class postTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 */
	public function it_does_not_run_apply_filters_when_doing_filter_and_create_nested_level_error() {
		// Remove autop to make it easier to match content.
		remove_filter( 'the_content', 'wpautop' );

		// Set Test Content, Create an Event, and Expected Content
		$content  = 'Test Content';
		$post_id  = $this->factory->post->create( [ 'post_content' => 'Event Content' ] );
		$expected = $content . 'Event ContentFILTERING';

		// Run tribe_get_the_content inside of the_content filter call.
		add_filter( 'the_content', static function ( $content ) use ( $post_id ) {

			return $content . tribe_get_the_content( null, false, $post_id ) . 'FILTERING';
		} );

		$content = apply_filters( 'the_content', $content );

		$this->assertEquals( $expected, $content );
	}

	/**
	 * @test
	 */
	public function it_runs_apply_filters() {
		// Remove autop to make it easier to match content.
		remove_filter( 'the_content', 'wpautop' );

		// Set Test Content, Create an Event, and Expected Content
		$content  = 'Test Content';
		$post_id  = $this->factory->post->create( [ 'post_content' => 'Event Content' ] );
		$expected = $content . 'Event ContentFILTERING';

		$content = $content . tribe_get_the_content( null, false, $post_id ) . 'FILTERING';

		$this->assertEquals( $expected, $content );
	}
}