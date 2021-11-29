<?php

namespace Tribe\Values;

abstract class Abstract_Value implements Value_Interface {

	use ValueCalculation;

	/**
	 * Holds the initial value passed to the constructor. This variable does not change.
	 *
	 * @var mixed
	 */
	private $initial_value;

	/**
	 * Holds the value normalized value calculated when instantiating an object or setting new values.
	 *
	 * @var float
	 */
	private $normalized_amount;

	/**
	 * The integer representation of the amount. By default, this is the float value, rounded to the object precision
	 * places and multiplied by (10^precision).
	 *
	 * @var int
	 */
	private $integer = 0;

	/**
	 * The float representation of the amount. By default, this is the same as $normalized_amount
	 *
	 * @var float
	 */
	private $float = 0.0;

	/**
	 * The decimal precision to use in calculations.
	 *
	 * @var int
	 */
	private $precision = 2;

	/**
	 * Initialize class
	 *
	 * @since TBD
	 *
	 * @param mixed $amount the value to set initially
	 */
	public function __construct( $amount = 0 ) {
		$this->set_initial_representation( $amount );
		$this->set_normalized_amount( $amount );
		$this->update();
	}

	/**
	 * Public setter to use for any object.
	 *
	 * Any time the value in a child class needs to be updated, use this method to do it, as it will update
	 * all properties of the object state.
	 *
	 * @since TBD
	 *
	 * @param mixed $amount the value to set
	 */
	public function set_value( $amount ) {
		$this->set_normalized_amount( $amount );
		$this->update();
	}

	/**
	 * Get the current integer representation of the object value
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_integer() {
		return $this->integer;
	}

	/**
	 * Get the current float representation of the object value
	 *
	 * @since TBD
	 *
	 * @return float
	 */
	public function get_float() {
		return $this->float;
	}

	/**
	 * Get the current decimal precision set for the object
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_precision() {
		return $this->precision;
	}

	/**
	 * Get the current normalized value for the object
	 *
	 * @since TBD
	 *
	 * @return float
	 */
	public function get_normalized_value() {
		return $this->normalized_amount;
	}

	/**
	 * Get the value initially passed when the object was instantiated
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function get_initial_representation() {
		return $this->initial_value;
	}

	/**
	 * Transforms any formatted numeric string into a numeric value
	 *
	 * @since TBD
	 *
	 * @param string $amount the formatted string.
	 *
	 * @return float
	 */
	public function normalize( $amount ) {

		if ( is_numeric( $amount ) ) {
			return (float) $amount;
		}

		if ( $this->is_character_block( $amount ) ) {
			return 0.0;
		}

		// If we can split the amount by spaces, remove any blocks that don't contain any digits
		// This is important in case the currency unit contains the same characters as the decimal/thousands
		// separators such as in Moroccan Dirham (1,234.56 .د.م.) or Danish Krone (kr. 1.234,56)
		foreach ( explode( ' ', $amount ) as $block ) {
			if ( $this->is_character_block( $block ) ) {
				$amount = str_replace( $block, '', $amount );
			}
		}

		// Remove encoded html entities
		$amount = preg_replace( '/&[^;]+;/', '', trim( $amount ) );

		// Get all non-digits from the amount
		preg_match_all( '/[^\d]/', $amount, $matches );

		// if the string is all digits, it is numeric
		if ( empty( $matches[0] ) ) {
			return (float) $amount;
		}

		$tokens    = array_unique( $matches[0] );
		$separator = '/////';

		foreach ( $tokens as $token ) {
			if ( $this->is_decimal_separator( $token, $amount ) ) {
				$separator = $token;
				continue;
			}

			$amount = str_replace( $token, '', $amount );
		}

		$pieces  = explode( $separator, $amount );

		// If the initial amount did not have decimals specified, $pieces will be an array of a single
		// numeric value, so we just return it as a float.
		if ( 1 === count( $pieces ) && is_numeric( reset( $pieces ) ) ) {
			return (float) reset( $pieces );
		}

		$decimal = array_pop( $pieces );

		return implode( '', $pieces ) . '.' . $decimal;
	}

