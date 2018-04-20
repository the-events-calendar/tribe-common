<?php

/**
 * Class Tribe__Process__Handler
 *
 * The base class for all Modern Tribe async process handlers.
 *
 * @since TBD
 *
 * @see   Tribe__Service_Providers__Processes for more insight about this class utility.
 */
abstract class Tribe__Process__Handler extends WP_Async_Request {
	/**
	 * @var string The common identified prefix to all our async process handlers.
	 */
	protected $prefix = 'tribe_process';

	/**
	 * Tribe__Process__Handler constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$class        = get_class( $this );
		$this->action = $class::action();
		parent::__construct();
	}

	/**
	 * Returns the async process action name.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract public static function action();

	/**
	 * Handles the process immediately, not in an async manner.
	 *
	 * @since TBD
	 *
	 * @param array|null $data_source If not provided the method will read the handler data from the
	 *                                request array.
	 *
	 * @return mixed
	 */
	abstract public function sync_handle( array $data_source = null );

	/**
	 * Overrides the base `dispatch` method to allow for constants and/or environment vars to run
	 * async requests in sync mode.
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function dispatch() {
		if (
			( defined( 'TRIBE_NO_ASYNC' ) && true === TRIBE_NO_ASYNC )
			|| true == getenv( 'TRIBE_NO_ASYNC' )
		) {
			return $this->sync_handle( $this->data );
		}

		return parent::dispatch();
	}
}
