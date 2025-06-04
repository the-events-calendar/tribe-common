<?php
/**
 * Country list.
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */

namespace TEC\Common\Lists;

use Tribe__Cache as Cache;

/**
 * Class Country
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */
class Country {
	/**
	 * The list of countries.
	 *
	 * @since 6.8.0
	 *
	 * @var array<string,array<string,string>>
	 */
	protected const COUNTRIES = [
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
	 * The cache instance.
	 *
	 * @since 6.8.0
	 *
	 * @var Cache
	 */
	private Cache $cache;

	/**
	 * Constructor.
	 *
	 * @since 6.8.0
	 *
	 * @param Cache $cache The cache instance.
	 */
	public function __construct( Cache $cache ) {
		$this->cache = $cache;
	}

	/**
	 * Get a list of countries. Grouped by continent/region.
	 *
	 * @since 6.7.0.
	 *
	 * @return array<string,array<string,string>> The list of countries.
	 */
	public function get_country_list(): array {
		/**
		 * Filter the list of countries.
		 *
		 * @since 6.7.0
		 *
		 * @param array $countries The list of countries. Grouped by continent/region.
		 */
		return apply_filters( 'tec_common_country_list', self::COUNTRIES );
	}

	/**
	 * Get a formatted list of countries.
	 *
	 * @since 6.8.0
	 *
	 * @return array<string,array<string,mixed>> The formatted list of countries.
	 */
	public function get_country_list_with_data(): array {
		$cache_key   = 'tec_common_country_list_formatted';
		$cached_data = $this->cache->get( $cache_key );

		if ( $cached_data && is_array( $cached_data ) ) {
			return $cached_data;
		}

		$base_countries = $this->get_country_list();

		$formatted = [];
		foreach ( $base_countries as $continent => $countries ) {
			foreach ( $countries as $code => $name ) {
				$formatted[ $code ] = [
					'name'       => $name,
					'group'      => $continent,
					'has_paypal' => false,
					'has_stripe' => false,
					'has_square' => false,
					'currencies' => [],
				];
			}
		}

		$this->cache[ $cache_key ] = $formatted;

		return $formatted;
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
	public function find_country_by_key( string $key ): ?string {
		if ( ! $key ) {
			return null;
		}

		return $this->get_country_list_with_data()[ $key ]['name'] ?? null;
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
	public function find_country_by_value( string $value ): ?string {
		if ( ! $value ) {
			return null;
		}

		$reverse_flat_array = array_flip( array_merge( ...array_values( $this->get_country_list() ) ) );

		return $reverse_flat_array[ $value ] ?? null;
	}

	/**
	 * Get a country by its currency.
	 *
	 * @since 6.8.0
	 *
	 * @param string $currency The currency.
	 *
	 * @return array|null The country or null if not found.
	 */
	public function get_country_by_currency( $currency ): ?array {
		$countries = $this->get_gateway_countries();
		$filtered  = array_filter( $countries, fn( $country ) => in_array( $currency, $country['currencies'] ) );
		return reset( $filtered );
	}

	/**
	 * Get a list of countries with Payment Gateways support information.
	 *
	 * @since 6.7.0
	 *
	 * @return array<string,array<string,mixed>> The list of countries with Payment Gateways support information.
	 */
	public function get_gateway_countries(): array {
		$cache_key = 'tec_common_payment_gateway_enabled_countries';

		// Try to get from cache first.
		$cached_data = $this->cache->get( $cache_key );
		if ( $cached_data && is_array( $cached_data ) ) {
			return $cached_data;
		}

		// Get the base country list.
		$base_countries = $this->get_country_list_with_data();

		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
		$response = wp_remote_get( 'https://whodatdev.theeventscalendar.com/commerce/v1/countries/' );
		$api_data = json_decode( wp_remote_retrieve_body( $response ), true );

		// If API error or invalid response, return all countries with default values.
		if ( is_wp_error( $response ) || ! isset( $api_data['countries'] ) || ! is_array( $api_data['countries'] ) ) {
			$this->cache->set( $cache_key, $base_countries, 4 * HOUR_IN_SECONDS );
			return $base_countries;
		}

		foreach ( $api_data['countries'] as $country ) {
			if ( ! isset( $base_countries[ $country['id'] ] ) ) {
				// Unknown local country, we bail.
				continue;
			}

			$base_countries[ $country['id'] ] = array_merge(
				$base_countries[ $country['id'] ],
				[
					// Question Stephen/George: Why are we using the offset 0 here which is a string ? I wou8ld expect us to just do: `$country['currencies'] ?? []`.
					'currency'   => $country['currencies'][0] ?? [],
					'has_paypal' => $country['paypal']['is_active'] ?? false,
					'has_stripe' => $country['stripe']['is_active'] ?? false,
					'has_square' => $country['square']['is_active'] ?? false,
				]
			);
		}

		$this->cache->set( $cache_key, $base_countries, 4 * HOUR_IN_SECONDS );
		return $base_countries;
	}
}
