<?php

namespace Tribe\Service_Providers;

use TEC\Common\Contracts\Service_Provider;

/**
 * Class Tribe__Service_Providers__Tooltip
 *
 * @since 4.9.8
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tooltip extends Service_Provider {


	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.9.8
	 */
	public function register() {
		tribe_singleton( 'tooltip.view', \Tribe\Tooltip\View::class );

		$this->hook();
	}

	/**
	 * Setup hooks for classes.
	 *
	 * @since 4.9.8
	 */
	private function hook() {
		add_action( 'tribe_common_loaded', [ $this, 'add_tooltip_assets' ] );
	}

	/**
	 * Register assets associated with tooltip
	 *
	 * @since 4.9.8
	 */
	public function add_tooltip_assets() {
		$main    = \Tribe__Main::instance();
		$helpers = \Tribe__Admin__Helpers::instance();

		tribe_asset(
			$main,
			'tribe-tooltip',
			'tooltip.css',
			[ 'tribe-common-skeleton-style' ],
			'admin_enqueue_scripts',
			[
				'groups'       => 'tribe-tooltip',
				'conditionals' => [
					[ $helpers, 'is_screen' ],
				],
			]
		);

		tribe_asset(
			$main,
			'tribe-tooltip-js',
			'tooltip.js',
			[ 'jquery', 'tribe-common' ],
			'admin_enqueue_scripts',
			[
				'groups'       => 'tribe-tooltip',
				'conditionals' => [
					[ $helpers, 'is_screen' ],
				],
			]
		);
	}
}
