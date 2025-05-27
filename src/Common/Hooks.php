<?php
/**
 * TEC Common Hooks
 *
 * @since 6.5.3
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Library_Provider;
use TEC\Common\Contracts\Container;

/**
 * Class Hooks
 *
 * @since 6.5.3
 */
class Hooks extends Controller_Contract {
	/**
	 * The hook prefix.
	 *
	 * @since 6.7.1
	 *
	 * @var string
	 */
	private string $hook_prefix;

	/**
	 * Constructor.
	 *
	 * @since 6.7.1
	 *
	 * @param Container        $container The container.
	 * @param Library_Provider $library_provider The library provider.
	 */
	public function __construct( Container $container, Library_Provider $library_provider ) {
		parent::__construct( $container );
		$this->hook_prefix = $library_provider->get_hook_prefix();
	}

	/**
	 * Registers the hooks added by the controller.
	 *
	 * @since 6.5.3
	 * @since 6.7.1 Added hook for group paths to follow symlinks.
	 */
	public function do_register(): void {
		add_action( 'current_screen', [ $this, 'admin_headers_about_to_be_sent' ], PHP_INT_MAX );
		add_action( 'shutdown', [ $this, 'tec_shutdown' ], 0 );
		add_filter( "stellarwp/assets/{$this->hook_prefix}/group_path", [ $this, 'group_paths_should_follow_symlinks' ] );
	}

	/**
	 * Removes hooks added by the controller.
	 *
	 * @since 6.5.3
	 * @since 6.7.1 Removed hook for group paths to follow symlinks.
	 */
	public function unregister(): void {
		remove_action( 'current_screen', [ $this, 'admin_headers_about_to_be_sent' ], PHP_INT_MAX );
		remove_action( 'shutdown', [ $this, 'tec_shutdown' ], 0 );
		remove_filter( "stellarwp/assets/{$this->hook_prefix}/group_path", [ $this, 'group_paths_should_follow_symlinks' ] );
	}

	/**
	 * Fires an action just before headers are sent.
	 *
	 * @since 6.5.3
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
		 * @since 6.5.3
		 */
		do_action( 'tec_admin_headers_about_to_be_sent' );
	}

	/**
	 * Fires an action during the shutdown action.
	 *
	 * @since 6.5.3
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
		 * @since 6.5.3
		 */
		do_action( 'tec_shutdown' );
	}

	/**
	 * Ensure we follow symlinks for the group paths.
	 *
	 * @since 6.7.1
	 *
	 * @param array $group_path_data The group path data.
	 *
	 * @return array The group path data.
	 */
	public function group_paths_should_follow_symlinks( array $group_path_data ): array {
		// Follow symlinks.
		$group_path_data['root'] = str_replace( trailingslashit( dirname( __DIR__, 4 ) ), trailingslashit( WP_PLUGIN_DIR ), $group_path_data['root'] ?? '' );

		return $group_path_data;
	}
}
