<?php
/**
 * Display functions (template-tags) for use in WordPress templates.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__Main' ) ) {
	return;
}

/**
 * Get Options
 *
 * Retrieve specific key from options array, optionally provide a default return value
 *
 * @category Events
 * @param string $optionName Name of the option to retrieve.
 * @param string $default    Value to return if no such option is found.
 *
 * @return mixed Value of the option if found.
 * @todo Abstract this function out of template tags or otherwise secure it from other namespace conflicts.
 */
function tribe_get_option( $optionName, $default = '' ) {
	return apply_filters( 'tribe_get_option', Tribe__Settings_Manager::get_option( $optionName, $default ), $optionName, $default );
}

/**
 * Update Option
 *
 * Set specific key from options array, optionally provide a default return value
 *
 * @category Events
 * @param string $optionName Name of the option to retrieve.
 * @param string $value      Value to save
 *
 * @return void
 */
function tribe_update_option( $optionName, $value ) {
	Tribe__Settings_Manager::set_option( $optionName, $value );
}

/**
 * Get Network Options
 *
 * Retrieve specific key from options array, optionally provide a default return value
 *
 * @category Events
 * @param string $optionName Name of the option to retrieve.
 * @param string $default    Value to return if no such option is found.
 *
 * @return mixed Value of the option if found.
 * @todo Abstract this function out of template tags or otherwise secure it from other namespace conflicts.
 */
function tribe_get_network_option( $optionName, $default = '' ) {
	return Tribe__Settings_Manager::get_network_option( $optionName, $default );
}

/**
 * Returns or echoes a url to a file in the Events Calendar plugin resources directory
 *
 * @category Events
 * @param string $resource the filename of the resource
 * @param bool   $echo     whether or not to echo the url
 *
 * @return string
 **/
