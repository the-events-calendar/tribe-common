<?php
/**
 * Country list.
 *
 * @since TBD
 *
 * @package TEC\Common\Lists
 */

namespace TEC\Common\Lists;

/**
 * Class Country
 *
 * @since TBD
 *
 * @package TEC\Common\Lists
 */
class Country {

	/**
	 * Get a list of countries. Grouped by continent/region.
	 *
	 * @since TBD
	 *
	 * @return array<string,array<string,string>> The list of countries.
	 */
	public function get_country_list(): array {
		$countries = [
			'Africa'     => [
				'AO' => [
					'code' => 'AO',
					'name' => 'Angola',
				],
				'BJ' => [
					'code' => 'BJ',
				],
				'BW' => [
					'code' => 'BW',
					'name' => 'Botswana',
				],
				'BF' => [
					'code' => 'BF',
					'name' => 'Burkina Faso',
				],
				'BI' => [
					'code' => 'BI',
					'name' => 'Burundi',
				],
				'CM' => [
					'code' => 'CM',
					'name' => 'Cameroon',
				],
				'CF' => [
					'code' => 'CF',
					'name' => 'Central African Republic',
				],
				'KM' => [
					'code' => 'KM',
					'name' => 'Comoros',
				],
				'CG' => [
					'code' => 'CG',
					'name' => 'Congo - Brazzaville',
				],
				'CD' => [
					'code' => 'CD',
					'name' => 'Congo - Kinshasa',
				],
				'CI' => [
					'code' => 'CI',
					'name' => "Côte d’Ivoire",
				],
				'DJ' => [
					'code' => 'DJ',
					'name' => 'Djibouti',
				],
				'GQ' => [
					'code' => 'GQ',
					'name' => 'Equatorial Guinea',
				],
				'ER' => [
					'code' => 'ER',
					'name' => 'Eritrea',
				],
				'SZ' => [
					'code' => 'SZ',
					'name' => 'Eswatini (Swaziland)',
				],
				'ET' => [
					'code' => 'ET',
					'name' => 'Ethiopia',
				],
				'GA' => [
					'code' => 'GA',
					'name' => 'Gabon',
				],
				'GM' => [
					'code' => 'GM',
					'name' => 'Gambia',
				],
				'GH' => [
					'code' => 'GH',
					'name' => 'Ghana',
				],
				'GW' => [
					'code' => 'GW',
					'name' => 'Guinea-Bissau',
				],
				'GN' => [
					'code' => 'GN',
					'name' => 'Guinea',
				],
				'KE' => [
					'code' => 'KE',
					'name' => 'Kenya',
				],
				'LS' => [
					'code' => 'LS',
					'name' => 'Lesotho',
				],
				'LR' => [
					'code' => 'LR',
					'name' => 'Liberia',
				],
				'MG' => [
					'code' => 'MG',
					'name' => 'Madagascar',
				],
				'MW' => [
					'code' => 'MW',
					'name' => 'Malawi',
				],
				'ML' => [
					'code' => 'ML',
					'name' => 'Mali',
				],
				'MR' => [
					'code' => 'MR',
					'name' => 'Mauritania',
				],
				'MU' => [
					'code' => 'MU',
					'name' => 'Mauritius',
				],
				'MZ' => [
					'code' => 'MZ',
					'name' => 'Mozambique',
				],
				'NA' => [
					'code' => 'NA',
					'name' => 'Namibia',
				],
				'NE' => [
					'code' => 'NE',
					'name' => 'Niger',
				],
				'NG' => [
					'code' => 'NG',
					'name' => 'Nigeria',
				],
				'RW' => [
					'code' => 'RW',
					'name' => 'Rwanda',
				],
				'SH' => [
					'code' => 'SH',
					'name' => 'Saint Helena',
				],
				'ST' => [
					'code' => 'ST',
					'name' => 'São Tomé and Príncipe',
				],
				'SN' => [
					'code' => 'SN',
					'name' => 'Senegal',
				],
				'SC' => [
					'code' => 'SC',
					'name' => 'Seychelles',
				],
				'SL' => [
					'code' => 'SL',
					'name' => 'Sierra Leone',
				],
				'SO' => [
					'code' => 'SO',
					'name' => 'Somalia',
				],
				'ZA' => [
					'code' => 'ZA',
					'name' => 'South Africa',
				],
				'SD' => [
					'code' => 'SD',
					'name' => 'Sudan',
				],
				'TZ' => [
					'code' => 'TZ',
					'name' => 'Tanzania',
				],
				'TG' => [
					'code' => 'TG',
					'name' => 'Togo',
				],
				'UG' => [
					'code' => 'UG',
					'name' => 'Uganda',
				],
				'ZM' => [
					'code' => 'ZM',
					'name' => 'Zambia',
				],
				'ZW' => [
					'code' => 'ZW',
					'name' => 'Zimbabwe',
				],
			],
			'Americas'   => [
				'AG' => [
					'code' => 'AG',
					'name' => 'Antigua and Barbuda',
				],
				'AR' => [
					'code' => 'AR',
					'name' => 'Argentina',
				],
				'AW' => [
					'code' => 'AW',
					'name' => 'Aruba',
				],
				'BS' => [
					'code' => 'BS',
					'name' => 'Bahamas',
				],
				'BB' => [
					'code' => 'BB',
					'name' => 'Barbados',
				],
				'BO' => [
					'code' => 'BO',
					'name' => 'Bolivia',
				],
				'BR' => [
					'code' => 'BR',
					'name' => 'Brazil',
				],
				'VG' => [
					'code' => 'VG',
					'name' => 'British Virgin Islands',
				],
				'CA' => [
					'code' => 'CA',
					'name' => 'Canada',
				],
				'KY' => [
					'code' => 'KY',
					'name' => 'Cayman Islands',
				],
				'CL' => [
					'code' => 'CL',
					'name' => 'Chile',
				],
				'CO' => [
					'code' => 'CO',
					'name' => 'Colombia',
				],
				'CR' => [
					'code' => 'CR',
					'name' => 'Costa Rica',
				],
				'CU' => [
					'code' => 'CU',
					'name' => 'Cuba',
				],
				'EC' => [
					'code' => 'EC',
					'name' => 'Ecuador',
				],
				'SV' => [
					'code' => 'SV',
					'name' => 'El Salvador',
				],
				'FK' => [
					'code' => 'FK',
					'name' => 'Falkland Islands',
				],
				'GF' => [
					'code' => 'GF',
					'name' => 'French Guiana',
				],
				'GL' => [
					'code' => 'GL',
					'name' => 'Greenland',
				],
				'GD' => [
					'code' => 'GD',
					'name' => 'Grenada',
				],
				'GP' => [
					'code' => 'GP',
					'name' => 'Guadeloupe',
				],
				'GT' => [
					'code' => 'GT',
					'name' => 'Guatemala',
				],
				'GY' => [
					'code' => 'GY',
					'name' => 'Guyana',
				],
				'HT' => [
					'code' => 'HT',
					'name' => 'Haiti',
				],
				'HN' => [
					'code' => 'HN',
					'name' => 'Honduras',
				],
				'JM' => [
					'code' => 'JM',
					'name' => 'Jamaica',
				],
				'MX' => [
					'code' => 'MX',
					'name' => 'Mexico',
				],
				'MS' => [
					'code' => 'MS',
					'name' => 'Montserrat',
				],
				'NI' => [
					'code' => 'NI',
					'name' => 'Nicaragua',
				],
				'PA' => [
					'code' => 'PA',
					'name' => 'Panama',
				],
				'PY' => [
					'code' => 'PY',
					'name' => 'Paraguay',
				],
				'PE' => [
					'code' => 'PE',
					'name' => 'Peru',
				],
				'PR' => [
					'code' => 'PR',
					'name' => 'Puerto Rico',
				],
				'BL' => [
					'code' => 'BL',
					'name' => 'Saint Barthélemy',
				],
				'KN' => [
					'code' => 'KN',
					'name' => 'Saint Kitts and Nevis',
				],
				'LC' => [
					'code' => 'LC',
					'name' => 'Saint Lucia',
				],
				'MF' => [
					'code' => 'MF',
					'name' => 'Saint Martin',
				],
				'VC' => [
					'code' => 'VC',
					'name' => 'Saint Vincent and the Grenadines',
				],
				'SX' => [
					'code' => 'SX',
					'name' => 'Sint Maarten',
				],
				'SR' => [
					'code' => 'SR',
					'name' => 'Suriname',
				],
				'TT' => [
					'code' => 'TT',
					'name' => 'Trinidad and Tobago',
				],
				'TC' => [
					'code' => 'TC',
					'name' => 'Turks and Caicos Islands',
				],
				'UY' => [
					'code' => 'UY',
					'name' => 'Uruguay',
				],
				'VE' => [
					'code' => 'VE',
					'name' => 'Venezuela',
				],
			],
			'Antarctica' => [
				'AQ' => [
					'code' => 'AQ',
					'name' => 'Antarctica',
				],
			],
			'Asia'       => [
				'AF' => [
					'code' => 'AF',
					'name' => 'Afghanistan',
				],
				'AM' => [
					'code' => 'AM',
					'name' => 'Armenia',
				],
				'AZ' => [
					'code' => 'AZ',
					'name' => 'Azerbaijan',
				],
				'BD' => [
					'code' => 'BD',
					'name' => 'Bangladesh',
				],
				'BT' => [
					'code' => 'BT',
					'name' => 'Bhutan',
				],
				'IO' => [
					'code' => 'IO',
					'name' => 'British Indian Ocean Territory',
				],
				'BN' => [
					'code' => 'BN',
					'name' => 'Brunei',
				],
				'KH' => [
					'code' => 'KH',
					'name' => 'Cambodia',
				],
				'CN' => [
					'code' => 'CN',
					'name' => 'China',
				],
				'CX' => [
					'code' => 'CX',
					'name' => 'Christmas Island',
				],
				'CC' => [
					'code' => 'CC',
					'name' => 'Cocos [Keeling] Islands',
				],
				'CY' => [
					'code' => 'CY',
					'name' => 'Cyprus',
				],
				'GE' => [
					'code' => 'GE',
					'name' => 'Georgia',
				],
				'HK' => [
					'code' => 'HK',
					'name' => 'Hong Kong',
				],
				'IN' => [
					'code' => 'IN',
					'name' => 'India',
				],
				'ID' => [
					'code' => 'ID',
					'name' => 'Indonesia',
				],
				'IR' => [
					'code' => 'IR',
					'name' => 'Iran',
				],
				'IQ' => [
					'code' => 'IQ',
					'name' => 'Iraq',
				],
				'IL' => [
					'code' => 'IL',
					'name' => 'Israel',
				],
				'JP' => [
					'code' => 'JP',
					'name' => 'Japan',
				],
				'JO' => [
					'code' => 'JO',
					'name' => 'Jordan',
				],
				'KZ' => [
					'code' => 'KZ',
					'name' => 'Kazakhstan',
				],
				'KW' => [
					'code' => 'KW',
					'name' => 'Kuwait',
				],
				'KG' => [
					'code' => 'KG',
					'name' => 'Kyrgyzstan',
				],
				'LA' => [
					'code' => 'LA',
					'name' => 'Laos',
				],
				'MO' => [
					'code' => 'MO',
					'name' => 'Macao',
				],
				'MY' => [
					'code' => 'MY',
					'name' => 'Malaysia',
				],
				'MV' => [
					'code' => 'MV',
					'name' => 'Maldives',
				],
				'MN' => [
					'code' => 'MN',
					'name' => 'Mongolia',
				],
				'MM' => [
					'code' => 'MM',
					'name' => 'Myanmar [Burma]',
				],
				'NP' => [
					'code' => 'NP',
					'name' => 'Nepal',
				],
				'KP' => [
					'code' => 'KP',
					'name' => 'North Korea',
				],
				'OM' => [
					'code' => 'OM',
					'name' => 'Oman',
				],
				'PK' => [
					'code' => 'PK',
					'name' => 'Pakistan',
				],
				'PS' => [
					'code' => 'PS',
					'name' => 'Palestine',
				],
				'PH' => [
					'code' => 'PH',
					'name' => 'Philippines',
				],
				'QA' => [
					'code' => 'QA',
					'name' => 'Qatar',
				],
				'SA' => [
					'code' => 'SA',
					'name' => 'Saudi Arabia',
				],
				'SG' => [
					'code' => 'SG',
					'name' => 'Singapore',
				],
				'KR' => [
					'code' => 'KR',
					'name' => 'South Korea',
				],
				'LK' => [
					'code' => 'LK',
					'name' => 'Sri Lanka',
				],
				'SY' => [
					'code' => 'SY',
					'name' => 'Syria',
				],
				'TW' => [
					'code' => 'TW',
					'name' => 'Taiwan',
				],
				'TJ' => [
					'code' => 'TJ',
					'name' => 'Tajikistan',
				],
				'TH' => [
					'code' => 'TH',
					'name' => 'Thailand',
				],
				'TM' => [
					'code' => 'TM',
					'name' => 'Turkmenistan',
				],
				'AE' => [
					'code' => 'AE',
					'name' => 'United Arab Emirates',
				],
				'UZ' => [
					'code' => 'UZ',
					'name' => 'Uzbekistan',
				],
				'VN' => [
					'code' => 'VN',
					'name' => 'Vietnam',
				],
				'YE' => [
					'code' => 'YE',
					'name' => 'Yemen',
				],
			],
			'Oceania'    => [
				'AS' => [
					'code' => 'AS',
					'name' => 'American Samoa',
				],
				'AU' => [
					'code' => 'AU',
					'name' => 'Australia',
				],
				'CK' => [
					'code' => 'CK',
					'name' => 'Cook Islands',
				],
				'FJ' => [
					'code' => 'FJ',
					'name' => 'Fiji',
				],
				'MH' => [
					'code' => 'MH',
					'name' => 'Marshall Islands',
				],
				'FM' => [
					'code' => 'FM',
					'name' => 'Micronesia',
				],
				'NR' => [
					'code' => 'NR',
					'name' => 'Nauru',
				],
				'NC' => [
					'code' => 'NC',
					'name' => 'New Caledonia',
				],
				'NF' => [
					'code' => 'NF',
					'name' => 'Norfolk Island',
				],
				'MP' => [
					'code' => 'MP',
					'name' => 'Northern Mariana Islands',
				],
				'PW' => [
					'code' => 'PW',
					'name' => 'Palau',
				],
				'PG' => [
					'code' => 'PG',
					'name' => 'Papua New Guinea',
				],
				'SB' => [
					'code' => 'SB',
					'name' => 'Solomon Islands',
				],
				'TK' => [
					'code' => 'TK',
					'name' => 'Tokelau',
				],
				'TO' => [
					'code' => 'TO',
					'name' => 'Tonga',
				],
				'TV' => [
					'code' => 'TV',
					'name' => 'Tuvalu',
				],
				'UM' => [
					'code' => 'UM',
					'name' => 'U.S. Minor Outlying Islands',
				],
				'VU' => [
					'code' => 'VU',
					'name' => 'Vanuatu',
				],
				'WF' => [
					'code' => 'WF',
					'name' => 'Wallis and Futuna',
				],
			],
			'Europe'     => [
				'AX' => [
					'code' => 'AX',
					'name' => 'Åland Islands',
				],
				'AL' => [
					'code' => 'AL',
					'name' => 'Albania',
				],
				'AD' => [
					'code' => 'AD',
					'name' => 'Andorra',
				],
				'AT' => [
					'code' => 'AT',
					'name' => 'Austria',
				],
				'BY' => [
					'code' => 'BY',
					'name' => 'Belarus',
				],
				'BE' => [
					'code' => 'BE',
					'name' => 'Belgium',
				],
				'BA' => [
					'code' => 'BA',
					'name' => 'Bosnia and Herzegovina',
				],
				'BG' => [
					'code' => 'BG',
					'name' => 'Bulgaria',
				],
				'HR' => [
					'code' => 'HR',
					'name' => 'Croatia',
				],
				'CY' => [
					'code' => 'CY',
					'name' => 'Cyprus',
				],
				'CZ' => [
					'code' => 'CZ',
					'name' => 'Czech Republic',
				],
				'DK' => [
					'code' => 'DK',
					'name' => 'Denmark',
				],
				'FO' => [
					'code' => 'FO',
					'name' => 'Faroe Islands',
				],
				'FI' => [
					'code' => 'FI',
					'name' => 'Finland',
				],
				'FR' => [
					'code' => 'FR',
					'name' => 'France',
				],
				'DE' => [
					'code' => 'DE',
					'name' => 'Germany',
				],
				'GI' => [
					'code' => 'GI',
					'name' => 'Gibraltar',
				],
				'GR' => [
					'code' => 'GR',
					'name' => 'Greece',
				],
				'GG' => [
					'code' => 'GG',
					'name' => 'Guernsey',
				],
				'HU' => [
					'code' => 'HU',
					'name' => 'Hungary',
				],
				'IS' => [
					'code' => 'IS',
					'name' => 'Iceland',
				],
				'IT' => [
					'code' => 'IT',
					'name' => 'Italy',
				],
				'JE' => [
					'code' => 'JE',
					'name' => 'Jersey',
				],
				'LV' => [
					'code' => 'LV',
					'name' => 'Latvia',
				],
				'LI' => [
					'code' => 'LI',
					'name' => 'Liechtenstein',
				],
				'LT' => [
					'code' => 'LT',
					'name' => 'Lithuania',
				],
				'LU' => [
					'code' => 'LU',
					'name' => 'Luxembourg',
				],
				'MT' => [
					'code' => 'MT',
					'name' => 'Malta',
				],
				'MD' => [
					'code' => 'MD',
					'name' => 'Moldova',
				],
				'MC' => [
					'code' => 'MC',
					'name' => 'Monaco',
				],
				'ME' => [
					'code' => 'ME',
					'name' => 'Montenegro',
				],
				'NL' => [
					'code' => 'NL',
					'name' => 'Netherlands',
				],
				'MK' => [
					'code' => 'MK',
					'name' => 'North Macedonia',
				],
				'NO' => [
					'code' => 'NO',
					'name' => 'Norway',
				],
				'PL' => [
					'code' => 'PL',
					'name' => 'Poland',
				],
				'PT' => [
					'code' => 'PT',
					'name' => 'Portugal',
				],
				'RO' => [
					'code' => 'RO',
					'name' => 'Romania',
				],
				'RU' => [
					'code' => 'RU',
					'name' => 'Russia',
				],
				'SM' => [
					'code' => 'SM',
					'name' => 'San Marino',
				],
				'RS' => [
					'code' => 'RS',
					'name' => 'Serbia',
				],
				'ES' => [
					'code' => 'ES',
					'name' => 'Spain',
				],
				'SJ' => [
					'code' => 'SJ',
					'name' => 'Svalbard and Jan Mayen',
				],
				'SE' => [
					'code' => 'SE',
					'name' => 'Sweden',
				],
				'CH' => [
					'code' => 'CH',
					'name' => 'Switzerland',
				],
				'UA' => [
					'code' => 'UA',
					'name' => 'Ukraine',
				],
				'GB' => [
					'code' => 'GB',
					'name' => 'United Kingdom',
				],
				'VA' => [
					'code' => 'VA',
					'name' => 'Vatican City',
				],
			],
		];

		/**
		 * Filter the list of countries.
		 *
		 * @since TBD
		 *
		 * @param array $countries The list of countries. Grouped by continent/region.
		 */
		return apply_filters( 'tec_country_list', $countries );
	}

