<?php
/**
 * Abstract for Plugin Merge operations.
 *
 * @since 6.0.0
 *
 * @package TEC\Common\Integrations
 */

namespace TEC\Common\Integrations;

use TEC\Common\Contracts\Service_Provider;
use TEC\Common\lucatume\DI52\Container;
use Tribe__Admin__Notices;
use Tribe__Settings_Manager;

/**
 * Class Plugin_Merge_Provider_Abstract
 *
 * @since 6.0.0
 *
 * @package TEC\Common\Integrations
 */
abstract class Plugin_Merge_Provider_Abstract extends Service_Provider {
	/**
	 * If the plugin was updated from a version that is less than the merged plugin version.
	 *
	 * @var bool
	 */
	protected bool $updated_to_merge_version = false;

	/**
	 * The list of parent plugins initialized.
	 *
	 * @since 6.0.0
	 *
	 * @var array<string, string>
	 */
	protected static array $plugin_updated_names = [];

	/**
	 * Get the plugins version where the merge was applied.
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	abstract public function get_merged_version(): string;

	/**
	 * Get version key for the last version option.
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	abstract public function get_last_version_option_key(): string;

	/**
	 * Get the key of the plugin file, e.g. path/file.php.
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	abstract public function get_plugin_file_key(): string;

	/**
	 * Retrieve the relative path to the child plugin.
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function get_plugin_real_path(): string {
		static $plugins_path = [];

		$text_domain = $this->get_child_plugin_text_domain();

		// Check if the result is already memoized.
		if ( isset( $plugins_path[ $text_domain ] ) ) {
			return $plugins_path[ $text_domain ];
		}

		$plugins     = get_option( 'active_plugins', [] );
		$plugin_path = '';

		foreach ( $plugins as $plugin ) {
			$plugin_file_path = WP_PLUGIN_DIR . '/' . $plugin;

			// Skip if the path is a directory or the file does not exist.
			if ( is_dir( $plugin_file_path ) || ! file_exists( $plugin_file_path ) ) {
				continue;
			}

			// Get plugin data.
			$plugin_data = get_plugin_data( $plugin_file_path, false, false );

			// Check for TextDomain and match.
			if ( isset( $plugin_data['TextDomain'] ) && $plugin_data['TextDomain'] === $text_domain ) {
				$plugin_path = $plugin;
				break;
			}
		}

		// Return empty string if no matching plugin is found.
		if ( empty( $plugin_path ) ) {
			return '';
		}

		// Memoize the result if we found it.
		$plugins_path[ $text_domain ] = $plugin_path;

		return $plugin_path;
	}

	/**
	 * Get the slug of the notice to display with various notices.
	 *
	 * @return string
	 */
	abstract public function get_merge_notice_slug(): string;

	/**
	 * Get the message to display when the parent plugin is being updated to the merge.
	 *
	 * @return string
	 */
	abstract public function get_updated_merge_notice_message(): string;

	/**
	 * Get the message to display when the child plugin is being activated.
	 *
	 * @return string
	 */
	abstract public function get_activating_merge_notice_message(): string;

	/**
	 * Retrieves the name of the plugin.
	 *
	 * @since 6.0.0
	 *
	 * @return string The name of the parent plugin.
	 */
	abstract public function get_plugin_updated_name(): string;

	/**
	 * Run initialization of container and plugin version comparison.
	 *
	 * @since 6.0.0
	 *
	 * @param Container $container The container instance for DI.
	 */
	public function __construct( Container $container ) {
		parent::__construct( $container );
		// Was updated from a version that is less than the merged version?
		$this->updated_to_merge_version = $this->did_update_to_merge_version();
	}

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 6.0.0
	 */
	public function register(): void {
		$this->init();
	}

	/**
	 * Check if the plugin was updated from a version that is less than the merged plugin version.
	 *
	 * @since 6.0.0
	 *
	 * @return bool
	 */
	protected function did_update_to_merge_version(): bool {
		return version_compare( Tribe__Settings_Manager::get_option( $this->get_last_version_option_key() ), $this->get_merged_version(), '<' );
	}

	/**
	 * Is the child plugin active?
	 *
	 * @since 6.0.0
	 */
	protected function is_child_plugin_active(): bool {
		if ( is_plugin_active( $this->get_plugin_file_key() ) ) {
			return true;
		}

		$real_path = $this->get_plugin_real_path();

		return $real_path && is_plugin_active( $real_path );
	}

	/**
	 * This fires if we are initializing the merge and stores the parent plugin information.
	 *
	 * @since 6.0.0
	 *
	 * @return void
	 */
	public function register_update(): void {
		// Stores the upgrade string.
		if ( ! in_array( $this->get_plugin_updated_name(), self::$plugin_updated_names, true ) ) {
			self::$plugin_updated_names[] = $this->get_plugin_updated_name();
		}
	}

