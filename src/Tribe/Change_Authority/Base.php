<?php

abstract class Tribe__Change_Authority__Base implements Tribe__Change_Authority__Interface {
	/**
	 * An array defining the name of the fields that should be propagated.
	 * @var array
	 */
	protected $fields = array();

	/**
	 * Sets the fields the change authority should apply a propagation policy to.
	 *
	 * @param array $fields
	 *
	 * @return mixed
	 */
	public function set_fields( array $fields ) {
		$this->fields = $fields;
	}

	/**
	 * Propagates the changes from the source to the destination.
	 *
	 * @param mixed $from The source object or data.
	 * @param mixed $to   The destination object or data.
	 *
	 * @return array An associative array in the format [ <field> => <propagated> ]
	 */
	public function propagate( $from, $to ) {
		if ( empty( $this->fields ) ) {
			return array();
		}

		if ( empty( $from ) || empty( $to ) ) {
			return array_combine( $this->fields, array_fill( 0, count( $this->fields ), false ) );
		}

		foreach ( $this->fields as $field ) {
			if ( ! $this->should_propagate( $from, $to, $field ) ) {
				$resul[ $field ] = false;
			}
			$result[ $field ] = $this->propagate_field( $from, $to, $field );
		}
	}

	/**
	 * Whether a field should be propagated from the source to the destination.
	 *
	 * @param mixed  $from The source object or data.
	 * @param mixed  $to   The destination object or data.
	 * @param string $type The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field should be propagated or not.
	 */
	public function should_propagate( $from, $to, $field ) {
		return in_array( $field, $this->fields );
	}

	/**
	 * Propagates a field from the source to the destination.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field was propagated or not.
	 */
	abstract public function propagate_field( $from, $to, $field );
}