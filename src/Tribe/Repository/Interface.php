<?php

/**
 * Interface Tribe__Repository__Interface
 *
 * @since TBD
 *
 * @method by_args( array $args )
 * @method where_args( array $args )
 * @method page( $page )
 * @method per_page( $per_page )
 * @method found()
 * @method all()
 * @method offset( $offset, $increment = false )
 * @method order( $order = 'ASC' )
 * @method order_by( $order_by )
 * @method fields( $fields )
 * @method permission( $permission )
 * @method in( $post_ids )
 * @method not_in( $post_ids )
 * @method parent( $post_id )
 * @method parent_in( $post_ids )
 * @method parent_not_in( $post_ids )
 * @method search( $search )
 * @method count()
 * @method first()
 * @method last()
 * @method nth( $n )
 * @method take( $n )
 * @method by_primary_key( $primary_key )
 */
interface Tribe__Repository__Interface {

	/**
	 * Returns the Read repository.
	 *
	 * @since TBD
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function fetch();

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
	 * Returns the Update repository.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Repository__Read_Interface $read A read repository instance.
	 *
	 * @return Tribe__Repository__Update_Interface
	 */
	public function update( Tribe__Repository__Read_Interface $read = null );

	/**
	 * Shortcut method to build a Read repository and call `where`
	 * on it.
	 *
	 * It's equivalent to a call to `fetch()->where( $key, $value )->...`.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function where( $key, $value );

	/**
	 * Shortcut method to build a Read repository and call `by`
	 * on it; usually an alias of `where`.
	 *
	 * It's equivalent to a call to `fetch()->by( $key, $value )->...`.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return Tribe__Repository__Read_Interface
	 */
	public function by( $key, $value );
}
