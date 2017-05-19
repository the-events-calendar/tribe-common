<?php

/**
 * Interface Tribe__Validator__Interface
 *
 * Models any class that provides methods to validate values.
 */
interface Tribe__Validator__Interface {
	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_numeric( $value );

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_string( $value );

	/**
	 * Whether the value is a timestamp or a string parseable by the strtotime function or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_time( $value );

	/**
	 * Whether the value corresponds to an existing user ID or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_user_id( $value );
}