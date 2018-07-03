<?php

/**
 * Class Tribe__Repository__Schema
 *
 * @since TBD
 */
class Tribe__Repository__Schema implements Tribe__Repository__Schema_Interface {

	/**
	 * @var array An associative array mapping slugs to callbacks.
	 */
	protected $call_map = array();

	/**
	 * Tribe__Repository__Schema constructor.
	 *
	 * @since TBD
	 *
	 * @param array $call_map
	 */
	public function __construct( $call_map ) {
		$this->call_map = $call_map;
	}

	/**
	 * {@inheritdoc}
	 */
	public function apply( $key, $value ) {
		$call_args = func_get_args();

		$application = Tribe__Utils__Array::get( $this->call_map, $key, null );

		/**
		 * Return primitives as they are.
		 */
		if ( ! is_callable( $application ) ) {
			return $application;
		}

		/**
		 * Allow for callbacks to fire immediately and return more complex values.
		 * This also means that callbacks meant to run on the next step, the one
		 * where args are applied, will need to be "wrapped" in callbacks themselves.
		 * Arguments are inverted to allow callbacks to get the value first and avoid
		 * unused args.
		 */
		return call_user_func_array( $application, $call_args );
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_application_for( $key ) {
		return isset( $this->call_map[ $key ] );
	}
}
