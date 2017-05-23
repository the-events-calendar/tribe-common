<?php

/**
 * Class Tribe__Validator__Base
 *
 * Provides methods to validate values.
 */
class Tribe__Validator__Base implements Tribe__Validator__Interface {

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_numeric( $value ) {
		return is_numeric( $value );
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_string( $value ) {
		return ! empty( $value ) && is_string( $value );
	}

	/**
	 * Whether the value is a timestamp or a string parseable by the strtotime function or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_time( $value ) {
		return is_numeric( $value ) || ( is_string( $value ) && strtotime( $value ) );
	}

	/**
	 * Whether the value corresponds to an existing user ID or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_user_id( $value ) {
		return is_numeric( $value ) && (bool) get_user_by( 'ID', $value );
	}

	/**
	 * Whether the value is a positive integer or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_positive_int( $value ) {
		return is_numeric( $value ) && intval( $value ) == $value && intval( $value ) > 0;
	}

	/**
	 * Trims a string.
	 *
	 * Differently from the trim method it will not use the second argument.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function trim( $value ) {
		return is_string( $value ) ? trim( $value ) : $value;
	}
}