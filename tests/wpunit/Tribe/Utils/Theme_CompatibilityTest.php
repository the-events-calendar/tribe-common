<?php

namespace Tribe\Utils;

use Codeception\TestCase\WPTestCase;
use Tribe\Utils\Theme_Compatibility;

class Theme_CompatibilityTest extends WPTestCase {

	public function it_should_detect_the_current_theme() {
		$theme = Theme_Compatibility::get_current_theme();

		$this->assertEquals( get_stylesheet(), $theme );
	}
}
