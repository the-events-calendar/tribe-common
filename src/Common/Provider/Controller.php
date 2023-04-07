<?php
/**
 * The base clas all Controllers should extend.
 *
 * @since   TBD
 *
 * @package TEC\Common\Provider;
 */

namespace TEC\Common\Provider;

use tad_DI52_ServiceProvider as Service_Provider;
use TEC\Common\StellarWP\ContainerContract\ContainerInterface;
use Tribe__Log as Log;

/**
 * Class Controller.
 *
 * @since   TBD
 *
 * @package TEC\Common\Provider;
 *
 * @property ContainerInterface $container
 */
abstract class Controller extends Service_Provider {
	/**
	 * Registers the filters and actions hooks added by the controller if the controller has not registered yet.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register() {
		/*
		 * Look up and set the value in the container request cache to allow building the same Controller
		 * with a **different** container. (e.g. in tests).
		 */
		if ( $this->container->getVar( static::class . '_registered' ) ) {
			return;
		}

		if ( ! $this->is_active() ) {
			return;
		}

		$this->container->setVar( static::class . '_registered', true );

		// Register the controller as a singleton.j
		$this->container->singleton( self::class, self::class );

		$this->do_register();
	}

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	abstract protected function do_register(): void;

	/**
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * Bound implementations should not be removed in this method!
	 *
	 * @since TBD
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	abstract public function unregister(): void;

	/**
	 * Whether the controller is active or not.
	 *
	 * Controllers will be active by default, if that is not the case, the controller should override this method.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the controller is active or not.
	 */
	public function is_active(): bool {
		return true;
	}

	/**
	 * Logs a message at the `debug` level.
	 *
	 * @since TBD
	 *
	 * @param string $message The message to log.
	 *
	 * @return void The message is logged.
	 */
	protected function debug( string $message ): void {
		do_action( 'tribe_log', Log::DEBUG, $message, [
			'message' => $message,
			'context' => static::class,
		] );
	}

	/**
	 * Logs a message at the `warning` level.
	 *
	 * @since TBD
	 *
	 * @param string $message The message to log.
	 *
	 * @return void The message is logged.
	 */
	protected function warning( string $message ): void {
		do_action( 'tribe_log', Log::WARNING, $message, [
			'message' => $message,
			'context' => static::class,
		] );
	}

	/**
	 * Logs a message at the `error` level.
	 *
	 * @since TBD
	 *
	 * @param string $message The message to log.
	 *
	 * @return void The message is logged.
	 */
	protected function error( string $message ): void {
		do_action( 'tribe_log', Log::ERROR, $message, [
			'message' => $message,
			'context' => static::class,
		] );
	}
}