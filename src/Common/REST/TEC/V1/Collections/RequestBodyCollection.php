<?php
/**
 * Request body collection.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Collections
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Collections;

use TEC\Common\REST\TEC\V1\Parameter_Types\Definition_Parameter;
use TEC\Common\REST\TEC\V1\Parameter_Types\Entity;
use TEC\Common\REST\TEC\V1\Contracts\Parameter;
use Tribe\Utils\Lazy_String;
use InvalidArgumentException;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * Request body collection.
 *
 * @since 6.9.0
 */
class RequestBodyCollection extends Collection {

	/**
	 * The description provider.
	 *
	 * @since 6.9.0
	 *
	 * @var ?callable
	 */
	private $description_provider = null;

	/**
	 * The required flag.
	 *
	 * @since 6.9.0
	 *
	 * @var bool
	 */
	private bool $required;

	/**
	 * The example.
	 *
	 * @since 6.9.0
	 *
	 * @var ?array
	 */
	private ?array $example = null;

	/**
	 * Sets the description provider.
	 *
	 * @since 6.9.0
	 *
	 * @param ?callable $provider The description provider.
	 *
	 * @return self
	 *
	 * @throws InvalidArgumentException If the description provider is not a callable.
	 */
	public function set_description_provider( $provider ): self {
		if ( ! is_callable( $provider ) ) {
			throw new InvalidArgumentException( 'The description provider must be a callable.' );
		}

		$this->description_provider = $provider;

		return $this;
	}

	/**
	 * Sets the example.
	 *
	 * @since 6.9.0
	 *
	 * @param ?array $example The example.
	 *
	 * @return self
	 */
	public function set_example( ?array $example ): self {
		$this->example = $example;

		return $this;
	}

	/**
	 * Returns the example.
	 *
	 * @since 6.9.0
	 *
	 * @return ?array
	 */
	public function get_example(): ?array {
		return $this->example;
	}

	/**
	 * Returns the description provider.
	 *
	 * @since 6.9.0
	 *
	 * @return ?Lazy_String
	 */
	public function get_description_provider(): ?Lazy_String {
		if ( ! $this->description_provider ) {
			return null;
		}

		return new Lazy_String( $this->description_provider );
	}

	/**
	 * Sets the required flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $required The required flag.
	 *
	 * @return self
	 */
	public function set_required( bool $required ): self {
		$this->required = $required;

		return $this;
	}

	/**
	 * Returns the collection as an array.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_array(): array {
		$properties = array_map(
			function ( $argument ) {
				if ( is_string( $argument ) ) {
					return $argument;
				}

				if ( ! is_array( $argument ) ) {
					throw new InvalidArgumentException( 'The argument must be a string or an array.' );
				}

				unset(
					$argument['uniqueItems'],
				);

				return $argument;
			},
			array_merge( ...$this->map( fn( Parameter $argument ) => $argument instanceof Definition_Parameter ? [ '$ref' => $argument->to_openapi_schema()['schema']['$ref'] ] : [ $argument->get_name() => $argument->to_openapi_schema()['schema'] ] ) )
		);

		$schema = ! isset( $properties['$ref'] ) ?
			[
				'type'       => 'object',
				'properties' => $properties,
			] : $properties;

		return array_filter(
			[
				'description' => $this->get_description_provider(),
				'required'    => $this->required,
				'content'     => [
					'application/json' => [
						'schema'  => $schema,
						'example' => $this->get_example(),
					],
				],
			],
			fn( $value ) => $value !== null,
		);
	}

	/**
	 * Returns the properties as an array.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_props_array(): array {
		return $this->get_props_from_collection();
	}

	/**
	 * Returns the properties from a collection.
	 *
	 * @since 6.9.0
	 *
	 * @param ?Collection $collection The collection.
	 *
	 * @return array
	 */
	protected function get_props_from_collection( ?Collection $collection = null ): array {
		if ( ! $collection ) {
			$collection = $this;
		}

		$props = [];

		foreach ( $collection as $parameter ) {
			if ( $parameter instanceof Definition_Parameter ) {
				$collections = $parameter->get_collections();
				foreach ( $collections as $collection ) {
					$props = array_merge( $props, $this->get_props_from_collection( $collection ) );
				}

				continue;
			}

			if ( $parameter instanceof Entity ) {
				$collection = $parameter->get_properties();
				$props      = array_merge( $props, $collection ? $this->get_props_from_collection( $collection ) : [] );
				continue;
			}

			$props[] = $parameter;
		}

		return $props;
	}

	/**
	 * Returns the collection as a query argument collection.
	 *
	 * @since 6.10.0
	 *
	 * @return QueryArgumentCollection
	 */
	public function to_query_argument_collection(): QueryArgumentCollection {
		return new QueryArgumentCollection( $this->to_props_array() );
	}
}
