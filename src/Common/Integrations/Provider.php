<?php
/**
 * Handles Integrations.
 *
 * @since   TBD
 *
 * @package TEC\Common\Integrations
 */
namespace TEC\Common\Integrations;

/**
 * Class Provider.
 *
 * @since   TBD
 *
 * @package TEC\Common\Integrations
 */
class Provider extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
	}
}
