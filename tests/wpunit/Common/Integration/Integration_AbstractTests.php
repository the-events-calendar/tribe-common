<?php

namespace TEC\Common\Integration;


use TEC\Common\Integrations\Dummy_Module;
use TEC\Common\Integrations\Dummy_Plugin;
use TEC\Common\Integrations\Dummy_Server;
use TEC\Common\Integrations\Dummy_Theme;

/**
 * Class Integration_AbstractTests
 *
 * @since 5.1.3
 *
 * @package TEC\Common\Integration
 */
class Integration_AbstractTests {
	public static function setUpBeforeClass() {
		tribe_register_provider( Dummy_Plugin::class );
		tribe_register_provider( Dummy_Theme::class );
		tribe_register_provider( Dummy_Module::class );
		tribe_register_provider( Dummy_Server::class );
	}

	public function get_integration_instances() {
		yield 'plugin' => [
			tribe( Dummy_Plugin::class ),
			'plugin',
			'dummy-plugin',
			'dummy-parent',
			true
		];

		yield 'theme' => [
			tribe( Dummy_Theme::class ),
			'theme',
			'dummy-theme',
			'dummy-parent',
			false,
		];

		yield 'module' => [
			tribe( Dummy_Module::class ),
			'module',
			'dummy-module',
			'dummy-parent',
			false
		];

		yield 'server' => [
			tribe( Dummy_Server::class ),
			'server',
			'dummy-server',
			'dummy-parent',
			true
		];
	}

	/**
	 * @test
	 * @dataProvider get_integration_instances
	 */
	public function integrations_should_load_as_intended( $integration, $type, $slug, $parent, $was_loaded ) {
		$this->assertEquals( $integration->get_type(), $type, 'Integration type does not match' );
		$this->assertEquals( $integration->get_slug(), $slug, 'Integration slug does not match' );
		$this->assertEquals( $integration->get_parent(), $parent, 'Integration parent does not match' );
		$this->assertEquals( $integration->tests_was_loaded, $was_loaded, 'Integration load status does not match' );
	}
}
