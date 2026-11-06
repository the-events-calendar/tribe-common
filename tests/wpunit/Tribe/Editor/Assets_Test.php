<?php

namespace Tribe\Editor;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Editor\Canvas_Styles;
use TEC\Common\StellarWP\Assets\Assets;

class Assets_Test extends WPTestCase {
	/**
	 * @test
	 */
	public function it_should_put_the_shared_editor_styles_in_the_canvas_group(): void {
		$this->assertContains(
			Canvas_Styles::GROUP,
			Assets::init()->get( 'tribe-common-gutenberg-main-styles' )->get_groups()
		);
	}
}
