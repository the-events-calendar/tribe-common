<?php

namespace TEC\Common\Libraries;

use TEC\Common\StellarWP\DB;
use TEC\Common\Contracts\Service_Provider;


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

		DB\Config::setHookPrefix( $this->get_hook_prefix() );
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
