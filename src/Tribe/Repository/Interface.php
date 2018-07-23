<?php

/**
 * Interface Tribe__Repository__Interface
 *
 * @since TBD
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
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_default_args();

	/**
	 * Sets the default arguments of the repository.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @param Tribe__Repository__Formatter_Interface $formatter
	 */
	public function set_formatter( Tribe__Repository__Formatter_Interface $formatter );


	/**
	 * Build, without initializing it, the query.
	 *
	 * @since TBD
	 *
	 * @return WP_Query
	 */
	public function build_query();

	/**
	 * Adds a custom JOIN clause to the query.
	 *
	 * @since TBD
	 *
	 * @param string $join
	 */
	public function join_clause( $join );

	/**
	 * Adds a custom WHERE clause to the query.
	 *
	 * @since TBD
	 *
	 * @param string $where
	 */
	public function where_clause( $where );

	/**
	 * Sets the object in charge of building and returning the query.
	 *
	 * @since TBD
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
}
