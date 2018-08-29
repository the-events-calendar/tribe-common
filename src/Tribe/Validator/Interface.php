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

	/**
	 * Whether the value is a positive integer or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_positive_int( $value );

	/**
	 * Trims a string.
	 *
	 * Differently from the trim method it will not use the second argument.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function trim( $value );

	/**
	 * Whether the value(s) all map to existing post tags.
	 *
	 * @param mixed $tag
	 *
	 * @return bool
	 */
	public function is_post_tag( $tag );

	/**
	 * Whether the term exists and is a term of the specified taxonomy.
	 *
	 * @param mixed  $term Either a single term `term_id` or `slug` or an array of
	 *                     `term_id`s and `slug`s
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public function is_term_of_taxonomy( $term, $taxonomy );

	/**
	 * Whether the provided value points to an existing attachment ID or an existing image URL.
	 *
	 * @param int|string $image
	 *
	 * @return mixed
	 */
	public function is_image( $image );

	/**
	 * Returns the ID of a post for the specified name and type if any.
	 *
	 * @since TBD
	 *
	 * @param string $slug
	 * @param string $post_type
	 *
	 * @return int|false The post ID if found, `false` otherwise.
	 */
	public function get_id_for_slug( $slug, $post_type );
}
