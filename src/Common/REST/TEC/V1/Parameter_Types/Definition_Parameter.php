<?php
/**
 * Definition parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface as Definition;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use Closure;

/**
 * Definition parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */
class Definition_Parameter extends Entity {
	/**
	 * The definition.
	 *
	 * @since TBD
	 *
	 * @var Definition
	 */
	private Definition $definition;

	/**
	 * The sanitized data.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private array $sanitized_data;

	/**
	 * Constructor.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_array(): array {
		return [ '$ref' => '#/components/schemas/' . $this->definition->get_type() ];
	}

	/**
	 * Returns the validator.
	 *
	 * @since TBD
	 *
	 * @return Closure
	 */
	public function get_validator(): Closure {
		return $this->validator ?? fn( $value ) => $this->validate( $value );
	}

	/**
	 * Returns the sanitizer.
	 *
	 * @since TBD
	 *
	 * @return Closure
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ) => $this->sanitize( $value );
	}

	/**
	 * Returns the definition.
	 *
	 * @since TBD
	 *
	 * @param array $data The data to validate.
	 *
	 * @return self
	 *
	 * @throws InvalidRestArgumentException If the data is invalid.
	 */
	public function validate( array $data = [] ): self {
		$docs = $this->definition->get_documentation();

		$docs = ! empty( $docs['allOf'] ) ? $docs['allOf'] : [ $docs ];

		$collections = $this->get_collections( $docs );

		$sanitized_data = [];

		/** @var PropertiesCollection $collection */
		foreach ( $collections as $collection ) {
			/** @var Property $property */
			foreach ( $collection as $property ) {
				if ( ! isset( $data[ $property->get_name() ] ) ) {
					continue;
				}

				$is_valid = $property->get_validator()( $data[ $property->get_name() ] );

				if ( ! $is_valid ) {
					throw new InvalidRestArgumentException( 'Invalid value for ' . $property->get_name() );
				}

				$sanitized_data[ $property->get_name() ] = $property->get_sanitizer()( $data[ $property->get_name() ] );
			}
		}

		$this->sanitized_data = $sanitized_data;
		return $this;
	}

	/**
	 * Returns the sanitized data.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function sanitize(): array {
		return $this->sanitized_data;
	}

	/**
	 * Returns the collections from the docs.
	 *
	 * @since TBD
	 *
	 * @param array $docs The docs.
	 *
	 * @return array
	 */
	protected function get_collections( array $docs ): array {
		$collections = [];

		foreach ( $docs as $doc ) {
			if ( isset( $doc['$ref'] ) ) {
				$sub_docs = $this->definition::get_instance_from_ref( $doc['$ref'] )->get_documentation();
				$sub_docs = ! empty( $sub_docs['allOf'] ) ? $sub_docs['allOf'] : [ $sub_docs ];

				$collections = array_merge( $collections, $this->get_collections( $sub_docs ) );
			}

			if ( isset( $doc['properties'] ) ) {
				$collections[] = $doc['properties'];
			}
		}

		return $collections;
	}
}
