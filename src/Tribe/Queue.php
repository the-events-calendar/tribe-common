<?php

class Tribe__Queue {
	/**
	 * The name of the option that stores the queue current works and their stati.
	 */
	const WORKS_OPTION = 'tribe_q_works';

	/**
	 * Tells the factory to work on all the available works.
	 *
	 * This is the method that should be hooked to actions and crons.
	 *
	 * @return bool
	 */
	public static function work() {
		$instance = new self();

		$list = array_keys( $instance->get_work_list() );

		if ( empty( $list ) ) {
			return true;
		}

		return $instance->work_on( reset( $list ) );
	}

	/**
	 * Tells the factory to work on a particular work immediately.
	 *
	 * @param string $work_id
	 *
	 * @return bool `true` if the work was done successfully, `false` otherwise.
	 *              Successfully means that something was done, it does not mean complete.
	 */
	public function work_on( $work_id ) {
		$work = $this->get_work( $work_id );

		if ( empty( $work ) ) {
			return false;
		}

		$work->work();

		if ( $work->get_status() === Tribe__Queue__Worker::DONE ) {
			$this->remove_work_from_list( $work );
		} else {
			$this->update_work_status( $work );
		}

		return true;
	}

	protected function remove_work_from_list( Tribe__Queue__Worker $work ) {
		$list = $this->get_work_list();

		unset( $list[ $work->get_id() ] );

		update_option( self::WORKS_OPTION, $list );
	}

	/**
	 * Appends a new work to the queue.
	 *
	 * @param array                 $targets
	 * @param       callable| array $callback  Either a callable object or array or a container reference in the
	 *                                         ['tribe', <alias>, <method>] format.
	 *                                         The callback will receive three arguments: the current target, the
	 *                                         target index in the complete list of targets and the data for this work.
	 * @param mixed                 $data      Some additional data that will be passed to the work callback.
	 *
	 * @return Tribe__Queue__Worker
	 */
	public function queue_work( array $targets, $callback, $data = null ) {
		// let the Tribe__Queue__Work class make its own verifications
		$work = new Tribe__Queue__Worker( $targets, $targets, $callback, $data, Tribe__Queue__Worker::QUEUED );

		$this->update_work_status( $work );

		return $work;
	}

	/**
	 * Prepends a new work to the queue.
	 *
	 * @param array                 $targets
	 * @param       callable| array $callback  Either a callable object or array or a container reference in the
	 *                                         ['tribe', <alias>, <method>] format.
	 *                                         The callback will receive three arguments: the current target, the
	 *                                         target index in the complete list of targets and the data for this work.
	 * @param mixed                 $data      Some additional data that will be passed to the work callback.
	 *
	 * @return Tribe__Queue__Worker
	 */
	public function prepend_work( array $targets, $callback, $data = null ) {
		// let the Tribe__Queue__Work class make its own verifications
		$work = new Tribe__Queue__Worker( $targets, $targets, $callback, $data, Tribe__Queue__Worker::QUEUED );

		$this->update_work_status( $work, false );

		return $work;
	}

	/**
	 * Updates the status of a work in the Factory managed option.
	 *
	 * @param $work
	 */
	protected function update_work_status( Tribe__Queue__Worker $work, $append = true ) {
		$list = $this->get_work_list();

		if ( $append ) {
			$list[ $work->get_id() ] = $work->get_status();
		} else {
			$list = array_merge( array( $work->get_id() => $work->get_status() ), $list );
		}

		update_option( self::WORKS_OPTION, $list );
	}

	/**
	 * @return array
	 */
	public function get_work_list() {
		$list = get_option( self::WORKS_OPTION );

		if ( empty( $list ) ) {
			$list = array();
		}

		$working_stati = array( Tribe__Queue__Worker::WORKING, Tribe__Queue__Worker::QUEUED );

		$filtered = array();
		foreach ( $list as $work_id => $work_status ) {
			$work = $this->get_work( $work_id );
			if ( empty( $work ) || ! in_array( $work_status, $working_stati ) ) {
				continue;
			}
			$filtered[ $work_id ] = $work_status;
		}

		return $filtered;
	}

	/**
	 * Returns the status of a work.
	 *
	 * @param string $work_id
	 *
	 * @see Tribe__Queue__Worker for possible stati.
	 *
	 * @return string
	 */
	public function get_work_status( $work_id ) {
		$work = $this->get_work( $work_id );

		return false !== $work ? $work->get_status() : Tribe__Queue__Worker::NOT_FOUND;
	}

	/**
	 * Builds and returns a Tribe__Queue__Work object from its id.
	 *
	 * @param string $work_id
	 *
	 * @return Tribe__Queue__Worker|false Either the built Work object or `false` if the work object could not be found.
	 */
	public function get_work( $work_id ) {
		$work_data = get_transient( $this->build_transient_name( $work_id ) );

		if ( empty( $work_data ) || false === $decoded = json_decode( $work_data ) ) {
			// either invalid or expired
			return false;
		}

		return $this->build_from_data( $decoded );
	}

	/**
	 * Builds the name of the transient storing a work information from its work id.
	 *
	 * @param string $work_id
	 *
	 * @return string The complete transient name.
	 */
	protected function build_transient_name( $work_id ) {
		$transient = Tribe__Queue__Worker::TRANSIENT_PREFIX . $work_id;

		return $transient;
	}

	/**
	 * Builds a Work object from its JSON representation.
	 *
	 * @param stdClass $work_data
	 *
	 * @return Tribe__Queue__Worker
	 */
	protected function build_from_data( stdClass $work_data ) {
		$work = new Tribe__Queue__Worker( $work_data->targets, $work_data->remaining, $work_data->callback, $work_data->data, $work_data->status );

		if ( isset( $work_data->batch_size ) ) {
			$work->set_batch_size( $work_data->batch_size );
		}

		return $work;
	}
}