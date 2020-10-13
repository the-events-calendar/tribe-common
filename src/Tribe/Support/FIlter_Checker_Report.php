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
		 * should be the plugin name and the value an array of filter slugs and their default value:
		 *
		 *     plugin_name => [
		 *         filter_slug         => [value,
		 *         another_filter_slug => [
		 *             value,
		 *             num_args
		 *     ],
		 *
		 * @var array $plugin_filters
		 */
		return apply_filters( 'tribe_support_registered_filter_overrides', [] );
	}

	/**
	 * Creates a report for the specified plugin.
	 *
	 * @param string $plugin_name
	 * @param array  $plugin_filters
	 */
	protected static function generate_for( $plugin_name, array $plugin_filters ) {
		$report = '<dt>' . esc_html( $plugin_name ) . '</dt>';

		foreach ( $plugin_filters[ $plugin_name ] as $filter_slug => $filter_value ) {
			$filtered_value = apply_filters( $filter_slug, $filter_value );
			if ( $filtered_value === $filter_value ) {

			}

			$plugin_filters[ $plugin_name ][ $filter_slug ] = $filtered_value
		}

		if ( empty( $newly_introduced_or_updated ) && empty( $outdated_or_unknown ) ) {
			$report .= '<dd>' . __( 'No notable changes detected', 'tribe-common' ) . '</dd>';
		}

		if ( ! empty( $newly_introduced_or_updated ) ) {
			$report .= '<dd><p>' . sprintf( __( 'Templates introduced or updated with this release (%s):', 'tribe-common' ), $template_system[ self::VERSION_INDEX ] ) . '</p><ul>';

			foreach ( $newly_introduced_or_updated as $view_name => $version ) {
				$report .= '<li>' . esc_html( $view_name ) . '</li>';
			}

			$report .= '</ul></dd>';
		}

		if ( ! empty( $outdated_or_unknown ) ) {
			$report .= '<dd><p>' . __( 'Existing theme overrides that may need revision:', 'tribe-common' ) . '</p><ul>';

			foreach ( $outdated_or_unknown as $view_name => $version ) {
				$version_note = empty( $version )
					? __( 'version data missing from override', 'tribe-common' )
					: sprintf( __( 'based on %s version', 'tribe-common' ), $version );

				$report .= '<li>' . esc_html( $view_name ) . ' (' . $version_note . ') </li>';
			}

			$report .= '</ul></dd>';
		}

		self::$plugin_reports[ $plugin_name ] = $report;
	}

	/**
	 * Wraps the individual plugin template reports ready for display.
	 */
	protected static function wrap_report() {
		if ( empty( self::$plugin_reports ) ) {
			self::$complete_report = '<p>' . __( 'No notable template changes detected.', 'tribe-common' ) . '</p>';
		} else {
			self::$complete_report = '<p>' . __( 'Information about recent template changes and potentially impacted template overrides is provided below.', 'tribe-common' ) . '</p>'
				. '<div class="template-updates-wrapper">' . join( ' ', self::$plugin_reports ) . '</div>';
		}
	}
}