	/**
	 * Get a list of continents used as keys in the list.
	 *
	 * @since TBD
	 *
	 * @return array<string,string> The list of continents.
	 */
	public function get_continent_list(): array {
		return array_keys( $this->get_country_list() );
	}

	/**
	 * Get a list of countries for a specific continent.
	 *
	 * @since TBD
	 *
	 * @param string $continent The continent to get the countries for.
	 *
	 * @return array<string,array<string,string>> The list of countries by continent.
	 */
	public function get_country_list_by_continent( $continent ): array {
		return $this->get_country_list()[ $continent ] ?? [];
	}

	/**
	 * Get a list of countries with their names. Organized by continent and country code.
	 *
	 * @since TBD
	 *
	 * @return array<string,array<string,string>> The list of countries with their names.
	 */
	public function get_country_name_list() {
		$countries = $this->get_country_list();
		$country_names = [];

		foreach ( $countries as $continent => $continent_countries ) {
			foreach ( $continent_countries as $country_code => $country_data ) {
				$country_names[$continent][$country_code] = $country_data['name'];
			}
		}

		return $country_names;
	}

	/**
	 * Get a list of countries that have a specific key.
	 *
	 * @since TBD
	 *
	 * @param string      $key    The key to get the countries for.
	 * @param bool       $sorted  Whether to keep the countries sorted by continent.
	 * @param mixed|null $value   Optional value to match against the key.
	 *
	 * @return array<string,array<string,mixed>> The list of countries by continent and key.
	 */
	public function get_country_list_by_key( string $key, bool $sorted = true, $value = null ): array {
		$countries = $this->get_country_list();
		$filtered = [];

		foreach ( $countries as $continent => $continent_countries ) {
			foreach ( $continent_countries as $country_code => $country_data ) {
				// Skip if the key doesn't exist
				if ( ! isset( $country_data[ $key ] ) ) {
					continue;
				}

				// Skip if a value was provided and it doesn't match
				if ( null !== $value && $country_data[ $key ] !== $value ) {
					continue;
				}

				if ( $sorted ) {
					$filtered[ $continent ][ $country_code ] = $country_data;
				} else {
					$filtered[ $country_code ] = $country_data;
				}
			}
		}

		return $filtered;
	}

	/**
	 * Find a country by a specific key-value pair in the country data.
	 *
	 * @since TBD
	 *
	 * @param string $key   The key to search for in the country data.
	 * @param string $value The value to match.
	 *
	 * @return ?array The country data array if found, null otherwise.
	 */
	public function get_country_by_key( string $key, string $value ): ?array {
		foreach ( $this->get_country_list() as $continent => $countries ) {
			foreach ( $countries as $country_data ) {
				if ( isset( $country_data[ $key ] ) && $country_data[ $key ] === $value ) {
					return $country_data;
				}
			}
		}

		return null;
	}

	/**
	 * Get a country by its code.
	 *
	 * @since TBD
	 *
	 * @param string $code The country code to search for.
	 *
	 * @return ?array The country data array if found, null otherwise.
	 */
	public function get_country_by_code( string $code ): ?array {
		return $this->get_country_by_key( 'code', $code );
	}

	/**
	 * Get a country by its name.
	 *
	 * @since TBD
	 *
	 * @param string $name The name to search for in the country data.
	 *
	 * @return ?array The country data array if found, null otherwise.
	 */
	public function get_country_by_name( string $name ): ?array {
		return $this->get_country_by_key( 'name', $name );
	}
}
