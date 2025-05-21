<?php
/**
 * Country list.
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */

namespace TEC\Common\Lists;

/**
 * Class Country
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */
class Country {
	/**
	 * Get a list of countries. Grouped by continent/region.
	 *
	 * @since 6.7.0.
	 *
	 * @return array<string,array<string,string>> The list of countries.
	 */
	public function get_country_list(): array {
		$countries = [
			'Africa'     => [
				'AO' => 'Angola',
				'BJ' => 'Benin',
				'BW' => 'Botswana',
				'BF' => 'Burkina Faso',
				'BI' => 'Burundi',
				'CM' => 'Cameroon',
				'CF' => 'Central African Republic',
				'KM' => 'Comoros',
				'CG' => 'Congo - Brazzaville',
				'CD' => 'Congo - Kinshasa',
				'CI' => 'Côte d\'Ivoire',
				'DJ' => 'Djibouti',
				'GQ' => 'Equatorial Guinea',
				'ER' => 'Eritrea',
				'SZ' => 'Eswatini (Swaziland)',
				'ET' => 'Ethiopia',
				'GA' => 'Gabon',
				'GM' => 'Gambia',
				'GH' => 'Ghana',
				'GW' => 'Guinea-Bissau',
				'GN' => 'Guinea',
				'KE' => 'Kenya',
				'LS' => 'Lesotho',
				'LR' => 'Liberia',
				'MG' => 'Madagascar',
				'MW' => 'Malawi',
				'ML' => 'Mali',
				'MR' => 'Mauritania',
				'MU' => 'Mauritius',
				'MZ' => 'Mozambique',
				'NA' => 'Namibia',
				'NE' => 'Niger',
				'NG' => 'Nigeria',
				'RW' => 'Rwanda',
				'SH' => 'Saint Helena',
				'ST' => 'São Tomé and Príncipe',
				'SN' => 'Senegal',
				'SC' => 'Seychelles',
				'SL' => 'Sierra Leone',
				'SO' => 'Somalia',
				'ZA' => 'South Africa',
				'SD' => 'Sudan',
				'TZ' => 'Tanzania',
				'TG' => 'Togo',
				'UG' => 'Uganda',
				'ZM' => 'Zambia',
				'ZW' => 'Zimbabwe',
			],
			'Americas'   => [
				'AG' => 'Antigua and Barbuda',
				'AR' => 'Argentina',
				'AW' => 'Aruba',
				'BS' => 'Bahamas',
				'BB' => 'Barbados',
				'BZ' => 'Belize',
				'BM' => 'Bermuda',
				'BO' => 'Bolivia',
				'BR' => 'Brazil',
				'VG' => 'British Virgin Islands',
				'CA' => 'Canada',
				'KY' => 'Cayman Islands',
				'CL' => 'Chile',
				'CO' => 'Colombia',
				'CR' => 'Costa Rica',
				'CU' => 'Cuba',
				'DM' => 'Dominica',
				'DO' => 'Dominican Republic',
				'EC' => 'Ecuador',
				'SV' => 'El Salvador',
				'FK' => 'Falkland Islands',
				'GF' => 'French Guiana',
				'GL' => 'Greenland',
				'GD' => 'Grenada',
				'GP' => 'Guadeloupe',
				'GT' => 'Guatemala',
				'GY' => 'Guyana',
				'HT' => 'Haiti',
				'HN' => 'Honduras',
				'JM' => 'Jamaica',
				'MX' => 'Mexico',
				'MS' => 'Montserrat',
				'NI' => 'Nicaragua',
				'PA' => 'Panama',
				'PY' => 'Paraguay',
				'PE' => 'Peru',
				'PR' => 'Puerto Rico',
				'BL' => 'Saint Barthélemy',
				'KN' => 'Saint Kitts and Nevis',
				'LC' => 'Saint Lucia',
				'MF' => 'Saint Martin',
				'VC' => 'Saint Vincent and the Grenadines',
				'SX' => 'Sint Maarten',
				'SR' => 'Suriname',
				'TT' => 'Trinidad and Tobago',
				'TC' => 'Turks and Caicos Islands',
				'VI' => 'U.S. Virgin Islands',
				'US' => 'United States',
				'UY' => 'Uruguay',
				'VE' => 'Venezuela',
			],
			'Antarctica' => [
				'AQ' => 'Antarctica',
			],
			'Asia'       => [
				'AF' => 'Afghanistan',
				'AM' => 'Armenia',
				'AZ' => 'Azerbaijan',
				'BD' => 'Bangladesh',
				'BT' => 'Bhutan',
				'IO' => 'British Indian Ocean Territory',
				'BN' => 'Brunei',
				'KH' => 'Cambodia',
				'CN' => 'China',
				'CX' => 'Christmas Island',
				'CC' => 'Cocos [Keeling] Islands',
				'GE' => 'Georgia',
				'HK' => 'Hong Kong',
				'IN' => 'India',
				'ID' => 'Indonesia',
				'IR' => 'Iran',
				'IQ' => 'Iraq',
				'IL' => 'Israel',
				'JP' => 'Japan',
				'JO' => 'Jordan',
				'KZ' => 'Kazakhstan',
				'KW' => 'Kuwait',
				'KG' => 'Kyrgyzstan',
				'LA' => 'Laos',
				'MO' => 'Macao',
				'MY' => 'Malaysia',
				'MV' => 'Maldives',
				'MN' => 'Mongolia',
				'MM' => 'Myanmar [Burma]',
				'NP' => 'Nepal',
				'KP' => 'North Korea',
				'OM' => 'Oman',
				'PK' => 'Pakistan',
				'PS' => 'Palestine',
				'PH' => 'Philippines',
				'QA' => 'Qatar',
				'SA' => 'Saudi Arabia',
				'SG' => 'Singapore',
				'KR' => 'South Korea',
				'LK' => 'Sri Lanka',
				'SY' => 'Syria',
				'TW' => 'Taiwan',
				'TJ' => 'Tajikistan',
				'TH' => 'Thailand',
				'TM' => 'Turkmenistan',
				'AE' => 'United Arab Emirates',
				'UZ' => 'Uzbekistan',
				'VN' => 'Vietnam',
				'YE' => 'Yemen',
			],
			'Oceania'    => [
				'AS' => 'American Samoa',
				'AU' => 'Australia',
				'CK' => 'Cook Islands',
				'FJ' => 'Fiji',
				'PF' => 'French Polynesia',
				'GU' => 'Guam',
				'KI' => 'Kiribati',
				'MH' => 'Marshall Islands',
				'FM' => 'Micronesia',
				'NR' => 'Nauru',
				'NC' => 'New Caledonia',
				'NZ' => 'New Zealand',
				'NU' => 'Niue',
				'NF' => 'Norfolk Island',
				'MP' => 'Northern Mariana Islands',
				'PW' => 'Palau',
				'PG' => 'Papua New Guinea',
				'PN' => 'Pitcairn Islands',
				'WS' => 'Samoa',
				'SB' => 'Solomon Islands',
				'TK' => 'Tokelau',
				'TO' => 'Tonga',
				'TV' => 'Tuvalu',
				'UM' => 'U.S. Minor Outlying Islands',
				'VU' => 'Vanuatu',
				'WF' => 'Wallis and Futuna',
			],
			'Europe'     => [
				'AX' => 'Åland Islands',
				'AL' => 'Albania',
				'AD' => 'Andorra',
				'AT' => 'Austria',
				'BY' => 'Belarus',
				'BE' => 'Belgium',
				'BA' => 'Bosnia and Herzegovina',
				'BG' => 'Bulgaria',
				'HR' => 'Croatia',
				'CY' => 'Cyprus',
				'CZ' => 'Czech Republic',
				'DK' => 'Denmark',
				'EE' => 'Estonia',
				'FO' => 'Faroe Islands',
				'FI' => 'Finland',
				'FR' => 'France',
				'DE' => 'Germany',
				'GI' => 'Gibraltar',
				'GR' => 'Greece',
				'GG' => 'Guernsey',
				'HU' => 'Hungary',
				'IS' => 'Iceland',
				'IE' => 'Ireland',
				'IM' => 'Isle of Man',
				'IT' => 'Italy',
				'JE' => 'Jersey',
				'LV' => 'Latvia',
				'LI' => 'Liechtenstein',
				'LT' => 'Lithuania',
				'LU' => 'Luxembourg',
				'MT' => 'Malta',
				'MD' => 'Moldova',
				'MC' => 'Monaco',
				'ME' => 'Montenegro',
				'NL' => 'Netherlands',
				'MK' => 'North Macedonia',
				'NO' => 'Norway',
				'PL' => 'Poland',
				'PT' => 'Portugal',
				'RO' => 'Romania',
				'RU' => 'Russia',
				'SM' => 'San Marino',
				'RS' => 'Serbia',
				'SK' => 'Slovakia',
				'SI' => 'Slovenia',
				'ES' => 'Spain',
				'SJ' => 'Svalbard and Jan Mayen',
				'SE' => 'Sweden',
				'CH' => 'Switzerland',
				'UA' => 'Ukraine',
				'GB' => 'United Kingdom',
				'VA' => 'Vatican City',
			],
		];

		/**
		 * Filter the list of countries.
		 *
		 * @since 6.7.0
		 *
		 * @param array $countries The list of countries. Grouped by continent/region.
		 */
		return apply_filters( 'tec_country_list', $countries );
	}

