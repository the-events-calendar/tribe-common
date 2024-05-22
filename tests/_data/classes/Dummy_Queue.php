<?php

namespace Tribe\Common\Tests;


class Dummy_Queue extends \Tribe__Process__Queue {

	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * Returns the async process action name.
	 *
	 * @since 4.7.12
	 *
	 * @return string
	 */
	public static function action() {
		return 'dummy_queue';
	}

	public function set_callback( callable $callback ) {
		$this->callback = $callback;
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over.
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		return call_user_func_array( $this->callback, func_get_args() );
	}
}
