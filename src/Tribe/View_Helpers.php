<?php
/**
 * Various helper methods used in views
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PEAR.NamingConventions.ValidClassName.Invalid
// phpcs:disable TEC.Classes.ValidClassName.NotSnakeCase
// phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
// phpcs:disable WordPress.DateTime.RestrictedFunctions.date_date

if ( ! class_exists( 'Tribe__View_Helpers' ) ) {
	/**
	 * Class Tribe__View_Helpers
	 */
	class Tribe__View_Helpers {

		/**
		 * Get the countries being used and available for the plugin.
		 *
		 * @param string $post_id     The post ID.
		 * @param bool   $use_default If we should use the defaults.
		 *
		 * @return array The countries array.
		 */
		public static function constructCountries( $post_id = '', $use_default = true ) {
			static $cache_var_name = __METHOD__;

			$countries = tribe_get_var( $cache_var_name, null );

			if ( $countries ) {
				return $countries;
			}

			$event_countries = tribe_get_option( 'tribeEventsCountries' );

			if ( '' !== $event_countries ) {
				$countries = [];

				$country_rows = explode( "\n", $event_countries );
				foreach ( $country_rows as $crow ) {
					$country = explode( ',', $crow );
					if ( isset( $country[0] ) && isset( $country[1] ) ) {
						$country[0] = trim( $country[0] );
						$country[1] = trim( $country[1] );

						if ( $country[0] && $country[1] ) {
							$countries[ $country[0] ] = $country[1];
						}
					}
				}
			}

			if ( ! isset( $countries ) || ! is_array( $countries ) || count( $countries ) < 1 ) {
				$countries = tribe( 'languages.locations' )->get_countries();
			}

			// Perform a natural sort: this maintains the key -> index associations but ensures the countries
			// are in the expected order, even once translated.
			natsort( $countries );

			// Placeholder option ('Select a Country') first by default.
			$select_country = [ '' => esc_html__( 'Select a Country:', 'tribe-common' ) ];
			$countries      = $select_country + $countries;

			if ( ( $post_id || $use_default ) ) {
				$country_value = get_post_meta( $post_id, '_EventCountry', true );
				if ( $country_value ) {
					$default_country = [ array_search( $country_value, $countries ), $country_value ];
				} else {
					// @TODO: This function lives in TEC, this should not be used here.
					$default_country = tribe_get_default_value( 'country' );
				}

				if ( $default_country && '' !== $default_country[0] ) {
					$select_country = array_shift( $countries );
					asort( $countries );
					$countries = [ $default_country[0] => $default_country[1] ] + $countries;
					$countries = [ '' => $select_country ] + $countries;
					array_unique( $countries );
				}
			}

			tribe_set_var( $cache_var_name, $countries );

			return $countries;
		}

		/**
		 * Get the i18ned states available to the plugin.
		 *
		 * @return array The states array.
		 */
		public static function loadStates() {
			$states = tribe( 'languages.locations' )->get_us_states();

			/**
			 * Enables filtering the list of states in the USA available to venues.
			 *
			 * @since 4.5.12
			 *
			 * @param array $states The list of states.
			 */
			return apply_filters( 'tribe_get_state_options', $states );
		}

		/**
		 * Builds a set of options for displaying an hour chooser
		 *
		 * @param string $date the current date (optional).
		 * @param bool   $is_start if this is the start time.
		 *
		 * @return string a set of HTML options with hours (current hour selected)
		 */
		public static function getHourOptions( $date = '', $is_start = false ) {
			$hours = self::hours();

			if ( count( $hours ) == 12 ) {
				$h = 'h';
			} else {
				$h = 'H';
			}
			$options = '';

			if ( empty( $date ) ) {
				$hour = ( $is_start ) ? '08' : ( count( $hours ) == 12 ? '05' : '17' );
			} else {
				$timestamp = strtotime( $date );
				$hour      = date( $h, $timestamp );
				// Fix hours if time_format has changed from what is saved.
				if ( preg_match( '(pm|PM)', $timestamp ) && 'H' === $h ) {
					$hour = $hour + 12;
				}
				if ( $hour > 12 && 'h' === $h ) {
					$hour = $hour - 12;
				}
			}

			$hour = apply_filters( 'tribe_get_hour_options', $hour, $date, $is_start );

			foreach ( $hours as $hour_text ) {
				if ( $hour == $hour_text ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$options .= "<option value='$hour_text' $selected>$hour_text</option>\n";
			}

			return $options;
		}

		/**
		 * Builds a set of options for displaying a minute chooser
		 *
		 * @param string $date     The current date (optional).
		 * @param bool   $is_start If this is the start time.
		 *
		 * @return string a set of HTML options with minutes (current minute selected)
		 */
		public static function getMinuteOptions( $date = '', $is_start = false ) {
			$options = '';

			if ( empty( $date ) ) {
				$minute = '00';
			} else {
				$minute = date( 'i', strtotime( $date ) );
			}

			$minute  = apply_filters( 'tribe_get_minute_options', $minute, $date, $is_start );
			$minutes = self::minutes( $minute );

			foreach ( $minutes as $minute_text ) {
				if ( $minute == $minute_text ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$options .= "<option value='$minute_text' $selected>$minute_text</option>\n";
			}

			return $options;
		}

		/**
		 * Helper method to return an array of 1-12 for hours
		 *
		 * @return array The hours array.
		 */
		private static function hours() {
			$hours       = [];
			$range_max   = self::is_24hr_format() ? 23 : 12;
			$range_start = $range_max > 12 ? 0 : 1;
			foreach ( range( $range_start, $range_max ) as $hour ) {
				if ( $hour < 10 ) {
					$hour = '0' . $hour;
				}
				$hours[ $hour ] = $hour;
			}

			// In a 12hr context lets put 12 at the start (so the sequence will run 12, 1, 2, 3 ... 11).
			if ( 12 === $range_max ) {
				array_unshift( $hours, array_pop( $hours ) );
			}

			return $hours;
		}

		/**
		 * Determines if the provided date/time format (or else the default WordPress time_format)
		 * is 24hr or not.
		 *
		 * In inconclusive cases, such as if there are now hour-format characters, 12hr format is
		 * assumed.
		 *
		 * @param null $format The format to check.
		 *
		 * @return bool
		 */
		public static function is_24hr_format( $format = null ) {
			// Use the provided format or else use the value of the current time_format setting.
			$format = ( null === $format ) ? get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ) : $format;

			// Count instances of the H and G symbols.
			$h_symbols = substr_count( $format, 'H' );
			$g_symbols = substr_count( $format, 'G' );

			// If none have been found then consider the format to be 12hr.
			if ( ! $h_symbols && ! $g_symbols ) {
				return false;
			}

			// It's possible H or G have been included as escaped characters.
			$h_escaped = substr_count( $format, '\H' );
			$g_escaped = substr_count( $format, '\G' );

			// Final check, accounting for possibility of escaped values.
			return ( $h_symbols > $h_escaped || $g_symbols > $g_escaped );
		}

		/**
		 * Helper method to return an array of 00-59 for minutes
		 *
		 * @param int $exact_minute optionally specify an exact minute to be included (outwith the default intervals).
		 *
		 * @return array The minutes array.
		 */
		private static function minutes( $exact_minute = 0 ) {
			$minutes = [];

			// The exact minute should be an absint between 0 and 59.
			$exact_minute = absint( $exact_minute );

			if ( $exact_minute < 0 || $exact_minute > 59 ) {
				$exact_minute = 0;
			}

			/**
			 * Filters the amount of minutes to increment the minutes drop-down by
			 *
			 * @param int Increment amount (defaults to 5)
			 */
			$default_increment = apply_filters( 'tribe_minutes_increment', 5 );

			// Unless an exact minute has been specified we can minimize the amount of looping we do.
			$increment = ( 0 === $exact_minute ) ? $default_increment : 1;

			for ( $minute = 0; $minute < 60; $minute += $increment ) {
				// Skip if this $minute doesn't meet the increment pattern and isn't an additional exact minute.
				if ( 0 !== $minute % $default_increment && $exact_minute !== $minute ) {
					continue;
				}

				if ( $minute < 10 ) {
					$minute = '0' . $minute;
				}
				$minutes[ $minute ] = $minute;
			}

			return $minutes;
		}

		/**
		 * Builds a set of options for displaying a meridian chooser
		 *
		 * @param string $date     YYYY-MM-DD HH:MM:SS to select (optional).
		 * @param bool   $is_start if this is the start time.
		 *
		 * @return string a set of HTML options with all meridians
		 */
		public static function getMeridianOptions( $date = '', $is_start = false ) {
			if ( strstr( get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ), 'A' ) ) {
				$a         = 'A';
				$meridians = [ 'AM', 'PM' ];
			} else {
				$a         = 'a';
				$meridians = [ 'am', 'pm' ];
			}
			if ( empty( $date ) ) {
				$meridian = ( $is_start ) ? $meridians[0] : $meridians[1];
			} else {
				$meridian = date( $a, strtotime( $date ) );
			}

			$meridian = apply_filters( 'tribe_get_meridian_options', $meridian, $date, $is_start );

			$return = '';
			foreach ( $meridians as $m ) {
				$return .= "<option value='$m'";
				if ( $m == $meridian ) {
					$return .= ' selected="selected"';
				}
				$return .= ">$m</option>\n";
			}

			return $return;
		}

		/**
		 * Helper method to return an array of years
		 * default is back 5 and forward 5
		 *
		 * @return array The array of years.
		 */
		private static function years() {
			$current_year  = (int) date_i18n( 'Y' );
			$years_back    = (int) apply_filters( 'tribe_years_to_go_back', 5, $current_year );
			$years_forward = (int) apply_filters( 'tribe_years_to_go_forward', 5, $current_year );
			$years         = [];
			for ( $i = $years_back; $i > 0; $i-- ) {
				$year    = $current_year - $i;
				$years[] = $year;
			}
			$years[] = $current_year;
			for ( $i = 1; $i <= $years_forward; $i++ ) {
				$year    = $current_year + $i;
				$years[] = $year;
			}

			return (array) apply_filters( 'tribe_years_array', $years );
		}

		/**
		 * Helper method to return an array of 1-31 for days.
		 *
		 * @param int $total_days The total days.
		 *
		 * @return array The days array.
		 */
		public static function days( $total_days ) {
			$days = [];
			foreach ( range( 1, $total_days ) as $day ) {
				$days[ $day ] = $day;
			}

			return $days;
		}
	}
}
