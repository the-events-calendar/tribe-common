<?php
/**
 * Controller class for handling the AI MCP integration feature.
 * This class acts as the main entry point for managing the lifecycle of
 * AI MCP tools, including registering dependencies, adding filters, and
 * unregistering actions when necessary.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\AI
 */

namespace TEC\Common\AI;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller
 *
 * @since 6.9.0
 *
 * @package TEC\Common\AI
 */
class Controller extends Controller_Contract {

	/**
	 * Whether the controller is active or not.
	 *
	 * @since 6.9.0
	 *
	 * @return bool Whether the controller is active or not.
	 */
	public function is_active(): bool {
		/**
		 * Filters whether the AI MCP integration is active.
		 *
		 * @since 6.9.0
		 *
		 * @param bool $is_active Whether the AI MCP integration is active.
		 */
		return (bool) apply_filters( 'tec_common_ai_mcp_is_active', true );
	}

	/**
	 * Register the controller.
	 *
	 * @since 6.9.0
	 */
	protected function do_register(): void {
		$this->container->register( MCP\Angie::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.9.0
	 */
	public function unregister(): void {
	}
}
