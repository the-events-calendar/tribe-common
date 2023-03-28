<?php
/**
 * The base clas all Controllers should extend.
 *
 * @since   TBD
 *
 * @package TEC\Common\Provider;
 */

namespace TEC\Common\Provider;

use TEC\Common\lucatume\DI52\ServiceProvider as Service_Provider;

/**
 * Class Controller.
 *
 * @since   TBD
 *
 * @package TEC\Common\Provider;
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