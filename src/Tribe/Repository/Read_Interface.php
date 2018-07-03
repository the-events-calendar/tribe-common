<?php

/**
 * Interface Tribe__Repository__Read_Interface
 *
 * @since TBD
 */
interface Tribe__Repository__Read_Interface {
	const PERMISSION_EDITABLE = 'editable';
	const PERMISSION_READABLE = 'readable';

	/**
	 * Batch filter application method.
	 *
	 * This is the same as calling `by` multiple times with different arguments.
	 *
	 * @since TBD
	 *
	 * @param array $args An associative array of arguments to filter
	 *                    the posts by in the shape [ <key>, <value> ].
	 *
	 * @return $this|Tribe__Repository__Read_Interface
	 */
	public function by_args( array $args );

	/**
	 * Applies a filter to the query.
	 *
	 * While the signature only shows 2 arguments additional arguments will be passed
	 * to the schema filters.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param mixed  ...$args Additional, optional, call arguments that will be passed to
	 *                        the schema.
	 *
	 * @return $this|Tribe__Repository__Read_Interface
	 */
	public function by( $key, $value );

	/**
	 * Just an alias of the `by` method to allow for easier reading.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Tribe__Repository__Read|Tribe__Repository__Read_Interface
	 */
	public function where( $key, $value );

	/**
	 * Sets the page of posts to fetch.
	 *
	 * Mind that this implementation does not support a `by( 'page', 2 )`
	 * filter to force more readable code.
	 *
	 * @since TBD
	 *
	 * @param int $page
	 *
	 * @return $this|Tribe__Repository__Read_Interface
	 */
	public function page( $page );

	/**
	 * Sets the number of posts to retrieve per page.
	 *
	 * Mind that this implementation does not support a `by( 'per_page', 5 )`
	 * filter to force more readable code; by default posts per page is `-1`.
	 *
	 * @param int $per_page
	 *
	 * @return $this|Tribe__Repository__Read_Interface
	 */
	public function per_page( $per_page );

	/**

	 * Returns the number of posts found matching the query.
	 *
	 * @return int
	 */
	public function found();

	/**
	 * Returns all posts matching the query.
	 *
	 * Mind that "all" means "all the posts matching all the filters" so pagination applies.
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Sets the offset on the query.
	 *
	 * Mind that this implementation does not support a `by( 'offset', 2 )`
	 * filter to force more readable code.
	 *
	 * @since TBD
	 *
	 * @param int $offset
	 *
	 * @return $this
	 */
	public function offset( $offset );

	/**
	 * Sets the order on the query.
	 *
	 * Mind that this implementation does not support a `by( 'order', 2 )`
	 * filter to force more readable code.
	 *
	 * @since TBD
	 *
	 * @param string $order
	 *
	 * @return $this
	 */
	public function order( $order = 'ASC' );

	/**
	 * Sets the order criteria results should be fetched by.
	 *
	 * Mind that this implementation does not support a `by( 'order_by', 'title' )`
	 * filter to force more readable code.
	 *
	 * @since TBD
	 *
	 * @param string $order_by
	 *
	 * @return $this
	 */
	public function order_by( $order_by );

	/**
	 * Sets the fields that should be returned by the query.
	 *
	 * Mind that this implementation does not support a `by( 'fields', 'ids' )`
	 * filter to force more readable code.
	 *
	 * @since TBD
	 *
	 * @param string $fields
	 *
	 * @return $this
	 */
	public function fields( $fields );

	/**
	 * Sets the permission that should be used to get the posts.
	 *
	 * Mind that this implementation does not support a `by( 'perm', 'editable' )`
	 * filter to force more readable code.
	 *
	 * @param string $permission One of the two `self::PERMISSION` constants.
	 *
	 * @return $this
	 */
	public function permission( $permission );

	/**
	 * Sets the dynamic part of the filter tag that will be used to filter
	 * the query arguments and object.
	 *
	 * @param string $filter_name
	 *
	 * @return $this
	 */
	public function filter_name( $filter_name );

	/**
	 * Sugar method to set the `post__in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since TBD
	 *
	 * @param array|int $post_ids
	 *
	 * @return $this
	 */
	public function in( $post_ids );

	/**
	 * Sugar method to set the `post__not_in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since TBD
	 *
	 * @param array|int $post_ids
	 *
	 * @return $this
	 */
	public function not_in( $post_ids );

	/**
	 * Sugar method to set the `post_parent__in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since TBD
	 *
	 * @param array|int $post_id
	 *
	 * @return $this
	 */
	public function parent( $post_id );

	/**
	 * Sugar method to set the `post_parent__in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since TBD
	 *
	 * @param array $post_ids
	 *
	 * @return $this
	 */
	public function parent_in( $post_ids );

	/**
	 * Sugar method to set the `post_parent__not_in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since TBD
	 *
	 * @param array $post_ids
	 *
	 * @return $this
	 */
	public function parent_not_in( $post_ids );

	/**
	 * Sugar method to set the `s` argument.
	 *
	 * Successive calls will replace the search string.
	 * This is the default WordPress searh, to search by title,
	 * content or excerpt only use the `title`, `content`, `excerpt` filters.
	 *
	 * @param $search
	 *
	 * @return $this
	 */
	public function search( $search );

	/**
	 * Returns the number of posts found matching the query.
	 *
	 * An alias of the `found` method.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function count();
}
