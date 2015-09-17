<?php
/**
 * Various helper methods used in views
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__View_Helpers' ) ) {
	class Tribe__View_Helpers {

		/**
		 * Get the countries being used and available for the plugin.
		 *
		 * @param string $postId     The post ID.
		 * @param bool   $useDefault Should we use the defaults?
		 *
		 * @return array The countries array.
		 */
		public static function constructCountries( $postId = '', $useDefault = true ) {

			if ( tribe_get_option( 'tribeEventsCountries' ) != '' ) {
				$countries = array(
					'' => __( 'Select a Country:', 'tribe-common' ),
				);

				$country_rows = explode( "\n", tribe_get_option( 'tribeEventsCountries' ) );
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

			if ( ! isset( $countries ) || ! is_array( $countries ) || count( $countries ) == 1 ) {
				$countries = array(
					''   => __( 'Select a Country:', 'tribe-common' ),
					'US' => __( 'United States', 'tribe-common' ),
					'AF' => __( 'Afghanistan', 'tribe-common' ),
					'AL' => __( 'Albania', 'tribe-common' ),
					'DZ' => __( 'Algeria', 'tribe-common' ),
					'AS' => __( 'American Samoa', 'tribe-common' ),
					'AD' => __( 'Andorra', 'tribe-common' ),
					'AO' => __( 'Angola', 'tribe-common' ),
					'AI' => __( 'Anguilla', 'tribe-common' ),
					'AQ' => __( 'Antarctica', 'tribe-common' ),
					'AG' => __( 'Antigua And Barbuda', 'tribe-common' ),
					'AR' => __( 'Argentina', 'tribe-common' ),
					'AM' => __( 'Armenia', 'tribe-common' ),
					'AW' => __( 'Aruba', 'tribe-common' ),
					'AU' => __( 'Australia', 'tribe-common' ),
					'AT' => __( 'Austria', 'tribe-common' ),
					'AZ' => __( 'Azerbaijan', 'tribe-common' ),
					'BS' => __( 'Bahamas', 'tribe-common' ),
					'BH' => __( 'Bahrain', 'tribe-common' ),
					'BD' => __( 'Bangladesh', 'tribe-common' ),
					'BB' => __( 'Barbados', 'tribe-common' ),
					'BY' => __( 'Belarus', 'tribe-common' ),
					'BE' => __( 'Belgium', 'tribe-common' ),
					'BZ' => __( 'Belize', 'tribe-common' ),
					'BJ' => __( 'Benin', 'tribe-common' ),
					'BM' => __( 'Bermuda', 'tribe-common' ),
					'BT' => __( 'Bhutan', 'tribe-common' ),
					'BO' => __( 'Bolivia', 'tribe-common' ),
					'BA' => __( 'Bosnia And Herzegowina', 'tribe-common' ),
					'BW' => __( 'Botswana', 'tribe-common' ),
					'BV' => __( 'Bouvet Island', 'tribe-common' ),
					'BR' => __( 'Brazil', 'tribe-common' ),
					'IO' => __( 'British Indian Ocean Territory', 'tribe-common' ),
					'BN' => __( 'Brunei Darussalam', 'tribe-common' ),
					'BG' => __( 'Bulgaria', 'tribe-common' ),
					'BF' => __( 'Burkina Faso', 'tribe-common' ),
					'BI' => __( 'Burundi', 'tribe-common' ),
					'KH' => __( 'Cambodia', 'tribe-common' ),
					'CM' => __( 'Cameroon', 'tribe-common' ),
					'CA' => __( 'Canada', 'tribe-common' ),
					'CV' => __( 'Cape Verde', 'tribe-common' ),
					'KY' => __( 'Cayman Islands', 'tribe-common' ),
					'CF' => __( 'Central African Republic', 'tribe-common' ),
					'TD' => __( 'Chad', 'tribe-common' ),
					'CL' => __( 'Chile', 'tribe-common' ),
					'CN' => __( 'China', 'tribe-common' ),
					'CX' => __( 'Christmas Island', 'tribe-common' ),
					'CC' => __( 'Cocos (Keeling) Islands', 'tribe-common' ),
					'CO' => __( 'Colombia', 'tribe-common' ),
					'KM' => __( 'Comoros', 'tribe-common' ),
					'CG' => __( 'Congo', 'tribe-common' ),
					'CD' => __( 'Congo, The Democratic Republic Of The', 'tribe-common' ),
					'CK' => __( 'Cook Islands', 'tribe-common' ),
					'CR' => __( 'Costa Rica', 'tribe-common' ),
					'CI' => __( "Cote D'Ivoire", 'tribe-common' ),
					'HR' => __( 'Croatia (Local Name: Hrvatska)', 'tribe-common' ),
					'CU' => __( 'Cuba', 'tribe-common' ),
					'CY' => __( 'Cyprus', 'tribe-common' ),
					'CZ' => __( 'Czech Republic', 'tribe-common' ),
					'DK' => __( 'Denmark', 'tribe-common' ),
					'DJ' => __( 'Djibouti', 'tribe-common' ),
					'DM' => __( 'Dominica', 'tribe-common' ),
					'DO' => __( 'Dominican Republic', 'tribe-common' ),
					'TP' => __( 'East Timor', 'tribe-common' ),
					'EC' => __( 'Ecuador', 'tribe-common' ),
					'EG' => __( 'Egypt', 'tribe-common' ),
					'SV' => __( 'El Salvador', 'tribe-common' ),
					'GQ' => __( 'Equatorial Guinea', 'tribe-common' ),
					'ER' => __( 'Eritrea', 'tribe-common' ),
					'EE' => __( 'Estonia', 'tribe-common' ),
					'ET' => __( 'Ethiopia', 'tribe-common' ),
					'FK' => __( 'Falkland Islands (Malvinas)', 'tribe-common' ),
					'FO' => __( 'Faroe Islands', 'tribe-common' ),
					'FJ' => __( 'Fiji', 'tribe-common' ),
					'FI' => __( 'Finland', 'tribe-common' ),
					'FR' => __( 'France', 'tribe-common' ),
					'FX' => __( 'France, Metropolitan', 'tribe-common' ),
					'GF' => __( 'French Guiana', 'tribe-common' ),
					'PF' => __( 'French Polynesia', 'tribe-common' ),
					'TF' => __( 'French Southern Territories', 'tribe-common' ),
					'GA' => __( 'Gabon', 'tribe-common' ),
					'GM' => __( 'Gambia', 'tribe-common' ),
					'GE' => __( 'Georgia', 'tribe-common' ),
					'DE' => __( 'Germany', 'tribe-common' ),
					'GH' => __( 'Ghana', 'tribe-common' ),
					'GI' => __( 'Gibraltar', 'tribe-common' ),
					'GR' => __( 'Greece', 'tribe-common' ),
					'GL' => __( 'Greenland', 'tribe-common' ),
					'GD' => __( 'Grenada', 'tribe-common' ),
					'GP' => __( 'Guadeloupe', 'tribe-common' ),
					'GU' => __( 'Guam', 'tribe-common' ),
					'GT' => __( 'Guatemala', 'tribe-common' ),
					'GN' => __( 'Guinea', 'tribe-common' ),
					'GW' => __( 'Guinea-Bissau', 'tribe-common' ),
					'GY' => __( 'Guyana', 'tribe-common' ),
					'HT' => __( 'Haiti', 'tribe-common' ),
					'HM' => __( 'Heard And Mc Donald Islands', 'tribe-common' ),
					'VA' => __( 'Holy See (Vatican City State)', 'tribe-common' ),
					'HN' => __( 'Honduras', 'tribe-common' ),
					'HK' => __( 'Hong Kong', 'tribe-common' ),
					'HU' => __( 'Hungary', 'tribe-common' ),
					'IS' => __( 'Iceland', 'tribe-common' ),
					'IN' => __( 'India', 'tribe-common' ),
					'ID' => __( 'Indonesia', 'tribe-common' ),
					'IR' => __( 'Iran (Islamic Republic Of)', 'tribe-common' ),
					'IQ' => __( 'Iraq', 'tribe-common' ),
					'IE' => __( 'Ireland', 'tribe-common' ),
					'IL' => __( 'Israel', 'tribe-common' ),
					'IT' => __( 'Italy', 'tribe-common' ),
					'JM' => __( 'Jamaica', 'tribe-common' ),
					'JP' => __( 'Japan', 'tribe-common' ),
					'JO' => __( 'Jordan', 'tribe-common' ),
					'KZ' => __( 'Kazakhstan', 'tribe-common' ),
					'KE' => __( 'Kenya', 'tribe-common' ),
					'KI' => __( 'Kiribati', 'tribe-common' ),
					'KP' => __( "Korea, Democratic People's Republic Of", 'tribe-common' ),
					'KR' => __( 'Korea, Republic Of', 'tribe-common' ),
					'KW' => __( 'Kuwait', 'tribe-common' ),
					'KG' => __( 'Kyrgyzstan', 'tribe-common' ),
					'LA' => __( "Lao People's Democratic Republic", 'tribe-common' ),
					'LV' => __( 'Latvia', 'tribe-common' ),
					'LB' => __( 'Lebanon', 'tribe-common' ),
					'LS' => __( 'Lesotho', 'tribe-common' ),
					'LR' => __( 'Liberia', 'tribe-common' ),
					'LY' => __( 'Libya', 'tribe-common' ),
					'LI' => __( 'Liechtenstein', 'tribe-common' ),
					'LT' => __( 'Lithuania', 'tribe-common' ),
					'LU' => __( 'Luxembourg', 'tribe-common' ),
					'MO' => __( 'Macau', 'tribe-common' ),
					'MK' => __( 'Macedonia', 'tribe-common' ),
					'MG' => __( 'Madagascar', 'tribe-common' ),
					'MW' => __( 'Malawi', 'tribe-common' ),
					'MY' => __( 'Malaysia', 'tribe-common' ),
					'MV' => __( 'Maldives', 'tribe-common' ),
					'ML' => __( 'Mali', 'tribe-common' ),
					'MT' => __( 'Malta', 'tribe-common' ),
					'MH' => __( 'Marshall Islands', 'tribe-common' ),
					'MQ' => __( 'Martinique', 'tribe-common' ),
					'MR' => __( 'Mauritania', 'tribe-common' ),
					'MU' => __( 'Mauritius', 'tribe-common' ),
					'YT' => __( 'Mayotte', 'tribe-common' ),
					'MX' => __( 'Mexico', 'tribe-common' ),
					'FM' => __( 'Micronesia, Federated States Of', 'tribe-common' ),
					'MD' => __( 'Moldova, Republic Of', 'tribe-common' ),
					'MC' => __( 'Monaco', 'tribe-common' ),
					'MN' => __( 'Mongolia', 'tribe-common' ),
					'ME' => __( 'Montenegro', 'tribe-common' ),
					'MS' => __( 'Montserrat', 'tribe-common' ),
					'MA' => __( 'Morocco', 'tribe-common' ),
					'MZ' => __( 'Mozambique', 'tribe-common' ),
					'MM' => __( 'Myanmar', 'tribe-common' ),
					'NA' => __( 'Namibia', 'tribe-common' ),
					'NR' => __( 'Nauru', 'tribe-common' ),
					'NP' => __( 'Nepal', 'tribe-common' ),
					'NL' => __( 'Netherlands', 'tribe-common' ),
					'AN' => __( 'Netherlands Antilles', 'tribe-common' ),
					'NC' => __( 'New Caledonia', 'tribe-common' ),
					'NZ' => __( 'New Zealand', 'tribe-common' ),
					'NI' => __( 'Nicaragua', 'tribe-common' ),
					'NE' => __( 'Niger', 'tribe-common' ),
					'NG' => __( 'Nigeria', 'tribe-common' ),
					'NU' => __( 'Niue', 'tribe-common' ),
					'NF' => __( 'Norfolk Island', 'tribe-common' ),
					'MP' => __( 'Northern Mariana Islands', 'tribe-common' ),
					'NO' => __( 'Norway', 'tribe-common' ),
					'OM' => __( 'Oman', 'tribe-common' ),
					'PK' => __( 'Pakistan', 'tribe-common' ),
					'PW' => __( 'Palau', 'tribe-common' ),
					'PA' => __( 'Panama', 'tribe-common' ),
					'PG' => __( 'Papua New Guinea', 'tribe-common' ),
					'PY' => __( 'Paraguay', 'tribe-common' ),
					'PE' => __( 'Peru', 'tribe-common' ),
					'PH' => __( 'Philippines', 'tribe-common' ),
					'PN' => __( 'Pitcairn', 'tribe-common' ),
					'PL' => __( 'Poland', 'tribe-common' ),
					'PT' => __( 'Portugal', 'tribe-common' ),
					'PR' => __( 'Puerto Rico', 'tribe-common' ),
					'QA' => __( 'Qatar', 'tribe-common' ),
					'RE' => __( 'Reunion', 'tribe-common' ),
					'RO' => __( 'Romania', 'tribe-common' ),
					'RU' => __( 'Russian Federation', 'tribe-common' ),
					'RW' => __( 'Rwanda', 'tribe-common' ),
					'KN' => __( 'Saint Kitts And Nevis', 'tribe-common' ),
					'LC' => __( 'Saint Lucia', 'tribe-common' ),
					'VC' => __( 'Saint Vincent And The Grenadines', 'tribe-common' ),
					'WS' => __( 'Samoa', 'tribe-common' ),
					'SM' => __( 'San Marino', 'tribe-common' ),
					'ST' => __( 'Sao Tome And Principe', 'tribe-common' ),
					'SA' => __( 'Saudi Arabia', 'tribe-common' ),
					'SN' => __( 'Senegal', 'tribe-common' ),
					'RS' => __( 'Serbia', 'tribe-common' ),
					'SC' => __( 'Seychelles', 'tribe-common' ),
					'SL' => __( 'Sierra Leone', 'tribe-common' ),
					'SG' => __( 'Singapore', 'tribe-common' ),
					'SK' => __( 'Slovakia (Slovak Republic)', 'tribe-common' ),
					'SI' => __( 'Slovenia', 'tribe-common' ),
					'SB' => __( 'Solomon Islands', 'tribe-common' ),
					'SO' => __( 'Somalia', 'tribe-common' ),
					'ZA' => __( 'South Africa', 'tribe-common' ),
					'GS' => __( 'South Georgia, South Sandwich Islands', 'tribe-common' ),
					'ES' => __( 'Spain', 'tribe-common' ),
					'LK' => __( 'Sri Lanka', 'tribe-common' ),
					'SH' => __( 'St. Helena', 'tribe-common' ),
					'PM' => __( 'St. Pierre And Miquelon', 'tribe-common' ),
					'SD' => __( 'Sudan', 'tribe-common' ),
					'SR' => __( 'Suriname', 'tribe-common' ),
					'SJ' => __( 'Svalbard And Jan Mayen Islands', 'tribe-common' ),
					'SZ' => __( 'Swaziland', 'tribe-common' ),
					'SE' => __( 'Sweden', 'tribe-common' ),
					'CH' => __( 'Switzerland', 'tribe-common' ),
					'SY' => __( 'Syrian Arab Republic', 'tribe-common' ),
					'TW' => __( 'Taiwan', 'tribe-common' ),
					'TJ' => __( 'Tajikistan', 'tribe-common' ),
					'TZ' => __( 'Tanzania, United Republic Of', 'tribe-common' ),
					'TH' => __( 'Thailand', 'tribe-common' ),
					'TG' => __( 'Togo', 'tribe-common' ),
					'TK' => __( 'Tokelau', 'tribe-common' ),
					'TO' => __( 'Tonga', 'tribe-common' ),
					'TT' => __( 'Trinidad And Tobago', 'tribe-common' ),
					'TN' => __( 'Tunisia', 'tribe-common' ),
					'TR' => __( 'Turkey', 'tribe-common' ),
					'TM' => __( 'Turkmenistan', 'tribe-common' ),
					'TC' => __( 'Turks And Caicos Islands', 'tribe-common' ),
					'TV' => __( 'Tuvalu', 'tribe-common' ),
					'UG' => __( 'Uganda', 'tribe-common' ),
					'UA' => __( 'Ukraine', 'tribe-common' ),
					'AE' => __( 'United Arab Emirates', 'tribe-common' ),
					'GB' => __( 'United Kingdom', 'tribe-common' ),
					'UM' => __( 'United States Minor Outlying Islands', 'tribe-common' ),
					'UY' => __( 'Uruguay', 'tribe-common' ),
					'UZ' => __( 'Uzbekistan', 'tribe-common' ),
					'VU' => __( 'Vanuatu', 'tribe-common' ),
					'VE' => __( 'Venezuela', 'tribe-common' ),
					'VN' => __( 'Viet Nam', 'tribe-common' ),
					'VG' => __( 'Virgin Islands (British)', 'tribe-common' ),
					'VI' => __( 'Virgin Islands (U.S.)', 'tribe-common' ),
					'WF' => __( 'Wallis And Futuna Islands', 'tribe-common' ),
					'EH' => __( 'Western Sahara', 'tribe-common' ),
					'YE' => __( 'Yemen', 'tribe-common' ),
					'ZM' => __( 'Zambia', 'tribe-common' ),
					'ZW' => __( 'Zimbabwe', 'tribe-common' ),
				);
			}
			if ( ( $postId || $useDefault ) ) {
				$countryValue = get_post_meta( $postId, '_EventCountry', true );
				if ( $countryValue ) {
					$defaultCountry = array( array_search( $countryValue, $countries ), $countryValue );
				} else {
					$defaultCountry = tribe_get_default_value( 'country' );
				}
				if ( $defaultCountry && $defaultCountry[0] != '' ) {
					$selectCountry = array_shift( $countries );
					asort( $countries );
					$countries = array( $defaultCountry[0] => __( $defaultCountry[1], 'tribe-common' ) ) + $countries;
					$countries = array( '' => __( $selectCountry, 'tribe-common' ) ) + $countries;
					array_unique( $countries );
				}

				return $countries;
			} else {
				return $countries;
			}
		}

		/**
		 * Get the i18ned states available to the plugin.
		 *
		 * @return array The states array.
		 */
		public static function loadStates() {
			return array(
				'AL' => __( 'Alabama', 'tribe-common' ),
				'AK' => __( 'Alaska', 'tribe-common' ),
				'AZ' => __( 'Arizona', 'tribe-common' ),
				'AR' => __( 'Arkansas', 'tribe-common' ),
				'CA' => __( 'California', 'tribe-common' ),
				'CO' => __( 'Colorado', 'tribe-common' ),
				'CT' => __( 'Connecticut', 'tribe-common' ),
				'DE' => __( 'Delaware', 'tribe-common' ),
				'DC' => __( 'District of Columbia', 'tribe-common' ),
				'FL' => __( 'Florida', 'tribe-common' ),
				'GA' => __( 'Georgia', 'tribe-common' ),
				'HI' => __( 'Hawaii', 'tribe-common' ),
				'ID' => __( 'Idaho', 'tribe-common' ),
				'IL' => __( 'Illinois', 'tribe-common' ),
				'IN' => __( 'Indiana', 'tribe-common' ),
				'IA' => __( 'Iowa', 'tribe-common' ),
				'KS' => __( 'Kansas', 'tribe-common' ),
				'KY' => __( 'Kentucky', 'tribe-common' ),
				'LA' => __( 'Louisiana', 'tribe-common' ),
				'ME' => __( 'Maine', 'tribe-common' ),
				'MD' => __( 'Maryland', 'tribe-common' ),
				'MA' => __( 'Massachusetts', 'tribe-common' ),
				'MI' => __( 'Michigan', 'tribe-common' ),
				'MN' => __( 'Minnesota', 'tribe-common' ),
				'MS' => __( 'Mississippi', 'tribe-common' ),
				'MO' => __( 'Missouri', 'tribe-common' ),
				'MT' => __( 'Montana', 'tribe-common' ),
				'NE' => __( 'Nebraska', 'tribe-common' ),
				'NV' => __( 'Nevada', 'tribe-common' ),
				'NH' => __( 'New Hampshire', 'tribe-common' ),
				'NJ' => __( 'New Jersey', 'tribe-common' ),
				'NM' => __( 'New Mexico', 'tribe-common' ),
				'NY' => __( 'New York', 'tribe-common' ),
				'NC' => __( 'North Carolina', 'tribe-common' ),
				'ND' => __( 'North Dakota', 'tribe-common' ),
				'OH' => __( 'Ohio', 'tribe-common' ),
				'OK' => __( 'Oklahoma', 'tribe-common' ),
				'OR' => __( 'Oregon', 'tribe-common' ),
				'PA' => __( 'Pennsylvania', 'tribe-common' ),
				'RI' => __( 'Rhode Island', 'tribe-common' ),
				'SC' => __( 'South Carolina', 'tribe-common' ),
				'SD' => __( 'South Dakota', 'tribe-common' ),
				'TN' => __( 'Tennessee', 'tribe-common' ),
				'TX' => __( 'Texas', 'tribe-common' ),
				'UT' => __( 'Utah', 'tribe-common' ),
				'VT' => __( 'Vermont', 'tribe-common' ),
				'VA' => __( 'Virginia', 'tribe-common' ),
				'WA' => __( 'Washington', 'tribe-common' ),
				'WV' => __( 'West Virginia', 'tribe-common' ),
				'WI' => __( 'Wisconsin', 'tribe-common' ),
				'WY' => __( 'Wyoming', 'tribe-common' ),
			);
		}

		/**
		 * Builds a set of options for displaying an hour chooser
		 *
		 * @param string $date the current date (optional)
		 * @param bool   $isStart
		 *
		 * @return string a set of HTML options with hours (current hour selected)
		 */
		public static function getHourOptions( $date = '', $isStart = false ) {
			$hours = self::hours();

			if ( count( $hours ) == 12 ) {
				$h = 'h';
			} else {
				$h = 'H';
			}
			$options = '';

			if ( empty( $date ) ) {
				$hour = ( $isStart ) ? '08' : ( count( $hours ) == 12 ? '05' : '17' );
			} else {
				$timestamp = strtotime( $date );
				$hour      = date( $h, $timestamp );
				// fix hours if time_format has changed from what is saved
				if ( preg_match( '(pm|PM)', $timestamp ) && $h == 'H' ) {
					$hour = $hour + 12;
				}
				if ( $hour > 12 && $h == 'h' ) {
					$hour = $hour - 12;
				}
			}

			$hour = apply_filters( 'tribe_get_hour_options', $hour, $date, $isStart );

			foreach ( $hours as $hourText ) {
				if ( $hour == $hourText ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$options .= "<option value='$hourText' $selected>$hourText</option>\n";
			}

			return $options;
		}

		/**
		 * Builds a set of options for displaying a minute chooser
		 *
		 * @param string $date the current date (optional)
		 * @param bool   $isStart
		 *
		 * @return string a set of HTML options with minutes (current minute selected)
		 */
		public static function getMinuteOptions( $date = '', $isStart = false ) {
			$minutes = self::minutes();
			$options = '';

			if ( empty( $date ) ) {
				$minute = '00';
			} else {
				$minute = date( 'i', strtotime( $date ) );
			}

			$minute = apply_filters( 'tribe_get_minute_options', $minute, $date, $isStart );

			foreach ( $minutes as $minuteText ) {
				if ( $minute == $minuteText ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$options .= "<option value='$minuteText' $selected>$minuteText</option>\n";
			}

			return $options;
		}

		/**
		 * Helper method to return an array of 1-12 for hours
		 *
		 * @return array The hours array.
		 */
		private static function hours() {
			$hours      = array();
			$rangeMax   = self::is_24hr_format() ? 23 : 12;
			$rangeStart = $rangeMax > 12 ? 0 : 1;
			foreach ( range( $rangeStart, $rangeMax ) as $hour ) {
				if ( $hour < 10 ) {
					$hour = '0' . $hour;
				}
				$hours[ $hour ] = $hour;
			}

			// In a 12hr context lets put 12 at the start (so the sequence will run 12, 1, 2, 3 ... 11)
			if ( 12 === $rangeMax ) {
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
		 * @param null $format
		 * @return bool
		 */
		public static function is_24hr_format( $format = null ) {
			// Use the provided format or else use the value of the current time_format setting
			$format = ( null === $format ) ? get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ) : $format;

			// Count instances of the H and G symbols
			$h_symbols = substr_count( $format, 'H' );
			$g_symbols = substr_count( $format, 'G' );

			// If none have been found then consider the format to be 12hr
			if ( ! $h_symbols && ! $g_symbols ) return false;

			// It's possible H or G have been included as escaped characters
			$h_escaped = substr_count( $format, '\H' );
			$g_escaped = substr_count( $format, '\G' );

			// Final check, accounting for possibility of escaped values
			return ( $h_symbols > $h_escaped || $g_symbols > $g_escaped );
		}

		/**
		 * Helper method to return an array of 00-59 for minutes
		 *
		 * @return array The minutes array.
		 */
		private static function minutes() {
			$minutes = array();
			/**
			 * Filters the amount of minutes to increment the minutes drop-down by
			 *
			 * @param int Increment amount (defaults to 5)
			 */
			$increment = apply_filters( 'tribe_minutes_increment', 5 );
			for ( $minute = 0; $minute < 60; $minute += $increment ) {
				if ( $minute < 10 ) {
					$minute = '0' . $minute;
				}
				$minutes[ $minute ] = $minute;
			}

			return $minutes;
		}

		/**
		 * Builds a set of options for diplaying a meridian chooser
		 *
		 * @param string $date YYYY-MM-DD HH:MM:SS to select (optional)
		 * @param bool   $isStart
		 *
		 * @return string a set of HTML options with all meridians
		 */
		public static function getMeridianOptions( $date = '', $isStart = false ) {
			if ( strstr( get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ), 'A' ) ) {
				$a         = 'A';
				$meridians = array( 'AM', 'PM' );
			} else {
				$a         = 'a';
				$meridians = array( 'am', 'pm' );
			}
			if ( empty( $date ) ) {
				$meridian = ( $isStart ) ? $meridians[0] : $meridians[1];
			} else {
				$meridian = date( $a, strtotime( $date ) );
			}

			$meridian = apply_filters( 'tribe_get_meridian_options', $meridian, $date, $isStart );

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
			$years         = array();
			for ( $i = $years_back; $i > 0; $i -- ) {
				$year    = $current_year - $i;
				$years[] = $year;
			}
			$years[] = $current_year;
			for ( $i = 1; $i <= $years_forward; $i ++ ) {
				$year    = $current_year + $i;
				$years[] = $year;
			}

			return (array) apply_filters( 'tribe_years_array', $years );
		}

		/**
		 * Helper method to return an array of 1-31 for days
		 *
		 * @return array The days array.
		 */
		public static function days( $totalDays ) {
			$days = array();
			foreach ( range( 1, $totalDays ) as $day ) {
				$days[ $day ] = $day;
			}

			return $days;
		}
	}
}
