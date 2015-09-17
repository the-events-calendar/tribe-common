<?php
/**
 * Date Functions
 *
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
 * Formatted Date
 *
 * Returns formatted date
 *
 * @category Events
 * @param string $date        String representing the datetime, assumed to be UTC (relevant if timezone conversion is used)
 * @param bool   $displayTime If true shows date and time, if false only shows date
 * @param string $dateFormat  Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
 *
 * @return string
 */
function tribe_format_date( $date, $displayTime = true, $dateFormat = '' ) {

	if ( ! Tribe__Date_Utils::is_timestamp( $date ) ) {
		$date = strtotime( $date );
	}

	if ( $dateFormat ) {
		$format = $dateFormat;
	} else {
		$date_year = date( 'Y', $date );
		$cur_year  = date( 'Y', current_time( 'timestamp' ) );

		// only show the year in the date if it's not in the current year
		$with_year = $date_year == $cur_year ? false : true;

		if ( $displayTime ) {
			$format = tribe_get_datetime_format( $with_year );
		} else {
			$format = tribe_get_date_format( $with_year );
		}
	}

	$date = date_i18n( $format, $date );

	/**
	 * Deprecated tribe_event_formatted_date in 4.0 in favor of tribe_formatted_date. Remove in 5.0
	 */
	$date = apply_filters( 'tribe_event_formatted_date', $date, $displayTime, $dateFormat );

	return apply_filters( 'tribe_formatted_date', $date, $displayTime, $dateFormat );
}

/**
 * Returns formatted date for the official beginning of the day according to the Multi-day cutoff time option
 *
 * @category Events
 * @param string $date   The date to find the beginning of the day, defaults to today
 * @param string $format Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
 *
 * @return string
 */
function tribe_beginning_of_day( $date = null, $format = 'Y-m-d H:i:s' ) {
	$multiday_cutoff = explode( ':', tribe_get_option( 'multiDayCutoff', '00:00' ) );
	$hours_to_add    = $multiday_cutoff[0];
	$minutes_to_add  = $multiday_cutoff[1];
	if ( is_null( $date ) || empty( $date ) ) {
		$date = date( $format, strtotime( date( 'Y-m-d' ) . ' +' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) );
	} else {
		$date = date( $format, strtotime( date( 'Y-m-d', strtotime( $date ) ) . ' +' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) );
	}

	/**
	 * Deprecated filter tribe_event_beginning_of_day in 4.0 in favor of tribe_beginning_of_day. Remove in 5.0
	 */
	$date = apply_filters( 'tribe_event_beginning_of_day', $date );

	/**
	 * Filters the beginning of day date
	 *
	 * @param string $date
	 */
	return apply_filters( 'tribe_beginning_of_day', $date );
}

/**
 * Returns formatted date for the official end of the day according to the Multi-day cutoff time option
 *
 * @category Events
 * @param string $date   The date to find the end of the day, defaults to today
 * @param string $format Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
 *
 * @return string
 */
function tribe_end_of_day( $date = null, $format = 'Y-m-d H:i:s' ) {
	$multiday_cutoff = explode( ':', tribe_get_option( 'multiDayCutoff', '00:00' ) );
	$hours_to_add    = $multiday_cutoff[0];
	$minutes_to_add  = $multiday_cutoff[1];
	if ( is_null( $date ) || empty( $date ) ) {
		$date = date( $format, strtotime( 'tomorrow  +' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) - 1 );
	} else {
		$date = date( $format, strtotime( date( 'Y-m-d', strtotime( $date ) ) . ' +1 day ' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) - 1 );
	}

	/**
	 * Deprecated filter tribe_event_end_of_day in 4.0 in favor of tribe_end_of_day. Remove in 5.0
	 */
	$date = apply_filters( 'tribe_event_end_of_day', $date );

	/**
	 * Filters the end of day date
	 *
	 * @param string $date
	 */
	return apply_filters( 'tribe_end_of_day', $date );
}

/**
 * Get the datetime saparator from the database option with escaped characters or not ;)
 *
 * @param string $default Default Separator if it's blank on the Database
 * @param bool $esc If it's going to be used on a `date` function or method it needs to be escaped
 *
 * @filter tribe_datetime_separator
 *
 * @return string
 */
function tribe_get_datetime_separator( $default = ' @ ', $esc = false ) {
	$separator = (string) tribe_get_option( 'dateTimeSeparator', $default );
	if ( $esc ) {
		$separator = (array) str_split( $separator );
		$separator = ( ! empty( $separator ) ? '\\' : '' ) . implode( '\\', $separator );
	}
	return apply_filters( 'tribe_datetime_separator', $separator );
}
