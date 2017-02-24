<?php

interface Tribe__Change_Authority__Interface {

	/**
	 * Sets the fields the change authority should apply a propagation policy to.
	 *
	 * @param array $fields
	 *
	 * @return mixed
	 */
	public function set_fields( array $fields );

	/**
	 * Whether a field should be propagated from the source to the destination.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field should be propagated or not.
	 */
	public function should_propagate( $from, $to, $field );

	/**
	 * Propagates the changes from the source to the destination.
	 *
	 * @param mixed $from The source object or data.
	 * @param mixed $to   The destination object or data.
	 *
	 * @return array An associative array in the format [ <field> => <propagated> ]
	 */
	public function propagate( $from, $to );

	/**
	 * Propagates a field from the source to the destination.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field was propagated or not.
	 */
	public function propagate_field( $from, $to, $field );
}