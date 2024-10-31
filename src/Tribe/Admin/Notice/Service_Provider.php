<?php
/**
 * Handles admin notice functions.
 *
 * @since   4.14.2
 *
 * @package Tribe\Admin\Notice;
 */

namespace Tribe\Admin\Notice;

use TEC\Common\Contracts\Service_Provider as Provider_Contract;

/**
 * Class Notice
 *
 * @since 4.14.2
 *
 * @package Tribe\Admin\Notice
 */
class Service_Provider extends Provider_Contract {


	/**
	 * Registers the objects and filters required by the provider to manage admin notices.
	 *
	 * @since 4.14.2
	 */
	public function register() {
		tribe_singleton( 'pue.notices', 'Tribe__PUE__Notices' );
		tribe_singleton( WP_Version::class, WP_Version::class, [ 'hook' ] );
		tribe_singleton( 'admin.notice.php.version', \Tribe__Admin__Notice__Php_Version::class, [ 'hook' ] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since 4.14.2
	 */
	private function hooks() {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded'] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since 4.14.2
	 */
	public function plugins_loaded() {
		tribe( 'pue.notices' );
		tribe( 'admin.notice.php.version' );
		tribe( WP_Version::class );
	}

/**
	 * This method is used to enqueue additional assets for the admin notices.
	 * Each should conditionally call an internal `enqueue_additional_assets()` function to handle the enqueueing.
	 *
	 * @since 5.1.10
	 * @deprecated 6.3.0
	 */
	public function enqueue_additional_assets() {
		_deprecated_function( __METHOD__, '6.3.0', 'No replacement.' );
	}
}
