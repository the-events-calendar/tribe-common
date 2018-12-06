<?php

/**
 * Class Tribe__Repository__Decorator
 *
 * This is the base repository decorator class to ease the decoration
 * of repositories.
 *
 * @since 4.7.19
 */
abstract class Tribe__Repository__Decorator implements Tribe__Repository__Interface {
	/**
	 * @var Tribe__Repository__Interface|Tribe__Repository__Read_Interface|Tribe__Repository__Update_Interface
	 */
	protected $decorated;

	/**
	 * {@inheritdoc}
	 */
	public function get_default_args() {
		return $this->decorated->get_default_args();
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_default_args( array $default_args ) {
		return $this->decorated->set_default_args( $default_args );
	}

	/**
	 * {@inheritdoc}
	 */
	public function filter_name( $filter_name ) {
		$this->decorated->filter_name( $filter_name );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_args( array $args ) {
		$this->decorated->by_args( $args );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by( $key, $value ) {
		$call_args = func_get_args();
		call_user_func_array( array( $this->decorated, 'by' ), $call_args );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function where( $key, $value ) {
		$call_args = func_get_args();
		call_user_func_array( array( $this->decorated, 'where' ), $call_args );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function page( $page ) {
		$this->decorated->page( $page );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function per_page( $per_page ) {
		$this->decorated->per_page( $per_page );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function found() {
		return $this->decorated->found();
	}

	/**
	 * {@inheritdoc}
	 */
	public function all() {
		return $this->decorated->all();
	}

	/**
	 * {@inheritdoc}
	 */
	public function offset( $offset, $increment = false ) {
		$this->decorated->offset( $offset );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function order( $order = 'ASC' ) {
		$this->decorated->order( $order );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function order_by( $order_by ) {
		$this->decorated->order_by( $order_by );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function fields( $fields ) {
		$this->decorated->fields( $fields );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function permission( $permission ) {
		$this->decorated->permission( $permission );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function in( $post_ids ) {
		$this->decorated->in( $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function not_in( $post_ids ) {
		$this->decorated->not_in( $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parent( $post_id ) {
		$this->decorated->parent( $post_id );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parent_in( $post_ids ) {
		$this->decorated->parent_in( $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parent_not_in( $post_ids ) {
		$this->decorated->parent_not_in( $post_ids );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function search( $search ) {
		$this->decorated->search( $search );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		return $this->decorated->count();
	}

	/**
	 * {@inheritdoc}
	 */
	public function first() {
		return $this->decorated->first();
	}

	/**
	 * {@inheritdoc}
	 */
	public function last() {
		return $this->decorated->last();
	}

	/**
	 * {@inheritdoc}
	 */
	public function nth( $n ) {
		return $this->decorated->first();
	}

	/**
	 * {@inheritdoc}
	 */
	public function take( $n ) {
		return $this->decorated->take( $n );
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_primary_key( $primary_key ) {
		return $this->decorated->by_primary_key( $primary_key );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set( $key, $value ) {
		$this->decorated->set( $key, $value );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query() {
		return $this->decorated->get_query();
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_args( array $update_map ) {
		$this->decorated->set_args( $update_map );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save( $sync = true ) {
		$this->decorated->save( $sync );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_formatter( Tribe__Repository__Formatter_Interface $formatter ) {
		$this->decorated->set_formatter( $formatter );
	}

	/**
	 * {@inheritdoc}
	 */
	public function join_clause( $join ) {
		$this->decorated->join_clause( $join );
	}

	/**
	 * {@inheritdoc}
	 */
	public function where_clause( $where ) {
		$this->decorated->where_clause( $where );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_query_builder( $query_builder ) {
		$this->decorated->set_query_builder( $query_builder );
	}

	/**
	 * Sets the repository to be decorated.
	 *
	 * @since 4.7.19
	 *
	 * @param Tribe__Repository__Interface $decorated
	 */
	protected function set_decorated_repository( Tribe__Repository__Interface $decorated ) {
		$this->decorated = $decorated;
	}

	/**
	 * {@inheritdoc}
	 */
	public function build_query() {
		return $this->decorated->build_query();
	}

	/**
	 * {@inheritdoc}
	 */
	public function where_or( $callbacks ) {
		$call_args = func_get_args();
		call_user_func_array( array( $this->decorated, 'where_or' ), $call_args );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_related_to_min( $by_meta_keys, $min, $keys = null, $values = null ) {
		$this->decorated->by_related_to_min( $by_meta_keys, $min, $keys, $values );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_related_to_max( $by_meta_keys, $max, $keys = null, $values = null ) {
		$this->decorated->by_related_to_max( $by_meta_keys, $max, $keys, $values );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function by_related_to_between( $by_meta_keys, $min, $max, $keys = null, $values = null ) {
		$this->decorated->by_related_to_between( $by_meta_keys, $min, $max, $keys, $values );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_filter( $key, $value = null ) {
		return $this->decorated->has_filter( $key, $value );
	}
}
