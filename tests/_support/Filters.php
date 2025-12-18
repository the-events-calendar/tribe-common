<?php
/**
 * A collection of utility functions used in tests.
 *
 * See documentation in the file docs/tests/filters.md.
 */

namespace TEC\Common\Tests;

/**
 * Class Filters.
 *
 * @package TEC\Common\Tests;
 */
class Filters {
	/**
	 * Adds a filter to the WordPress filter stack.
	 *
	 * The function will add a filter even before WordPress is loaded.
	 *
	 * @param string   $tag           The name of the filter to hook into.
	 * @param callable $callback      The callback to execute when the filter is applied.
	 * @param int      $priority      The priority of the filter, where 10 is the highest priority.
	 * @param int      $accepted_args The number of arguments the callback accepts.
	 *
	 * @return void The filter is added either using the WordPress function or the pre-initialized approach.
	 */
	public static function add_pre_initialized_filter( string $tag, callable $callback, int $priority = 10, int $accepted_args = 1 ) {
		if ( function_exists( 'add_filter' ) ) {
			// Use the correct function if it exists.
			add_filter( $tag, $callback, $priority, $accepted_args );

			return;
		}

		// Since WordPress is not loaded yet, use the pre-initialized approach.
		// @see WP_Hook::build_preinitialized_hooks()
		global $wp_filter;
		$wp_filter                        = is_array( $wp_filter ) ? $wp_filter : [];
		$wp_filter[ $tag ]                = is_array( $wp_filter[ $tag ] ) ? $wp_filter[ $tag ] : [];
		$wp_filter[ $tag ][ $priority ]   = is_array( $wp_filter[ $tag ][ $priority ] ) ? $wp_filter[ $tag ][ $priority ] : [];
		$wp_filter[ $tag ][ $priority ][] =
			[
				'accepted_args' => $accepted_args,
				'function'      => $callback
			];
	}

	/**
	 * Adds an action to the WordPress action stack.
	 *
	 * @param string   $tag      The name of the filter to hook into.
	 * @param callable $callback The callback to execute when the filter is applied.
	 * @param int      $priority The priority of the filter, where 10 is the highest priority.
	 *
	 * @return void The action is added either using the WordPress function or the pre-initialized approach.
	 */
	public static function add_pre_initialized_action( string $tag, callable $callback, int $priority = 10 ) {
		add_pre_initialized_filter( $tag, $callback, $priority, 0 );
	}
}
