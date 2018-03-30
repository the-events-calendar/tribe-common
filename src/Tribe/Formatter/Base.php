<?php

/**
 * Class Tribe__Formatter__Base
 *
 * Formats (validation and conversion) a raw array of content using a format map.
 *
 * Example usage: the need is to validate this array:
 *      $input = array(
 *          'one' => 23,
 *          'two' => 89,
 *          'three' => 'some string',
 *      );
 *
 * And to make sure that `one` and `two` are numbers, and `three` is a string if present.
 * As a bare minimum each key in the format map should specify if the parameter is required or not
 * using the `required` key.
 *
 * The code to make the validation using the formatter is this:
 *
 *      $formatter = new Tribe__Formatter__Base();
 *      $formatter->set_format_map( array(
 *          'one' => array( 'required' => true, 'validate_callback' => 'is_numeric' ),
 *          'two' => array( 'required' => true, 'validate_callback' => 'is_numeric' ),
 *          'three' => array( 'required' => false, 'validate_callback' => 'is_string' ),
 *      ));
 *
 *      try {
 *          $formatted = $formatter->process( $input );
 *      } catch ( InvalidArgumentException $e ) {
 *          // something required is missing or is not valid
 *      }
 *
 * The formatter can handle conversions too specifying a `conversion_callback`:
 *
 *      $input = array(
 *          'title' => 'Party',
 *          'start' => 'tomorrow 9am',
 *          'end' => '+4 days 10pm',
 *      );
 *
 *      $formatter = new Tribe__Formatter__Base();
 *      $formatter->set_format_map( array(
 *          'title' => array( 'required' => true, 'validate_callback' => 'is_string', 'conversion_callback' => 'ucwords' ),
 *          'start' => array( 'required' => true, 'validate_callback' => 'strtotime', 'conversion_callback' => 'strtotime' ),
 *          'end' => array( 'required' => true, 'validate_callback' => 'strtotime', 'conversion_callback' => 'strtotime' ),
 *      ));
 *
 *      try {
 *          $formatted = $formatter->process( $input );
 *      } catch ( InvalidArgumentException $e ) {
 *          // something required is missing or is not valid
 *      }
 *
 * Validation and conversion callbacks can be any valid PHP callback, not just internal functions.
 * Validation callbacks should return a `bool`, conversion callbacks should return the converted value and throw an `Exception` of
 * some kind to mark the conversion as failed.
 * The format map can specify aliases that should be looked up for each key to get its value:
 *
 *      $input = array(
 *          'description' => 'Party',
 *          'start' => 'tomorrow 9am',
 *          'end' => '+4 days 10pm',
 *      );
 *
 *      $formatter = new Tribe__Formatter__Base();
 *      $formatter->set_format_map( array(
 *          'title' => array(
 *              'required' => true,
 *              'validate_callback' => 'is_string',
 *              'conversion_callback' => 'ucwords',
 *              'alias' => array( 'description','announcement' )
 *          ),
 *          'start' => array( 'required' => true, 'validate_callback' => 'strtotime', 'conversion_callback' => 'strtotime' ),
 *          'end' => array( 'required' => true, 'validate_callback' => 'strtotime', 'conversion_callback' => 'strtotime' ),
 *      ));
 *
 *      try {
 *          $formatted = $formatter->process( $input );
 *      } catch ( InvalidArgumentException $e ) {
 *          // something required is missing or is not valid
 *      }
 *
 * The formatter will take care or renaming the aliases, in the case above the formatted value will be:
 *
 *      $formatted = array(
 *          'title' => 'Party',
 *          ...
 *      );
 *
 * Finally format maps can be nested to format nested arrays:
 *
 *      $input = array(
 *          'description' => 'Party',
 *          'details' => array(
 *              'start' => 'tomorrow 9am',
 *              'end' => '+4 days 10pm',
 *              'organizer' => array(
 *                  'name' => 'John Doe'
 *                  'phone' => '1223343434'
 *              )
 *          ));
 *
 *      $formatter = new Tribe__Formatter__Base();
 *      $formatter->set_format_map( array(
 *          'title' => array(
 *              'required' => true,
 *              'validate_callback' => 'is_string',
 *              'conversion_callback' => 'ucwords',
 *              'alias' => array( 'description','announcement' )
 *          ),
 *          'details' => array(
 *              'start' => array( 'required' => true, 'validate_callback' => 'strtotime', 'conversion_callback' => 'strtotime' ),
 *              'end' => array( 'required' => true, 'validate_callback' => 'strtotime', 'conversion_callback' => 'strtotime' ),
 *              'organizer' => array(
 *                  'name' => array( 'required' => true, 'validate_callback' => 'is_string' ),
 *                  'phone' => array( 'required' => true, 'validate_callback' => 'is_numeric' ),
 *              )
 *          )
 *      ));
 *
 *      try {
 *          $formatted = $formatter->process( $input );
 *      } catch ( InvalidArgumentException $e ) {
 *          // something required is missing or is not valid
 *      }
 *
 * Validation, conversion and aliases will apply to nested values too.
 * Due to the size the format maps can reach the way to use this is to extend class in a specialized class that will set up its own
 * format map and context.
 */
