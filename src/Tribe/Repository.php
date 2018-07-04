<?php

abstract class Tribe__Repository implements Tribe__Repository__Interface {
	/**
	 * @var array
	 */
	protected $read_schema = array();

	/**
	 * @var array
	 */
	protected $default_args = array( 'post_type' => 'post' );

	/**
	 * {@inheritdoc}
	 */
	public function fetch() {
		return new Tribe__Repository__Read(
			$this->read_schema,
			tribe()->make( 'Tribe__Repository__Query_Filters' ),
			$this->default_args
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_args() {
		return $this->default_args;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_default_args( array $default_args ) {
		$this->default_args = $default_args;

	}
}
