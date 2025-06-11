<?php
/**
 * Currency list.
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */

namespace TEC\Common\Lists;

/**
 * Class Currency
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */
class Currency {

	/**
	 * Get a list of currencies.
	 * Note: we don't currently use "code" or "entity", but they are included for future use.
	 *
	 * @since 6.7.0
	 *
	 * @return array
	 */
	public static function get_currency_list(): array {
		$default_currencies = [
			'aud'     => [
				'code'   => 'AUD',
				'name'   => __( 'Australian Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'brl'     => [
				'code'   => 'BRL',
				'name'   => __( 'Brazilian Real', 'event-tickets' ),
				'symbol' => 'R$',
				'entity' => 'R&#36;',
			],
			'gbp'     => [
				'code'   => 'GBP',
				'name'   => __( 'British Pound', 'event-tickets' ),
				'symbol' => '£',
				'entity' => '&pound;',
			],
			'bgn'     => [
				'code'   => 'BGN',
				'name'   => __( 'Bulgarian Lev', 'event-tickets' ),
				'symbol' => 'лв',
				'entity' => '&#1083;&#1074;',
			],
			'cad'     => [
				'code'   => 'CAD',
				'name'   => __( 'Canadian Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'cny'     => [
				'code'   => 'CNY',
				'name'   => __( 'Chinese Yen (¥)', 'event-tickets' ),
				'symbol' => '¥',
				'entity' => '&yen;',
			],
			'cny2'    => [
				'code'   => 'CNY',
				'name'   => __( 'Chinese Yuan (元)', 'event-tickets' ),
				'symbol' => '元',
				'entity' => '&#20803;',
			],
			'hrk'     => [
				'code'   => 'HRK',
				'name'   => __( 'Croatian Kuna', 'event-tickets' ),
				'symbol' => 'kn',
				'entity' => 'kn',
			],
			'czk'     => [
				'code'   => 'CZK',
				'name'   => __( 'Czech Koruna', 'event-tickets' ),
				'symbol' => 'Kč',
				'entity' => 'K&#x10D;',
			],
			'dkk'     => [
				'code'   => 'DKK',
				'name'   => __( 'Danish Krone', 'event-tickets' ),
				'symbol' => 'kr.',
				'entity' => 'kr.',
			],
			'euro'    => [
				'code'   => 'EUR',
				'name'   => __( 'Euro', 'event-tickets' ),
				'symbol' => '€',
				'entity' => '&euro;',
			],
			'hkd'     => [
				'code'   => 'HKD',
				'name'   => __( 'Hong Kong Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'huf'     => [
				'code'   => 'HUF',
				'name'   => __( 'Hungarian Forint', 'event-tickets' ),
				'symbol' => 'Ft',
				'entity' => 'Ft',
			],
			'inr'     => [
				'code'   => 'INR',
				'name'   => __( 'Indian Rupee', 'event-tickets' ),
				'symbol' => '₹',
				'entity' => '&#x20B9;',
			],
			'idr'     => [
				'code'   => 'IDR',
				'name'   => __( 'Indonesian Rupiah', 'event-tickets' ),
				'symbol' => 'Rp',
				'entity' => 'Rp',
			],
			'ils'     => [
				'code'   => 'ILS',
				'name'   => __( 'Israeli New Sheqel', 'event-tickets' ),
				'symbol' => '₪',
				'entity' => '&#x20AA;',
			],
			'jpy'     => [
				'code'   => 'JPY',
				'name'   => __( 'Japanese Yen', 'event-tickets' ),
				'symbol' => '¥',
				'entity' => '&yen;',
			],
			'krw'     => [
				'code'   => 'KRW',
				'name'   => __( 'Korean Won', 'event-tickets' ),
				'symbol' => '₩',
				'entity' => '&#8361;',
			],
			'myr'     => [
				'code'   => 'MYR',
				'name'   => __( 'Malaysian Ringgit', 'event-tickets' ),
				'symbol' => 'RM',
				'entity' => 'RM',
			],
			'mxn'     => [
				'code'   => 'MXN',
				'name'   => __( 'Mexican Peso', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'nzd'     => [
				'code'   => 'NZD',
				'name'   => __( 'New Zealand Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'ngn'     => [
				'code'   => 'NGN',
				'name'   => __( 'Nigerian Naira', 'event-tickets' ),
				'symbol' => '₦',
				'entity' => '&#8358;',
			],
			'nok'     => [
				'code'   => 'NOK',
				'name'   => __( 'Norwegian Krone', 'event-tickets' ),
				'symbol' => 'kr',
				'entity' => 'kr',
			],
			'php'     => [
				'code'   => 'PHP',
				'name'   => __( 'Philippine Peso', 'event-tickets' ),
				'symbol' => '₱',
				'entity' => '&#x20B1;',
			],
			'pln'     => [
				'code'   => 'PLN',
				'name'   => __( 'Polish Złoty', 'event-tickets' ),
				'symbol' => 'zł',
				'entity' => 'z&#x142;',
			],
			'ron'     => [
				'code'   => 'RON',
				'name'   => __( 'Romanian Leu', 'event-tickets' ),
				'symbol' => 'lei',
				'entity' => 'lei',
			],
			'rub'     => [
				'code'   => 'RUB',
				'name'   => __( 'Russian Ruble', 'event-tickets' ),
				'symbol' => '₽',
				'entity' => '&#8381;',
			],
			'sar'     => [
				'code'   => 'SAR',
				'name'   => __( 'Saudi Riyal', 'event-tickets' ),
				'symbol' => 'ر.س',
				'entity' => '&#x631;.&#x633;',
			],
			'sgd'     => [
				'code'   => 'SGD',
				'name'   => __( 'Singapore Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'zar'     => [
				'code'   => 'ZAR',
				'name'   => __( 'South African Rand', 'event-tickets' ),
				'symbol' => 'R',
				'entity' => 'R',
			],
			'sek'     => [
				'code'   => 'SEK',
				'name'   => __( 'Swedish Krona', 'event-tickets' ),
				'symbol' => 'kr',
				'entity' => 'kr',
			],
			'chf'     => [
				'code'   => 'CHF',
				'name'   => __( 'Swiss Franc', 'event-tickets' ),
				'symbol' => 'Fr',
				'entity' => 'Fr',
			],
			'twd'     => [
				'code'   => 'TWD',
				'name'   => __( 'Taiwan New Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'thb'     => [
				'code'   => 'THB',
				'name'   => __( 'Thai Baht', 'event-tickets' ),
				'symbol' => '฿',
				'entity' => '&#x0E3F;',
			],
			'trl'     => [
				'code'   => 'TRL',
				'name'   => __( 'Turkish Lira', 'event-tickets' ),
				'symbol' => '₺',
				'entity' => '&#8378;',
			],
			'aed'     => [
				'code'   => 'AED',
				'name'   => __( 'United Arab Emirates Dirham', 'event-tickets' ),
				'symbol' => 'د.إ',
				'entity' => '&#x62f;.&#x625;',
			],
			'usd'     => [
				'code'   => 'USD',
				'name'   => __( 'US Dollar', 'event-tickets' ),
				'symbol' => '$',
				'entity' => '&#36;',
			],
			'usdcent' => [
				'code'   => 'USDCENT',
				'name'   => __( 'US Cent', 'event-tickets' ),
				'symbol' => '¢',
				'entity' => '&cent;',
			],
			'vnd'     => [
				'code'   => 'VND',
				'name'   => __( 'Vietnamese Dong', 'event-tickets' ),
				'symbol' => '₫',
				'entity' => '&#8363;',
			],
		];

		return (array) apply_filters( 'tec_currencies_list', $default_currencies );
	}
}