	/**
	 * Find a country in the list by its key.
	 *
	 * @since 6.7.0
	 *
	 * @param string $key The country key.
	 *
	 * @return string|null The country name or null if not found.
	 */
	public function find_country_by_key( $key ): ?string {
		if ( empty( $key ) ) {
			return null;
		}

		$countries = $this->get_country_list();
		// Use array_filter to locate the array containing the key.
		$filtered = array_filter( $countries, fn( $country_list ) => array_key_exists( $key, $country_list ) );

		// If the filtered array is not empty, fetch the value.
		if ( ! empty( $filtered ) ) {
			$continent = reset( $filtered ); // Get the first match.
			return $continent[ $key ];
		}
		return null;
	}

	/**
	 * Find a country key in the list by its value.
	 *
	 * @since 6.7.0
	 *
	 * @param string $value The country value.
	 *
	 * @return string|null The country key or null if not found.
	 */
	public function find_country_by_value( $value ): ?string {
		if ( empty( $value ) ) {
			return null;
		}

		$countries = $this->get_country_list();
		// Use array_filter to locate the array containing the key.
		$filtered = array_filter( $countries, fn( $country_list ) => in_array( $value, $country_list ) );

		// If the filtered array is not empty, fetch the value.
		if ( ! empty( $filtered ) ) {
			$continent = reset( $filtered ); // Get the first match.
			return array_search( $value, $continent );
		}

		return null;
	}