class Tribe__Formatter__Base implements Tribe__Formatter__Interface {
	/**
	 * @var string
	 */
	protected $name = 'Base';

	/**
	 * @var array The format map that will be used to format and validate the raw input.
	 */
	protected $format_map = array();

	/**
	 * @var array The components of the context for this formatter.
	 */
	protected $context = array();
	/**
	 * @var array An array of data generated during the formatting.
	 */
	protected $generated_data = array();

	/**
	 * @var array The starting raw input.
	 */
	protected $raw = array();

	/**
	 * Returns the format map the formatter is using.
	 *
	 * @return array
	 */
	public function get_format_map() {
		return $this->format_map;
	}

	/**
	 * Sets the format map for this formatter.
	 *
	 * @param array $format_map
	 */
	public function set_format_map( array $format_map ) {
		$this->format_map = $format_map;
	}

	/**
	 * Returns the formatter current context.
	 *
	 * @return array
	 */
	public function get_context() {
		return (array) $this->context;
	}

	/**
	 * Sets the context for this formatter.
	 *
	 * The context will be used by the formatter to provide insightful error messages.
	 *
	 * @param array|string $context
	 */
	public function set_context( $context ) {
		$this->context = (array) $context;
	}

	/**
	 * Processes an array of raw input validating, converting and pruning its elements.
	 *
	 * @param array $raw The input to format.
	 *
	 * @return array The formatted input.
	 *
	 * @throws InvalidArgumentException If a required argument is missing or not valid.
	 */
	public function process( array $raw = array() ) {
		$this->reset_data( $raw );

		$data = $this->format( $raw, $this->format_map, $this->context );

		$data = array_merge_recursive( $data, $this->generated_data );

		return $data;
	}

	/**
	 * Resets the raw input and the generated data.
	 *
	 * @param array $raw
	 */
	protected function reset_data( array $raw ) {
		$this->raw = $raw;
		$this->generated_data = array();
	}

	/**
	 * Formats the values  recursively using the format map.
	 *
	 * @param     mixed $value      The input to format.
	 * @param array     $format_map The format map that should be used to format the input.
	 * @param array     $context    An array of successive context levels.
	 *
	 * @return array
	 */
	protected function format( $value, array $format_map, &$context = null ) {
		if ( null === $context ) {
			$context = $this->context;
		}

		$context = is_array( $context ) ? $context : array( $context );

		$data = array();

		if ( ! $this->is_format_map_entry( $format_map ) ) {
			foreach ( array_keys( $format_map ) as $key ) {
				$alias = ! empty( $format_map[ $key ]['alias'] ) ? $this->find_alias( $format_map[ $key ]['alias'], $value ) : $key;

				if ( empty( $value[ $alias ] ) && empty( $value[ $key ] ) && ! isset( $format_map[ $key ]['default'] ) ) {
					$is_required_key = ! empty( $format_map[ $key ]['required'] ) || $this->contains_required_keys( $format_map[ $key ] );
					if ( $is_required_key ) {
						$context[] = $alias === $key ? $key : sprintf( '%s (%s)', $key, implode( '|', (array) $alias ) );
						throw new InvalidArgumentException( $this->get_required_error_for( $context ) );
					}
					continue;
				}

				if ( empty( $value[ $alias ] ) && empty( $value[ $key ] ) ) {
					$value[ $key ] = $format_map[ $key ]['default'];
				}

				$context[] = $alias === $key ? $key : sprintf( '%s (%s)', $key, implode( '|', (array) $alias ) );
				$target = ! empty( $value[ $key ] ) ? $value[ $key ] : $value[ $alias ];
				$data[ $key ] = $this->format( $target, $format_map[ $key ], $context );
			}
			$value = $data;
		} else {
			if ( ! empty( $format_map['validate_callback'] ) ) {
				try {
					$valid = $this->call_callback_for( $value, $format_map['validate_callback'] );
				} catch ( Exception $e ) {
					throw new InvalidArgumentException( $this->get_invalid_error_for( $context, $e ) );
				}

				if ( false === $valid && empty( $format_map['allow_empty'] ) ) {
					throw new InvalidArgumentException( $this->get_invalid_error_for( $context ) );
				}
			}

			if ( ! empty( $format_map['conversion_callback'] ) ) {
				try {
					$value = $this->call_callback_for( $value, $format_map['conversion_callback'] );
				} catch ( Exception $e ) {
					throw new InvalidArgumentException( $this->get_conversion_error_for( $context, $e ) );
				}
			}
		}

		array_pop( $context );

		return $value;
	}

