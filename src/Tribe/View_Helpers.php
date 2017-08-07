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
					'' => esc_html__( 'Select a Country:', 'tribe-common' ),
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
					''   => esc_html__( 'Select a Country:', 'tribe-common' ),
					'US' => esc_html__( 'United States', 'tribe-common' ),
					'AF' => esc_html__( 'Afghanistan', 'tribe-common' ),
					'AL' => esc_html__( 'Albania', 'tribe-common' ),
					'DZ' => esc_html__( 'Algeria', 'tribe-common' ),
					'AS' => esc_html__( 'American Samoa', 'tribe-common' ),
					'AD' => esc_html__( 'Andorra', 'tribe-common' ),
					'AO' => esc_html__( 'Angola', 'tribe-common' ),
					'AI' => esc_html__( 'Anguilla', 'tribe-common' ),
					'AQ' => esc_html__( 'Antarctica', 'tribe-common' ),
					'AG' => esc_html__( 'Antigua And Barbuda', 'tribe-common' ),
					'AR' => esc_html__( 'Argentina', 'tribe-common' ),
					'AM' => esc_html__( 'Armenia', 'tribe-common' ),
					'AW' => esc_html__( 'Aruba', 'tribe-common' ),
					'AU' => esc_html__( 'Australia', 'tribe-common' ),
					'AT' => esc_html__( 'Austria', 'tribe-common' ),
					'AZ' => esc_html__( 'Azerbaijan', 'tribe-common' ),
					'BS' => esc_html__( 'Bahamas', 'tribe-common' ),
					'BH' => esc_html__( 'Bahrain', 'tribe-common' ),
					'BD' => esc_html__( 'Bangladesh', 'tribe-common' ),
					'BB' => esc_html__( 'Barbados', 'tribe-common' ),
					'BY' => esc_html__( 'Belarus', 'tribe-common' ),
					'BE' => esc_html__( 'Belgium', 'tribe-common' ),
					'BZ' => esc_html__( 'Belize', 'tribe-common' ),
					'BJ' => esc_html__( 'Benin', 'tribe-common' ),
					'BM' => esc_html__( 'Bermuda', 'tribe-common' ),
					'BT' => esc_html__( 'Bhutan', 'tribe-common' ),
					'BO' => esc_html__( 'Bolivia', 'tribe-common' ),
					'BA' => esc_html__( 'Bosnia And Herzegowina', 'tribe-common' ),
					'BW' => esc_html__( 'Botswana', 'tribe-common' ),
					'BV' => esc_html__( 'Bouvet Island', 'tribe-common' ),
					'BR' => esc_html__( 'Brazil', 'tribe-common' ),
					'IO' => esc_html__( 'British Indian Ocean Territory', 'tribe-common' ),
					'BN' => esc_html__( 'Brunei Darussalam', 'tribe-common' ),
					'BG' => esc_html__( 'Bulgaria', 'tribe-common' ),
					'BF' => esc_html__( 'Burkina Faso', 'tribe-common' ),
					'BI' => esc_html__( 'Burundi', 'tribe-common' ),
					'KH' => esc_html__( 'Cambodia', 'tribe-common' ),
					'CM' => esc_html__( 'Cameroon', 'tribe-common' ),
					'CA' => esc_html__( 'Canada', 'tribe-common' ),
					'CV' => esc_html__( 'Cape Verde', 'tribe-common' ),
					'KY' => esc_html__( 'Cayman Islands', 'tribe-common' ),
					'CF' => esc_html__( 'Central African Republic', 'tribe-common' ),
					'TD' => esc_html__( 'Chad', 'tribe-common' ),
					'CL' => esc_html__( 'Chile', 'tribe-common' ),
					'CN' => esc_html__( 'China', 'tribe-common' ),
					'CX' => esc_html__( 'Christmas Island', 'tribe-common' ),
					'CC' => esc_html__( 'Cocos (Keeling) Islands', 'tribe-common' ),
					'CO' => esc_html__( 'Colombia', 'tribe-common' ),
					'KM' => esc_html__( 'Comoros', 'tribe-common' ),
					'CG' => esc_html__( 'Congo', 'tribe-common' ),
					'CD' => esc_html__( 'Congo, The Democratic Republic Of The', 'tribe-common' ),
					'CK' => esc_html__( 'Cook Islands', 'tribe-common' ),
					'CR' => esc_html__( 'Costa Rica', 'tribe-common' ),
					'CI' => esc_html__( "C&ocirc;te d'Ivoire", 'tribe-common' ),
					'HR' => esc_html__( 'Croatia (Local Name: Hrvatska)', 'tribe-common' ),
					'CU' => esc_html__( 'Cuba', 'tribe-common' ),
					'CY' => esc_html__( 'Cyprus', 'tribe-common' ),
					'CZ' => esc_html__( 'Czech Republic', 'tribe-common' ),
					'DK' => esc_html__( 'Denmark', 'tribe-common' ),
					'DJ' => esc_html__( 'Djibouti', 'tribe-common' ),
					'DM' => esc_html__( 'Dominica', 'tribe-common' ),
					'DO' => esc_html__( 'Dominican Republic', 'tribe-common' ),
					'TP' => esc_html__( 'East Timor', 'tribe-common' ),
					'EC' => esc_html__( 'Ecuador', 'tribe-common' ),
					'EG' => esc_html__( 'Egypt', 'tribe-common' ),
					'SV' => esc_html__( 'El Salvador', 'tribe-common' ),
					'GQ' => esc_html__( 'Equatorial Guinea', 'tribe-common' ),
					'ER' => esc_html__( 'Eritrea', 'tribe-common' ),
					'EE' => esc_html__( 'Estonia', 'tribe-common' ),
					'ET' => esc_html__( 'Ethiopia', 'tribe-common' ),
					'FK' => esc_html__( 'Falkland Islands (Malvinas)', 'tribe-common' ),
					'FO' => esc_html__( 'Faroe Islands', 'tribe-common' ),
					'FJ' => esc_html__( 'Fiji', 'tribe-common' ),
					'FI' => esc_html__( 'Finland', 'tribe-common' ),
					'FR' => esc_html__( 'France', 'tribe-common' ),
					'FX' => esc_html__( 'France, Metropolitan', 'tribe-common' ),
					'GF' => esc_html__( 'French Guiana', 'tribe-common' ),
					'PF' => esc_html__( 'French Polynesia', 'tribe-common' ),
					'TF' => esc_html__( 'French Southern Territories', 'tribe-common' ),
					'GA' => esc_html__( 'Gabon', 'tribe-common' ),
					'GM' => esc_html__( 'Gambia', 'tribe-common' ),
					'GE' => esc_html__( 'Georgia', 'tribe-common' ),
					'DE' => esc_html__( 'Germany', 'tribe-common' ),
					'GH' => esc_html__( 'Ghana', 'tribe-common' ),
					'GI' => esc_html__( 'Gibraltar', 'tribe-common' ),
					'GR' => esc_html__( 'Greece', 'tribe-common' ),
					'GL' => esc_html__( 'Greenland', 'tribe-common' ),
					'GD' => esc_html__( 'Grenada', 'tribe-common' ),
					'GP' => esc_html__( 'Guadeloupe', 'tribe-common' ),
					'GU' => esc_html__( 'Guam', 'tribe-common' ),
					'GT' => esc_html__( 'Guatemala', 'tribe-common' ),
					'GN' => esc_html__( 'Guinea', 'tribe-common' ),
					'GW' => esc_html__( 'Guinea-Bissau', 'tribe-common' ),
					'GY' => esc_html__( 'Guyana', 'tribe-common' ),
					'HT' => esc_html__( 'Haiti', 'tribe-common' ),
					'HM' => esc_html__( 'Heard And Mc Donald Islands', 'tribe-common' ),
					'VA' => esc_html__( 'Holy See (Vatican City State)', 'tribe-common' ),
					'HN' => esc_html__( 'Honduras', 'tribe-common' ),
					'HK' => esc_html__( 'Hong Kong', 'tribe-common' ),
					'HU' => esc_html__( 'Hungary', 'tribe-common' ),
					'IS' => esc_html__( 'Iceland', 'tribe-common' ),
					'IN' => esc_html__( 'India', 'tribe-common' ),
					'ID' => esc_html__( 'Indonesia', 'tribe-common' ),
					'IR' => esc_html__( 'Iran (Islamic Republic Of)', 'tribe-common' ),
					'IQ' => esc_html__( 'Iraq', 'tribe-common' ),
					'IE' => esc_html__( 'Ireland', 'tribe-common' ),
					'IL' => esc_html__( 'Israel', 'tribe-common' ),
					'IT' => esc_html__( 'Italy', 'tribe-common' ),
					'JM' => esc_html__( 'Jamaica', 'tribe-common' ),
					'JP' => esc_html__( 'Japan', 'tribe-common' ),
					'JO' => esc_html__( 'Jordan', 'tribe-common' ),
					'KZ' => esc_html__( 'Kazakhstan', 'tribe-common' ),
					'KE' => esc_html__( 'Kenya', 'tribe-common' ),
					'KI' => esc_html__( 'Kiribati', 'tribe-common' ),
					'KP' => esc_html__( "Korea, Democratic People's Republic Of", 'tribe-common' ),
					'KR' => esc_html__( 'Korea, Republic Of', 'tribe-common' ),
					'KW' => esc_html__( 'Kuwait', 'tribe-common' ),
					'KG' => esc_html__( 'Kyrgyzstan', 'tribe-common' ),
					'LA' => esc_html__( "Lao People's Democratic Republic", 'tribe-common' ),
					'LV' => esc_html__( 'Latvia', 'tribe-common' ),
					'LB' => esc_html__( 'Lebanon', 'tribe-common' ),
					'LS' => esc_html__( 'Lesotho', 'tribe-common' ),
					'LR' => esc_html__( 'Liberia', 'tribe-common' ),
					'LY' => esc_html__( 'Libya', 'tribe-common' ),
					'LI' => esc_html__( 'Liechtenstein', 'tribe-common' ),
					'LT' => esc_html__( 'Lithuania', 'tribe-common' ),
					'LU' => esc_html__( 'Luxembourg', 'tribe-common' ),
					'MO' => esc_html__( 'Macau', 'tribe-common' ),
					'MK' => esc_html__( 'Macedonia', 'tribe-common' ),
					'MG' => esc_html__( 'Madagascar', 'tribe-common' ),
					'MW' => esc_html__( 'Malawi', 'tribe-common' ),
					'MY' => esc_html__( 'Malaysia', 'tribe-common' ),
					'MV' => esc_html__( 'Maldives', 'tribe-common' ),
					'ML' => esc_html__( 'Mali', 'tribe-common' ),
					'MT' => esc_html__( 'Malta', 'tribe-common' ),
					'MH' => esc_html__( 'Marshall Islands', 'tribe-common' ),
					'MQ' => esc_html__( 'Martinique', 'tribe-common' ),
					'MR' => esc_html__( 'Mauritania', 'tribe-common' ),
					'MU' => esc_html__( 'Mauritius', 'tribe-common' ),
					'YT' => esc_html__( 'Mayotte', 'tribe-common' ),
					'MX' => esc_html__( 'Mexico', 'tribe-common' ),
					'FM' => esc_html__( 'Micronesia, Federated States Of', 'tribe-common' ),
					'MD' => esc_html__( 'Moldova, Republic Of', 'tribe-common' ),
					'MC' => esc_html__( 'Monaco', 'tribe-common' ),
					'MN' => esc_html__( 'Mongolia', 'tribe-common' ),
					'ME' => esc_html__( 'Montenegro', 'tribe-common' ),
					'MS' => esc_html__( 'Montserrat', 'tribe-common' ),
					'MA' => esc_html__( 'Morocco', 'tribe-common' ),
					'MZ' => esc_html__( 'Mozambique', 'tribe-common' ),
					'MM' => esc_html__( 'Myanmar', 'tribe-common' ),
					'NA' => esc_html__( 'Namibia', 'tribe-common' ),
					'NR' => esc_html__( 'Nauru', 'tribe-common' ),
					'NP' => esc_html__( 'Nepal', 'tribe-common' ),
					'NL' => esc_html__( 'Netherlands', 'tribe-common' ),
					'AN' => esc_html__( 'Netherlands Antilles', 'tribe-common' ),
					'NC' => esc_html__( 'New Caledonia', 'tribe-common' ),
					'NZ' => esc_html__( 'New Zealand', 'tribe-common' ),
					'NI' => esc_html__( 'Nicaragua', 'tribe-common' ),
					'NE' => esc_html__( 'Niger', 'tribe-common' ),
					'NG' => esc_html__( 'Nigeria', 'tribe-common' ),
					'NU' => esc_html__( 'Niue', 'tribe-common' ),
					'NF' => esc_html__( 'Norfolk Island', 'tribe-common' ),
					'MP' => esc_html__( 'Northern Mariana Islands', 'tribe-common' ),
					'NO' => esc_html__( 'Norway', 'tribe-common' ),
					'OM' => esc_html__( 'Oman', 'tribe-common' ),
					'PK' => esc_html__( 'Pakistan', 'tribe-common' ),
					'PW' => esc_html__( 'Palau', 'tribe-common' ),
					'PA' => esc_html__( 'Panama', 'tribe-common' ),
					'PG' => esc_html__( 'Papua New Guinea', 'tribe-common' ),
					'PY' => esc_html__( 'Paraguay', 'tribe-common' ),
					'PE' => esc_html__( 'Peru', 'tribe-common' ),
					'PH' => esc_html__( 'Philippines', 'tribe-common' ),
					'PN' => esc_html__( 'Pitcairn', 'tribe-common' ),
					'PL' => esc_html__( 'Poland', 'tribe-common' ),
					'PT' => esc_html__( 'Portugal', 'tribe-common' ),
					'PR' => esc_html__( 'Puerto Rico', 'tribe-common' ),
					'QA' => esc_html__( 'Qatar', 'tribe-common' ),
					'RE' => esc_html__( 'Reunion', 'tribe-common' ),
					'RO' => esc_html__( 'Romania', 'tribe-common' ),
					'RU' => esc_html__( 'Russian Federation', 'tribe-common' ),
					'RW' => esc_html__( 'Rwanda', 'tribe-common' ),
					'KN' => esc_html__( 'Saint Kitts And Nevis', 'tribe-common' ),
					'LC' => esc_html__( 'Saint Lucia', 'tribe-common' ),
					'VC' => esc_html__( 'Saint Vincent And The Grenadines', 'tribe-common' ),
					'WS' => esc_html__( 'Samoa', 'tribe-common' ),
					'SM' => esc_html__( 'San Marino', 'tribe-common' ),
					'ST' => esc_html__( 'Sao Tome And Principe', 'tribe-common' ),
					'SA' => esc_html__( 'Saudi Arabia', 'tribe-common' ),
					'SN' => esc_html__( 'Senegal', 'tribe-common' ),
					'RS' => esc_html__( 'Serbia', 'tribe-common' ),
					'SC' => esc_html__( 'Seychelles', 'tribe-common' ),
					'SL' => esc_html__( 'Sierra Leone', 'tribe-common' ),
					'SG' => esc_html__( 'Singapore', 'tribe-common' ),
					'SK' => esc_html__( 'Slovakia (Slovak Republic)', 'tribe-common' ),
					'SI' => esc_html__( 'Slovenia', 'tribe-common' ),
					'SB' => esc_html__( 'Solomon Islands', 'tribe-common' ),
					'SO' => esc_html__( 'Somalia', 'tribe-common' ),
					'ZA' => esc_html__( 'South Africa', 'tribe-common' ),
					'GS' => esc_html__( 'South Georgia, South Sandwich Islands', 'tribe-common' ),
					'ES' => esc_html__( 'Spain', 'tribe-common' ),
					'LK' => esc_html__( 'Sri Lanka', 'tribe-common' ),
					'SH' => esc_html__( 'St. Helena', 'tribe-common' ),
					'PM' => esc_html__( 'St. Pierre And Miquelon', 'tribe-common' ),
					'SD' => esc_html__( 'Sudan', 'tribe-common' ),
					'SR' => esc_html__( 'Suriname', 'tribe-common' ),
					'SJ' => esc_html__( 'Svalbard And Jan Mayen Islands', 'tribe-common' ),
					'SZ' => esc_html__( 'Swaziland', 'tribe-common' ),
					'SE' => esc_html__( 'Sweden', 'tribe-common' ),
					'CH' => esc_html__( 'Switzerland', 'tribe-common' ),
					'SY' => esc_html__( 'Syrian Arab Republic', 'tribe-common' ),
					'TW' => esc_html__( 'Taiwan', 'tribe-common' ),
					'TJ' => esc_html__( 'Tajikistan', 'tribe-common' ),
					'TZ' => esc_html__( 'Tanzania, United Republic Of', 'tribe-common' ),
					'TH' => esc_html__( 'Thailand', 'tribe-common' ),
					'TG' => esc_html__( 'Togo', 'tribe-common' ),
					'TK' => esc_html__( 'Tokelau', 'tribe-common' ),
					'TO' => esc_html__( 'Tonga', 'tribe-common' ),
					'TT' => esc_html__( 'Trinidad And Tobago', 'tribe-common' ),
					'TN' => esc_html__( 'Tunisia', 'tribe-common' ),
					'TR' => esc_html__( 'Turkey', 'tribe-common' ),
					'TM' => esc_html__( 'Turkmenistan', 'tribe-common' ),
					'TC' => esc_html__( 'Turks And Caicos Islands', 'tribe-common' ),
					'TV' => esc_html__( 'Tuvalu', 'tribe-common' ),
					'UG' => esc_html__( 'Uganda', 'tribe-common' ),
					'UA' => esc_html__( 'Ukraine', 'tribe-common' ),
					'AE' => esc_html__( 'United Arab Emirates', 'tribe-common' ),
					'GB' => esc_html__( 'United Kingdom', 'tribe-common' ),
					'UM' => esc_html__( 'United States Minor Outlying Islands', 'tribe-common' ),
					'UY' => esc_html__( 'Uruguay', 'tribe-common' ),
					'UZ' => esc_html__( 'Uzbekistan', 'tribe-common' ),
					'VU' => esc_html__( 'Vanuatu', 'tribe-common' ),
					'VE' => esc_html__( 'Venezuela', 'tribe-common' ),
					'VN' => esc_html__( 'Viet Nam', 'tribe-common' ),
					'VG' => esc_html__( 'Virgin Islands (British)', 'tribe-common' ),
					'VI' => esc_html__( 'Virgin Islands (U.S.)', 'tribe-common' ),
					'WF' => esc_html__( 'Wallis And Futuna Islands', 'tribe-common' ),
					'EH' => esc_html__( 'Western Sahara', 'tribe-common' ),
					'YE' => esc_html__( 'Yemen', 'tribe-common' ),
					'ZM' => esc_html__( 'Zambia', 'tribe-common' ),
					'ZW' => esc_html__( 'Zimbabwe', 'tribe-common' ),
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
					$countries = array( $defaultCountry[0] => $defaultCountry[1] ) + $countries;
					$countries = array( '' => $selectCountry ) + $countries;
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
				'AL' => esc_html__( 'Alabama', 'tribe-common' ),
				'AK' => esc_html__( 'Alaska', 'tribe-common' ),
				'AZ' => esc_html__( 'Arizona', 'tribe-common' ),
				'AR' => esc_html__( 'Arkansas', 'tribe-common' ),
				'CA' => esc_html__( 'California', 'tribe-common' ),
				'CO' => esc_html__( 'Colorado', 'tribe-common' ),
				'CT' => esc_html__( 'Connecticut', 'tribe-common' ),
				'DE' => esc_html__( 'Delaware', 'tribe-common' ),
				'DC' => esc_html__( 'District of Columbia', 'tribe-common' ),
				'FL' => esc_html__( 'Florida', 'tribe-common' ),
				'GA' => esc_html__( 'Georgia', 'tribe-common' ),
				'HI' => esc_html__( 'Hawaii', 'tribe-common' ),
				'ID' => esc_html__( 'Idaho', 'tribe-common' ),
				'IL' => esc_html__( 'Illinois', 'tribe-common' ),
				'IN' => esc_html__( 'Indiana', 'tribe-common' ),
				'IA' => esc_html__( 'Iowa', 'tribe-common' ),
				'KS' => esc_html__( 'Kansas', 'tribe-common' ),
				'KY' => esc_html__( 'Kentucky', 'tribe-common' ),
				'LA' => esc_html__( 'Louisiana', 'tribe-common' ),
				'ME' => esc_html__( 'Maine', 'tribe-common' ),
				'MD' => esc_html__( 'Maryland', 'tribe-common' ),
				'MA' => esc_html__( 'Massachusetts', 'tribe-common' ),
				'MI' => esc_html__( 'Michigan', 'tribe-common' ),
				'MN' => esc_html__( 'Minnesota', 'tribe-common' ),
				'MS' => esc_html__( 'Mississippi', 'tribe-common' ),
				'MO' => esc_html__( 'Missouri', 'tribe-common' ),
				'MT' => esc_html__( 'Montana', 'tribe-common' ),
				'NE' => esc_html__( 'Nebraska', 'tribe-common' ),
				'NV' => esc_html__( 'Nevada', 'tribe-common' ),
				'NH' => esc_html__( 'New Hampshire', 'tribe-common' ),
				'NJ' => esc_html__( 'New Jersey', 'tribe-common' ),
				'NM' => esc_html__( 'New Mexico', 'tribe-common' ),
				'NY' => esc_html__( 'New York', 'tribe-common' ),
				'NC' => esc_html__( 'North Carolina', 'tribe-common' ),
				'ND' => esc_html__( 'North Dakota', 'tribe-common' ),
				'OH' => esc_html__( 'Ohio', 'tribe-common' ),
				'OK' => esc_html__( 'Oklahoma', 'tribe-common' ),
				'OR' => esc_html__( 'Oregon', 'tribe-common' ),
				'PA' => esc_html__( 'Pennsylvania', 'tribe-common' ),
				'RI' => esc_html__( 'Rhode Island', 'tribe-common' ),
				'SC' => esc_html__( 'South Carolina', 'tribe-common' ),
				'SD' => esc_html__( 'South Dakota', 'tribe-common' ),
				'TN' => esc_html__( 'Tennessee', 'tribe-common' ),
				'TX' => esc_html__( 'Texas', 'tribe-common' ),
				'UT' => esc_html__( 'Utah', 'tribe-common' ),
				'VT' => esc_html__( 'Vermont', 'tribe-common' ),
				'VA' => esc_html__( 'Virginia', 'tribe-common' ),
				'WA' => esc_html__( 'Washington', 'tribe-common' ),
				'WV' => esc_html__( 'West Virginia', 'tribe-common' ),
				'WI' => esc_html__( 'Wisconsin', 'tribe-common' ),
				'WY' => esc_html__( 'Wyoming', 'tribe-common' ),
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
			$options = '';

			if ( empty( $date ) ) {
				$minute = '00';
			} else {
				$minute = date( 'i', strtotime( $date ) );
			}

			$minute = apply_filters( 'tribe_get_minute_options', $minute, $date, $isStart );
			$minutes = self::minutes( $minute );

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
		 * @param  int $exact_minute optionally specify an exact minute to be included (outwith the default intervals)
		 *
		 * @return array The minutes array.
		 */
		private static function minutes( $exact_minute = 0 ) {
			$minutes = array();

			// The exact minute should be an absint between 0 and 59
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

			// Unless an exact minute has been specified we can minimize the amount of looping we do
			$increment = ( 0 === $exact_minute ) ? $default_increment : 1;

			for ( $minute = 0; $minute < 60; $minute += $increment ) {
				// Skip if this $minute doesn't meet the increment pattern and isn't an additional exact minute
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
