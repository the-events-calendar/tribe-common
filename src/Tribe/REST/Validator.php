<?php


class Tribe__REST__Validator {

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_numeric( $value ) {
		return is_numeric( $value );
	}
}