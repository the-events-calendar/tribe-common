<?php
/**
 * Assembles a report of recently updated plugin views and template overrides in
 * possible revision, for each plugin that registers itself and its template
 * filepaths.
 */
class Tribe__Support__Filter_Checker_Report {
	/**
	 * Contains the individual reports for each registered plugin.
	 *
	 * @var array
	 */
	protected static $plugin_reports = [];

	/**
	 * Container for finished report.
	 *
	 * @var string
	 */
	protected static $complete_report = '';

	/**
	 * Provides an up-to-date report concerning filter overrides.
	 *
	 * @return string
	 */
	public static function generate() {
		foreach ( self::registered_filters() as $plugin_name => $plugin_filters ) {
			self::generate_for( $plugin_name, $plugin_filters );
		}

		self::wrap_report();
		return self::$complete_report;
	}

	protected static function registered_filters() {
		/**
		 * Provides a mechanism for plugins to register information about their filters.
		 *
		 * This should be done by adding an entry to $plugin_filters where the key
		 * should be the plugin name and the value an array of filter slugs to check:
		 *
		 *     plugin_name => [
		 *         filter_slug,
		 *         another_filter_slug,
		 * 		],
		 *
		 * @var array $plugin_filters
		 */
		return apply_filters(
			'tribe_support_registered_filter_overrides',
			[
				'the-events-calendar' => [
					'tribe_events_views_v2_is_enabled',
					'tribe_settings_fields',
					'tribe_get_template_part_content',
					'tribe_tickets_settings_post_types',
					'tribe_events_get_link',
					'tribe_get_ical_link',
					'tribe_events_before_html',

				],
			]
		);
	}

	/**
	 * Creates a report for the specified plugin.
	 *
	 * @param string $plugin_name
	 * @param array  $plugin_filters
	 */
	protected static function generate_for( $plugin_name, array $plugin_filters ) {
		$report = '<dt>' . esc_html( $plugin_name ) . '</dt>';
		$applied_filters = [];

		foreach ( $plugin_filters as $filter_slug ) {
			if ( has_filter( $filter_slug ) ) {
				$applied_filters[ $plugin_name ][] = $filter_slug;
			}
		}

		if ( empty( $applied_filters ) ) {
			$report .= '<dd><p>' . _x( 'No filters applied.', 'Message for no applied filters found', 'tribe-common' ) . '</p>';
		} else {
			$report = '<ul>';
			//$applied_filters[ $plugin_name ][ $filter_slug ]
			foreach ( $applied_filters as $plugin_name => $filters ) {

				$report .= '<ul>';
				foreach ( $filters as $filter ) {
					$count = self::filter_callbacks( $filter );
					$report .= sprintf(
						'<li>%s (%d)</li>',
						esc_html( $filter ),
						esc_html( $count )
					);
				}
				$report .= '</ul>';
			}

			$report .= '</dd>';
		}

		self::$plugin_reports[ $plugin_name ] = $report;
	}

	protected static function filter_callbacks( $hook_name ) {
		global $wp_filter;
		$action = $wp_filter[$hook_name];

		return count( $action->callbacks );
	}

	/**
	 * Wraps the individual plugin template reports ready for display.
	 */
	protected static function wrap_report() {
		if ( empty( self::$plugin_reports ) ) {
			self::$complete_report = '<p>' . __( 'No notable template changes detected.', 'tribe-common' ) . '</p>';
		} else {
			self::$complete_report = '<p>' . __( 'A list of hooked filters is provided below.', 'tribe-common' ) . '</p>'
				. '<div class="template-updates-wrapper">' . join( ' ', self::$plugin_reports ) . '</div>';
		}
	}
}
