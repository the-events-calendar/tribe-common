<?php

namespace TEC\Common\Action_Scheduler;

class Provider extends \tad_DI52_ServiceProvider {

	public function register() {
		$this->container->singleton( Provider::class );

		$this->hooks();
	}

	protected function hooks() {
		add_action( 'init', [ $this, 'plugins_loaded' ] );
	}

	public function plugins_loaded() {
		require_once \Tribe__Main::instance()->plugin_path . 'vendor/woocommerce/action-scheduler/action-scheduler.php';
	}
}