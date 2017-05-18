<?php

interface Tribe__REST__Validator_Interface {
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