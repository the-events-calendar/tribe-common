<?php

if ( ! function_exists( 'set_object_state' ) ) {
	/**
	 * Proxy for `stdClass::__set_state`.
	 *
	 * Use PHPStorm to copy a value as `var_export` and run:
	 *
	 *      `:s/stdClass::__set_state/set_object_state/g`
	 *
	 * Have fun.
	 *
	 * @param array $properties
	 *
	 * @return stdClass
	 */
	function set_object_state( array $properties ) {
		$obj = new stdClass();
		foreach ( $properties as $key => $value ) {
			$obj->{$key} = $value;
		}

		return $obj;
	}
}

if ( ! function_exists( 'template' ) ) {
	function ea_mocker_template( $template, array $data = array() ) {
		if ( empty( $data ) ) {
			return $template;
		}

		if ( is_array( $template ) || is_object( $template ) ) {
			$template = json_encode( (array) $template );
		}

		$keys = array();
		$count = count( $data );
		for ( $i = 0; $i < $count; $i ++ ) {
			$data_keys = array_keys( $data );
			$keys[] = '{{' . $data_keys[ $i ] . '}}';
		}

		return json_decode( str_replace( $keys, array_values( $data ), $template ) );
	}
}

