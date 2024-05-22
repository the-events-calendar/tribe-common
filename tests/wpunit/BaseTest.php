<?php

use TEC\Event_Automator\Tests\Support\Plugins;

class BaseTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should activate plugins correctly
	 *
	 * @test
	 */
	public function should_activate_plugins_correctly() {
		$this->assertTrue( is_plugin_active( Plugins::TEC_PLUGIN_PATH ) );
		$this->assertTrue( is_plugin_active( Plugins::AUTOMATOR_PLUGIN_PATH ) );
	}
}
