<?php

namespace Tribe\Values;

abstract class Abstract_Currency extends Abstract_Value implements Currency_Interface {

	private $currency = '';

	private $decimal;

	private $string;

	private $currency_code = 'USD';

	private $currency_separator_decimal = '.';

	private $currency_separator_thousands = ',';

	private $currency_symbol = '$';

	private $currency_symbol_position = 'prefix';

	private $use_tec_currency_locale = true;

	use ValueFormatting;

	public function __construct( $amount = 0 ) {
		parent::__construct( $amount );

		$this->set_up_currency_details();
	}

	public function get_currency_code() {
		return $this->currency_code;
	}

	public function get_currency_symbol() {
		return $this->currency_symbol;
	}

	public function get_currency_symbol_position() {
		return $this->currency_symbol_position;
	}

	public function get_currency_separator_decimal() {
		return $this->currency_separator_decimal;
	}

	public function get_currency_separator_thousands() {
		return $this->currency_separator_thousands;
	}

	public function get_currency() {
		return $this->currency;
	}

	public function get_decimal() {
		return $this->decimal;
	}

	public function get_string() {
		return $this->string;
	}

	private function set_currency_value() {
		$this->currency = $this->to_currency( $this->get_normalized_value() );
	}

	private function set_string_value() {
		$this->string = $this->to_string( $this->get_normalized_value() );
	}

	private function set_decimal_value() {
		$this->decimal = $this->to_decimal( $this->get_normalized_value() );
	}
}