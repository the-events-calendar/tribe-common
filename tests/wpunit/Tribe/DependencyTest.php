<?php
namespace Tribe;

use org\bovigo\vfs\vfsStream;
use Tribe__Dependency as Dependency;
use Tribe__PUE__Checker;

include codecept_data_dir( 'classes/Dependency/Eventbrite.php' );
include codecept_data_dir( 'classes/Dependency/Filterbar.php' );
include codecept_data_dir( 'classes/Dependency/Pro.php' );

class DependencyTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * A mock, virtual, plugin filesystem.
	 * @var \org\bovigo\vfs\vfsStreamDirectory
	 */
	protected $mock_fs;

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
					'plugin_file' => '/events-calendar-pro/events-calendar-pro.php',
				],
				'Tribe__Events__Filterbar__View',
				[
					'pue_slug' => 'tribe-filterbar',
					'plugin_file' => '/the-events-calendar-filter-view/the-events-calendar-filter-view.php',
				],
				'Tribe__Events__Tickets__Eventbrite__Main',
				[
					'pue_slug' => 'tribe-eventbrite',
					'plugin_file' => '/tribe-eventbrite/tribe-eventbrite.php',
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
		$this->assertContains( $pue->get_plugin_file(), $expected['plugin_file'] );
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

	public function plugin_data_set() {
		$this->mock_fs = vfsStream::setup( 'fs', 0777, [
			'one' => [
				'plugin.php' => '<?php /** Plugin Name: One */'
			],
			'two' => [
				'plugin.php' => '<?php /** Plugin Name: Two */'
			],
			'three' => [
				'plugin.php' => '<?php /** Plugin Name: Three */'
			],
			'four' => [
				'plugin.php' => '<?php /** Plugin Name: Four */'
			],
		] );

		$one = [
			'file_path'    => $this->mock_fs->url() . '/one/plugin.php',
			'main_class'   => 'Tribe_One',
			'version'      => '2.0.0',
			'classes_req'  => [],
			'dependencies' => [
				'addon-dependencies' => [
					'Tribe_Two' => '2.0.0',
					'Tribe_Three' => '2.0.0',
					'Tribe_Four' => '2.0.0',
				],
			],
		];

		$two = [
			'file_path'    => $this->mock_fs->url() . '/two/plugin.php',
			'main_class'   => 'Tribe_Two',
			'version'      => '2.0.0',
			'classes_req'  => [],
			'dependencies' => [
				'parent-dependencies' => [
					'Tribe_One' => '2.0.0',
				],
			],
		];

		$three = [
			'file_path'    => $this->mock_fs->url() . '/three/plugin.php',
			'main_class'   => 'Tribe_Three',
			'version'      => '2.0.0',
			'classes_req'  => [],
			'dependencies' => [
				'parent-dependencies' => [
					'Tribe_One' => '2.0.0',
				],
				'addon-dependencies' => [
					'Tribe_Two' => '2.0.0',
				],
			],
		];

		$four = [
			'file_path'    => $this->mock_fs->url() . '/four/plugin.php',
			'main_class'   => 'Tribe_Four',
			'version'      => '2.0.0',
			'classes_req'  => [],
			'dependencies' => [
				'parent-dependencies' => [
					'Tribe_One' => '2.0.0',
				],
			],
		];

		yield 'All deps ok' => [
			[
				'one'   => array_merge( $one, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin One should activate; Two and Three add-ons have the correct versions.',
				] ),
				'two'   => array_merge( $two, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin Two depends on One and it should activate; One has the correct ' .
					                       'version and Two has the correct version.',
				] ),
				'three' => array_merge( $three, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin Three depends on One and Two and  it should activate; One has the ' .
					                       'correct version and Two has the correct version.',
				] ),
			],
		];

		yield 'Two version too low' => [
			[
				'one'   => array_merge( $one, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin One should activate, its requirement will prevent Two from' .
					                       'activating and, as a consequence, Three will not activate.',
				] ),
				'two'   => array_merge( $two, [
					'version' => '1.0.0',
					'should_initialize' => false,
					'failure_message'   => 'Plugin Two should not activate: its version is too low.',
				] ),
				'three' => array_merge( $three, [
					'should_initialize' => false,
					'failure_message'   => 'Plugin Three should not activate: it depends on Two that is not activating.',
				] ),
			],
		];

		yield 'Three version too low' => [
			[
				'one'   => array_merge( $one, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin One should activate: its requirement will prevent Three from' .
					                       'activating.',
				] ),
				'two'   => array_merge( $two, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin Two should activate: its version is the one required by One.',
				] ),
				'three' => array_merge( $three, [
					'version' => '1.0.0',
					'should_initialize' => false,
					'failure_message'   => 'Plugin Three should not activate: its version is too low.',
				] ),
			],
		];

		yield 'Four version too low' => [
			[
				'one'   => array_merge( $one, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin One should activate: its requirement will prevent Three from' .
					                       'activating.',
				] ),
				'two'   => array_merge( $two, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin Two should activate: its version is the one required by One.',
				] ),
				'three'   => array_merge( $three, [
					'should_initialize' => true,
					'failure_message'   => 'Plugin Three should activate: its version is the one required by One.',
				] ),
				'four' => array_merge( $four, [
					'version' => '1.0.0',
					'should_initialize' => false,
					'failure_message'   => 'Plugin Four should not activate: its version is too low.',
				] ),
			],
		];
	}

	/**
	 * It should activate other plugins if one is not fulfilling dependencies
	 *
	 * @test
	 * @dataProvider plugin_data_set
	 */
	public function should_activate_other_plugins_if_one_is_not_fulfilling_dependencies( array $mock_plugins ) {
		$dependency   = new \Tribe__Dependency();

		foreach ( $mock_plugins as $mock_plugin ) {
			$dependency->register_plugin(
				$mock_plugin['file_path'],
				$mock_plugin['main_class'],
				$mock_plugin['version'],
				$mock_plugin['classes_req'],
				$mock_plugin['dependencies']
			);
		}

		foreach ( $mock_plugins as $mock_plugin ) {
			$this->assertEquals(
				$mock_plugin['should_initialize'],
				$dependency->check_plugin( $mock_plugin['main_class'] ),
				$mock_plugin['failure_message']
			);
		}
	}
}
