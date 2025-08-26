<?php
/**
 * Serializes and unserializes values into JSON object.
 *
 * @since 6.9.1
 *
 * @package TEC\Common\Json_Packer;
 */

namespace TEC\Common\Json_Packer;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use stdClass;

/**
 * Class Json_Packer.
 *
 * @since 6.9.1
 *
 * @package TEC\Common\Json_Packer;
 */
class Json_Packer {
	/**
	 * Tracks object references to handle circular references.
	 *
	 * @since 6.9.1
	 *
	 * @var array
	 */
	private array $references = [];

	/**
	 * Stores objects during unpacking for reference resolution.
	 *
	 * @since 6.9.1
	 *
	 * @var array
	 */
	private array $unpack_references = [];

	/**
	 * Whether to fail on error or not.
	 *
	 * @since 6.9.1
	 *
	 * @var bool
	 */
	private bool $fail_on_error = true;

	/**
	 * A list of classes that it's safe to encode in the packed JSON string.
	 * Objects from unsafe classes are replaced with stdClass objects with the same
	 * properties, but not the original methods.
	 *
	 * The variable format is optimized for `isset` lookup.
	 *
	 * @since 6.9.1
	 *
	 * @var array<string,true>
	 */
	private array $allowed_classes;

	/**
	 * Returns a set of classes considered safe to unpack and pack by default.
	 *
	 * The stdClass is not included as it's required to be regarded as safe.
	 *
	 * @since 6.9.1
	 *
	 * @return string[]
	 */
	protected static function get_default_allowed_classes(): array {
		return [
			DateTime::class,
			DateTimeImmutable::class,
			DateTimeZone::class,
		];
	}

	/**
	 * Converts a value into a JSON string good to be unpacked later with the `pack` method..
	 *
	 * @since 6.9.1
	 *
	 * @param mixed         $value           The value to convert to JSON string.
	 * @param array<string> $allowed_classes A list of class names that it's safe to encode. By default
	 *                                       all classes will be replaced with a stdClass instance with
	 *                                       the same properties, but not the original methods.
	 *
	 * @return string The JSON string representing the packed value.
	 */
	public function pack( $value, array $allowed_classes = [] ): string {
		// Include the classes considered secure by default.
		$allowed_classes = array_merge( $allowed_classes, self::get_default_allowed_classes() );
		// Optimize the array for `isset` lookup.
		$this->allowed_classes = array_combine(
			$allowed_classes,
			array_fill( 0, count( $allowed_classes ), true )
		);
		$this->references      = [];
		$packed                = $this->pack_value( $value, [] );

		return wp_json_encode( $packed, JSON_PRETTY_PRINT );
	}

	/**
	 * Packs a single value recursively.
	 *
	 * @param mixed $value The value to pack.
	 * @param array $path  The current path in the object tree.
	 *
	 * @return array The packed representation.
	 */
	private function pack_value( $value, array $path ): array {
		if ( is_null( $value ) ) {
			return [
				'type'  => 'null',
				'value' => null,
			];
		}

		if ( is_bool( $value ) ) {
			return [
				'type'  => 'boolean',
				'value' => $value,
			];
		}

		if ( is_int( $value ) ) {
			return [
				'type'  => 'integer',
				'value' => $value,
			];
		}

		if ( is_float( $value ) ) {
			return [
				'type'  => 'float',
				'value' => $value,
			];
		}

		if ( is_string( $value ) ) {
			return [
				'type'  => 'string',
				'value' => $value,
			];
		}

		if ( is_array( $value ) ) {
			return $this->pack_array( $value, $path );
		}

		if ( is_object( $value ) ) {
			return $this->pack_object( $value, $path );
		}

		return [
			'type'  => 'unknown',
			'value' => null,
		];
	}

	/**
	 * Packs an array.
	 *
	 * @param array $array_to_pack The array to pack.
	 * @param array $path          The current path in the object tree.
	 *
	 * @return array The packed representation.
	 */
	private function pack_array( array $array_to_pack, array $path ): array {
		$is_associative = $this->is_associative_array( $array_to_pack );
		$packed         = [
			'type'  => 'array',
			'value' => $is_associative ? new stdClass() : [],
		];

		foreach ( $array_to_pack as $key => $item ) {
			$item_path   = array_merge( $path, [ 'value', $key ] );
			$packed_item = $this->pack_value( $item, $item_path );

			if ( $is_associative ) {
				$packed['value']->$key = $packed_item;
			} else {
				$packed['value'][] = $packed_item;
			}
		}

		return $packed;
	}

