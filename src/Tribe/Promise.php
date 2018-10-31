<?php
/**
 * Models a promise to do something in asynchronous mode.
 *
 * Example usage:
 *
 *      $promise = new Promise( 'wp_insert_post', $a_lot_of_posts_to_insert );
 *      $promise->save()->dispatch();
 *      $promise_id = $promise->get_id();
 *
 * The promise is really a background process in disguise and will work, for all
 * intents and purposes, like one.
 *
 * @since TBD
 */

class Tribe__Promise extends Tribe__Process__Queue {

	/**
	 * {@inheritdoc}
	 */
	public static function action() {
		return 'promise';
	}

	/**
	 * Tribe__Promise constructor.
	 *
	 * @param string|array|Tribe__Utils__Callback $callback   The callback that should run to perform the promise task.
	 * @param   array                             $items      The items to process, each item will be passed as first
	 *                                                        argument to the callback at run-time.
	 * @param array                               $extra_args An array of extra arguments that will be passed to the
	 *                                                        callback function.
	 */
	public function __construct( $callback = null, array $items = null, array $extra_args = array() ) {
		parent::__construct();

		if ( ! empty( $callback ) && ! empty( $items ) ) {
			foreach ( $items as $target ) {
				$item['callback'] = $callback;
				$item['args']     = array_merge( array( $target ), $extra_args );
				$this->push_to_queue( $item );
			}
		}
	}

	/**
	 * Performs the task associated with the promise.
	 *
	 * The promise is really just a flexible background process that
	 *
	 * @since TBD
	 *
	 * @param array $item The promise payload, keys:
	 *                    {
	 *                    @param callable|Tribe__Utils__Callback $callback The callback this promise will
	 *                                                  call to perform the task.
	 *                   @param array $args An array of arguments that will be passed to the callback.
	 *                    }
	 *
	 *
	 * @return bool `true` if the task needs to run again, `false` if the task is complete.
	 */
	protected function task( $item ) {
		$callback = $item['callback'];
		$args     = ! empty( $item['args'] ) ? $item['args'] : array();

		if ( $callback instanceof Tribe__Utils__Callback ) {
			$callback = array( tribe( $callback->get_slug() ), $callback->get_method() );
		}

		if ( count( $args ) ) {
			$done = call_user_func_array( $callback, $args );
		} else {
			$done = $callback();
		}

		// If we are done then return `false` to indicate "no need to run again".
		return $done ? false : true;
	}
}