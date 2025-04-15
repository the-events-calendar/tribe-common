<?php
/**
 * QR Code Controller.
 *
 * @since 6.6.0
 *
 * @package TEC\Common\QR
 */

namespace TEC\Common\QR;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use Tribe__Main;

/**
 * Class Controller.
 *
 * @since  6.6.0
 *
 * @package TEC\Common\QR
 */
class Controller extends Controller_Contract {
	/**
	 * The custom action that will be fired when the controller registers.
	 *
	 * @since 6.6.0
	 *
	 * @var string
	 */
	public static string $registration_action = 'tec_qr_code_loaded';

	/**
	 * Register the controller.
	 *
	 * @since  6.6.0
	 *
	 * @uses  Notices::register_admin_notices()
	 *
	 * @return void
	 */
	public function do_register(): void {
		$this->container->bind( QR::class, [ $this, 'bind_facade_or_error' ] );
		$this->container->singleton( Notices::class );

		$this->add_actions();
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.6.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->remove_actions();
	}

	/**
	 * Adds the actions required by the controller.
	 *
	 * @since 6.6.0
	 *
	 * @return void
	 */
	protected function add_actions(): void {
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Removes the actions required by the controller.
	 *
	 * @since 6.6.0
	 *
	 * @return void
	 */
	protected function remove_actions(): void {
		remove_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since 4.14.2
	 */
	public function plugins_loaded() {
		$this->container->make( Notices::class )->register_admin_notices();
	}

	/**
	 * Binds the facade or throws an error.
	 *
	 * @since 6.6.0
	 *
	 * @return \WP_Error|QR Either the build QR façade, or an error to detail the failure.
	 */
	public function bind_facade_or_error() {
		if ( ! $this->can_use() ) {
			return new \WP_Error(
				'tec_tickets_qr_code_cannot_use',
				__( 'The QR code cannot be used, please contact your host and ask for `gzip` and `gd` support.', 'tribe-common' )
			);
		}

		// Load the library if it's not loaded already.
		$this->load_library();

		return new QR();
	}

	/**
	 * Determines if the QR code library is loaded.
	 *
	 * @since 6.6.0
	 */
	public function has_library_loaded(): bool {
		return defined( 'TEC_QR_CACHEABLE' );
	}

	/**
	 * Loads the QR code library if it's not loaded already.
	 *
	 * @since 6.6.0
	 */
	protected function load_library(): void {
		if ( $this->has_library_loaded() ) {
			return;
		}

		require_once Tribe__Main::instance()->plugin_path . 'vendor/phpqrcode/qrlib.php';
	}

	/**
	 * Determines if the QR code can be used.
	 *
	 * @since 6.6.0
	 *
	 * @return bool Whether the current server configuration supports the QR functionality.
	 */
	public function can_use(): bool {
		$can_use = function_exists( 'gzuncompress' ) && function_exists( 'ImageCreate' );

		/**
		 * Filter to determine if the QR code can be used.
		 *
		 * @deprecated 6.6.0 Moved from ET, no longer used and will be removed in the future.
		 *
		 * @param bool $can_use Whether the QR code can be used based on the current environment.
		 */
		$can_use = apply_filters_deprecated( 'tec_tickets_qr_code_can_use', [ $can_use ], '5.7.0', 'tec_qr_code_can_use' );

		/**
		 * Filter to determine if the QR code can be used.
		 *
		 * @since 6.6.0
		 *
		 * @param bool $can_use Whether the QR code can be used based on the current environment.
		 */
		return apply_filters( 'tec_qr_code_can_use', $can_use );
	}
}