	/**
	 * Packs an object.
	 *
	 * @param object $object_to_pack The object to pack.
	 * @param array  $path           The current path in the object tree.
	 *
	 * @return array The packed representation.
	 */
	private function pack_object( object $object_to_pack, array $path ): array {
		$object_id = spl_object_id( $object_to_pack );

		// Check for circular reference.
		if ( isset( $this->references[ $object_id ] ) ) {
			return [
				'type'  => 'reference',
				'value' => $this->references[ $object_id ],
			];
		}

		// Register this object.
		$path_string                    = empty( $path ) ? '@root' : '@root.' . implode( '.', $path );
		$this->references[ $object_id ] = $path_string;

		$class_name = get_class( $object_to_pack );

		if ( count( $this->allowed_classes ) === 0 || ! isset( $this->allowed_classes[ $class_name ] ) ) {
			$original_class_name = $class_name;
			$class_name          = stdClass::class;
		}

		$packed = [
			'type'       => $class_name,
			'properties' => new stdClass(),
		];

		// Handle DateTime objects specially.
		if ( $object_to_pack instanceof DateTime || $object_to_pack instanceof DateTimeImmutable ) {
			$packed['properties']->date          = [
				'type'  => 'string',
				'value' => $object_to_pack->format( 'Y-m-d H:i:s' ),
			];
			$packed['properties']->timezone_type = [
				'type'  => 'integer',
				'value' => 3,
			];
			$packed['properties']->timezone      = [
				'type'  => 'string',
				'value' => $object_to_pack->getTimezone()->getName(),
			];

			return $packed;
		}

		// Use reflection to access all properties.
		$reflection = new ReflectionClass( $object_to_pack );
		// This variable starts as an accumulator.
		/** @var ReflectionProperty[][] $properties */
		$properties = [];

		// Get all properties from the class and its parents.
		do {
			$properties[] = $reflection->getProperties();
		} while ( $reflection = $reflection->getParentClass() );

		// Merge and make the property a flat array to avoid running `array_merge` in a loop.
		/** @var ReflectionProperty[] $properties */
		$properties = array_merge( ...$properties );

		foreach ( $properties as $property ) {
			$property->setAccessible( true );

			if ( ! $property->isInitialized( $object_to_pack ) ) {
				continue;
			}

			$property_name  = $property->getName();
			$property_value = $property->getValue( $object_to_pack );
			$property_path  = array_merge( $path, [ 'properties', $property_name ] );

			$packed['properties']->$property_name = $this->pack_value( $property_value, $property_path );
		}

		// Collect dynamic properties directly set on the object.
		$object_vars = get_object_vars( $object_to_pack );

		if ( isset( $original_class_name ) ) {
			$object_vars['__original_class__'] = $original_class_name;
		}

		foreach ( $object_vars as $name => $value ) {
			if ( isset( $packed['properties']->{$name} ) ) {
				// Already packed.
				continue;
			}

			$property_path                 = array_merge( $path, [ 'properties', $name ] );
			$packed['properties']->{$name} = $this->pack_value( $value, $property_path );
		}

		return $packed;
	}