	/**
	 * Initializes the merged compatibility checks.
	 *
	 * @since 6.0.0
	 */
	public function init(): void {
		// Load our is_plugin_activated function.
		if ( ! function_exists( 'is_plugin_activated' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( $this->updated_to_merge_version && ! $this->is_child_plugin_active() ) {
			// Leave a notice of the recent update.
			$this->send_updated_notice();
			if ( ! $this->is_activating_plugin() ) {
				$this->init_merged_plugin();
			}
			return;
		}

		// If the child plugin is active, we need to deactivate it before continuing to avoid a fatal.
		if ( $this->is_child_plugin_active() ) {
			$this->deactivate_plugin();

			// Leave a notice of the forced deactivation.
			$this->send_updated_merge_notice();
			$this->send_updated_notice();
			return;
		}

		// If the action is to activate the plugin, we should not continue to avoid a fatal.
		if ( $this->is_activating_plugin() ) {
			add_action( 'activated_plugin', [ $this, 'deactivate_plugin' ] );

			// Remove "Plugin activated" notice from redirect.
			add_action( 'activate_plugin', [ $this, 'remove_activated_from_redirect' ] );
			// Leave a notice of the forced deactivation.
			$this->send_activating_merge_notice();
			return;
		}

		// If the plugin is not active and the action is not to activate it, we can proceed with the merge.
		$this->init_merged_plugin();
	}

	/**
	 * If any initialization is necessary for the merged plugin to work after child plugin deactivation is resolved.
	 *
	 * @since 6.0.0
	 */
	public function init_merged_plugin(): void {
		// Implement the merged plugin initialization.
	}

	/**
	 * Deactivates the merged plugin.
	 *
	 * @since 6.0.0
	 */
	public function deactivate_plugin(): void {
		deactivate_plugins( $this->get_plugin_real_path(), true, is_multisite() && is_plugin_active_for_network( $this->get_plugin_real_path() ) );
	}

	/**
	 * Fetch the plugin text domain used for locating and checking a specific plugin.
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	abstract public function get_child_plugin_text_domain(): string;

	/**
	 * Adds the hook to remove the "Plugin activated" notice from the redirect.
	 *
	 * @since 6.0.0
	 *
	 * @param string $plugin The plugin file path.
	 */
	public function remove_activated_from_redirect( $plugin ): void {
		if ( basename( $plugin ) === basename( $this->get_plugin_file_key() ) ) {
			add_filter( 'wp_redirect', [ $this, 'filter_remove_activated_from_redirect' ] );
		}
	}

	/**
	 * Filter the redirect location to remove the "activate" query arg.
	 *
	 * @since 6.0.0
	 *
	 * @param string $location The redirect location.
	 *
	 * @return string The redirect location without the "activate" query arg.
	 */
	public function filter_remove_activated_from_redirect( $location ): string {
		return remove_query_arg( 'activate', $location );
	}

	/**
	 * Send admin notice about the updates in the merged version.
	 *
	 * @since 6.0.0
	 */
	public function send_updated_notice(): void {
		$this->register_update();

		// Defer so we have time to register updates for each plugin.
		add_action( 'admin_init', [ $this, 'register_updated_notice' ], 99 );
	}

	/**
	 * Registers the notice transient with the rendered message.
	 *
	 * @since 6.0.0
	 *
	 * @return void
	 */
	public function register_updated_notice(): void {
		$notice_slug = 'updated-to-merge-version-consolidated-notice';

		// Remove dismissed flag since we want to show the notice every time this is triggered.
		Tribe__Admin__Notices::instance()->undismiss( $notice_slug );

		tribe_transient_notice(
			$notice_slug,
			$this->render_updated_notice(),
			[
				'type'            => 'success',
				'dismiss'         => true,
				'action'          => 'admin_notices',
				'priority'        => 1,
				'active_callback' => __CLASS__ . '::should_show_merge_notice',
			],
			YEAR_IN_SECONDS
		);
	}

	/**
	 * Compiles the updated plugin message.
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function render_updated_notice(): string {
		$plugins_list = static::$plugin_updated_names;
		$last_plugin  = array_pop( $plugins_list );
		$plugins_str  = $last_plugin;

		// Do we have more than one?
		if ( count( $plugins_list ) ) {
			$separator    = _x(
				', ',
				'Initial separator for list of plugins for the plugin consolidation notice message.',
				'tribe-common'
			);
			$all_but_last = join( $separator, $plugins_list );
			$plugins_str  = sprintf(
				/* Translators: %1$s is the list of plugins except the last, %2$s is the last plugin name. i.e "one and two" or "one, two and three" */
				_x(
					'%1$s and %2$s',
					'Joined plugin list, last after the "and" separator.',
					'tribe-common'
				),
				$all_but_last,
				$last_plugin
			);
		}