function tribe_resource_url( $resource, $echo = false ) {
	$extension = pathinfo( $resource, PATHINFO_EXTENSION );
	$resources_path = 'src/resources/';
	switch ( $extension ) {
		case 'css':
			$resource_path = $resources_path .'css/';
			break;
		case 'js':
			$resource_path = $resources_path .'js/';
			break;
		case 'scss':
			$resource_path = $resources_path .'scss/';
			break;
		default:
			$resource_path = $resources_path;
			break;
	}

	$path = $resource_path . $resource;

	$plugin_path = trailingslashit( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
	$plugin_dir  = trailingslashit( basename( $plugin_path ) );
	$url  = plugins_url( $plugin_dir );

	/**
	 * Filters the resource URL
	 *
	 * @param $url
	 * @param $resource
	 */
	$url = apply_filters( 'tribe_resource_url', $url . $path, $resource );

	/**
	 * Deprected the tribe_events_resource_url filter in 4.0 in favor of tribe_resource_url. Remove in 5.0
	 */
	$url = apply_filters( 'tribe_events_resource_url', $url, $resource );

	if ( $echo ) {
		echo $url;
	}

	return $url;
}

/**
 * helper function to remove empty lines from multi-line strings
 *
 * @category Events
 * @link http://stackoverflow.com/questions/709669/how-do-i-remove-blank-lines-from-text-in-php
 *
 * @param string $multi_line_string a multiline string
 *
 * @return string the same string without empty lines
 */
function tribe_multi_line_remove_empty_lines( $multi_line_string ) {
	return preg_replace( "/^\n+|^[\t\s]*\n+/m", '', $multi_line_string );
}

/**
 * Get the date format specified in the tribe options
 *
 * @category Events
 * @param bool $with_year
 *
 * @return mixed
 */
function tribe_get_date_format( $with_year = false ) {
	if ( $with_year ) {
		$format = tribe_get_option( 'dateWithYearFormat', get_option( 'date_format' ) );
	} else {
		$format = tribe_get_option( 'dateWithoutYearFormat', 'F j' );
	}

	// Strip slashes - otherwise the slashes for escaped characters will themselves be escaped
	return apply_filters( 'tribe_date_format', stripslashes( $format ) );
}

/**
 * Get the Datetime Format
 *
 * @category Events
 *
 * @param bool $with_year
 *
 * @return mixed|void
 */
function tribe_get_datetime_format( $with_year = false ) {
	$separator = (array) str_split( tribe_get_option( 'dateTimeSeparator', ' @ ' ) );

	$format = tribe_get_date_format( $with_year );
	$format .= ( ! empty( $separator ) ? '\\' : '' ) . implode( '\\', $separator );
	$format .= get_option( 'time_format' );

	return apply_filters( 'tribe_datetime_format', $format );

}

/**
 * Get the time format
 *
 * @category Events
 *
 * @return mixed|void
 */
function tribe_get_time_format( ) {
	$format = get_option( 'time_format' );
	return apply_filters( 'tribe_time_format', $format );
}

/**
 * Accepts two dates and returns the number of days between them
 *
 * @category Events
 *
 * @param string      $start_date
 * @param string      $end_date
 * @param string|bool $day_cutoff
 *
 * @return int
 * @see Tribe__Date_Utils::date_diff()
 **/
function tribe_get_days_between( $start_date, $end_date, $day_cutoff = '00:00' ) {
	if ( $day_cutoff === false ) {
		$day_cutoff = '00:00';
	} elseif ( $day_cutoff === true ) {
		$day_cutoff = tribe_get_option( 'multiDayCutoff', '00:00' );
	}

	$start_date = new DateTime( $start_date );
	if ( $start_date < new DateTime( $start_date->format( 'Y-m-d ' . $day_cutoff ) ) ) {
		$start_date->modify( '-1 day' );
	}
	$end_date = new DateTime( $end_date );
	if ( $end_date <= new DateTime( $end_date->format( 'Y-m-d ' . $day_cutoff ) ) ) {
		$end_date->modify( '-1 day' );
	}

	return Tribe__Date_Utils::date_diff( $start_date->format( 'Y-m-d ' . $day_cutoff ), $end_date->format( 'Y-m-d ' . $day_cutoff ) );
}

/**
 * Function to prepare content for use as a value in a json encoded string destined for storage on a html data attribute.
 * Hence the double quote fun, especially in case they pass html encoded &quot; along. Any of those getting through to the data att will break jquery's parseJSON method.
 * Themers can use this function to prepare data they may want to send to tribe_events_template_data() in the templates, and we use it in that function ourselves.
 *
 * @category Events
 *
 * @param $string
 *
 * @return string
 */

function tribe_prepare_for_json( $string ) {

	$value = trim( htmlspecialchars( $string, ENT_QUOTES, 'UTF-8' ) );
	$value = str_replace( '&quot;', '"', $value );

	return $value;
}

/**
 * Recursively iterate through an nested structure, calling
 * tribe_prepare_for_json() on all scalar values
 *
 * @category Events
 *
 * @param mixed $value The data to be cleaned
 *
 * @return mixed The clean data
 */
function tribe_prepare_for_json_deep( $value ) {
	if ( is_array( $value ) ) {
		$value = array_map( 'tribe_prepare_for_json_deep', $value );
	} elseif ( is_object( $value ) ) {
		$vars = get_object_vars( $value );
		foreach ( $vars as $key => $data ) {
			$value->{$key} = tribe_prepare_for_json_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = tribe_prepare_for_json( $value );
	}
	return $value;
}

/**
 * Generates html for any notices that have been queued on the current view
 *
 * @category Events
 *
 * @param bool $echo Whether or not to echo the notices html
 *
 * @return void | string
 * @see Tribe__Notices::get()
 **/
function tribe_the_notices( $echo = true ) {
	$notices = Tribe__Notices::get();

	$html        = ! empty( $notices ) ? '<div class="tribe-events-notices"><ul><li>' . implode( '</li><li>', $notices ) . '</li></ul></div>' : '';

	/**
	 * Deprecated the tribe_events_the_notices filter in 4.0 in favor of tribe_the_notices. Remove in 5.0
	 */
	$the_notices = apply_filters( 'tribe_events_the_notices', $html, $notices );

	/**
	 * filters the notices HTML
	 */
	$the_notices = apply_filters( 'tribe_the_notices', $html, $notices );
	if ( $echo ) {
		echo $the_notices;
	} else {
		return $the_notices;
	}
}

/**
 * tribe_is_bot checks if the visitor is a bot and returns status
 *
 * @category Events
 *
 * @return bool
 */
function tribe_is_bot() {
	// get the current user agent
	$user_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

	// check if the user agent is empty since most browsers identify themselves, so possibly a bot
	if ( empty( $user_agent ) ) {
		return apply_filters( 'tribe_is_bot_status', true, $user_agent, null );
	}

	// declare known bot user agents (lowercase)
	$user_agent_bots = (array) apply_filters(
		'tribe_is_bot_list', array(
			'bot',
			'slurp',
			'spider',
			'crawler',
			'yandex',
		)
	);

	foreach ( $user_agent_bots as $bot ) {
		if ( stripos( $user_agent, $bot ) !== false ) {
			return apply_filters( 'tribe_is_bot_status', true, $user_agent, $bot );
		}
	}

	// we think this is probably a real human
	return apply_filters( 'tribe_is_bot_status', false, $user_agent, null );
}

/**
 * Count keys in a hierarchical array
 *
 * @param $value
 * @param $key
 * @todo - remove, only used in the meta walker
 */
function tribe_count_hierarchical_keys( $value, $key ) {
	global $tribe_count_hierarchical_increment;
	$tribe_count_hierarchical_increment++;
}

/**
 * Count items in a hierarchical array
 *
 * @param array $walk
 *
 * @return int
 * @todo - remove, only used in the meta walker
 */
function tribe_count_hierarchical( array $walk ) {
	global $tribe_count_hierarchical_increment;
	$tribe_count_hierarchical_increment = 0;
	array_walk_recursive( $walk, 'tribe_count_hierarchical_keys' );

	return $tribe_count_hierarchical_increment;
}

/**
 * Mobile breakpoint
 *
 * Get the breakpoint for switching to mobile styles. Defaults to 768.
 *
 * @category Events
 *
 * @param int $default The default width (in pixels) at which to break into mobile styles
 *
 * @return int
 */
function tribe_get_mobile_breakpoint( $default = 768 ) {
	return apply_filters( 'tribe_events_mobile_breakpoint', $default );
}

/**
 * Gets the render context of the given query
 *
 * @param WP_Query $query Query object
 * @return string
 */
function tribe_get_render_context( $query = null ) {
	global $wp_query;

	if ( ! $query instanceof WP_Query ) {
		$query = $wp_query;
	}

	if ( empty( $query->query['tribe_render_context'] ) ) {
		return 'default';
	}

	return $query->query['tribe_render_context'];
}
