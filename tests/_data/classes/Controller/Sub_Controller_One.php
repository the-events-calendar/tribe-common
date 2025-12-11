<?php

namespace TEC\Common\Tests\Controller;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

class Sub_Controller_One extends Controller_Contract {
	protected function do_register(): void {
		add_action( 'sub_controller_one_action', [ $this, 'on_sub_controller_one_action' ] );
		add_filter( 'sub_controller_one_filter', [ $this, 'on_sub_controller_one_filter' ] );
	}

	public function on_sub_controller_one_action() {
		// Do nothing.
	}

	public function on_sub_controller_one_filter() {
		// Do nothing.
	}

	public function unregister(): void {
		remove_action( 'sub_controller_one_action', [ $this, 'on_sub_controller_one_action' ] );
		remove_filter( 'sub_controller_one_filter', [ $this, 'on_sub_controller_one_filter' ] );
	}
}
