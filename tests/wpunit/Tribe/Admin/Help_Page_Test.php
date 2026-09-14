<?php

namespace Tribe\Admin;

use Tribe__Admin__Help_Page as Help_Page;

class Help_Page_Test extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 */
	public function should_link_to_the_unfiltered_reviews_page(): void {
		$plugins = tribe( Help_Page::class )->get_plugins();

		$this->assertNotEmpty( $plugins );

		foreach ( $plugins as $slug => $plugin ) {
			$this->assertStringEndsWith( '/reviews/', $plugin['stars_url'], "{$slug} stars_url" );
			$this->assertStringNotContainsString( 'filter=', $plugin['stars_url'], "{$slug} stars_url" );
		}
	}
}
