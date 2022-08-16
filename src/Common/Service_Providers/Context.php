<?php
/**
 * Handles Common Context manipulation.
 *
 * @since   TBD
 *
 * @package TEC\Common\Service_Providers
 */

namespace TEC\Common\Service_Providers;

use Tribe__Context;
use Tribe__Main as Common;

/**
 * Class Context
 *
 * @since   TBD
 *
 * @package TEC\Common\Service_Providers
 */
class Context extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		add_filter( 'tribe_context_locations', [ $this, 'filter_context_locations' ] );
	}

	/**
	 * Filters the context locations to add the ones used by Common
	 *
	 * @since TBD
	 *
	 * @param array $locations The array of context locations.
	 *
	 * @return array The modified context locations.
	 */
	public function filter_context_locations( array $locations = [] ) {
		$locations = array_merge(
			$locations,
			[
				'common_admin_request' => [
					'read' => [
						Tribe__Context::REQUEST_VAR => [ Common::$request_slug, 'state' ],
					],
				],
			]
		);

		return $locations;
	}
}