	/**
	 * Whether the array represents a format map entry or it's just an array of format map entries.
	 *
	 * @param array $format_map
	 *
	 * @return bool
	 */
	protected function is_format_map_entry( array $format_map ) {
		return is_array( $format_map ) && isset( $format_map['required'] );
	}

	/**
	 * Finds the alias that should be used depending on its availability in the value.
	 *
	 * @param array|string $aliases
	 * @param array        $value
	 *
	 * @return string
	 */
	protected function find_alias( $aliases, $value ) {
		$aliases = (array) $aliases;

		foreach ( $aliases as $alias ) {
			if ( ! empty( $value[ $alias ] ) ) {
				return $alias;
			}
		}

		return $aliases[0];
	}

	/**
	 * Checks if the format map contains required keys.
	 *
	 * @param array $format_map
	 *
	 * @return bool
	 */
	protected function contains_required_keys( array $format_map ) {
		// Use JSON encode because Serialization might contain Object private/protected props
		$json = json_encode( $format_map );

		return false !== strpos( $json, '"required":true' );
	}

	/**
	 * Returns the error message produced for a missing required key.
	 *
	 * @param array $context
	 *
	 * @return string
	 */
	protected function get_required_error_for( array $context = array() ) {
		$context = implode( ' > ', $context );

		return sprintf( __( 'Argument "%1$s" is required', 'tribe-common' ), $context );
	}

	/**
	 * Calls the specified callback with the right number of parameters.
	 *
	 * @param mixed    $value
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	protected function call_callback_for( $value, $callback ) {
		if ( is_array( $callback ) ) {
			$ref = new ReflectionMethod( $callback[0], $callback[1] );
		} else {
			// closure or function
			$ref = new ReflectionFunction( $callback );
		}

		$args = array( $value, &$this->generated_data, $this->raw );
		$args = array_splice( $args, 0, $ref->getNumberOfParameters() );
		$valid = call_user_func_array( $callback, $args );

		return $valid;
	}

	/**
	 * Returns the error message produced for an invalid key.
	 *
	 * @param array          $context
	 * @param Exception|null $exception
	 *
	 * @return string
	 */
	protected function get_invalid_error_for( $context, Exception $exception = null ) {
		$context = implode( ' > ', $context );

		if ( null === $exception ) {
			$message = sprintf( __( 'The value provided for "%1$s" is invalid.', 'tribe-common' ), $context );
		} else {
			$message = sprintf( __( 'Error while validating "%1$s": %2$s.', 'tribe-common' ), $context, $exception->getMessage() );
		}

		return $message;
	}

	/**
	 * Returns the error produced for a conversion function error.
	 *
	 * @param array     $context   The error context.
	 * @param Exception $exception The exception generated by the conversion callback.
	 *
	 * @return string
	 */
	protected function get_conversion_error_for( array $context, Exception $exception ) {
		$context = implode( ' > ', $context );

		return sprintf( __( 'Error while converting "%1$s": %2$s', 'tribe-common' ), $context, $exception->getMessage() );
	}

	/**
	 * Returns this formatter name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Sets the formatter name.
	 *
	 * @param $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}
}
