<?php

class Tribe__Queue__Worker {

	public static $queued = 'queued';
	public static $working = 'working';
	public static $done = 'done';
	public static $not_found = 'not-found';
	public static $transient_prefix = 'tribe_q_';

	/**
	 * @var array All the targets for this work.
	 */
	protected $targets;

	/**
	 * @var callable The callback that will be called on each target to complete the work.
	 *               The callback will receive the following arguments:
	 *               `target` - the current target object
	 *               `data` - the optional additional data provided
	 */
	protected $callback;

	/**
	 * @var
	 */
	protected $status;

	/**
	 * @var array An array of the targets that have to be completed yet.
	 */
	protected $remaining;

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var int
	 */
	protected $batch_size = 10;

	/**
	 * @var int
	 */
	protected $priority = 10;

	/**
	 * @var mixed Additional data that will be passed to the callback function to comp
	 */
	private $data;

	/**
	 * Tribe__Queue__Worker constructor.
	 *
	 * @param array                 $targets   An array of target objects the worker should commplete.
	 * @param array                 $remaining An array of target objects the worker still has to work on.
	 * @param       callable| array $callback  Either a callable object or array or a container reference in the
	 *                                         ['tribe', <alias>, <method>] format.
	 *                                         The callback will receive three arguments: the current target, the
	 *                                         target index in the complete list of targets and the data for this work.
	 * @param mixed                 $data      Some additional data that will be passed to the work callback.
	 * @param string                $status    A string representing  the status of this Worker.
	 */
	public function __construct( array $targets, array $remaining, $callback, $data = null, $status = null, $priority = 10 ) {
		$this->id = md5( serialize( $targets ) . serialize( $callback ) . serialize( $data ) );
		$this->targets = $targets;
		$this->remaining = $remaining;
		$this->status = null !== $status ? $status : self::$queued;

		if ( ! ( is_callable( $callback ) || $this->is_container_callback( $callback ) ) ) {
			throw new InvalidArgumentException( "Callback argument must be a callable or a container reference in the ['tribe', <alias>, <method>]" );
		}

		$this->callback = $callback;
		$this->data = $data;
		$this->status = empty( $targets ) ? self::$done : $status;
		$this->priority = $priority;
	}

	/**
	 * Saves the status of this worker in a transient on the database.
	 *
	 * @return string The worker id.
	 */
	public function save() {
		$transient = $this->build_transient_name();
		set_transient( $transient, json_encode( $this->to_array() ), 2 * DAY_IN_SECONDS );

		return $this->id;
	}

	/**
	 * Returns an array representation of the worker.
	 *
	 * @return array An array representation of the worker.
	 */
	public function to_array() {
		return array(
			'targets'    => $this->targets,
			'remaining'  => $this->remaining,
			'callback'   => $this->callback,
			'data'       => $this->data,
			'status'     => $this->status,
			'batch_size' => $this->batch_size,
			'priority'   => $this->priority,
		);
	}

	/**
	 * Returns the worker current status.
	 *
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Builds the name of the transient storing a work information from its work id.
	 *
	 * @param string $work_id
	 *
	 * @return string The complete transient name.
	 */
	protected function build_transient_name() {
		$transient = self::$transient_prefix . $this->id;

		return $transient;
	}

	/**
	 * Sets the batch size this worker should use.
	 *
	 * @param int $batch_size
	 *
	 * @return $this
	 *
	 * @throws InvalidArgumentException If the batch size is not a positive integer.
	 */
	public function set_batch_size( $batch_size ) {
		if ( ! filter_var( $batch_size, FILTER_VALIDATE_INT ) || intval( $batch_size ) < 1 ) {
			throw new InvalidArgumentException( 'The batch size should be a positive integer.' );
		}
		$this->batch_size = $batch_size;

		return $this;
	}

	/**
	 * Tells the worker to work on a batch of its queue.
	 *
	 * @return string The worker id.
	 */
	public function work() {
		if ( empty( $this->remaining ) ) {
			$this->status = self::$done;

			return $this->save();
		}

		$batch = array_splice( $this->remaining, 0, $this->batch_size );

		$failures = array();

		$callback = $this->callback;
		if ( $this->is_container_callback( $callback ) ) {
			$callback = array( tribe( $this->callback[1] ), $this->callback[2] );
		}

		foreach ( $batch as $target ) {
			$target_index = array_search( $target, $this->targets );
			try {
				$success = (bool) call_user_func_array( $callback, array( $target, $target_index, $this->data ) );
			} catch ( Exception $e ) {
				$success = false;
			}

			if ( true !== $success ) {
				$failures[] = $target;
			}
		}

		// to avoid iterating over failing elements the failed ones are moved at the end of the qeueue
		$this->remaining = array_merge( $this->remaining, $failures );

		if ( empty( $this->remaining ) ) {
			$this->status = self::$done;
		} else {
			$this->status = self::$working;
		}

		return $this->save();
	}

	/**
	 * Returns an array of the remaining targets the worker should work on.
	 *
	 * @return array
	 */
	public function get_remaining() {
		return $this->remaining;
	}

	/**
	 * Returns the worker id.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Returns the name of the transient the worker saves and reads its status from.
	 *
	 * @return string
	 */
	public function get_transient_name() {
		return $this->build_transient_name();
	}

	/**
	 * Reads the worker status from the database.
	 *
	 * This is not in sync with save to allow for undo!
	 *
	 * @return array|mixed
	 */
	public function read() {

		$read = json_decode( get_transient( $this->build_transient_name() ) );

		return ! empty( $read ) ? $read : array();
	}

	/**
	 * @param $callback
	 *
	 * @return bool
	 */
	protected function is_container_callback( $callback ) {
		return is_array( $callback ) && count( $callback ) === 3 && 'tribe' === $callback[0];
	}

	/**
	 * Sets the work priority in a way similar to the one used by WordPress hooks and filters: lower goes first.
	 *
	 * @param int $priority
	 */
	public function set_priority( $priority ) {
		if ( ! filter_var( $priority, FILTER_VALIDATE_INT ) ) {
			throw new InvalidArgumentException( 'Priority must be an integer.' );
		}

		$this->priority = intval( $priority );

		return $this;
	}

	/**
	 * Returns the work priority.
	 *
	 * @return int
	 */
	public function get_priority() {
		return $this->priority;
	}
}
