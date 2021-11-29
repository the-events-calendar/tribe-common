<?php

namespace Tribe\Values;

abstract class Abstract_Currency extends Abstract_Value implements Currency_Interface {

	private $formatted;

	private $currency_code;

	private $currency_code_fallback = 'USD';

	private $currency_symbol;

	private $currency_symbol_position;

	use ValueFormatting;

	public function __construct( $amount = 0 ) {
		parent::__construct( $amount );

		//$this->set_currency();
	}

	public function get_currency_code() {
		return $this->currency_code;
	}

	public function get_currency_code_fallback() {
		return $this->currency_code_fallback;
	}

	public function get_currency_symbol() {
		return $this->currency_symbol;
	}

	public function get_currency_symbol_position() {
		return $this->currency_symbol_position;
	}


	public function set_decimal( $amount ) {
		$this->decimal = $amount;
	}

	public function get_formatted() {
		return $this->formatted;
	}

	public function get_decimal( Abstract_Currency $amount ) {
		return $amount->decimal;
	}

	private function set_currency() {
		$this->currency_code_fallback = apply_filters( 'tec_tickets_commerce_currency_code_fallback', 'USD' );

		$this->currency_code = apply_filters(
			'tec_tickets_commerce_currency_code',
			\tribe_get_option( 'tickets-commerce-currency-code', $this->currency_code_fallback )
		);

		$this->currency_symbol = apply_filters(
			'tec_tickets_commerce_currency_symbol',
			\tribe_get_option( 'tickets-commerce-currency-symbol', '$' )
		);

		$this->currency_symbol_position = apply_filters(
			'tec_tickets_commerce_currency_symbol_position',
			\tribe_get_option( 'tickets-commerce-currency-symbol-position', 'prefix' )
		);
	}

	private function set_formatted_value( $amount ) {
		$this->formatted = $this->format( $amount );
	}

	private function format( $value ) {

		$use_currency_locale = \tribe_get_option( 'tickets-commerce-use-currency-locale', false );

		/**
		 * Whether the currency's own locale should be used to format the price or not.
		 *
		 * @since TBD
		 *
		 * @param bool             $use_currency_locale If `true` then the currency own locale will override the site one.
		 * @param string|int|float $value                The value to format without the symbol.
		 * @param int              $post_id             The current post ID if any.
		 */
		$use_currency_locale = apply_filters( 'tribe_tickets_commerce_price_format_use_currency_locale', $use_currency_locale, $value, $post_id );

		if ( ! $use_currency_locale ) {
			$value = number_format_i18n( $value, 2 );
		} else {
			$value = number_format(
				$value,
				$this->get_precision(),
				$this->get_decimal_separator(),
				$this->get_thousands_separator()
			);
		}

		$value = $this->get_currency_symbol_position() === 'prefix'
			? $this->get_currency_symbol() . $value
			: $value . $this->get_currency_symbol();

		return $value;
	}

}