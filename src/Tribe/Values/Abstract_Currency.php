<?php

namespace Tribe\Values;

abstract class Abstract_Currency extends Abstract_Value implements Currency_Interface {

	private $currency;

	private $decimal;

	private $string;

	use Value_Formatting;
	use Value_Update;

	public function __construct( $amount = 0 ) {
		$this->set_up_currency_details();

		parent::__construct( $amount );
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

	protected function set_currency_value() {
		$this->currency = $this->to_currency( $this->get_normalized_value() );
	}

	protected function set_decimal_value() {
		$this->decimal = $this->to_decimal( $this->get_normalized_value() );
	}

	protected function set_string_value() {
		$this->string = $this->to_string( $this->get_normalized_value() );
	}

}