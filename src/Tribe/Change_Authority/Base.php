<?php

/**
 * Class Tribe__Change_Authority__Base
 *
 * The basic general purpose change authority to propagate data from a generic source to a generic destination.
 */
abstract class Tribe__Change_Authority__Base implements Tribe__Change_Authority__Interface {
	/**
	 * An array defining the name of the fields that should be propagated.
	 * @var array
	 */
	protected $fields = array();
	/**
	 * An array of callbacks that will be called when evaluating the propagation to a field.
	 *
	 * @var array
	 */
	protected $propagation_conditions = array();

	/**
	 * Sets the fields the change authority should apply a propagation policy to.
	 *
	 * @param array $fields
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

		$result = array();

		foreach ( $this->fields as $field ) {
			if ( ! $this->should_propagate( $from, $to, $field ) ) {
				$result[ $field ] = false;
				continue;
			}
			$result[ $field ] = $this->propagate_field( $from, $to, $field );
		}

		return $result;
	}

	/**
	 * Whether a field should be propagated from the source to the destination.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether the field should be propagated or not.
	 */
	public function should_propagate( $from, $to, $field ) {
		return $this->evaluate_propagation_conditions( $from, $to, $field ) && in_array( $field, $this->fields );
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

	/**
	 * Sets a condition that will be evaluated before a field propagation is done to check whether the propagation
	 * should happen or not.
	 *
	 * The callback function will receive three arguments:
	 *          $from - the source object or data
	 *          $to - the destination object or data
	 *          $field - the propagation target field
	 *
	 * @param callable $callback
	 */
	public function set_propagation_condition( $callback ) {
		$this->propagation_conditions[] = $callback;
	}

	/**
	 * Evaluates the set propagation conditions in an AND logic.
	 *
	 * @param mixed  $from  The source object or data.
	 * @param mixed  $to    The destination object or data.
	 * @param string $field The name of the field that's to be evaluated for propagation.
	 *
	 * @return bool Whether all the propagation conditions return truthy values or not.
	 */
	protected function evaluate_propagation_conditions( $from, $to, $field ) {
		if ( empty( $this->propagation_conditions ) ) {
			return true;
		}

		foreach ( $this->propagation_conditions as $callback ) {
			try {
				if ( false != call_user_func( $callback, $from, $to, $field ) ) {
					continue;
				}
				throw new RuntimeException( 'Should not propagate' );
			} catch ( Exception $e ) {
				return false;
			}
		}

		return true;
	}
}