<?php

namespace Tribe\Values;

trait ValueFormatting {

	private function to_string( $value ) {
		return (string) $this->to_decimal( $value );
	}

	private function to_decimal( $value ) {
		return round( $value, $this->get_precision() );
	}

	private function to_currency( $value ) {
		$value = number_format(
			$value,
			$this->get_precision(),
			$this->get_currency_separator_decimal(),
			$this->get_currency_separator_thousands()
		);

		$value = $this->get_currency_symbol_position() === 'prefix'
			? $this->get_currency_symbol() . $value
			: $value . $this->get_currency_symbol();

		return $value;
	}

}