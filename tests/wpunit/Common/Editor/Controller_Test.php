<?php

namespace TEC\Common\Editor;

use TEC\Common\Tests\Provider\Controller_Test_Case;

class Controller_Test extends Controller_Test_Case {
	protected $controller_class = Controller::class;

	/**
	 * @test
	 */
	public function it_should_hook_the_canvas_styles_late_on_enqueue_block_assets_and_unhook_them(): void {
		$controller = $this->make_controller();
		$callback   = $this->test_services->callback( Canvas_Styles::class, 'enqueue' );

		$controller->register();

		$this->assertSame( 1000, has_action( 'enqueue_block_assets', $callback ) );

		$controller->unregister();

		$this->assertFalse( has_action( 'enqueue_block_assets', $callback ) );
	}
}
