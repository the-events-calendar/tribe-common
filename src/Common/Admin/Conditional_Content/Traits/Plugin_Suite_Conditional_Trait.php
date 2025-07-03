<?php
/**
 * Plugin Suite Conditional Trait to check plugin activation and context.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Conditional_Content\Traits;

/**
 * Trait for plugin suite-related conditional functionality.
 *
 * @since TBD
 */
trait Plugin_Suite_Conditional_Trait {

	/**
	 * Determines the current admin suite context (Events or Tickets).
	 * This method is now expected to be implemented by the class using this trait
	 * (i.e., Promotional_Content_Abstract or its concrete classes).
	 *
	 * @since TBD
	 *
	 * @return string|null 'events', 'tickets', or null if no context could be determined.
	 */
	abstract protected function get_current_admin_suite_context(): ?string;

	/**
	 * Defines the mapping of suites to creative content configurations.
	 * This method is expected to be implemented by the concrete class.
	 *
	 * @since TBD
	 *
	 * @return array Associative array of plugin suites and their creative configurations.
	 */
	abstract protected function get_suite_creative_map(): array;

	/**
	 * Defines which plugin suites this promotional content should target.
	 * This method is now expected to be implemented by the class using this trait
	 * (i.e., Promotional_Content_Abstract or its concrete classes).
	 *
	 * @since TBD
	 *
	 * @return array List of plugin suites ('events', 'tickets')
	 */
	abstract protected function get_target_plugin_suites(): array;


	/**
	 * Register the plugin suite display hook.
	 *
	 * @since TBD
	 *
	 * @param string $hook_name     The name of the filter to register.
	 * @param int    $priority      The priority of the filter.
	 * @param int    $accepted_args The number of arguments the filter accepts.
	 */
	protected function register_plugin_suite_display_hook( $hook_name, $priority = 10, $accepted_args = 2 ): void {
		/**
		 * Filters the content creative based on plugin suite context.
		 *
		 * @since TBD
		 *
		 * @param bool   $should_display Whether the content should be displayed.
		 * Passed through from the previous filter.
		 * @param object $instance       The promotional content instance.
		 */
		add_filter( $hook_name, [ $this, 'is_content_displayable' ], $priority, $accepted_args );
	}

	/**
	 * Determines if the content is displayable based on suite context.
	 * This works in conjunction with Datetime_Conditional_Trait.
	 *
	 * @since TBD
	 *
	 * @param bool   $should_display Whether the content should be displayed.
	 * Passed through from the previous filter.
	 * @param object $instance       The promotional content instance.
	 *
	 * @return bool Whether the content is displayable
	 */
	public function is_content_displayable( $should_display, $instance ): bool {
		// If a previous filter already set it to false, keep it false.
		if ( ! $should_display ) {
			return false;
		}

		// Determine the current active admin suite context.
		$current_suite_context = $this->get_current_admin_suite_context();

		// If no suite context, or if the current context is not targeted by this ad, don't display.
		$target_suites = $this->get_target_plugin_suites();

		if ( empty( $target_suites ) ) {
			return false;
		}

		if ( is_null( $current_suite_context ) ) {
			return false;
		}

		if ( ! in_array( $current_suite_context, $target_suites, true ) ) {
			return false;
		}

		// Return current status, letting other filters refine.
		return $should_display;
	}

	/**
	 * Register the plugin suite content hook.
	 *
	 * @since TBD
	 *
	 * @param string $hook_name     The name of the filter to register.
	 * @param int    $priority      The priority of the filter.
	 * @param int    $accepted_args The number of arguments the filter accepts.
	 */
	protected function register_plugin_suite_content_hook( $hook_name, $priority = 10, $accepted_args = 2 ): void {
		/**
		 * Filters the content creative based on plugin suite context.
		 *
		 * @since TBD
		 *
		 * @param array  $content_creative_rules The current content array. Expected to be empty initially
		 * or contain a generic default.
		 * @param object $instance               The promotional content instance.
		 */
		add_filter( $hook_name, [ $this, 'filter_plugin_suite_content_by_suite' ], $priority, $accepted_args );
	}

	/**
	 * Filter the content creative by selecting the appropriate suite's rules from the main matrix.
	 *
	 * This filter will select the 'creative_rules_for_suite' and pass them to subsequent filters.
	 *
	 * @since TBD
	 *
	 * @param array  $content_creative_rules The current content array. Expected to be empty initially
	 * or contain a generic default.
	 * @param object $instance               The promotional content instance.
	 *
	 * @return array The array of creative rules for the determined suite, or an empty array.
	 */
	public function filter_plugin_suite_content_by_suite( array $content_creative_rules, $instance ): array {
		$current_suite_context = $this->get_current_admin_suite_context();
		$suite_creative_map    = $this->get_suite_creative_map();

		// If no context or no map for this context, pass an empty array.
		if ( is_null( $current_suite_context ) || ! isset( $suite_creative_map[ $current_suite_context ] ) ) {
			return [];
		}

		// Return the specific creative rules for this suite context.
		// Subsequent filters (like Installed_Plugins_Conditional_Trait) will then pick from these rules.
		return $suite_creative_map[ $current_suite_context ];
	}
}
