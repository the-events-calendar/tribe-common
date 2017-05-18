<?php


class Tribe__Formatter__Base implements Tribe__Formatter__Interface {

	/**
	 * @var Tribe__REST__Validator_Interface
	 */
	protected $validator;

	/**
	 * @var array The format map that will be used to format and validate the raw input.
	 */
	protected $format_map = array();

	/**
	 * @var array The components of the context for this formatter.
	 */
	protected $context = array();

	/**
	 * Tribe__Formatter__Base constructor.
	 * * @param Tribe__REST__Validator_Interface $validator
	 */
	public function __construct( Tribe__REST__Validator_Interface $validator ) {
		$this->validator = $validator;
	}

	public function set_format_map( array $format_map ) {
		$this->format_map = $format_map;
	}

	/**
	 * Sets the context for this formatter.
	 *
	 * @param array $context
	 */
	public function set_context( array $context ) {
		$this->context = $context;
	}

	public function format( $value, array $format_map, &$context  = null) {
		if ( null === $context ) {
			$context = $this->context;
		}

		$data = array();

		if ( ! $this->is_format_map_entry( $format_map ) ) {
			foreach ( array_keys( $format_map ) as $key ) {
				if ( empty( $value[ $key ] ) ) {
					$is_required_key = ! empty( $format_map[ $key ]['required'] ) || $this->contains_required_keys($format_map[$key]);
					if ( $is_required_key ) {
						$context[] = $key;
						throw new InvalidArgumentException( $this->get_required_message_for( $context ) );
					}
					continue;
				}

				$context[] = $key;
				$data[ $key ] = $this->format( $value[ $key ], $format_map[ $key ], $context );
			}
			$value = $data;
		} else {
			$valid = call_user_func( array( $this->validator, $format_map['validate_callback'] ), $value );
			if ( false === $valid ) {
				throw new InvalidArgumentException( $this->get_invalid_message_for( $context ) );
			}
		}

		array_pop($context);

		return $value;
	}

	public function get_required_message_for( array $context = array() ) {
		$context = implode( ' > ', $context );

		return sprintf( __( 'Argument "%1$s" is required', 'tribe-common' ), $context );
	}

	/**
	 * @param $context
	 * @return string
	 */
	protected function get_invalid_message_for( $context ) {
		$context = implode( ' > ', $context );

		return sprintf( __( 'The value provided for "%1$s" is invalid.', 'tribe-common' ), $context );
	}

	public function process( array $raw ) {
		return $this->format( $raw, $this->format_map, $this->context );
	}

	/**
	 * @param array $format_map
	 * @return bool
	 */
	protected function is_format_map_entry( array $format_map ) {
		return is_array( $format_map ) && isset( $format_map['required'] );
	}

	protected function contains_required_keys( array $format_map ) {
		$serialized = serialize($format_map);

		return false !== strpos($serialized,'s:8:"required";b:1;');
	}
}
