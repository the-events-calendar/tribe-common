<?php
/**
 * Handles admin conditional content.
 *
 * @since   4.14.7
 * @package Tribe\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Contracts\Service_Provider as Provider_Contract;

use Tribe__Main as Common;

/**
 * Conditional Content Controller.
 *
 * @since 6.3.0
 */
class Controller extends Provider_Contract {


	/**
	 * Registers the required objects and filters.
	 *
	 * @since 6.3.0
	 */
	public function register() {
		// This is specifically for the admin, bail if we're not in the admin.
		if ( ! is_admin() ) {
			return;
		}

		$this->container->singleton(  Black_Friday::class, Black_Friday::class, [ 'hook' ] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since 4.14.7
	 */
	protected function hooks(): void {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since 6.3.0
	 */
	public function plugins_loaded(): void {
		$this->container->make( Black_Friday::class );

		$plugin = Common::instance();

		tribe_asset(
			$plugin,
			'tec-conditional-content',
			'admin/conditional-content.js',
			[
				'wp-data',
				'tribe-common',
			],
			'tec_conditional_content_black_friday',
		);
	}
}
