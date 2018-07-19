<?php


/**
 * Class Tribe__Repository__Specialized_Base
 *
 * A base to provide specialized repositories (e.g. Read, Create...) with
 * common methods.
 *
 * @since TBD
 */
abstract class Tribe__Repository__Specialized_Base {


	/**
	 * @var string
	 */
	protected $filter_name = 'default';

	/**
	 * @var Tribe__Repository__Interface
	 */
	protected $previous_repository;

	/**
	 * {@inheritdoc}
	 */
	public function filter_name( $filter_name ) {
		$this->filter_name = trim( $filter_name );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_previous_repository( Tribe__Repository__Specialized_Repository_Interface $previous_repository ) {
		$this->previous_repository = $previous_repository;
	}
}