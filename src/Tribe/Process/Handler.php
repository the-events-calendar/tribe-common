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
	 * This handler cron identifier.
	 *
	 * @var string
	 */
	protected $cron_hook_identifier;

	/**
	 * Handles the process request if valid and if authorized.
	 *
	 * @since 4.7.23
	 *
	 * @param array|null $data_source An optional data source.
	 */
	public function maybe_handle( $data_source = null ) {
		$data_source = (array) $data_source;

		if ( $this->feature_detection->supports_async_process() ) {
			parent::maybe_handle();
		}

		/*
		 * If the environment does not support AJAX-based async processing then
		 * fallback to use the cron-based approach and just call the handle method
		 * removing it first from the action to avoid multiple calls.
		 */
		remove_action( $this->cron_hook_identifier, array( $this, 'maybe_handle' ) );
		$this->handle( $data_source );
	}

	/**
	 * @var string The common identified prefix to all our async process handlers.
	 */
	protected $prefix = 'tribe_process';

	/**
	 * An instance of the object abstracting the feature detection functionality.
	 *
	 * @var Tribe__Feature_Detection
	 */
	protected $feature_detection;

	/**
	 * Tribe__Process__Handler constructor.
	 *
	 * @since 4.7.12
	 */
	public function __construct() {
		$class        = get_class( $this );
		$this->action = call_user_func( array( $class, 'action' ) );
		parent::__construct();

		$this->cron_hook_identifier = $this->identifier;
		$this->feature_detection = tribe( 'feature-detection' );

		/*
		 * This object might have been built while processing crons so
		 * we hook on the the object cron identifier to handle the task
		 * if the cron-triggered action ever fires.
		 */
		add_action( $this->cron_hook_identifier, array( $this, 'maybe_handle' ) );
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

		if ( $this->feature_detection->supports_async_process() ) {
			return parent::dispatch();
		}

		/*
		 * If async AJAX-based processing is not available then we "dispatch"
		 * by scheduling a single cron event immediately (as soon as possible)
		 * for this handler cron identifier.
		 */
		if ( ! wp_next_scheduled( $this->cron_hook_identifier, array( $this->data ) ) ) {
			// Schedule the event to happen as soon as possible.
			$scheduled = wp_schedule_single_event( time() - 1, $this->cron_hook_identifier, array( $this->data ) );

			if ( false === $scheduled ) {
				/** @var Tribe__Log__Logger $logger */
				$logger = tribe( 'logger' );
				$class  = get_class( $this );
				$src    = call_user_func( array( $class, 'action' ) );
				$logger->log( 'Could not schedule event for cron-based handling', Tribe__Log::ERROR, $src );
			}
		}

		return true;
	}

	/**
	 * Returns this handler cron hook identifier.
	 *
	 * The handler cron hook identifier is the one that the handler
	 * will use to schedule a single cron event when the `dispatch`
	 * method is called and the environment does not support async
	 * processing.
	 *
	 * @since 4.7.23
	 *
	 * @return string The complete cron hook name (identifier) for
	 *                this handler.
	 */
	public function get_cron_hook_identifier() {
		return $this->cron_hook_identifier;
	}

}
