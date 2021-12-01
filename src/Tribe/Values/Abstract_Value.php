<?php

namespace Tribe\Values;

use Tribe\Traits\Reflection_Tools;

abstract class Abstract_Value implements Value_Interface {

	use ValueCalculation;
	use Reflection_Tools;

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

	public $class_name;

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
		$this->class_name = trim( get_class( $this ), '\\' );
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
		/**
		 * Filter the value returned for get_integer() when implemented in a specific class name
		 *
		 * @since TBD
		 *
		 * @param int $value the integer representation of the value
		 * @param Abstract_Value the object instance
		 *
		 * @return int
		 */
		$value = apply_filters( "tec_common_value_{$this->class_name}_get_integer", $this->integer, $this );

		/**
		 * Filter the value returned for get_integer() when implemented in any class
		 *
		 * @since TBD
		 *
		 * @param int $value the integer representation of the value
		 * @param Abstract_Value the object instance
		 *
		 * @return int
		 */
		return apply_filters( 'tec_common_value_get_integer', $value, $this );

	}

	/**
	 * Get the current float representation of the object value
	 *
	 * @since TBD
	 *
	 * @return float
	 */
	public function get_float() {
		/**
		 * Filter the value returned for get_float() when implemented in a specific class name
		 *
		 * @since TBD
		 *
		 * @param float $value the float representation of the value
		 * @param Abstract_Value the object instance
		 *
		 * @return float
		 */
		$value = apply_filters( "tec_common_value_{$this->class_name}_get_integer", $this->float, $this );

		/**
		 * Filter the value returned for get_float() when implemented in any class
		 *
		 * @since TBD
		 *
		 * @param float $value the float representation of the value
		 * @param Abstract_Value the object instance
		 *
		 * @return float
		 */
		return apply_filters( 'tec_common_value_get_float', $value, $this );
	}

	/**
	 * Get the current decimal precision set for the object
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_precision() {
		/**
		 * Filter the value returned for get_precision() when implemented in a specific class name
		 *
		 * @since TBD
		 *
		 * @param int $value the precision to which values will be calculated
		 * @param Abstract_Value the object instance
		 *
		 * @return int
		 */
		$value = apply_filters( "tec_common_value_{$this->class_name}_get_precision", $this->precision, $this );

		/**
		 * Filter the value returned for get_precision() when implemented in any class
		 *
		 * @since TBD
		 *
		 * @param int $value the precision to which values will be calculated
		 * @param Abstract_Value the object instance
		 *
		 * @return int
		 */
		return apply_filters( 'tec_common_value_get_precision', $value, $this );
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
	 * @param string $value the formatted string.
	 *
	 * @return float
	 */
	public function normalize( $value ) {

		if ( is_numeric( $value ) ) {
			/**
			 * Filter the value returned for normalize() when implemented in a specific class name
			 *
			 * @since TBD
			 *
			 * @param float $value the normalized value
			 * @param Abstract_Value the object instance
			 *
			 * @return float
			 */
			$value = (float) apply_filters( "tec_common_value_{$this->class_name}_normalized", (float) $value, $this );

			/**
			 * Filter the value returned for normalize() when implemented in a specific class name
			 *
			 * @since TBD
			 *
			 * @param float $value the normalized value
			 * @param Abstract_Value the object instance
			 *
			 * @return float
			 */
			do_action( 'tec_common_value_normalize_is_numeric', $value, $this );

			/**
			 * Filter the value returned for get_precision() when implemented in any class
			 *
			 * @since TBD
			 *
			 * @param float $value the normalized value
			 * @param Abstract_Value the object instance
			 *
			 * @return float
			 */
			return (float) apply_filters( 'tec_common_value_normalized', $value, $this );
		}

		if ( $this->is_character_block( $value ) ) {
			return (float) 0;
		}

		// If we can split the amount by spaces, remove any blocks that don't contain any digits
		// This is important in case the currency unit contains the same characters as the decimal/thousands
		// separators such as in Moroccan Dirham (1,234.56 .د.م.) or Danish Krone (kr. 1.234,56)
		foreach ( explode( ' ', $value ) as $block ) {
			if ( $this->is_character_block( $block ) ) {
				$value = str_replace( $block, '', $value );
			}
		}

		// Remove encoded html entities
		$value = preg_replace( '/&[^;]+;/', '', trim( $value ) );

		// Get all non-digits from the amount
		preg_match_all( '/[^\d]/', $value, $matches );

		// if the string is all digits, it is numeric
		if ( empty( $matches[0] ) ) {
			return (float) $value;
		}

		$tokens    = array_unique( $matches[0] );
		$separator = '/////';

		foreach ( $tokens as $token ) {
			if ( $this->is_decimal_separator( $token, $value ) ) {
				$separator = $token;
				continue;
			}

			$value = str_replace( $token, '', $value );
		}

		$pieces = explode( $separator, $value );

		// If the initial amount did not have decimals specified, $pieces will be an array of a single
		// numeric value, so we just return it as a float.
		if ( 1 === count( $pieces ) && is_numeric( reset( $pieces ) ) ) {
			return (float) reset( $pieces );
		}

		$decimal = array_pop( $pieces );

		return (float) implode( '', array_merge( $pieces, [ '.', $decimal ] ) );
	}

	/**
	 * Value loader. This method uses Reflection to call all registered `set_$property_value` methods in the
	 * inheritance chain every time the object is updated, so the values in each of the formats are always kept up to
	 * date.
	 *
	 * @since TBD
	 */
	private function update() {
		$reflection = $this->get_reflection_class( $this );

		foreach ( $this->get_setters() as $setter ) {
			$method = $reflection->getMethod( $setter );
			$method->setAccessible( true );
			$method->invoke( $this );
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
		$methods = $this->get_object_method_names( $this, \ReflectionProperty::IS_PRIVATE );

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

		$vars    = $this->get_object_property_names( $this, \ReflectionProperty::IS_PRIVATE );
		$methods = $this->get_object_method_names( $this, \ReflectionProperty::IS_PRIVATE );

		if ( ! in_array( $name, $methods, true ) ) {
			return false;
		}

		if ( strpos( $name, 'set_' ) !== 0 || strpos( $name, '_value' ) !== strlen( $name ) - 6 ) {
			return false;
		}

		$name = str_replace( [ 'set_', '_value' ], '', $name );

		if ( in_array( $name, $vars, true ) ) {
			return true;
		}

		return false;
	}
}