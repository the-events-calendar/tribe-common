<?php

/**
 * Interface Tribe__Repository__Interface
 *
 * @since 4.7.19
 *
 */
interface Tribe__Repository__Interface
	extends Tribe__Repository__Read_Interface,
	Tribe__Repository__Update_Interface {

	const PERMISSION_EDITABLE = 'editable';
	const PERMISSION_READABLE = 'readable';

	/**
	 * Returns the current default query arguments of the repository.
	 *
	 * @since 4.7.19
	 *
	 * @return array
	 */
	public function get_default_args();

	/**
	 * Sets the default arguments of the repository.
	 *
	 * @since 4.7.19
	 *
	 * @param array $default_args
	 *
	 * @return mixed
	 */
	public function set_default_args( array $default_args );

	/**
	 * Sets the dynamic part of the filter tag that will be used to filter
	 * the query arguments and object.
	 *
	 * @param string $filter_name
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function filter_name( $filter_name );

	/**
	 * Sets the formatter in charge of formatting items to the correct format.
	 *
	 * @since 4.7.19
	 *
	 * @param Tribe__Repository__Formatter_Interface $formatter
	 */
	public function set_formatter( Tribe__Repository__Formatter_Interface $formatter );


	/**
	 * Build, without initializing it, the query.
	 *
	 * @since 4.7.19
	 *
	 * @return WP_Query
	 */
	public function build_query();

	/**
	 * Adds a custom JOIN clause to the query.
	 *
	 * @since 4.7.19
	 *
	 * @param string $join
	 */
	public function join_clause( $join );

	/**
	 * Adds a custom WHERE clause to the query.
	 *
	 * @since 4.7.19
	 *
	 * @param string $where
	 */
	public function where_clause( $where );

	/**
	 * Sets the object in charge of building and returning the query.
	 *
	 * @since 4.7.19
	 *
	 * @param mixed $query_builder
	 *
	 * @return mixed
	 */
	public function set_query_builder( $query_builder );

	/**
	 * Builds a fenced group of WHERE clauses that will be used with OR logic.
	 *
	 * Mind that this is a lower level implementation of WHERE logic that requires
	 * each callback method to add, at least, one WHERE clause using the repository
	 * own `where_clause` method.
	 *
	 * @param array $callbacks       One or more WHERE callbacks that will be called
	 *                                this repository. The callbacks have the shape
	 *                                [ <method>, <...args>]
	 *
	 * @return $this
	 * @throws Tribe__Repository__Usage_Error If one of the callback methods does
	 *                                        not add any WHERE clause.
	 *
	 * @see Tribe__Repository::where_clause()
	 * @see Tribe__Repository__Query_Filters::where()
	 */
	public function where_or( $callbacks );

	/**
	 * Filters the query to return posts that have got a number or posts
	 * related to them by meta at least equal to a value.
	 *
	 * @since 4.7.19
	 *
	 * @param string|array $by_meta_keys One or more `meta_keys` relating
	 *                                   another post TO this post type.
	 * @param int          $min          The minimum number of posts of another type that should
	 *                                   be related to the queries post type(s).
	 * @param string|array $keys         One or more meta_keys to check on the post type in relation
	 *                                   with the query post type(s); if the `$values` parameter is
	 *                                   not provided then this will trigger an EXISTS check.
	 * @param string|array $values       One or more value the meta_key specified with `$keys` should
	 *                                   match.
	 *
	 * @return $this
	 */
	public function by_related_to_min( $by_meta_keys, $min, $keys = null, $values = null );

	/**
	 * Filters the query to return posts that have got a number or posts
	 * related to them by meta at most equal to a value.
	 *
	 * @since 4.7.19
	 *
	 * @param string|array $by_meta_keys One or more `meta_keys` relating
	 *                                   another post TO this post type.
	 *                                   be related to the queries post type(s).
	 * @param int          $max          The maximum number of posts of another type that should
	 *                                   be related to the queries post type(s).
	 * @param string|array $keys         One or more meta_keys to check on the post type in relation
	 *                                   with the query post type(s); if the `$values` parameter is
	 *                                   not provided then this will trigger an EXISTS check.
	 * @param string|array $values       One or more value the meta_key specified with `$keys` should
	 *                                   match.
	 *
	 * @return $this
	 */
	public function by_related_to_max( $by_meta_keys, $max, $keys = null, $values = null );

	/**
	 * Filters the query to return posts that have got a number or posts
	 * related to them by meta between two values.
	 *
	 * @since 4.7.19
	 *
	 * @param string|array $by_meta_keys One or more `meta_keys` relating
	 *                                   another post TO this post type.
	 * @param int          $min          The minimum number of posts of another type that should
	 *                                   be related to the queries post type(s).
	 * @param int          $max          The maximum number of posts of another type that should
	 *                                   be related to the queries post type(s).
	 *
	 * @param string|array $keys         One or more meta_keys to check on the post type in relation
	 *                                   with the query post type(s); if the `$values` parameter is
	 *                                   not provided then this will trigger an EXISTS check.
	 * @param string|array $values       One or more value the meta_key specified with `$keys` should
	 *                                   match.
	 *
	 * @return $this
	 */
	public function by_related_to_between( $by_meta_keys, $min, $max, $keys = null, $values = null );
}