	/**
	 * Checks if an array is associative.
	 *
	 * @param array $array_to_check The array to check.
	 *
	 * @return bool True if associative, false if sequential.
	 */
	private function is_associative_array( array $array_to_check ): bool {
		if ( empty( $array_to_check ) ) {
			return false;
		}

		foreach ( array_keys( $array_to_check ) as $key ) {
			if ( ! is_int( $key ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Converts a JSON string created with the `pack` method into the original value.
	 *
	 * @since 6.9.1
	 *
	 * @param string        $json             The JSON string containing the packed value.
	 * @param bool          $fail_on_error    Whether to fail, and return `null`, if one of the classes required to rebuild the
	 *                                        object are missing. If set to `false`, then instances of missing classes will be
	 *                                        replaced with `stdClass` instances.
	 * @param array<string> $allowed_classes  A list of class names that it's safe to encode. By default
	 *                                        all classes will be replaced with a stdClass instance with
	 *                                        the same properties, but not the original methods.
	 *
	 * @return mixed The original value or `null` on failure.
	 */
	public function unpack( string $json, bool $fail_on_error = true, array $allowed_classes = [] ) {
		// Include the classes considered secure by default.
		$allowed_classes = array_merge( $allowed_classes, self::get_default_allowed_classes() );
		// Optimize the array for `isset` lookup.
		$this->allowed_classes   = array_combine(
			$allowed_classes,
			array_fill( 0, count( $allowed_classes ), true )
		);
		$this->unpack_references = [];
		/** @noinspection JsonEncodingApiUsageInspection */
		$data = json_decode( $json, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return null;
		}

		$this->fail_on_error = $fail_on_error;

		try {
			return $this->unpack_value( $data, '@root' );
		} catch ( Unpack_Exception $e ) {
			return null;
		}
	}

	/**
	 * Unpacks a single value recursively.
	 *
	 * @param array  $data The packed data.
	 * @param string $path The current path for reference tracking.
	 *
	 * @return mixed The unpacked value.
	 *
	 * @throws Unpack_Exception If a class required to unpack the object is missing.
	 */
	private function unpack_value( array $data, string $path ) {
		$type  = $data['type'] ?? 'unknown';
		$value = $data['value'] ?? null;

		switch ( $type ) {
			case 'null':
			case 'unknown':
				return null;
			case 'boolean':
			case 'integer':
			case 'float':
			case 'string':
				return $value;
			case 'array':
				return $this->unpack_array( $data, $path );
			case 'reference':
				return $this->unpack_references[ $value ] ?? null;
			default:
				// It's an object.
				return $this->unpack_object( $data, $path );
		}
	}

	/**
	 * Unpacks an array.
	 *
	 * @param array  $data The packed array data.
	 * @param string $path The current path for reference tracking.
	 *
	 * @return array The unpacked array.
	 *
	 * @throws Unpack_Exception If a class required to unpack the object is missing.
	 */
	private function unpack_array(
		array $data,
		string $path
	): array {
		$result = [];
		$value  = $data['value'];

		if ( is_array( $value ) ) {
			// Sequential array.
			foreach ( $value as $index => $item ) {
				$item_path        = $path . '.value[' . $index . ']';
				$result[ $index ] = $this->unpack_value( $item, $item_path );
			}
		} else {
			// Associative array (stored as object).
			foreach ( (array) $value as $key => $item ) {
				$item_path      = $path . '.value.' . $key;
				$result[ $key ] = $this->unpack_value( $item, $item_path );
			}
		}

		return $result;
	}

	/**
	 * Unpacks an object.
	 *
	 * @param array  $data The packed object data.
	 * @param string $path The current path for reference tracking.
	 *
	 * @return object The unpacked object.
	 *
	 * @throws Unpack_Exception If a class required to unpack the object is missing.
	 */
	private function unpack_object(
		array $data,
		string $path
	): object {
		$class_name = $data['type'];

		$properties = $data['properties'] ?? [];

		// Handle stdClass.
		if ( $class_name === 'stdClass' ) {
			$object                           = new stdClass();
			$this->unpack_references[ $path ] = $object;

			foreach ( $properties as $name => $prop_data ) {
				$prop_path     = $path . '.properties.' . $name;
				$object->$name = $this->unpack_value( $prop_data, $prop_path );
			}

			return $object;
		}

		// Handle DateTime.
		if ( $class_name === 'DateTime' || $class_name === 'DateTimeImmutable' ) {
			$date     = $properties['date']['value'] ?? 'now';
			$timezone = $properties['timezone']['value'] ?? 'UTC';

			try {
				if ( $class_name === 'DateTime' ) {
					$object = new DateTime( $date, new DateTimeZone( $timezone ) );
				} else {
					$object = new DateTimeImmutable( $date, new DateTimeZone( $timezone ) );
				}
			} catch ( Exception $e ) {
				throw new Unpack_Exception( "Error while unpacking Date object: {$e->getMessage()}" );
			}

			$this->unpack_references[ $path ] = $object;

			return $object;
		}

		// Create instance without constructor.
		$reflection = null;
		try {
			if (
				count( $this->allowed_classes ) === 0
				|| ! isset( $this->allowed_classes[ $class_name ] )
			) {
				$object                     = new stdClass();
				$object->__original_class__ = $class_name;
			} else {
				$reflection = new ReflectionClass( $class_name );
				$object     = $reflection->newInstanceWithoutConstructor();
			}
		} catch ( ReflectionException $e ) {
			if ( $this->fail_on_error ) {
				throw new Unpack_Exception( "Error while unpacking {$class_name}: {$e->getMessage()}" );
			}
			// We cannot use the original class: use a stdClass instance in its place.
			$object = new stdClass();
			// Store the original class name as a property for reference.
			$object->__original_class__ = $class_name;
		}

		$this->unpack_references[ $path ] = $object;

		// Set properties using reflection.
		foreach ( $properties as $name => $prop_data ) {
			$prop_path = $path . '.properties.' . $name;
			$value     = $this->unpack_value( $prop_data, $prop_path );

			// If we have a reflection object, try to find the property in the class hierarchy.
			if ( $reflection !== null ) {
				$current_reflection = $reflection;
				$property_set       = false;

				while ( $current_reflection ) {
					if ( $current_reflection->hasProperty( $name ) ) {
						$property = $current_reflection->getProperty( $name );
						$property->setAccessible( true );
						$property->setValue( $object, $value );
						$property_set = true;
						break;
					}
					$current_reflection = $current_reflection->getParentClass();
				}

				// If property not found in class hierarchy, set it dynamically.
				if ( ! $property_set ) {
					$object->$name = $value;
				}
			} else {
				// For stdClass fallback, just set the property directly.
				$object->$name = $value;
			}
		}

		return $object;
	}
}
