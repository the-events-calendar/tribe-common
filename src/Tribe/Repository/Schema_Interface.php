<?php

/**
 * Interface Tribe__Repository__Schema_Interface
 *
 * @since TBD
 */
interface Tribe__Repository__Schema_Interface {
	/**
	 * Applies and returns a schema entry.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param mixed  ...$args Additional arguments for the application.
	 *
	 * @return mixed
	 */
	public function apply( $key, $value );

	/**
	 * Whether the schema defines an application for the key or not.
	 *
	 * @since TBD
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has_application_for( $key );
}