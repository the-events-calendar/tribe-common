<?php

namespace Tribe\Service_Providers;

use Codeception\TestCase\WPTestCase;
use Tribe__Service_Providers__Debug_Bar;
use TypeError;

/**
 * `debug_bar_panels` belongs to the Debug Bar plugin, so the value that reaches our
 * callback is whatever the callbacks ahead of ours returned. A declaration narrower
 * than that turns another plugin's sloppy value into an uncaught TypeError.
 *
 * @see https://linear.app/nexcess/issue/SMTNC-2761
 */
class Debug_Bar_Test extends WPTestCase {

	/**
	 * Our panels extend Debug_Bar_Panel, which only exists when the Debug Bar plugin is
	 * active. The suite does not install it — and the filter under test never fires without
	 * it — so a stand-in stands in for the base class.
	 *
	 * @before
	 */
	public function stub_debug_bar_panel_base_class(): void {
		if ( class_exists( 'Debug_Bar_Panel' ) ) {
			return;
		}

		eval( 'class Debug_Bar_Panel { public function __construct( $title = "" ) {} }' ); // phpcs:ignore Squiz.PHP.Eval.Discouraged
	}

	/**
	 * @test
	 */
	public function should_not_fatal_when_the_panel_list_is_not_an_array(): void {
		$provider = new Tribe__Service_Providers__Debug_Bar( tribe() );

		try {
			$panels = $provider->add_panels( null );
		} catch ( TypeError $e ) {
			$this->fail( 'add_panels() rejected a value debug_bar_panels can deliver: ' . $e->getMessage() );
		}

		$this->assertIsArray( $panels );
	}
}
