<?php

/**
 * Interface Tribe__Repository__Read_Interface
 *
 * @since 4.7.19
 */
interface Tribe__Repository__Read_Interface {
	/**
	 * Batch filter application method.
	 *
	 * This is the same as calling `by` multiple times with different arguments.
	 *
	 * @since 4.7.19
	 *
	 * @param array $args An associative array of arguments to filter
	 *                    the posts by in the shape [ <key>, <value> ]. * * @return Tribe__Repository__Read_Interface */
	public function by_args( array $args );

	/**
	 * Batch filter application method.
	 *
	 * This is the same as calling `where` multiple times with different arguments.
	 *
	 * T

	/**
	 * Applies a filter to the query.
	 *
	 * While the signature only shows 2 arguments additional arguments will be passed
	 * to the schema filters.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param mixed  ...$args Additional, optional, call arguments that will be passed to
	 *                        the schema.
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function by( $key, $value );

	/**
	 * Just an alias of the `by` method to allow for easier reading.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function where( $key, $value );

	/**
	 * Sets the page of posts to fetch.
	 *
	 * Mind that this implementation does not support a `by( 'page', 2 )`
	 * filter to force more readable code.
	 *
	 * @since 4.7.19
	 *
	 * @param int $page
	 *
	 * @return Tribe__Repository__Read_Interface
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
	 * @return Tribe__Repository__Read_Interface
	 */
	public function per_page( $per_page );

	/**
	 * Returns the number of posts found matching the query.
	 *
	 * Mind that this value ignores the offset returning the
	 * number of results if limits where not applied.
	 *
	 * @since 4.7.19
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
	 * @since 4.7.19
	 *
	 * @param int  $offset
	 * @param bool $increment Whether to increment the offset by the value
	 *                        or replace it.
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function offset( $offset, $increment = false );

	/**
	 * Sets the order on the query.
	 *
	 * Mind that this implementation does not support a `by( 'order', 2 )`
	 * filter to force more readable code.
	 *
	 * @since 4.7.19
	 *
	 * @param string $order
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function order( $order = 'ASC' );

	/**
	 * Sets the order criteria results should be fetched by.
	 *
	 * Mind that this implementation does not support a `by( 'order_by', 'title' )`
	 * filter to force more readable code.
	 *
	 * @since 4.7.19
	 *
	 * @param string $order_by
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function order_by( $order_by );

	/**
	 * Sets the fields that should be returned by the query.
	 *
	 * Mind that this implementation does not support a `by( 'fields', 'ids' )`
	 * filter to force more readable code.
	 *
	 * @since 4.7.19
	 *
	 * @param string $fields
	 *
	 * @return Tribe__Repository__Read_Interface
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
	 * @return Tribe__Repository__Read_Interface
	 */
	public function permission( $permission );

	/**
	 * Sugar method to set the `post__in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since 4.7.19
	 *
	 * @param array|int $post_ids
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function in( $post_ids );

	/**
	 * Sugar method to set the `post__not_in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since 4.7.19
	 *
	 * @param array|int $post_ids
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function not_in( $post_ids );

	/**
	 * Sugar method to set the `post_parent__in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since 4.7.19
	 *
	 * @param array|int $post_id
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function parent( $post_id );

	/**
	 * Sugar method to set the `post_parent__in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since 4.7.19
	 *
	 * @param array $post_ids
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function parent_in( $post_ids );

	/**
	 * Sugar method to set the `post_parent__not_in` argument.
	 *
	 * Successive calls will stack, not replace each one.
	 *
	 * @since 4.7.19
	 *
	 * @param array $post_ids
	 *
	 * @return Tribe__Repository__Read_Interface
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
	 * @return Tribe__Repository__Read_Interface
	 */
	public function search( $search );

	/**
	 * Returns the number of posts found matching the query in the current page.
	 *
	 * While the `found` method will return the number of posts found
	 * across all pages this method will only return the number of
	 * posts found in the current page.
	 * Differently from the `found` method this method will apply the
	 * offset if set.
	 *
	 * @since 4.7.19
	 *
	 * @return int
	 */
	public function count();

	/**
	 * Returns the first post of the page matching the current query.
	 *
	 * If, by default or because set with the `per_page` method, all
	 * posts matching the query should be returned then this will be
	 * the first post of all those matching the query.
	 *
	 * @since 4.7.19
	 *
	 * @return WP_Post|mixed|null
	 *
	 * @see   Tribe__Repository__Read_Interface::per_page()
	 */
	public function first();

	/**
	 * Returns the last post of the page matching the current query.
	 *
	 * If, by default or because set with the `per_page` method, all
	 * posts matching the query should be returned then this will be
	 * the last post of all those matching the query.
	 *
	 * @since 4.7.19
	 *
	 * @return WP_Post|mixed|null
	 *
	 * @see   Tribe__Repository__Read_Interface::per_page()
	 */
	public function last();

	/**
	 * Returns the nth post (1-based) of the page matching the current query.
	 *
	 * Being 1-based the second post can be fetched using `nth( 2 )`.
	 * If, by default or because set with the `per_page` method, all
	 * posts matching the query should be returned then this will be
	 * the nth post of all those matching the query.
	 *
	 * @since 4.7.19
	 *
	 * @param int $n
	 *
	 * @return WP_Post|mixed|null
	 *
	 * @see   Tribe__Repository__Read_Interface::per_page()
	 */
	public function nth( $n );

	/**
	 * Returns the first n posts of the page matching the current query.
	 *
	 * If, by default or because set with the `per_page` method, all
	 * posts matching the query should be returned then this method will
	 * return the first n posts of all those matching the query.
	 *
	 * @since 4.7.19
	 *
	 * @return array An array of posts matching the query.
	 *
	 * @see   Tribe__Repository__Read_Interface::per_page()
	 */
	public function take( $n );

	/**
	 * Fetches a single instance of the post type handled by the repository by
	 * the primary key.
	 *
	 * By default the primary key is the post ID.
	 *
	 * @param mixed $primary_key
	 *
	 * @return WP_Post|null|mixed
	 */
	public function by_primary_key( $primary_key );

	/**
	 * Closes the query phase and builds an Update repository on the
	 * results of the applied filters.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Tribe__Repository__Update_Interface
	 */
	public function set( $key, $value );

	/**
	 * Returns the Read repository built WP_Query object.
	 *
	 * @since 4.7.19
	 *
	 * @return WP_Query
	 */
	public function get_query();

	/**
	 * Whether the current READ query will apply a specific `by` (or `where`)
	 * filter or not.
	 *
	 * @since 4.7.19
	 *
	 * @param string $key
	 * @param null $value If provided an ulterior check will be made to see if
	 *                    the value of the filter that is being applied matches
	 *                    the specified one (w/ loose comparison).
	 *
	 * @return bool Whether the current query setup has the specified filter applied
	 *              or not.
	 */
	public function has_filter( $key, $value = null );
}
