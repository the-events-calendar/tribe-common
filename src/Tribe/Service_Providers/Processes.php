<?php

/**
 * Class Tribe__Service_Providers__Processes
 *
 * @since 4.7.12
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tribe__Service_Providers__Processes extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		if ( ! (
			tribe( 'context' )->doing_ajax()
			&& false !== $action = tribe_get_request_var( 'action', false )
		) ) {
			return;
		}

		if (
			0 !== strpos( $action, 'tribe_process_' )
			&& 0 !== strpos( $action, 'tribe_queue_' )
		) {
			return;
		}

		if ( 0 === strpos( $action, 'tribe_process_' ) ) {
			$this->hook_handler_for( $action );
		} else {
			$this->hook_queue_for( $action );
		}
	}

	/**
	 * Hooks the correct handler for the action.
	 *
	 * @since 4.7.12
	 *
	 * @param string $action
	 */
	protected function hook_handler_for( $action ) {
		$handlers = array(
			'Tribe__Process__Post_Thumbnail_Setter',
		);

		/**
		 * Filters the process handler classes the Service Provider should handle.
		 *
		 * All handlers should extend the `Tribe__Process__Handler` base class.
		 *
		 * @since 4.7.12
		 *
		 * @param array $handlers
		 */
		$handlers = array_unique( apply_filters( 'tribe_process_handlers', $handlers ) );

		$all_handlers_actions = array_combine(
			$handlers,
			array_map( array( $this, 'get_handler_action' ), $handlers )
		);

		$array_search = array_search( $action, $all_handlers_actions );

		if ( false === $handler_class = $array_search ) {
			return;
		}

		// the handler will handle the hooking
		$this->container->make( $handler_class );
	}

	/**
	 * Hooks the correct queue for the action.
	 *
	 * @since 4.7.12
	 *
	 * @param string $action
	 */
	protected function hook_queue_for( $action ) {
		$queues = array();

		/**
		 * Filters the queue processing classes the Service Provider should handle.
		 *
		 * All queues should extend the `Tribe__Process__Queue` base class.
		 *
		 * @since 4.7.12
		 *
		 * @param array $queues
		 */
		$queues = array_unique( apply_filters( 'tribe_process_queues', $queues ) );

		$all_queues_actions = array_combine(
			$queues,
			array_map( array( $this, 'get_queue_action' ), $queues )
		);

		$array_search = array_search( $action, $all_queues_actions );

		if ( false === $queue_class = $array_search ) {
			return;
		}

		// the queue will handle the hooking
		$this->container->make( $queue_class );
	}

	/**
	 * Returns the action for the handler.
	 *
	 * @since 4.7.12
	 *
	 * @param string $handler_class
	 *
	 * @return string
	 */
	protected function get_handler_action( $handler_class ) {
		/** @var Tribe__Process__Handler handler_class */
		return 'tribe_process_' . call_user_func( array( $handler_class, 'action' ) );
	}

	/**
	 * Returns the action for the queue.
	 *
	 * @since 4.7.12
	 *
	 * @param string $queue_class
	 *
	 * @return string
	 */
	protected function get_queue_action( $queue_class ) {
		/** @var Tribe__Process__Queue queue_class */
		return 'tribe_queue_' . call_user_func( array( $queue_class, 'action' ) );
	}
}
