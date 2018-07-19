<?php

/**
 * Interface Tribe__Repository__Specialized_Repository_Interface
 *
 * @since TBD
 */
interface Tribe__Repository__Specialized_Repository_Interface {

	/**
	 * Sets the previous repository for this specialized repository.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Repository__Specialized_Repository_Interface $main_repository
	 */
	public function set_previous_repository( Tribe__Repository__Specialized_Repository_Interface $main_repository );
}