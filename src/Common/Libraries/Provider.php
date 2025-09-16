<?php

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Service_Provider;
use TEC\Common\StellarWP\Assets;
use TEC\Common\StellarWP\DB;
use TEC\Common\StellarWP\Schema;
use Tribe__Main as Common;

/**
 * Provider for the Common plugin.
 *
 * This class is used to register the libraries for the Common plugin.
 *
 * @since 5.0.10
 *
 * @package TEC\Common\Libraries
 */
class Provider extends Service_Provider {

	/**
	 * Hook prefix.
	 *
	 * @since 5.0.10
	 *
	 * @var string
	 */
	protected static $hook_prefix = 'tec';

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.0.10
	 */
	public function register() {
		$this->container->singleton( static::class, $this );

		tribe_register_provider( Installer\Provider::class );
		tribe_register_provider( Uplink_Controller::class );

		DB\Config::setHookPrefix( $this->get_hook_prefix() );
		Assets\Config::set_hook_prefix( $this->get_hook_prefix() );
		Assets\Config::set_path( Common::instance()->plugin_path . 'src/resources/' );
		Assets\Config::set_version( Common::VERSION );
		Assets\Config::set_relative_asset_path( 'src/resources/' );
		Schema\Config::set_db( DB\DB::class );
		Schema\Config::set_container( tribe() );

		$this->container->register( Shepherd::class );
	}

	/**
	 * Gets the hook prefix.
	 *
	 * @since 5.0.10
	 *
	 * @return string
	 */
	public function get_hook_prefix(): string {
		return static::$hook_prefix;
	}
}
