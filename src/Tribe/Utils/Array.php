<?php
defined( 'WPINC' ) || die; // Do not load directly.

/**
 * Array utilities
 */
class Tribe__Utils__Array {

	/**
	 * Set key/value within an array, can set a key nested inside of a multidimensional array.
	 *
	 * Example: set( $a, [ 0, 1, 2 ], 'hi' ) sets $a[0][1][2] = 'hi' and returns $a.
	 *
	 * @param mixed        $array  The array containing the key this sets.
	 * @param string|array $key    To set a key nested multiple levels deep pass an array
	 *                             specifying each key in order as a value.
	 *                             Example: array( 'lvl1', 'lvl2', 'lvl3' );
	 * @param mixed         $value The value.
	 *
	 * @return array Full array with the key set to the specified value.
	 */
	public static function set( array $array, $key, $value ) {
		// Convert strings and such to array.
		$key = (array) $key;

		// Setup a pointer that we can point to the key specified.
		$key_pointer = &$array;

		// Iterate through every key, setting the pointer one level deeper each time.
		foreach ( $key as $i ) {

			// Ensure current array depth can have children set.
			if ( ! is_array( $key_pointer ) ) {
				// $key_pointer is set but is not an array. Converting it to an array
				// would likely lead to unexpected problems for whatever first set it.
				$error = sprintf(
					'Attempted to set $array[%1$s] but %2$s is already set and is not an array.',
					implode( $key, '][' ),
					$i
				);

				_doing_it_wrong( __FUNCTION__, esc_html( $error ), '4.3' );
				break;
			} elseif ( ! isset( $key_pointer[ $i ] ) ) {
				$key_pointer[ $i ] = array();
			}

			// Dive one level deeper into the nested array.
			$key_pointer = &$key_pointer[ $i ];
		}

		// Set the value for the specified key
		$key_pointer = $value;

		return $array;
	}

	/**
	 * Find a value inside of an array or object, including one nested a few levels deep.
	 *
	 * Example: get( $a, [ 0, 1, 2 ] ) returns the value of $a[0][1][2] or the default.
	 *
	 * @param  array $variable  Array or object to search within.
	 * @param  array $indexes   Specify each nested index in order.
	 *                          Example: array( 'lvl1', 'lvl2' );
	 * @param  mixed $default   Default value if the search finds nothing.
	 *
	 * @return mixed The value of the specified index or the default if not found.
	 */
	public static function get( $variable, $indexes, $default = null ) {
		if ( is_object( $variable ) ) {
			$variable = (array) $variable;
		}

		if ( ! is_array( $variable ) ) {
			return $default;
		}

		foreach ( (array) $indexes as $index ) {
			if ( ! is_array( $variable ) || ! isset( $variable[ $index ] ) ) {
				$variable = $default;
				break;
			}

			$variable = $variable[ $index ];
		}

		return $variable;
	}

	/**
	 * Behaves exactly like the native strpos(), but accepts an array of needles.
	 *
	 * @see strpos()
	 *
	 * @param string       $haystack String to search in.
	 * @param array|string $needles  Strings to search for.
	 * @param int          $offset   Starting position of search.
	 *
	 * @return false|int Integer position of first needle occurrence.
	 */
	public static function strpos( $haystack, $needles, $offset = 0 ) {
		$needles = (array) $needles;

		foreach ( $needles as $i ) {
			$search = strpos( $haystack, $i, $offset );

			if ( false !== $search ) {
				return $search;
			}
		}

		return false;
	}

	/**
	 * Converts a list to an array filtering out empty string elements.
	 *
	 * @param     mixed   $value A string representing a list of values separated by the specified separator
	 *                           or an array.
	 * @param string $sep The char(s) separating the list elements; will be ignored if the list is an array.
	 *
	 * @return array An array of list elements.
	 */
	public static function list_to_array( $value, $sep = ',' ) {
		if ( empty( $value ) ) {
			return array();
		}

		if ( ! is_array( $value ) ) {
			$value = preg_split( '/\\s*' . preg_quote( $sep ) . '\\s*/', $value );
		}

		$filtered = array();
		foreach ( $value as $v ) {
			if ( '' === $v ) {
				continue;
			}
			$filtered[] = is_numeric( $v ) ? $v + 0 : $v;
		}

		return $filtered;
	}

	/**
	 * Returns a list separated by the specified separator.
	 *
	 * @since 4.6
	 *
	 * @param mixed  $list
	 * @param string $sep
	 *
	 * @return string The list separated by the specified separator or the original list if the list is empty.
	 */
	public static function to_list( $list, $sep = ',' ) {
		if ( empty( $list ) ) {
			return $list;
		}

		if ( is_array( $list ) ) {
			return implode( $sep, $list );
		}

		return $list;
	}
}
