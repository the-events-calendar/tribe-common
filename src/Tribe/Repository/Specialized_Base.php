<?php

/**
 * Class Tribe__Repository__Specialized_Base
 *
 * A base to provide specialized repositories (e.g. Read, Create...) with
 * common methods.
 *
 * @since TBD
 */
abstract class Tribe__Repository__Specialized_Base {
	/**
	 * @var array A map of callbacks in the shape [ <slug> => <callback|primitive> ]
	 */
	protected $schema;

	/**
	 * @var string
	 */
	protected $filter_name = 'default';

	/**
	 * @var Tribe__Repository__Interface
	 */
	protected $main_repository;

	/**
	 * Tribe__Repository__Specialized_Base constructor.
	 *
	 * @since TBD
	 *
	 * @param array $schema
	 */
	public function __construct( array $schema ) {
		$this->schema = $schema;
	}

	/**
	 * {@inheritdoc}
	 */
	public function filter_name( $filter_name ) {
		$this->filter_name = trim( $filter_name );

		return $this;
	}

	/**
	 * Applies and returns a schema entry.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param mixed  ...$args Additional arguments for the application.
	 *
	 * @return mixed A scalar value or a callable.
	 */
	public function apply_modifier( $key, $value ) {
		$call_args = func_get_args();

		$application = Tribe__Utils__Array::get( $this->schema, $key, null );

		/**
		 * Return primitives, including `null`, as they are.
		 */
		if ( ! is_callable( $application ) ) {
			return $application;
		}

		/**
		 * Allow for callbacks to fire immediately and return more complex values.
		 * This also means that callbacks meant to run on the next step, the one
		 * where args are applied, will need to be "wrapped" in callbacks themselves.
		 * The `$key` is removed from the args to get the value first and avoid
		 * unused args.
		 */
		$args_without_key = array_splice( $call_args, 1 );

		return call_user_func_array( $application, $args_without_key );
	}

	/**
	 * Whether the current schema defines an application for the key or not.
	 *
	 * @since TBD
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	protected function schema_has_modifier_for( $key ) {
		return isset( $this->schema[ $key ] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_main_repository( Tribe__Repository__Interface $main_repository ) {
		$this->main_repository = $main_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set( $key, $value ) {
		$update_repository = $this->main_repository->update();
		$update_repository->set_main_repository( $this->main_repository );
		$call_args = func_get_args();

		return call_user_func_array( array( $update_repository, 'set' ), $call_args );
	}
}