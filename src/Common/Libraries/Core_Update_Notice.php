<?php
/**
 * The Controller to set up the Core Update Notice library.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\StellarWP\CoreUpdateNotice\Config;
use TEC\Common\StellarWP\CoreUpdateNotice\Register;

/**
 * Controller for setting up the Core Update Notice library.
 *
 * The library carries its dismissal flag in an unprefixed site option, so a site running several
 * StellarWP plugins that show this notice only has to dismiss it once.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries
 */
class Core_Update_Notice extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function do_register(): void {
		Config::setContainer( $this->container );

		/* Deferred so the copy below is translated; the library hooks admin_init, which is later still. */
		add_action( 'init', [ $this, 'register_notice' ] );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( 'init', [ $this, 'register_notice' ] );
	}

	/**
	 * Register the shared WordPress core update notice.
	 *
	 * The copy is passed in rather than left to the library's English defaults so that it is
	 * extracted into this plugin's text domain.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_notice(): void {
		Register::notice(
			[
				'heading' => __( 'Keep your site protected. Update to the latest version of WordPress.', 'tribe-common' ),
				'body'    => __( 'Your site is running on an outdated version of WordPress, which can leave it vulnerable to security issues. To decrease your risk of exposure, please update your WordPress install to the latest version.', 'tribe-common' ),
				'dismiss' => __( 'Dismiss this notice.', 'tribe-common' ),
			]
		);
	}
}
