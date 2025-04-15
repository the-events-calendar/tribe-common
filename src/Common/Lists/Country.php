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
					'name' => __( 'Angola' ),
				],
				'BJ' => [
					'code' => 'BJ',
					'name' => __( 'Benin' ),
				],
				'BW' => [
					'code' => 'BW',
					'name' => __( 'Botswana' ),
				],
				'BF' => [
					'code' => 'BF',
					'name' => __( 'Burkina Faso' ),
				],
				'BI' => [
					'code' => 'BI',
					'name' => __( 'Burundi' ),
				],
				'CM' => [
					'code' => 'CM',
					'name' => __( 'Cameroon' ),
				],
				'CF' => [
					'code' => 'CF',
					'name' => __( 'Central African Republic' ),
				],
				'KM' => [
					'code' => 'KM',
					'name' => __( 'Comoros' ),
				],
				'CG' => [
					'code' => 'CG',
					'name' => __( 'Congo - Brazzaville' ),
				],
				'CD' => [
					'code' => 'CD',
					'name' => __( 'Congo - Kinshasa' ),
				],
				'CI' => [
					'code' => 'CI',
					'name' => __( "Côte d'Ivoire" ),
				],
				'DJ' => [
					'code' => 'DJ',
					'name' => __( 'Djibouti' ),
				],
				'GQ' => [
					'code' => 'GQ',
					'name' => __( 'Equatorial Guinea' ),
				],
				'ER' => [
					'code' => 'ER',
					'name' => __( 'Eritrea' ),
				],
				'SZ' => [
					'code' => 'SZ',
					'name' => __( 'Eswatini (Swaziland)' ),
				],
				'ET' => [
					'code' => 'ET',
					'name' => __( 'Ethiopia' ),
				],
				'GA' => [
					'code' => 'GA',
					'name' => __( 'Gabon' ),
				],
				'GM' => [
					'code' => 'GM',
					'name' => __( 'Gambia' ),
				],
				'GH' => [
					'code' => 'GH',
					'name' => __( 'Ghana' ),
				],
				'GW' => [
					'code' => 'GW',
					'name' => __( 'Guinea-Bissau' ),
				],
				'GN' => [
					'code' => 'GN',
					'name' => __( 'Guinea' ),
				],
				'KE' => [
					'code' => 'KE',
					'name' => __( 'Kenya' ),
				],
				'LS' => [
					'code' => 'LS',
					'name' => __( 'Lesotho' ),
				],
				'LR' => [
					'code' => 'LR',
					'name' => __( 'Liberia' ),
				],
				'MG' => [
					'code' => 'MG',
					'name' => __( 'Madagascar' ),
				],
				'MW' => [
					'code' => 'MW',
					'name' => __( 'Malawi' ),
				],
				'ML' => [
					'code' => 'ML',
					'name' => __( 'Mali' ),
				],
				'MR' => [
					'code' => 'MR',
					'name' => __( 'Mauritania' ),
				],
				'MU' => [
					'code' => 'MU',
					'name' => __( 'Mauritius' ),
				],
				'MZ' => [
					'code' => 'MZ',
					'name' => __( 'Mozambique' ),
				],
				'NA' => [
					'code' => 'NA',
					'name' => __( 'Namibia' ),
				],
				'NE' => [
					'code' => 'NE',
					'name' => __( 'Niger' ),
				],
				'NG' => [
					'code' => 'NG',
					'name' => __( 'Nigeria' ),
				],
				'RW' => [
					'code' => 'RW',
					'name' => __( 'Rwanda' ),
				],
				'SH' => [
					'code' => 'SH',
					'name' => __( 'Saint Helena' ),
				],
				'ST' => [
					'code' => 'ST',
					'name' => __( 'São Tomé and Príncipe' ),
				],
				'SN' => [
					'code' => 'SN',
					'name' => __( 'Senegal' ),
				],
				'SC' => [
					'code' => 'SC',
					'name' => __( 'Seychelles' ),
				],
				'SL' => [
					'code' => 'SL',
					'name' => __( 'Sierra Leone' ),
				],
				'SO' => [
					'code' => 'SO',
					'name' => __( 'Somalia' ),
				],
				'ZA' => [
					'code' => 'ZA',
					'name' => __( 'South Africa' ),
				],
				'SD' => [
					'code' => 'SD',
					'name' => __( 'Sudan' ),
				],
				'TZ' => [
					'code' => 'TZ',
					'name' => __( 'Tanzania' ),
				],
				'TG' => [
					'code' => 'TG',
					'name' => __( 'Togo' ),
				],
				'UG' => [
					'code' => 'UG',
					'name' => __( 'Uganda' ),
				],
				'ZM' => [
					'code' => 'ZM',
					'name' => __( 'Zambia' ),
				],
				'ZW' => [
					'code' => 'ZW',
					'name' => __( 'Zimbabwe' ),
				],
			],
			'Americas'   => [
				'AG' => [
					'code' => 'AG',
					'name' => __( 'Antigua and Barbuda' ),
				],
				'AR' => [
					'code' => 'AR',
					'name' => __( 'Argentina' ),
				],
				'AW' => [
					'code' => 'AW',
					'name' => __( 'Aruba' ),
				],
				'BS' => [
					'code' => 'BS',
					'name' => __( 'Bahamas' ),
				],
				'BB' => [
					'code' => 'BB',
					'name' => __( 'Barbados' ),
				],
				'BO' => [
					'code' => 'BO',
					'name' => __( 'Bolivia' ),
				],
				'BR' => [
					'code' => 'BR',
					'name' => __( 'Brazil' ),
				],
				'VG' => [
					'code' => 'VG',
					'name' => __( 'British Virgin Islands' ),
				],
				'CA' => [
					'code' => 'CA',
					'name' => __( 'Canada' ),
				],
				'KY' => [
					'code' => 'KY',
					'name' => __( 'Cayman Islands' ),
				],
				'CL' => [
					'code' => 'CL',
					'name' => __( 'Chile' ),
				],
				'CO' => [
					'code' => 'CO',
					'name' => __( 'Colombia' ),
				],
				'CR' => [
					'code' => 'CR',
					'name' => __( 'Costa Rica' ),
				],
				'CU' => [
					'code' => 'CU',
					'name' => __( 'Cuba' ),
				],
				'EC' => [
					'code' => 'EC',
					'name' => __( 'Ecuador' ),
				],
				'SV' => [
					'code' => 'SV',
					'name' => __( 'El Salvador' ),
				],
				'FK' => [
					'code' => 'FK',
					'name' => __( 'Falkland Islands' ),
				],
				'GF' => [
					'code' => 'GF',
					'name' => __( 'French Guiana' ),
				],
				'GL' => [
					'code' => 'GL',
					'name' => __( 'Greenland' ),
				],
				'GD' => [
					'code' => 'GD',
					'name' => __( 'Grenada' ),
				],
				'GP' => [
					'code' => 'GP',
					'name' => __( 'Guadeloupe' ),
				],
				'GT' => [
					'code' => 'GT',
					'name' => __( 'Guatemala' ),
				],
				'GY' => [
					'code' => 'GY',
					'name' => __( 'Guyana' ),
				],
				'HT' => [
					'code' => 'HT',
					'name' => __( 'Haiti' ),
				],
				'HN' => [
					'code' => 'HN',
					'name' => __( 'Honduras' ),
				],
				'JM' => [
					'code' => 'JM',
					'name' => __( 'Jamaica' ),
				],
				'MX' => [
					'code' => 'MX',
					'name' => __( 'Mexico' ),
				],
				'MS' => [
					'code' => 'MS',
					'name' => __( 'Montserrat' ),
				],
				'NI' => [
					'code' => 'NI',
					'name' => __( 'Nicaragua' ),
				],
				'PA' => [
					'code' => 'PA',
					'name' => __( 'Panama' ),
				],
				'PY' => [
					'code' => 'PY',
					'name' => __( 'Paraguay' ),
				],
				'PE' => [
					'code' => 'PE',
					'name' => __( 'Peru' ),
				],
				'PR' => [
					'code' => 'PR',
					'name' => __( 'Puerto Rico' ),
				],
				'BL' => [
					'code' => 'BL',
					'name' => __( 'Saint Barthélemy' ),
				],
				'KN' => [
					'code' => 'KN',
					'name' => __( 'Saint Kitts and Nevis' ),
				],
				'LC' => [
					'code' => 'LC',
					'name' => __( 'Saint Lucia' ),
				],
				'MF' => [
					'code' => 'MF',
					'name' => __( 'Saint Martin' ),
				],
				'VC' => [
					'code' => 'VC',
					'name' => __( 'Saint Vincent and the Grenadines' ),
				],
				'SX' => [
					'code' => 'SX',
					'name' => __( 'Sint Maarten' ),
				],
				'SR' => [
					'code' => 'SR',
					'name' => __( 'Suriname' ),
				],
				'TT' => [
					'code' => 'TT',
					'name' => __( 'Trinidad and Tobago' ),
				],
				'TC' => [
					'code' => 'TC',
					'name' => __( 'Turks and Caicos Islands' ),
				],
				'US' => [
					'code' => 'US',
					'name' => __( 'United States' ),
				],
				'UY' => [
					'code' => 'UY',
					'name' => __( 'Uruguay' ),
				],
				'VE' => [
					'code' => 'VE',
					'name' => __( 'Venezuela' ),
				],
			],
			'Antarctica' => [
				'AQ' => [
					'code' => 'AQ',
					'name' => __( 'Antarctica' ),
				],
			],
			'Asia'       => [
				'AF' => [
					'code' => 'AF',
					'name' => __( 'Afghanistan' ),
				],
				'AM' => [
					'code' => 'AM',
					'name' => __( 'Armenia' ),
				],
				'AZ' => [
					'code' => 'AZ',
					'name' => __( 'Azerbaijan' ),
				],
				'BD' => [
					'code' => 'BD',
					'name' => __( 'Bangladesh' ),
				],
				'BT' => [
					'code' => 'BT',
					'name' => __( 'Bhutan' ),
				],
				'IO' => [
					'code' => 'IO',
					'name' => __( 'British Indian Ocean Territory' ),
				],
				'BN' => [
					'code' => 'BN',
					'name' => __( 'Brunei' ),
				],
				'KH' => [
					'code' => 'KH',
					'name' => __( 'Cambodia' ),
				],
				'CN' => [
					'code' => 'CN',
					'name' => __( 'China' ),
				],
				'CX' => [
					'code' => 'CX',
					'name' => __( 'Christmas Island' ),
				],
				'CC' => [
					'code' => 'CC',
					'name' => __( 'Cocos [Keeling] Islands' ),
				],
				'GE' => [
					'code' => 'GE',
					'name' => __( 'Georgia' ),
				],
				'HK' => [
					'code' => 'HK',
					'name' => __( 'Hong Kong' ),
				],
				'IN' => [
					'code' => 'IN',
					'name' => __( 'India' ),
				],
				'ID' => [
					'code' => 'ID',
					'name' => __( 'Indonesia' ),
				],
				'IR' => [
					'code' => 'IR',
					'name' => __( 'Iran' ),
				],
				'IQ' => [
					'code' => 'IQ',
					'name' => __( 'Iraq' ),
				],
				'IL' => [
					'code' => 'IL',
					'name' => __( 'Israel' ),
				],
				'JP' => [
					'code' => 'JP',
					'name' => __( 'Japan' ),
				],
				'JO' => [
					'code' => 'JO',
					'name' => __( 'Jordan' ),
				],
				'KZ' => [
					'code' => 'KZ',
					'name' => __( 'Kazakhstan' ),
				],
				'KW' => [
					'code' => 'KW',
					'name' => __( 'Kuwait' ),
				],
				'KG' => [
					'code' => 'KG',
					'name' => __( 'Kyrgyzstan' ),
				],
				'LA' => [
					'code' => 'LA',
					'name' => __( 'Laos' ),
				],
				'MO' => [
					'code' => 'MO',
					'name' => __( 'Macao' ),
				],
				'MY' => [
					'code' => 'MY',
					'name' => __( 'Malaysia' ),
				],
				'MV' => [
					'code' => 'MV',
					'name' => __( 'Maldives' ),
				],
				'MN' => [
					'code' => 'MN',
					'name' => __( 'Mongolia' ),
				],
				'MM' => [
					'code' => 'MM',
					'name' => __( 'Myanmar [Burma]' ),
				],
				'NP' => [
					'code' => 'NP',
					'name' => __( 'Nepal' ),
				],
				'KP' => [
					'code' => 'KP',
					'name' => __( 'North Korea' ),
				],
				'OM' => [
					'code' => 'OM',
					'name' => __( 'Oman' ),
				],
				'PK' => [
					'code' => 'PK',
					'name' => __( 'Pakistan' ),
				],
				'PS' => [
					'code' => 'PS',
					'name' => __( 'Palestine' ),
				],
				'PH' => [
					'code' => 'PH',
					'name' => __( 'Philippines' ),
				],
				'QA' => [
					'code' => 'QA',
					'name' => __( 'Qatar' ),
				],
				'SA' => [
					'code' => 'SA',
					'name' => __( 'Saudi Arabia' ),
				],
				'SG' => [
					'code' => 'SG',
					'name' => __( 'Singapore' ),
				],
				'KR' => [
					'code' => 'KR',
					'name' => __( 'South Korea' ),
				],
				'LK' => [
					'code' => 'LK',
					'name' => __( 'Sri Lanka' ),
				],
				'SY' => [
					'code' => 'SY',
					'name' => __( 'Syria' ),
				],
				'TW' => [
					'code' => 'TW',
					'name' => __( 'Taiwan' ),
				],
				'TJ' => [
					'code' => 'TJ',
					'name' => __( 'Tajikistan' ),
				],
				'TH' => [
					'code' => 'TH',
					'name' => __( 'Thailand' ),
				],
				'TM' => [
					'code' => 'TM',
					'name' => __( 'Turkmenistan' ),
				],
				'AE' => [
					'code' => 'AE',
					'name' => __( 'United Arab Emirates' ),
				],
				'UZ' => [
					'code' => 'UZ',
					'name' => __( 'Uzbekistan' ),
				],
				'VN' => [
					'code' => 'VN',
					'name' => __( 'Vietnam' ),
				],
				'YE' => [
					'code' => 'YE',
					'name' => __( 'Yemen' ),
				],
			],
			'Oceania'    => [
				'AS' => [
					'code' => 'AS',
					'name' => __( 'American Samoa' ),
				],
				'AU' => [
					'code' => 'AU',
					'name' => __( 'Australia' ),
				],
				'CK' => [
					'code' => 'CK',
					'name' => __( 'Cook Islands' ),
				],
				'FJ' => [
					'code' => 'FJ',
					'name' => __( 'Fiji' ),
				],
				'MH' => [
					'code' => 'MH',
					'name' => __( 'Marshall Islands' ),
				],
				'FM' => [
					'code' => 'FM',
					'name' => __( 'Micronesia' ),
				],
				'NR' => [
					'code' => 'NR',
					'name' => __( 'Nauru' ),
				],
				'NC' => [
					'code' => 'NC',
					'name' => __( 'New Caledonia' ),
				],
				'NF' => [
					'code' => 'NF',
					'name' => __( 'Norfolk Island' ),
				],
				'MP' => [
					'code' => 'MP',
					'name' => __( 'Northern Mariana Islands' ),
				],
				'PW' => [
					'code' => 'PW',
					'name' => __( 'Palau' ),
				],
				'PG' => [
					'code' => 'PG',
					'name' => __( 'Papua New Guinea' ),
				],
				'SB' => [
					'code' => 'SB',
					'name' => __( 'Solomon Islands' ),
				],
				'TK' => [
					'code' => 'TK',
					'name' => __( 'Tokelau' ),
				],
				'TO' => [
					'code' => 'TO',
					'name' => __( 'Tonga' ),
				],
				'TV' => [
					'code' => 'TV',
					'name' => __( 'Tuvalu' ),
				],
				'UM' => [
					'code' => 'UM',
					'name' => __( 'U.S. Minor Outlying Islands' ),
				],
				'VU' => [
					'code' => 'VU',
					'name' => __( 'Vanuatu' ),
				],
				'WF' => [
					'code' => 'WF',
					'name' => __( 'Wallis and Futuna' ),
				],
			],
			'Europe'     => [
				'AX' => [
					'code' => 'AX',
					'name' => __( 'Åland Islands' ),
				],
				'AL' => [
					'code' => 'AL',
					'name' => __( 'Albania' ),
				],
				'AD' => [
					'code' => 'AD',
					'name' => __( 'Andorra' ),
				],
				'AT' => [
					'code' => 'AT',
					'name' => __( 'Austria' ),
				],
				'BY' => [
					'code' => 'BY',
					'name' => __( 'Belarus' ),
				],
				'BE' => [
					'code' => 'BE',
					'name' => __( 'Belgium' ),
				],
				'BA' => [
					'code' => 'BA',
					'name' => __( 'Bosnia and Herzegovina' ),
				],
				'BG' => [
					'code' => 'BG',
					'name' => __( 'Bulgaria' ),
				],
				'HR' => [
					'code' => 'HR',
					'name' => __( 'Croatia' ),
				],
				'CY' => [
					'code' => 'CY',
					'name' => __( 'Cyprus' ),
				],
				'CZ' => [
					'code' => 'CZ',
					'name' => __( 'Czech Republic' ),
				],
				'DK' => [
					'code' => 'DK',
					'name' => __( 'Denmark' ),
				],
				'FO' => [
					'code' => 'FO',
					'name' => __( 'Faroe Islands' ),
				],
				'FI' => [
					'code' => 'FI',
					'name' => __( 'Finland' ),
				],
				'FR' => [
					'code' => 'FR',
					'name' => __( 'France' ),
				],
				'DE' => [
					'code' => 'DE',
					'name' => __( 'Germany' ),
				],
				'GI' => [
					'code' => 'GI',
					'name' => __( 'Gibraltar' ),
				],
				'GR' => [
					'code' => 'GR',
					'name' => __( 'Greece' ),
				],
				'GG' => [
					'code' => 'GG',
					'name' => __( 'Guernsey' ),
				],
				'HU' => [
					'code' => 'HU',
					'name' => __( 'Hungary' ),
				],
				'IS' => [
					'code' => 'IS',
					'name' => __( 'Iceland' ),
				],
				'IT' => [
					'code' => 'IT',
					'name' => __( 'Italy' ),
				],
				'JE' => [
					'code' => 'JE',
					'name' => __( 'Jersey' ),
				],
				'LV' => [
					'code' => 'LV',
					'name' => __( 'Latvia' ),
				],
				'LI' => [
					'code' => 'LI',
					'name' => __( 'Liechtenstein' ),
				],
				'LT' => [
					'code' => 'LT',
					'name' => __( 'Lithuania' ),
				],
				'LU' => [
					'code' => 'LU',
					'name' => __( 'Luxembourg' ),
				],
				'MT' => [
					'code' => 'MT',
					'name' => __( 'Malta' ),
				],
				'MD' => [
					'code' => 'MD',
					'name' => __( 'Moldova' ),
				],
				'MC' => [
					'code' => 'MC',
					'name' => __( 'Monaco' ),
				],
				'ME' => [
					'code' => 'ME',
					'name' => __( 'Montenegro' ),
				],
				'NL' => [
					'code' => 'NL',
					'name' => __( 'Netherlands' ),
				],
				'MK' => [
					'code' => 'MK',
					'name' => __( 'North Macedonia' ),
				],
				'NO' => [
					'code' => 'NO',
					'name' => __( 'Norway' ),
				],
				'PL' => [
					'code' => 'PL',
					'name' => __( 'Poland' ),
				],
				'PT' => [
					'code' => 'PT',
					'name' => __( 'Portugal' ),
				],
				'RO' => [
					'code' => 'RO',
					'name' => __( 'Romania' ),
				],
				'RU' => [
					'code' => 'RU',
					'name' => __( 'Russia' ),
				],
				'SM' => [
					'code' => 'SM',
					'name' => __( 'San Marino' ),
				],
				'RS' => [
					'code' => 'RS',
					'name' => __( 'Serbia' ),
				],
				'ES' => [
					'code' => 'ES',
					'name' => __( 'Spain' ),
				],
				'SJ' => [
					'code' => 'SJ',
					'name' => __( 'Svalbard and Jan Mayen' ),
				],
				'SE' => [
					'code' => 'SE',
					'name' => __( 'Sweden' ),
				],
				'CH' => [
					'code' => 'CH',
					'name' => __( 'Switzerland' ),
				],
				'UA' => [
					'code' => 'UA',
					'name' => __( 'Ukraine' ),
				],
				'GB' => [
					'code' => 'GB',
					'name' => __( 'United Kingdom' ),
				],
				'VA' => [
					'code' => 'VA',
					'name' => __( 'Vatican City' ),
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
				$country_names[ $continent ][ $country_code ] = $country_data['name'];
			}
		}

		return $country_names;
	}

	/**
	 * Get a list of countries that have a specific key.
	 *
	 * @since TBD
	 *
	 * @param string $key    The key to get the countries for.
	 * @param bool   $sorted  Whether to keep the countries sorted by continent.
	 * @param ?mixed $value   Optional value to match against the key.
	 *
	 * @return array<string,array<string,mixed>> The list of countries by continent and key.
	 */
	public function get_country_list_by_key( string $key, bool $sorted = true, $value = null ): array {
		$countries = $this->get_country_list();
		$filtered   = [];

		foreach ( $countries as $continent => $continent_countries ) {
			foreach ( $continent_countries as $country_code => $country_data ) {
				// Skip if the key doesn't exist.
				if ( ! isset( $country_data[ $key ] ) ) {
					continue;
				}

				// Skip if a value was provided and it doesn't match.
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
			foreach ( $countries as $code => $country_data ) {
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
