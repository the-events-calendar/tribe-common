<?php
namespace Tribe;

use Tribe__Dependency as Dependency;

include codecept_data_dir( 'classes/Dependency/Eventbrite.php' );
include codecept_data_dir( 'classes/Dependency/Filterbar.php' );
include codecept_data_dir( 'classes/Dependency/Pro.php' );

class DependencyTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @return Dependency
	 */
	protected function make_instance() {
		return new Dependency();
	}

	/**
	 * @test
	 */
	public function should_be_instantiable() {
		$this->assertInstanceOf( Dependency::class, $this->make_instance() );
	}

	public function validClassToPueProvider() {
		$data = [
			[
				'Tribe__Events__Pro__Main',
				[
					'pue_slug' => 'events-calendar-pro',
					'plugin_file' => WP_PLUGIN_DIR . '/events-calendar-pro/events-calendar-pro.php',
				],
				'Tribe__Events__Filterbar__View',
				[
					'pue_slug' => 'tribe-filterbar',
					'plugin_file' => WP_PLUGIN_DIR . '/the-events-calendar-filter-view/the-events-calendar-filter-view.php',
				],
				'Tribe__Events__Tickets__Eventbrite__Main',
				[
					'pue_slug' => 'tribe-eventbrite',
					'plugin_file' => WP_PLUGIN_DIR . '/tribe-eventbrite/tribe-eventbrite.php',
				],
			],
		];

		return $data;
	}

	/**
	 * @test
	 * @dataProvider validClassToPueProvider
	 */
	public function should_return_pue_checker_for_main_classes( $class_name, $expected ) {
		$dependency = $this->make_instance();

		$pue = $dependency->get_pue_from_class( $class_name );

		$this->assertInstanceOf( Tribe__PUE__Checker::class, $pue );

		$this->assertEquals( $pue->get_slug(), $expected['pue_slug'] );
		$this->assertEquals( $pue->get_plugin_file(), $expected['plugin_file'] );
	}

	public function invalidClassToPueProvider() {
		$data = [
			[
				'not_a_class_but_string',
			],
			[
				1,
			],
			[
				[],
			],
			[
				'stdClass',
			],
			[
				'Tribe__Events__PUE_Invalid',
			],
		];

		return $data;
	}

	/**
	 * @test
	 * @dataProvider invalidClassToPueProvider
	 */
	public function should_not_return_pue_check_for_invalid_classes( $class_name ) {
		$dependency = $this->make_instance();

		$pue = $dependency->get_pue_from_class( $class_name );

		$this->assertFalse( $pue );
	}

}