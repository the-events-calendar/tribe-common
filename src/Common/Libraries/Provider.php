<?php

namespace TEC\Common\Libraries;

use TEC\Common\StellarWP\DB;

class Provider extends \tad_DI52_ServiceProvider {
	/**
	 * Hook prefix.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static $hook_prefix = 'tec';

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		$this->container->singleton( static::class, $this );

		tribe_register_provider( Installer\Provider::class );

		DB\Config::setHookPrefix( $this->get_hook_prefix() );
	}

	/**
	 * Gets the hook prefix.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_hook_prefix(): string {
		return static::$hook_prefix;
	}
}