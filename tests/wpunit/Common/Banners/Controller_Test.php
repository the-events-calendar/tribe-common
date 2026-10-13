<?php

namespace TEC\Common\Banners;

use TEC\Common\Tests\Provider\Controller_Test_Case;

class Controller_Test extends Controller_Test_Case {

	protected $controller_class = Controller::class;

	/**
	 * @after
	 */
	public function reset_screen(): void {
		set_current_screen( 'front' );
	}

	/**
	 * @covers \TEC\Common\Banners\Controller::do_register
	 * @covers \TEC\Common\Banners\Controller::unregister
	 */
	public function test_it_hooks_the_banner_and_unhooks_it(): void {
		set_current_screen( 'dashboard' );

		$controller = $this->make_controller();
		$controller->register();
		$register          = $this->test_services->callback( Software_Packages_Banner::class, 'register' );
		$render_at_top     = $this->test_services->callback( Software_Packages_Banner::class, 'render_at_top' );
		$render_in_sidebar = $this->test_services->callback( Software_Packages_Banner::class, 'render_in_sidebar' );

		$this->assertSame( 10, has_action( 'init', $register ) );
		$this->assertSame( 10, has_action( 'admin_notices', $render_at_top ) );
		$this->assertSame( 10, has_action( 'tec_settings_sidebar_start', $render_in_sidebar ) );

		$controller->unregister();

		$this->assertFalse( has_action( 'init', $register ) );
		$this->assertFalse( has_action( 'admin_notices', $render_at_top ) );
		$this->assertFalse( has_action( 'tec_settings_sidebar_start', $render_in_sidebar ) );
	}
}
