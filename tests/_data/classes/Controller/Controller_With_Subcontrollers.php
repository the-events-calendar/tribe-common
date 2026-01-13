<?php

namespace TEC\Common\Tests\Controller;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

class Controller_With_Subcontrollers extends Controller_Contract {
	protected function do_register(): void {
		add_action( 'main_controller_action', [ $this, 'on_main_controller_action' ] );
		add_filter( 'main_controller_filter', [ $this, 'on_main_controller_filter' ] );

		$this->container->register(Sub_Controller_One::class);
		$this->container->register(Sub_Controller_Two::class);
	}

	public function on_main_controller_action() {
		// Do nothing.
	}

	public function on_main_controller_filter() {
		// Do nothing.
	}

	public function unregister(): void {
		remove_action( 'main_controller_action', [ $this, 'on_main_controller_action' ] );
		remove_filter( 'main_controller_filter', [ $this, 'on_main_controller_filter' ] );

		$this->container->get(Sub_Controller_One::class)->unregister();
		$this->container->get(Sub_Controller_Two::class)->unregister();
	}
}