		$message = sprintf(
			/* Translators: %1$s is the plugin name(s) and version(s), %2$s and %3$s are the opening and closing anchor tags. */
			_x(
				'Thanks for upgrading %1$s now with even more value! Learn more about the latest changes %2$shere%3$s.',
				'Notice message after updating plugins to the merged version.',
				'tribe-common'
			),
			$plugins_str,
			'<a target="_blank" href="https://evnt.is/1bdy" rel="noopener noreferrer">',
			'</a>'
		);

		return sprintf( '<p>%1$s</p>', $message );
	}

	/**
	 * Send admin notice about the merge of the child plugin into the parent plugin.
	 *
	 * @since 6.0.0
	 */
	public function send_updated_merge_notice(): void {
		// Remove dismissed flag since we want to show the notice every time this is triggered.
		Tribe__Admin__Notices::instance()->undismiss( $this->get_merge_notice_slug() );

		$message = $this->get_updated_merge_notice_message();
		tribe_transient_notice(
			$this->get_merge_notice_slug(),
			sprintf( '<p>%s</p>', $message ),
			[
				'type'            => 'success',
				'dismiss'         => true,
				'action'          => 'admin_notices',
				'priority'        => 10,
				'active_callback' => __CLASS__ . '::should_show_merge_notice',
			],
			YEAR_IN_SECONDS
		);
	}

	/**
	 * Send admin notice about the merge of the Event Tickets Wallet Plus plugin into Tickets Plus.
	 * This notice is for after activating the deprecated Wallet Plus plugin.
	 *
	 * @since 6.0.0
	 */
	public function send_activating_merge_notice(): void {
		// Remove dismissed flag since we want to show the notice every time this is triggered.
		Tribe__Admin__Notices::instance()->undismiss( $this->get_merge_notice_slug() );

		$message = $this->get_activating_merge_notice_message();
		tribe_transient_notice(
			$this->get_merge_notice_slug(),
			sprintf( '<p>%s</p>', $message ),
			[
				'type'            => 'warning',
				'dismiss'         => true,
				'action'          => 'admin_notices',
				'priority'        => 10,
				'active_callback' => __CLASS__ . '::should_show_merge_notice',
			],
			YEAR_IN_SECONDS
		);
	}

	/**
	 * Check if the merge notice should be shown.
	 *
	 * @since 6.0.0
	 *
	 * @return bool
	 */
	public static function should_show_merge_notice(): bool {
		return tribe( \Tribe__Admin__Helpers::class )->is_screen() || tribe( \Tribe__Admin__Helpers::class )->is_screen( 'plugins' );
	}

	/**
	 * Implements Runner's private method cmd_starts_with.
	 *
	 * @since 6.0.0
	 *
	 * @param array $looking_for The array of strings to look for.
	 * @param mixed $args        The arguments to search in.
	 *
	 * @return bool
	 */
	protected function cli_args_start_with( array $looking_for, $args ): bool {
		if ( empty( $args ) || ! is_array( $args ) ) {
			return false;
		}

		return array_slice( $args, 0, count( $looking_for ) ) === $looking_for;
	}

	/**
	 * Checks if the current request is activating the VE plugin.
	 *
	 * @since 6.0.0
	 *
	 * @return bool
	 */
	protected function is_activating_plugin(): bool {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			// Taking advantage of Runner's __get method to access private properties.
			$args = \WP_CLI::get_runner()->arguments;
			return $this->cli_args_start_with( [ 'plugin', 'activate' ], $args ) || $this->cli_args_start_with( [ 'plugin', 'install' ], $args );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
		$action = $_GET['action'] ?? null;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
		$action = $_POST['action'] ?? $action;

		// Are we activating?
		if ( ! in_array( $action, [ 'activate', 'activate-selected' ] ) ) {
			return false;
		}

		// Can we even activate?
		if ( ! current_user_can( 'activate_plugins' ) || ! is_admin() ) {
			return false;
		}

		// Which plugin are we activating?
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
		$targeted_plugins = isset( $_GET['plugin'] ) ? [ basename( $_GET['plugin'] ) ] : null;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
		if ( ! $targeted_plugins && isset( $_POST['checked'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
			$targeted_plugins = array_map( 'basename', $_POST['checked'] );
		}

		// Something went wrong, bail.
		if ( ! is_array( $targeted_plugins ) ) {
			return false;
		}

		// Are we activating our plugin?
		$child_plugin = basename( $this->get_plugin_file_key() );

		return in_array( $child_plugin, $targeted_plugins );
	}
}
