<?php
/**
 * Entity parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use Closure;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use TEC\Common\REST\TEC\V1\Contracts\Parameter as Parameter_Contract;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;

/**
 * Entity parameter type.
 *
 * @since 6.9.0
 */
class Entity extends Parameter {
	/**
	 * Entity Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param string                $name               The name of the parameter.
	 * @param ?Closure              $description_provider The description provider.
	 * @param ?PropertiesCollection $properties         The properties.
	 * @param bool                  $required           Whether the parameter is required.
	 * @param ?Closure              $validator          The validator.
	 * @param ?Closure              $sanitizer          The sanitizer.
	 * @param string                $location           The location.
	 * @param ?bool                 $deprecated         Whether the parameter is deprecated.
	 * @param ?bool                 $nullable           Whether the parameter is nullable.
	 * @param ?bool                 $read_only          Whether the parameter is read only.
	 * @param ?bool                 $write_only         Whether the parameter is write only.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		?PropertiesCollection $properties = null,
		bool $required = true,
		?Closure $validator = null,
		?Closure $sanitizer = null,
		string $location = self::LOCATION_QUERY,
		?bool $deprecated = null,
		?bool $nullable = null,
		?bool $read_only = null,
		?bool $write_only = null
	) {
		$this->name                 = $name;
		$this->description_provider = $description_provider;
		$this->properties           = $properties;
		$this->required             = $required;
		$this->validator            = $validator;
		$this->sanitizer            = $sanitizer;
		$this->location             = $location;
		$this->deprecated           = $deprecated;
		$this->nullable             = $nullable;
		$this->read_only            = $read_only;
		$this->write_only           = $write_only;
	}

	/**
	 * @inheritDoc
	 */
	public function get_type(): string {
		return 'object';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		return $this->validator ?? function ( array $value ): bool {
			/** @var Parameter_Contract $property */
			foreach ( $this->get_properties() as $property ) {
				$argument = $this->get_name() ? $this->get_name() . '.' . $property->get_name() : $property->get_name();
				if ( $property->is_required() && ! isset( $value[ $property->get_name() ] ) ) {
					throw InvalidRestArgumentException::create(
						// translators: %s is the name of the property.
						sprintf( __( 'Property %s is required', 'tribe-common' ), $argument ),
						$argument,
						'tec_rest_required_property_missing',
						__( 'The property is required but missing.', 'tribe-common' )
					);
				}

				if ( ! isset( $value[ $property->get_name() ] ) ) {
					continue;
				}

				$is_valid = $property->get_validator()( $value[ $property->get_name() ] );

				if ( ! $is_valid ) {
					throw InvalidRestArgumentException::create(
						// translators: %s is the name of the property.
						sprintf( __( 'Property %s is invalid', 'tribe-common' ), $argument ),
						$argument,
						'tec_rest_invalid_property',
						__( 'The property is invalid.', 'tribe-common' )
					);
				}
			}

			return true;
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? function ( array $entity ): array {
			/** @var Parameter_Contract $property */
			foreach ( $this->get_properties() as $property ) {
				if ( ! isset( $entity[ $property->get_name() ] ) ) {
					continue;
				}

				$sanitizer = $property->get_sanitizer();

				$entity[ $property->get_name() ] = $sanitizer ? $sanitizer( $entity[ $property->get_name() ] ) : $entity[ $property->get_name() ];
			}

			return $entity;
		};
	}

	/**
	 * @inheritDoc
	 */
	public function get_default() {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): array {
		return $this->example ?? [
			'id'   => 1,
			'name' => 'Example',
		];
	}
}
