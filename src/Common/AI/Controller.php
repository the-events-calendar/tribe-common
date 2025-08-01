<?php
/**
 * Controller class for handling the AI MCP integration feature.
 * This class acts as the main entry point for managing the lifecycle of
 * AI MCP tools, including registering dependencies, adding filters, and
 * unregistering actions when necessary.
 *
 * @since TBD
 *
 * @package TEC\Common\AI
 */

namespace TEC\Common\AI;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller
 *
 * @since TBD
 *
 * @package TEC\Common\AI
 */
class Controller extends Controller_Contract {

	/**
	 * Whether the controller is active or not.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the controller is active or not.
	 */
	public function is_active(): bool {
		/**
		 * Filters whether the AI MCP integration is active.
		 *
		 * @since TBD
		 *
		 * @param bool $is_active Whether the AI MCP integration is active.
		 */
		return (bool) apply_filters( 'tec_common_ai_mcp_is_active', true );
	}

	/**
	 * Register the controller.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		// Register AI_Service singleton in the container.
		$this->container->singleton( MCP\AI_Service::class, MCP\AI_Service::class, [ 'init' ] );
		$this->container->register( MCP\Angie::class );

		// Initialize AI_Service.
		$this->container->make( MCP\AI_Service::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 */
	public function unregister(): void {
		// Get AI_Service instance and unregister it.
		if ( $this->container->has( MCP\AI_Service::class ) ) {
			$this->container->make( MCP\AI_Service::class )->unregister();
		}
	}
}
