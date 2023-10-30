<?php

namespace TEC\Common\Contracts;

use TEC\Common\lucatume\DI52\ServiceProvider as DI52_Service_Provider;

/**
 * Class ServiceProvider
 *
 * @package TEC\Common\Contracts
 *
 * @property bool $deferred
 * @property TEC\Common\lucatume\DI52\Container $container
 */
abstract class Service_Provider extends DI52_Service_Provider  {
    // Intentionally empty.
}
