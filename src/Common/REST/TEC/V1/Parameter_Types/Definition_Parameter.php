<?php
/**
 * Definition parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface as Definition;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use TEC\Common\REST\TEC\V1\Contracts\Parameter;
use Closure;

/**
 * Definition parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */
class Definition_Parameter extends Entity {
	/**
	 * The definition.
	 *
	 * @since 6.9.0
	 *
	 * @var Definition
	 */
	private Definition $definition;

	/**
	 * The sanitized data.
	 *
	 * @since 6.9.0
	 *
	 * @var array
	 */
	private array $sanitized_data;

	/**
	 * Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param Definition $definition The definition.
	 * @param string     $name       The name of the parameter.
	 */
	public function __construct( Definition $definition, string $name = '' ) {
		$this->definition = $definition;
		$this->name       = $name;
	}

	/**
	 * @inheritDoc
	 */
	public function to_openapi_schema(): array {
		return [
			'schema' => [
				'$ref' => '#/components/schemas/' . $this->definition->get_type(),
			],
		];
	}

	/**
	 * Returns the parameter as an array.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_array(): array {
		return [ '$ref' => '#/components/schemas/' . $this->definition->get_type() ];
	}

	/**
	 * Returns the validator.
	 *
	 * @since 6.9.0
	 *
	 * @return Closure
	 */
	public function get_validator(): Closure {
		return $this->validator ?? fn( $value ): bool => $this->validate( $value ) && true;
	}

	/**
	 * Returns the sanitizer.
	 *
	 * @since 6.9.0
	 *
	 * @return Closure
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): array => $this->sanitize( $value );
	}

	/**
	 * Filters the data.
	 *
	 * @since 6.10.0
	 *
	 * @param array $data The data to filter.
	 *
	 * @return array The filtered data.
	 *
	 * @throws InvalidRestArgumentException If the data is invalid.
	 */
	public function filter_before_request( array $data = [] ): array {
		$collections = $this->get_collections();

		$filtered_data = [];

		/** @var PropertiesCollection $collection */
		foreach ( $collections as $collection ) {
			/** @var Parameter $property */
			foreach ( $collection as $property ) {
				$param_name = $property->get_name();
				$argument   = $this->get_name() ? "{$this->get_name()}.{$param_name}" : $param_name;

				if ( $property->is_required() && ! isset( $data[ $param_name ] ) ) {
					throw InvalidRestArgumentException::create(
						// translators: %s is the name of the property.
						sprintf( __( 'Property %s is required', 'tribe-common' ), $argument ),
						$argument,
						'tec_rest_required_property_missing',
						__( 'The property is required but missing.', 'tribe-common' )
					);
				}

				if ( empty( $data[ $param_name ] ) && null !== $property->get_default() ) {
					$data[ $param_name ] = $property->get_default();
				}

				if ( ! isset( $data[ $param_name ] ) ) {
					continue;
				}

				$filtered_data[ $param_name ] = $data[ $param_name ];
			}
		}

		return $filtered_data;
	}

	/**
	 * Returns the definition.
	 *
	 * @since 6.9.0
	 *
	 * @param array $data The data to validate.
	 *
	 * @return self
	 *
	 * @throws InvalidRestArgumentException If the data is invalid.
	 */
	public function validate( array $data = [] ): self {
		$collections = $this->get_collections();

		$sanitized_data = [];

		/** @var PropertiesCollection $collection */
		foreach ( $collections as $collection ) {
			/** @var Parameter $property */
			foreach ( $collection as $property ) {
				$param_name = $property->get_name();
				$argument   = $this->get_name() ? $this->get_name() . '.' . $param_name : $param_name;

				if ( $property->is_required() && ! isset( $data[ $param_name ] ) ) {
					throw InvalidRestArgumentException::create(
						// translators: %s is the name of the property.
						sprintf( __( 'Property %s is required', 'tribe-common' ), $argument ),
						$argument,
						'tec_rest_required_property_missing',
						__( 'The property is required but missing.', 'tribe-common' )
					);
				}

				if ( empty( $data[ $param_name ] ) && null !== $property->get_default() ) {
					$data[ $param_name ] = $property->get_default();
				}

				if ( ! isset( $data[ $param_name ] ) ) {
					continue;
				}

				$is_valid = $property->get_validator()( $data[ $param_name ] );

				if ( ! $is_valid ) {
					throw InvalidRestArgumentException::create(
						// translators: %s: The name of the invalid property.
						sprintf( __( 'Property %s is invalid', 'tribe-common' ), $argument ),
						$argument,
						'tec_rest_invalid_property',
						__( 'The property is invalid.', 'tribe-common' )
					);
				}

				$sanitized_data[ $param_name ] = $property->get_sanitizer()( $data[ $param_name ] );
			}
		}

		$this->sanitized_data = $sanitized_data;
		return $this;
	}

	/**
	 * Returns the sanitized data.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function sanitize(): array {
		return $this->sanitized_data;
	}

	/**
	 * Returns the collections from the docs.
	 *
	 * @since 6.9.0
	 *
	 * @param array $docs The docs.
	 *
	 * @return array
	 */
	public function get_collections( array $docs = [] ): array {
		if ( empty( $docs ) ) {
			$docs = $this->definition->get_documentation();
		}

		$docs = ! empty( $docs['allOf'] ) ? $docs['allOf'] : [ $docs ];

		$collections = [];

		foreach ( $docs as $doc ) {
			if ( isset( $doc['$ref'] ) ) {
				$sub_docs = $this->definition::get_instance_from_ref( $doc['$ref'] )->get_documentation();

				$collections = array_merge( $collections, $this->get_collections( $sub_docs ) );
			}

			if ( isset( $doc['properties'] ) ) {
				$collections[] = $doc['properties'];
			}
		}

		return $collections;
	}
}
