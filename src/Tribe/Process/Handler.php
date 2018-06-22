<?php

/**
 * Class Tribe__Process__Handler
 *
 * The base class for all Modern Tribe async process handlers.
 *
 * @since 4.7.12
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
	 * @since 4.7.12
	 */
	public function __construct() {
		$class        = get_class( $this );
		$this->action = $class::action();
		parent::__construct();
	}

	/**
	 * Returns the async process action name.
	 *
	 * Extending classes must override this method to return their unique action slug.
	 *
	 * @since 4.7.12
	 *
	 * @return string
	 *
	 * @throws RuntimeException If the extending class does not override this method.
	 */
	public static function action() {
		$class = get_called_class();
		throw new RuntimeException( "Class {$class} should override the `action` method to define its own unique identifier." );
	}

	/**
	 * Handles the process immediately, not in an async manner.
	 *
	 * @since 4.7.12
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
	 * @since 4.7.12
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
