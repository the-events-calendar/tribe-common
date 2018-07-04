<?php

/**
 * Interface Tribe__Repository__Interface
 *
 * @since TBD
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
}
