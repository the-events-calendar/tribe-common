<?php
/**
 * TEC Common Hooks
 *
 * @since TBD
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Hooks
 *
 * @since TBD
 */
class Hooks extends Controller_Contract {

	/**
	 * Registers the hooks added by the controller.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		add_action( 'current_screen', [ $this, 'admin_headers_about_to_be_sent' ], PHP_INT_MAX );

		add_action( 'shutdown', [ $this, 'tec_shutdown' ], 0 );
	}

	/**
	 * Removes hooks added by the controller.
	 *
	 * @since TBD
	 */
	public function unregister(): void {}

	/**
	 * Fires an action just before headers are sent.
	 *
	 * @since TBD
	 */
	public function admin_headers_about_to_be_sent() {
		/**
		 * Fires just before headers are sent.
		 *
		 * We can use this action instead of headers_sent().
		 *
		 * Especially where a functionality would trigger a fatal error if headers are
		 * sent using an action is more forgiving.
		 *
		 * @since TBD
		 */
		do_action( 'tec_admin_headers_about_to_be_sent' );
	}

	/**
	 * Fires an action during the shutdown action.
	 *
	 * @since TBD
	 */
	public function tec_shutdown() {
		/**
		 * Fires during the shutdown action.
		 *
		 * This is mostly useful for testing code. We can trigger this action
		 * instead of triggering the whole shutdown.
		 *
		 * In production code, it can help us only in the sense of adding our own
		 * actions in a specific order.
		 *
		 * @since TBD
		 */
		do_action( 'tec_shutdown' );
	}
}