	/**
	 * Value loader. This method calls all registered `set_$property_value` methods every time the object is updated
	 * so the values in each of the formats are always kept up to date.
	 *
	 * @since TBD
	 */
	private function update() {
		foreach ( $this->get_setters() as $setter ) {
			$this->{$setter}();
		}
	}

	/**
	 * Private setter for the initial value the object was created with. This value cannot be changed during the object
	 * lifecycle.
	 *
	 * @since TBD
	 *
	 * To set a new value discard the original object and create a new one.
	 */
	private function set_initial_representation( $amount ) {
		if ( empty( $this->initial_value ) ) {
			$this->initial_value = $amount;
		}
	}

	/**
	 * Private setter for the normalized amount extracted from the initial value.
	 *
	 * @since TBD
	 *
	 * To set a new value use the public setter `$obj->set_value( $amount )`
	 */
	private function set_normalized_amount( $amount ) {
		$this->normalized_amount = $this->normalize( $amount );
	}

	/**
	 * Private setter for the integer representation of the object amount.
	 *
	 * @since TBD
	 *
	 * To set a new value use the public setter `$obj->set_value( $amount )`
	 */
	private function set_integer_value() {
		$this->integer = $this->to_integer( $this->normalized_amount );
	}

	/**
	 * Private setter for the floating point representation of the object amount.
	 *
	 * @since TBD
	 *
	 * To set a new value use the public setter `$obj->set_value( $amount )`
	 */
	private function set_float_value() {
		$this->float = $this->normalized_amount;
	}

	/**
	 * Tries to determine if a token is serving as a decimal separator or something else
	 * in a string;
	 *
	 * The rule to determine a decimal is straightforward. It needs to exist only once
	 * in the string and the piece of the string after the separator cannot be longer
	 * than 2 digits. Anything else is serving another purpose.
	 *
	 * @since TBD
	 *
	 * @param $separator string a separator token, like . or ,
	 * @param $value     string a number formatted as a string
	 *
	 * @return bool
	 */
	private function is_decimal_separator( $separator, $value ) {
		$pieces = array_filter( explode( $separator, $value ) );

		foreach ( $pieces as $i => $block ) {
			if ( $this->is_character_block( $block ) ) {
				unset( $pieces[ $i ] );
			}
		}

		if ( 2 === count( $pieces ) ) {
			return strlen( array_pop( $pieces ) ) < 3;
		}

		return false;
	}

	/**
	 * Tests if a string is composed entirely of non-digit characters
	 *
	 * @since TBD
	 *
	 * @param string $block the string to check
	 *
	 * @return bool
	 */
	private function is_character_block( $block ) {
		return empty( preg_replace( '/\D/', '', $block ) );
	}

	/**
	 * Get all valid setters registered to this object instance
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	private function get_setters() {
		$methods = get_class_methods( $this );

		return array_filter( $methods, function ( $item ) {
			return $this->is_valid_setter( $item );
		} );
	}

	/**
	 * Checks if a given method name represents a valid setter for the current object
	 *
	 * Valid setter names are any methods named `set_{$property}_value`, registered to the calling object,
	 * for an existing object $property name.
	 *
	 * @since TBD
	 *
	 * @param string $name a method name
	 *
	 * @return bool
	 */
	private function is_valid_setter( $name ) {
		$vars = get_class_vars( __CLASS__ );

		if ( ! method_exists( $this, $name ) ) {
			return false;
		}

		if ( strpos( $name, 'set_' ) !== 0 || strpos( $name, '_value' ) !== strlen( $name ) - 6 ) {
			return false;
		}

		$name = str_replace( [ 'set_', '_value' ], '', $name );

		if ( in_array( $name, array_keys( $vars ), true ) ) {
			return true;
		}

		return false;
	}
}