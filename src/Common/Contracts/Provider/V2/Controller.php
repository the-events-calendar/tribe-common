<?php
/**
 * The base class all Controllers should extend.
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts\Provider;
 */

namespace TEC\Common\Contracts\Provider\V2;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract_V1;
use RuntimeException;

/**
 * Class Controller.
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts\Provider\V2;
 *
 * @property Container $container
 */
abstract class Controller extends Controller_Contract_V1 {
	/**
	 * The controllers to register.
	 *
	 * @since TBD
	 *
	 * The expected return is that each element of the array can be passed in the register method
	 * of our container destructed. e.g. $this->container->register( ...$return['0'] );
	 *
	 * Exception is the 'on_action' offset which if detected will be unset and the controller will be registered
	 * on the action instead.
	 *
	 * @return array<array<class-string>>
	 */
	protected function get_controllers(): array {
		return [];
	}

	/**
	 * The singletons to register.
	 *
	 * @since TBD
	 *
	 * The expected return is that each element of the array can be passed in the singleton method
	 * of our container destructed. e.g. $this->container->singleton( ...$return['0'] );
	 *
	 * @return array<array<class-string>>
	 */
	protected function get_singletons(): array {
		return [];
	}

	/**
	 * The bindings to register.
	 *
	 * @since TBD
	 *
	 * The expected return is that each element of the array can be passed in the bind method
	 * of our container destructed. e.g. $this->container->bind( ...$return['0'] );
	 *
	 * @return array<array<class-string>>
	 */
	protected function get_bindings(): array {
		return [];
	}

	/**
	 * Hooks the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function hook(): void {}

	/**
	 * Unhooks the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function unhook(): void {}

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 *
	 * @throws RuntimeException If the bindings, singletons, or controllers are not in the correct format.
	 */
	protected function do_register(): void {
		foreach ( $this->get_bindings() as $binding ) {
			if ( ! is_array( $binding ) ) {
				throw new RuntimeException( 'Each binding must be an array, in a format that can be passed to the container bind method.' );
			}

			$this->container->bind( ...$binding );
		}

		foreach ( $this->get_singletons() as $singleton ) {
			if ( ! is_array( $singleton ) ) {
				throw new RuntimeException( 'Each singleton must be an array, in a format that can be passed to the container singleton method.' );
			}

			$this->container->singleton( ...$singleton );
		}

		foreach ( $this->get_controllers() as $controller ) {
			if ( ! is_array( $controller ) ) {
				throw new RuntimeException( 'Each controller must be an array, in a format that can be passed to the container register method.' );
			}

			if ( isset( $controller['on_action'] ) ) {
				$action = $controller['on_action'];
				unset( $controller['on_action'] );

				if ( ! ( $action && is_string( $action ) ) ) {
					throw new RuntimeException( 'The on_action key must be a string.' );
				}

				$this->container->register_on_action( $action, ...$controller );
				continue;
			}

			$this->container->register( ...$controller );
		}

		$this->hook();
	}

	/**
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	public function unregister(): void {
		foreach ( $this->get_controllers() as $controller ) {
			if ( ! $this->container->isBound( $controller[0] ) ) {
				continue;
			}

			$controller = $this->container->get( $controller[0] );

			if ( ! $controller instanceof Controller_Contract_V1 ) {
				continue;
			}

			$controller->unregister();
		}

		$this->unhook();
	}
}
