<?php
/**
 * Controller for the TEC SVG API.
 *
 * @since TBD
 *
 * @package TEC\Common\SVG
 */

declare( strict_types=1 );

namespace TEC\Common\SVG;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Controller for the TEC SVG API.
 *
 * @since TBD
 *
 * @package TEC\Common\SVG
 */
class Controller extends Controller_Contract {
	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( SVG::class );
	}

	/**
	 * Unregisters the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		// Nothing to do here.
	}
}
