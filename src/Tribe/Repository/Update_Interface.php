<?php

/**
 * Interface Tribe__Repository__Update_Interface
 *
 * @since 4.7.19
 */
interface Tribe__Repository__Update_Interface extends Tribe__Repository__Setter_Interface {

	/**
	 * Commits the updates to the selected post IDs to the database.
	 *
	 * @since 4.7.19
	 *
	 * @param bool $sync Whether to apply the updates in a synchronous process
	 *                   or in an asynchronous one.
	 *
	 * @return array A list of the post IDs that have been (synchronous) or will
	 *               be (asynchronous) updated.
	 */
	public function save( $sync = true );
}
