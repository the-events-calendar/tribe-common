<?php
/**
 * The base class all Controllers should extend.
 *
 * @since 5.0.17
 * @since 5.1.0 moved.
 *
 * @package TEC\Common\Contracts\Provider;
 */

namespace TEC\Common\Contracts\Provider;

use TEC\Common\Contracts\Service_Provider;
use Tribe__Log as Log;
use TEC\Common\Contracts\Container;

/**
 * Class Controller.
 *
 * @since 5.0.17
 *
 * @package TEC\Common\Provider;
 *
 * @property Container $container
 */
abstract class Controller extends Service_Provider {
	/**
	 * Registers the filters and actions hooks added by the controller if the controller has not registered yet.
	 *
	 * @since 5.0.17
	 *
	 * @return void
	 */
	public function register() {
		/*
		 * Look up and set the value in the container request cache to allow building the same Controller
		 * with a **different** container. (e.g. in tests).
		 */
		if ( static::is_registered() ) {
			return;
		}

		if ( ! $this->is_active() ) {
			return;
		}

		$this->container->setVar( static::class . '_registered', true );

		$this->do_register();
	}

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since 5.0.17
	 *
	 * @return void
	 */
	abstract protected function do_register(): void;

	/**
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * Bound implementations should not be removed in this method!
	 *
	 * @since 5.0.17
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	abstract public function unregister(): void;

	/**
	 * Whether the controller is active or not.
	 *
	 * Controllers will be active by default, if that is not the case, the controller should override this method.
	 *
	 * @since 5.0.17
	 *
	 * @return bool Whether the controller is active or not.
	 */
	public function is_active(): bool {
		return true;
	}

	/**
	 * Logs a message at the `debug` level.
	 *
	 * @since 5.0.17
	 *
	 * @param string $message The message to log.
	 * @param array  $context An array of context to log with the message.
	 *
	 * @return void The message is logged.
	 */
	protected function debug( string $message, array $context = [] ): void {
		do_action(
			'tribe_log',
			Log::DEBUG,
			$message,
			array_merge(
				[
					'controller' => static::class,
				],
				$context
			)
		);
	}

	/**
	 * Logs a message at the `warning` level.
	 *
	 * @since 5.0.17
	 *
	 * @param string $message The message to log.
	 * @param array  $context An array of context to log with the message.
	 *
	 * @return void The message is logged.
	 */
	protected function warning( string $message, array $context = [] ): void {
		do_action(
			'tribe_log',
			Log::WARNING,
			$message,
			array_merge(
				[
					'controller' => static::class,
				],
				$context
			)
		);
	}

	/**
	 * Logs a message at the `error` level.
	 *
	 * @since 5.0.17
	 *
	 * @param string $message The message to log.
	 * @param array  $context An array of context to log with the message.
	 *
	 * @return void The message is logged.
	 */
	protected function error( string $message, array $context = [] ): void {
		do_action(
			'tribe_log',
			Log::ERROR,
			$message,
			array_merge(
				[
					'controller' => static::class,
				],
				$context
			)
		);
	}

	/**
	 * Returns whether any instance of this controller has been registered or not.
	 *
	 * @since 5.0.17
	 *
	 * @return bool Whether any instance of this controller has been registered or not.
	 */
	public static function is_registered(): bool {
		return (bool) tribe()->getVar( static::class . '_registered' );
	}
}
