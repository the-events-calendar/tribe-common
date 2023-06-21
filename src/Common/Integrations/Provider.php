<?php
/**
 * Handles Integrations.
 *
 * @since   5.1.1
 *
 * @package TEC\Common\Integrations
 */
namespace TEC\Common\Integrations;

/**
 * Class Provider.
 *
 * @since   5.1.1
 *
 * @package TEC\Common\Integrations
 */
class Provider extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.1.1
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
	}
}
