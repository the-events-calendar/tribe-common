<?php

namespace Tribe\Values;

trait Value_Formatting {

	/**
	 * Transforms a normalized value into a string with the decimal representation with significant digits rounded to
	 * the precision, and with the proper separators.
	 *
	 * @since TBD
	 *
	 * @param float $value the normalized value to transform
	 *
	 * @param string the value rounded to the specified precision and formatted with proper separators.
	 */
	private function to_string( $value ) {

		if ( ! $this->is_value_normalized( $value, __METHOD__ ) ) {
			return $value;
		}

		return number_format(
			$this->to_decimal( $value ),
			$this->get_precision(),
			$this->get_currency_separator_decimal(),
			$this->get_currency_separator_thousands()
		);
	}

	/**
	 * Transforms a normalized value into a decimal representation by rounding the significant digits to the precision.
	 *
	 * @since TBD
	 *
	 * @param float $value the normalized value to transform
	 *
	 * @param float the value rounded to the specified precision
	 */
	private function to_decimal( $value ) {

		if ( ! $this->is_value_normalized( $value, __METHOD__ ) ) {
			return $value;

		}

		return round( $value, $this->get_precision() );
	}

	/**
	 * Transforms a normalized value into a currency representation using the defined currency symbol, position,
	 * separators and precision.
	 *
	 * @since TBD
	 *
	 * @param float $value the normalized value to transform
	 *
	 * @return string the currency-formatted string
	 */
	private function to_currency( $value ) {

		if ( ! $this->is_value_normalized( $value, __METHOD__ ) ) {
			return $value;
		}

		$value = $this->to_string( $value );

		if( 'prefix' === $this->get_currency_symbol_position() ) {
			return $this->get_currency_symbol() . $value;
		}

		return $value . $this->get_currency_symbol();
	}

	private function is_value_normalized( $value, $method ) {

		if ( is_float( $value ) ) {
			return true;
		}

		$type = gettype( $value );
		_doing_it_wrong( esc_html_e( "$method expects a float value, $type found in $value", 'tribe-common' ) );

		return false;
	}

}