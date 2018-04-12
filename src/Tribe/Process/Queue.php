<?php
abstract class Tribe__Process__Queue extends WP_Background_Process {
	/**
	 * @var string The common identified prefix to all our async process handlers.
	 */
	protected $prefix = 'tribe_queue';

	/**
	 * Tribe__Process__Queue constructor.
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
	 * @return mixed
	 */
	public function sync_process() {
		$result = [];
		foreach ( $this->data as $item ) {
			$result[] = $this->task( $item );
		}

		return $result;
	}

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
			return $this->sync_process( $this->data );
		}

		return parent::dispatch();
	}
}
