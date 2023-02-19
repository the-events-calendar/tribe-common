<?php

namespace TEC\Common\Libraries\Pigeon;

use StellarWP\Pigeon\Config;
use StellarWP\Pigeon\Pigeon;

class Provider extends \tad_DI52_ServiceProvider {

	protected $pigeon;

	public function register() {
		$this->container->singleton( Provider::class );

		$this->hooks();
	}

	protected function hooks() {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	public function plugins_loaded() {
		Config::set_hook_prefix('tec');
		$this->container->make( Pigeon::class )->init( new Container( $this->container ) );
	}
}