	/**
	 * Get a list of countries with Payment Gateways support information.
	 *
	 * @since 6.7.0
	 *
	 * @return array<string,array<string,mixed>> The list of countries with Payment Gateways support information.
	 */
	public function get_gateway_countries(): array {
		$cache     = tribe( 'cache' );
		$cache_key = 'payment_gateway_enabled_countries';

		// Try to get from cache first.
		$cached_data = $cache->get( $cache_key );
		if ( false !== $cached_data ) {
			return $cached_data;
		}

		// Get the base country list.
		$base_countries = $this->get_country_list();

		// Initialize the result array.
		$result = [];

		// Get Country API data.
		$country_api = 'https://whodatdev.theeventscalendar.com/commerce/v1/countries/';
		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
		$response = wp_remote_get( $country_api );
		$api_data = json_decode( wp_remote_retrieve_body( $response ), true );

		// If API error or invalid response, return all countries with default values.
		if ( is_wp_error( $response ) || ! isset( $api_data['countries'] ) ) {
			foreach ( $base_countries as $continent => $countries ) {
				foreach ( $countries as $code => $name ) {
					$result[ $code ] = [
						'name'       => $name,
						'group'      => $continent,
						'has_paypal' => false,
						'has_stripe' => false,
						'has_square' => false,
						'currencies' => [],
					];
				}
			}

			$cache->set( $cache_key, $result, 4 * HOUR_IN_SECONDS );
			return $result;
		}

		$api_countries = [];
		foreach ( $api_data['countries'] as $country ) {
			$api_countries[ $country['id'] ] = $country;
		}

		// Process all countries from the base list and add the Payment Gateways support information.
		foreach ( $base_countries as $continent => $countries ) {
			foreach ( $countries as $code => $name ) {
				$result[ $code ] = [
					'name'       => $name,
					'group'      => $continent,
					'currency'   => $api_countries[ $code ]['currencies'][0] ?? [],
					'has_paypal' => $api_countries[ $code ]['paypal']['is_active'] ?? false,
					'has_stripe' => $api_countries[ $code ]['stripe']['is_active'] ?? false,
					'has_square' => false,
				];
			}
		}

		$cache->set( $cache_key, $result, 4 * HOUR_IN_SECONDS );
		return $result;
	}
}
