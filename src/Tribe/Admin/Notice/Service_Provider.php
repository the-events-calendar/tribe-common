<?php
/**
 * Handles admin notice functions.
 *
 * @since   TBD
 *
 * @package Tribe\Admin\Notice;
 */

namespace Tribe\Admin\Notice;

/**
 * Class Notice
 *
 * @since TBD
 *
 * @package Tribe\Admin\Notice
 */
class Service_Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Registers the objects and filters required by the provider to manage admin notices.
	 *
	 * @since TBD
	 */
	public function register() {
		tribe_singleton( 'pue.notices', 'Tribe__PUE__Notices' );
		tribe_singleton( WP_Version::class, WP_Version::class, [ 'hook' ] );
		tribe_singleton( 'admin.notice.php.version', 'Tribe__Admin__Notice__Php_Version', [ 'hook' ] );
		tribe_singleton( 'admin.notice.marketing', 'Tribe__Admin__Notice__Marketing', [ 'hook' ] );
		tribe_singleton( Marketing\Stellar_Sale::class, Marketing\Stellar_Sale::class, [ 'hook' ] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	private function hooks() {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded'] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since TBD
	 */
	public function plugins_loaded() {
		tribe( 'pue.notices' );
		tribe( 'admin.notice.php.version' );
		tribe( WP_Version::class );

		if ( defined( 'TRIBE_HIDE_MARKETING_NOTICES' ) ) {
			return;
		}

		tribe( Marketing\Stellar_Sale::class );
		tribe( Marketing\Black_Friday::class );
	}
}
