<?php
/**
 * Handles admin conditional content.
 *
 * @since 4.14.7
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

		$this->container->singleton( Black_Friday::class, Black_Friday::class, [ 'hook' ] );
		$this->container->singleton( Stellar_Sale::class, Stellar_Sale::class, [ 'hook' ] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since 4.14.7
	 */
	protected function hooks(): void {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
		add_action( 'tec_conditional_content_header_notice', [ $this, 'render_header_notice' ] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since 6.3.0
	 */
	public function plugins_loaded(): void {
		$this->container->make( Black_Friday::class );
		$this->container->make( Stellar_Sale::class );

		$plugin = Common::instance();

		tec_asset(
			$plugin,
			'tec-conditional-content',
			'admin/conditional-content.js',
			[
				'wp-data',
				'tribe-common',
			],
			'tec_conditional_content_assets',
		);
	}

	/**
	 * Render the header notice.
	 *
	 * @since 6.3.0
	 */
	public function render_header_notice(): void {
		$this->container->make( Stellar_Sale::class )->render_header_notice();
		$this->container->make( Black_Friday::class )->render_header_notice();
	}
}
