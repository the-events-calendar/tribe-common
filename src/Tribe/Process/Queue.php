<?php

/**
 * Class Tribe__Process__Queue
 *
 * @since TBD
 *
 * The base class to process queues asynchronously.
 */
abstract class Tribe__Process__Queue extends WP_Background_Process {

	/**
	 * @var string The common identified prefix to all our async process handlers.
	 */
	protected $prefix = 'tribe_queue';

	/**
	 * @var string The queue unique identifier
	 */
	protected $id;

	/**
	 * @var int How many items this instance processed.
	 */
	protected $done = 0;

	/**
	 * @var int
	 */
	protected $original_batch_count = 0;

	/**
	 * @var int The maximum size of a fragment in bytes.
	 */
	protected $max_frag_size;

	/**
	 * @var bool Whether the current handling is sync or not.
	 */
	protected $doing_sync = false;

	/**
	 * @var bool Whether the queue `save` method was already called or not.
	 */
	protected $did_save = false;

	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
		$class        = get_class( $this );
		$this->action = $class::action();
		parent::__construct();
	}

	/**
	 * Stops a queue that might be running.
	 *
	 * The queue process results are not rolled back (e.g. 200 posts to create, stopped
	 * after 50, those 50 posts will persist).
	 *
	 * @since TBD
	 *
	 * @param string $queue_id The unique identifier of the queue that should be stopped.
	 *
	 * @see   Tribe__Process__Queue::save() to get the queue unique id.
	 */
	public static function stop_queue( $queue_id ) {
		$meta = (array) get_transient( $queue_id . '_meta' );
		delete_transient( $queue_id . '_meta' );

		if ( ! empty( $meta['identifier'] ) ) {
			delete_site_transient( $meta['identifier'] . '_process_lock' );
		}

		return delete_site_option( $queue_id );
	}

	/**
	 * Returns a queue status and information.
	 *
	 * @since TBD
	 *
	 * @param string $queue_id
	 *
	 * @return Tribe__Data An object containing information about the queue.
	 *
	 * @see   Tribe__Process__Queue::save() to get the queue unique id.
	 */
	public static function get_status_of( $queue_id ) {
		$meta = (array) get_transient( $queue_id . '_meta' );
		$data = array(
			'identifier' => $queue_id,
			'done'       => (int) Tribe__Utils__Array::get( $meta, 'done', 0 ),
			'total'      => (int) Tribe__Utils__Array::get( $meta, 'total', 0 ),
			'fragments'  => (int) Tribe__Utils__Array::get( $meta, 'fragments', 0 ),
		);

		return new Tribe__Data( $data, 0 );
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
	 * {@inheritdoc}
	 */
	public function delete( $key ) {
		global $wpdb;

		$table  = $wpdb->options;
		$column = 'option_name';

		if ( is_multisite() ) {
			$table  = $wpdb->sitemeta;
			$column = 'meta_key';
		}

		$key = $wpdb->esc_like( $key ) . '%';

		$wpdb->query( $wpdb->prepare( "
			DELETE
			FROM {$table}
			WHERE {$column} LIKE %s
		", $key ) );

		delete_transient( $this->get_meta_key( $key ) );

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function update( $key, $data ) {
		$meta = (array) get_transient( $this->get_meta_key( $key ) );
		$done = $this->original_batch_count - count( $data );

		set_transient( $this->get_meta_key( $key ), array_merge( $meta, array(
			'done' => $meta['done'] + $done,
		) ) );

		return parent::update( $key, $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		if ( empty( $this->id ) ) {
			$this->id = $this->generate_key();
		}

		$fragments_count = $this->save_split_data( $this->id, $this->data );

		set_transient( $this->get_meta_key( $this->id ), array(
			'identifier' => $this->identifier,
			'done'       => 0,
			'total'      => count( $this->data ),
			'fragments'  => $fragments_count,
		) );

		$this->did_save = true;

		return $this;
	}

	/**
	 * Saves the queue data to the database taking max_packet_size into account.
	 *
	 * In some instances the serialized size of the data might be bigger than the
	 * database `max_packet_size`; trying to write all the data in one query would
	 * make the db "go away...".
	 * Here we try to read the database `max_packet_size` setting and use that information
	 * to avoid overloading the query.
	 *
	 * @param       string $key
	 * @param array        $data
	 *
	 * @return int The number of fragments the data was split and stored into.
	 */
	protected function save_split_data( $key, array $data ) {
		if ( empty( $data ) ) {
			return 0;
		}

		$max_frag_size = $this->get_max_frag_size();
		// we add a 15% to the size to take the serialization and query overhead into account when fragmenting
		$serialized_size = ( strlen( utf8_decode( maybe_serialize( $data ) ) ) ) * 1.15;
		$frags_count     = (int) ceil( $serialized_size / $max_frag_size );
		$per_frag        = max( (int) floor( count( $data ) / $frags_count ), 1 );

		$split_data = array_chunk( $data, $per_frag );

		if ( empty( $split_data ) ) {
			return 0;
		}

		foreach ( $split_data as $i => $iValue ) {
			$postfix = 0 === $i ? '' : "_{$i}";
			update_site_option( $key . $postfix, $split_data[ $i ] );
		}

		return count( $split_data );
	}

	/**
	 * Returns the max frag size in bytes.
	 *
	 * The bottleneck here is the database `max_packet_size` so we try to read
	 * it from the database.
	 *
	 * @return int The max size, in bytes, of a data fragment.
	 */
	protected function get_max_frag_size() {
		if ( ! empty( $this->max_frag_size ) ) {
			return $this->max_frag_size;
		}

		return tribe( 'db' )->get_max_allowed_packet_size();
	}

	/**
	 * Sets the maximum size, in bytes, of the queue fragments.
	 *
	 * This will prevent the class from trying to read the value from the database.
	 *
	 * @since TBD
	 *
	 * @param int $max_frag_size
	 */
	public function set_max_frag_size( $max_frag_size ) {
		$this->max_frag_size = $max_frag_size;
	}

	/**
	 * Returns the queue unique identifier.
	 *
	 * Mind that an id will only be available after saving a queue.
	 *
	 * @since TBD
	 *
	 * @return string
	 * @throws RuntimeException if trying to get the queue id before saving it.
	 */
	public function get_id() {
		if ( null === $this->id ) {
			// not localized as this is a developer-land error
			throw new RuntimeException( 'Can only get the id of queue after saving it.' );
		}

		return $this->id;
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
			|| (bool) tribe_get_request_var( 'tribe_queue_sync', false )
			|| tribe_is_truthy( tribe_get_option( 'tribe_queue_sync', false ) )
		) {
			return $this->sync_process( $this->data );
		}

		return parent::dispatch();
	}

	/**
	 * Handles the process immediately, not in an async manner.
	 *
	 * @since TBD
	 *
	 * @return array An array containing the result of each item handling.
	 */
	public function sync_process() {
		$result = array();
		$this->doing_sync = true;

		foreach ( $this->data as $item ) {
			$result[] = $this->task( $item );
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_batch() {
		$batch = parent::get_batch();

		$this->original_batch_count = ! empty( $batch->data ) ? count( $batch->data ) : 0;

		return $batch;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_post_args() {
		$post_args = parent::get_post_args();

		/**
		 * While sending the data into the body makes sense for the async process it does
		 * not make sense when processing a queue since the data will be stored and read
		 * from the database; furthermore this could raise issues with the max POST size.
		 */
		$post_args['body'] = array();

		return $post_args;
	}

	/**
	 * Returns the name of the transient that will store the queue meta information
	 * for the specific key.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_meta_key( $key ) {
		return $key . '_meta';
	}

	/**
	 * Sets the queue unique id.
	 *
	 * When using this method the client code takes charge of the queue id uniqueness;
	 * the class will not check it.
	 *
	 * @since TBD
	 *
	 * @param string $queue_id
	 *
	 * @throws RuntimeException If trying to set the queue id after saving it.
	 */
	public function set_id( $queue_id ) {
		if ( $this->did_save ) {
			throw new RuntimeException( 'The queue id can be set only before saving it.' );
		}

		$this->id = $queue_id;
	}
}
