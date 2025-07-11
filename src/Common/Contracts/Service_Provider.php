<?php
/**
 * Service Provider contract.
 *
 * @since 5.1.0
 */

namespace TEC\Common\Contracts;

use TEC\Common\lucatume\DI52\ServiceProvider as DI52_Service_Provider;

/**
 * Service Provider contract.
 *
 * @since 5.1.0
 *
 * @property Container $container The container instance.
 */
abstract class Service_Provider extends DI52_Service_Provider {
	// Intentionally empty.
}
