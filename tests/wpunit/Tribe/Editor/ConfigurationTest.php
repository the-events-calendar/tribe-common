<?php

namespace Tribe\Editor;

use Tribe__Editor__Configuration as Configuration;

class ConfigurationTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 */
	public function should_localize_the_labels_of_the_current_post_type(): void {
		$post_id         = static::factory()->post->create( [ 'post_type' => 'page' ] );
		$GLOBALS['post'] = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Sets up the loop for the test.
		setup_postdata( $GLOBALS['post'] );

		$config = ( new Configuration() )->localize();

		$this->assertSame( 'page', $config['post']['type'] );
		$this->assertEquals( get_post_type_object( 'page' )->labels, $config['post']['labels'] );
		$this->assertSame( 'Pages', $config['post']['labels']->name );

		wp_reset_postdata();
	}

	/**
	 * @test
	 */
	public function should_localize_empty_labels_without_a_post_type(): void {
		unset( $GLOBALS['post'] );

		$config = ( new Configuration() )->localize();

		$this->assertSame( [], $config['post']['labels'] );
	}
}
