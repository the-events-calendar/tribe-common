<?php

abstract class Tribe__Repository implements Tribe__Repository__Interface {
	/**
	 * @var Tribe__Repository__Schema
	 */
	protected $read_schema;

	/**
	 * @var array
	 */
	protected $default_args = array( 'post_type' => 'post' );

	/**
	 * {@inheritdoc}
	 */
	public function fetch() {
		return new Tribe__Repository__Read( $this->read_schema, tribe()->make( 'Tribe__Repository__Query_Filters' ), $this->default_args );
	}
}
