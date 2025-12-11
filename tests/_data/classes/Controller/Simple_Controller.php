<?php

namespace TEC\Common\Tests\Controller;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

class Simple_Controller extends Controller_Contract {
	protected function do_register(): void {
		add_action( 'simple_controller_test_action', [ $this, 'on_simple_controller_test_action' ] );
		add_filter( 'simple_controller_test_filter', [ $this, 'on_simple_controller_test_filter' ] );
	}

	public function on_simple_controller_test_action() {
		// Do nothing.
	}

	public function on_simple_controller_test_filter() {
		// Do nothing.
	}

	public function unregister(): void {
		remove_action( 'simple_controller_test_action', [ $this, 'on_simple_controller_test_action' ] );
		remove_filter( 'simple_controller_test_filter', [ $this, 'on_simple_controller_test_filter' ] );
	}
}
