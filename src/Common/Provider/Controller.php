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
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * Bound implementations should not be removed in this method!
	 *
	 * @since TBD
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	abstract public function unregister(): void;
}