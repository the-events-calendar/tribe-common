<?php
/**
 * MCP Angie Controller
 *
 * @package TEC\Common\AI\MCP
 */

namespace TEC\Common\AI\MCP;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use Tribe__Main as Common_Main;

/**
 * Class Angie
 *
 * @since 6.9.0
 *
 * @package TEC\Common\AI\MCP
 */
class Angie extends Controller_Contract {

	/**
	 * Whether the controller is active or not.
	 *
	 * @since 6.9.0
	 *
	 * @return bool Whether the controller is active or not.
	 */
	public function is_active(): bool {
		$can_use_angie = defined( 'ANGIE_VERSION' ) && current_user_can( 'use_angie' ); // phpcs:ignore WordPress.WP.Capabilities.Unknown -- Custom capability for AI features
		return $can_use_angie;
	}

	/**
	 * Register the controller.
	 *
	 * @since 6.9.0
	 */
	protected function do_register(): void {
		$main = Common_Main::instance();
		tec_asset(
			$main,
			'tec-angie-mcp-server',
			'tec-angie-mcp-server.js',
			[],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ],
			[
				'groups'   => [ 'tec-angie-mcp' ],
				'localize' => [
					'name' => 'tecAngieMCP',
					'data' => [ $this, 'get_mcp_localized_data' ],
				],
			]
		);
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.9.0
	 */
	public function unregister(): void {
		// Remove hooks if needed.
	}

	/**
	 * Get localized data for MCP.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function get_mcp_localized_data() {
		return [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'tec-angie-mcp' ),
		];
	}
}
