<?php

/**
 * Interface Tribe__Repository__Specialized_Repository_Interface
 *
 * @since TBD
 */
interface Tribe__Repository__Specialized_Repository_Interface {

	/**
	 * Sets the main repository for this specialized repository.
	 *
	 * @param Tribe__Repository__Interface $main_repository
	 */
	public function set_main_repository( Tribe__Repository__Interface $main_repository );
}