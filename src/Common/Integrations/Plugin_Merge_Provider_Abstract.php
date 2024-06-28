<?php
/**
 * Abstract for Integrations.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations
 */
namespace TEC\Common\Integrations;

use TEC\Common\Contracts\Service_Provider;
use Tribe__Admin__Notices;
use Tribe__Settings_Manager;

/**
 * Class Plugin_Merge_Provider_Abstract
 *
 * @since TBD
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
	 * Get the plugins version where the merge was applied.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract public function get_merged_version(): string;

	/**
	 * Get version key for the last version option.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract public function get_last_version_option_key(): string;

	/**
	 * Get the key of the plugin file, e.g. path/file.php.
	 *
	 * @return string
	 */
	abstract public function get_plugin_file_key(): string;

	/**
	 * Get the slug of the notice to display with various notices.
	 *
	 * @return string
	 */
	abstract public function get_merge_notice_slug(): string;

	/**
	 * Get the message to display when the parent plugin is being updated but the child plugin is not active.
	 *
	 * @return string
	 */
	abstract public function get_updated_notice_message(): string;

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
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		// Was updated from a version that is less than the merged version?
		$this->updated_to_merge_version = $this->did_update_to_merge_version();
		$this->init();
	}

	/**
	 * Check if the plugin was updated from a version that is less than the merged plugin version.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	protected function did_update_to_merge_version(): bool {
		return version_compare( Tribe__Settings_Manager::get_option( $this->get_last_version_option_key() ), $this->get_merged_version(), '<' );
	}

	/**
	 * Is the child plugin active?
	 *
	 * @since TBD
	 */
	protected function is_child_plugin_active(): bool {
		return is_plugin_active( $this->get_plugin_file_key() );
	}

	/**
	 * Initializes the merged compatibility checks.
	 *
	 * @since TBD
	 */
	public function init() {
		if ( $this->updated_to_merge_version && ! $this->is_child_plugin_active() ) {
			// Leave a notice of the recent update.
			$this->send_updated_notice();
			$this->init_merged_plugin();
			return;
		}

		// If the Event Ticket Wallet Plus plugin is active, we need to deactivate it before continuing to avoid a fatal.
		if ( $this->is_child_plugin_active() ) {
			$this->deactivate_plugin();

			// Leave a notice of the forced deactivation.
			$this->send_updated_merge_notice();
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
	 * @since TBD
	 */
	public function init_merged_plugin() {
		// Implement the merged plugin initialization.
	}

	/**
	 * Deactivates the merged plugin.
	 *
	 * @since TBD
	 */
	public function deactivate_plugin() {
		deactivate_plugins( $this->get_plugin_file_key() );
	}

	/**
	 * Adds the hook to remove the "Plugin activated" notice from the redirect.
	 *
	 * @since TBD
	 *
	 * @param string $plugin The plugin file path.
	 */
	public function remove_activated_from_redirect( $plugin ) {
		if ( basename( $plugin ) === basename( $this->get_plugin_file_key() ) ) {
			add_filter( 'wp_redirect', [ $this, 'filter_remove_activated_from_redirect' ] );
		}
	}

	/**
	 * Filter the redirect location to remove the "activate" query arg.
	 *
	 * @since TBD
	 *
	 * @param string $location The redirect location.
	 *
	 * @return string The redirect location without the "activate" query arg.
	 */
	public function filter_remove_activated_from_redirect( $location ) {
		return remove_query_arg( 'activate', $location );
	}

	/**
	 * Send admin notice about the updates of Tickets Plus.
	 *
	 * @since TBD
	 */
	public function send_updated_notice() {
		// Remove dismissed flag since we want to show the notice everytime this is triggered.
		Tribe__Admin__Notices::instance()->undismiss( $this->get_merge_notice_slug() );

		$message = $this->get_updated_notice_message();
		tribe_transient_notice(
			$this->get_merge_notice_slug(),
			sprintf( '<p>%s</p>', $message ),
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
	 * Send admin notice about the merge of the Event Tickets Wallet Plus plugin into Tickets Plus.
	 * This notice is for after updating Tickets Plus to the merged version.
	 *
	 * @since TBD
	 */
	public function send_updated_merge_notice() {
		// Remove dismissed flag since we want to show the notice everytime this is triggered.
		Tribe__Admin__Notices::instance()->undismiss( $this->get_merge_notice_slug() );

		$message = $this->get_updated_merge_notice_message();
		tribe_transient_notice(
			$this->get_merge_notice_slug(),
			sprintf( '<p>%s</p>', $message ),
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
	 * Send admin notice about the merge of the Event Tickets Wallet Plus plugin into Tickets Plus.
	 * This notice is for after activating the deprecated Wallet Plus plugin.
	 *
	 * @since TBD
	 */
	public function send_activating_merge_notice() {
		// Remove dismissed flag since we want to show the notice everytime this is triggered.
		Tribe__Admin__Notices::instance()->undismiss( $this->get_merge_notice_slug() );

		$message = $this->get_activating_merge_notice_message();
		tribe_transient_notice(
			$this->get_merge_notice_slug(),
			sprintf( '<p>%s</p>', $message ),
			[
				'type'            => 'warning',
				'dismiss'         => true,
				'action'          => 'admin_notices',
				'priority'        => 1,
				'active_callback' => __CLASS__ . '::should_show_merge_notice',
			],
			YEAR_IN_SECONDS
		);
	}

	/**
	 * Check if the merge notice should be shown.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public static function should_show_merge_notice(): bool {
		return tribe( \Tribe__Admin__Helpers::class )->is_screen() || tribe( \Tribe__Admin__Helpers::class )->is_screen( 'plugins' );
	}

	/**
	 * Implements Runner's private method cmd_starts_with.
	 *
	 * @since TBD
	 *
	 * @param array $looking_for The array of strings to look for.
	 * @param mixed $args        The arguments to search in.
	 *
	 * @return bool
	 */
	protected function cli_args_start_with( array $looking_for, $args ) {
		if ( empty( $args ) || ! is_array( $args ) ) {
			return false;
		}

		return array_slice( $args, 0, count( $looking_for ) ) === $looking_for;
	}

	/**
	 * Checks if the current request is activating the VE plugin.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	protected function is_activating_plugin() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			// Taking advantage of Runner's __get method to access private properties.
			$args = \WP_CLI::get_runner()->arguments;
			return $this->cli_args_start_with( [ 'plugin', 'activate' ], $args ) || $this->cli_args_start_with( [ 'plugin', 'install' ], $args );
		}

		// phpcs:ignore
		$is_activating = isset( $_GET['action'] ) && $_GET['action'] === 'activate';
		// phpcs:ignore
		$is_child_plugin   = isset( $_GET['plugin'] ) && basename( $_GET['plugin'] ) === basename( $this->get_plugin_file_key() );
		$user_can_activate = current_user_can( 'activate_plugins' ) && is_admin();

		return $is_child_plugin && $is_activating && $user_can_activate;
	}
}
