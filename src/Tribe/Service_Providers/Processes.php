<?php

/**
 * Class Tribe__Service_Providers__Processes
 *
 * @since TBD
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tribe__Service_Providers__Processes extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 */
	public function register() {
		if ( ! ( tribe( 'context' )->doing_ajax() && ! empty( $_POST['action'] ) ) ) {
			return;
		}

		$action = $_POST['action'];

		if ( 0 !== strpos( $action, 'tribe_process_' ) ) {
			return;
		}

		$handlers = array(
			'Tribe__Process__Post_Thumbnail_Setter',
		);

		/**
		 * Filters the process handlers the Service Provider should handle.
		 *
		 * All handlers should extend the `Tribe__Process__Handler` base class.
		 *
		 * @since TBD
		 *
		 * @param array $handlers
		 */
		$handlers = apply_filters( 'tribe_process_handlers', $handlers );

		$all_handlers_actions = array_combine(
			$handlers,
			array_map( array( $this, 'get_handler_action' ), $handlers )
		);

		$array_search = array_search( $action, $all_handlers_actions );

		if ( false === $handler_class = $array_search ) {
			return;
		}

		// the handlers will handle the hooking
		$this->container->make( $handler_class );
	}

	/**
	 * Returns the action for the handler.
	 *
	 * @since TBD
	 *
	 * @param string $handler_class
	 *
	 * @return string
	 */
	protected function get_handler_action( $handler_class ) {
		/** @var Tribe__Process__Handler handler_class */
		return 'tribe_process_' . call_user_func( array( $handler_class, 'action' ) );
	}
}