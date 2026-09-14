<?php

/**
 * Class Tribe__Duplicate__Strategy__Same
 *
 * Models a close matching strategy; rougly a `==` equivalent.
 *
 * @since 4.6
 */
class Tribe__Duplicate__Strategy__Same
	extends Tribe__Duplicate__Strategy__Base
	implements Tribe__Duplicate__Strategy__Interface {
	/**
	 * Returns a string suitable to be used as a WHERE clause in a SQL query.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return string
	 *
	 * @since 4.6
	 */
	public function where( $key, $value ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		if ( $this->is_a_numeric_post_field( $key ) ) {
			return $wpdb->prepare( '%i = %d', $key, $value );
		}

		return $wpdb->prepare( '%i = %s', $key, $value );
	}

	/**
	 * Returns a string suitable to be used as a WHERE clause in a SQL query for a custom field JOIN.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param string $table_alias
	 *
	 * @return string
	 *
	 * @since 4.6
	 */
	public function where_custom_field( $key, $value, $table_alias ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		return $wpdb->prepare( '%i.meta_key = %s AND %i.meta_value = %s', $table_alias, $key, $table_alias, $value );
	}
}
