<?php
/**
 * Parameter interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

use JsonSerializable;
use Closure;
use ReturnTypeWillChange;
use TEC\Common\REST\TEC\V1\Collections\Collection;

/**
 * Parameter interface.
 *
 * @since 6.9.0
 */
interface Parameter extends JsonSerializable {
	/**
	 * Returns the parameter name.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Returns the parameter description.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * Returns the parameter type.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Returns the parameter default value.
	 *
	 * @since 6.9.0
	 *
	 * @return mixed
	 */
	#[ReturnTypeWillChange]
	public function get_default();

	/**
	 * Returns the parameter validator.
	 *
	 * @since 6.9.0
	 *
	 * @return ?Closure
	 */
	public function get_validator(): ?Closure;

	/**
	 * Returns the parameter sanitizer.
	 *
	 * @since 6.9.0
	 *
	 * @return ?Closure
	 */
	public function get_sanitizer(): ?Closure;

	/**
	 * Returns the parameter enum values.
	 *
	 * @since 6.9.0
	 *
	 * @return ?array
	 */
	public function get_enum(): ?array;

	/**
	 * Returns the parameter maximum value.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int
	 */
	public function get_maximum(): ?int;

	/**
	 * Returns the parameter minimum value.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int
	 */
	public function get_minimum(): ?int;

	/**
	 * Returns the parameter format.
	 *
	 * @since 6.9.0
	 *
	 * @return ?string
	 */
	public function get_format(): ?string;

	/**
	 * Returns the parameter items.
	 *
	 * @since 6.9.0
	 *
	 * @return ?array
	 */
	public function get_items(): ?array;

	/**
	 * Returns the parameter required.
	 *
	 * @since 6.9.0
	 *
	 * @return bool
	 */
	public function is_required(): bool;

	/**
	 * Returns the parameter min items.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int
	 */
	public function get_min_items(): ?int;

	/**
	 * Returns the parameter max items.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int
	 */
	public function get_max_items(): ?int;

	/**
	 * Returns the parameter min length.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int
	 */
	public function get_min_length(): ?int;

	/**
	 * Returns the parameter max length.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int
	 */
	public function get_max_length(): ?int;

	/**
	 * Returns the parameter explode.
	 *
	 * @since 6.9.0
	 *
	 * @return ?string
	 */
	public function get_pattern(): ?string;

	/**
	 * Returns the parameter explode.
	 *
	 * @since 6.9.0
	 *
	 * @return ?bool
	 */
	public function get_explode(): ?bool;

	/**
	 * Returns the parameter multiple of.
	 *
	 * @since 6.9.0
	 *
	 * @return ?int|float
	 */
	public function get_multiple_of();

	/**
	 * Returns the parameter unique items.
	 *
	 * @since 6.9.0
	 *
	 * @return ?bool
	 */
	public function is_unique_items(): ?bool;

	/**
	 * Returns the parameter properties.
	 *
	 * @since 6.9.0
	 *
	 * @return ?Collection
	 */
	public function get_properties(): ?Collection;

	/**
	 * Returns the parameter location.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_location(): string;

	/**
	 * Returns the parameter deprecated.
	 *
	 * @since 6.9.0
	 *
	 * @return ?bool
	 */
	public function is_deprecated(): ?bool;

	/**
	 * Returns the parameter example.
	 *
	 * @since 6.9.0
	 *
	 * @return mixed
	 */
	public function get_example();

	/**
	 * Returns the parameter nullable.
	 *
	 * @since 6.9.0
	 *
	 * @return ?bool
	 */
	public function is_nullable(): ?bool;

	/**
	 * Returns the parameter read only.
	 *
	 * @since 6.9.0
	 *
	 * @return ?bool
	 */
	public function is_read_only(): ?bool;

	/**
	 * Returns the parameter write only.
	 *
	 * @since 6.9.0
	 *
	 * @return ?bool
	 */
	public function is_write_only(): ?bool;

	/**
	 * Returns the parameter as an array.
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_array(): array;

	/**
	 * Returns the parameter as an OpenAPI schema.
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md#parameter-object
	 *
	 * @since 6.9.0
	 *
	 * @return array
	 */
	public function to_openapi_schema(): array;

	/**
	 * Sets the required flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $required Whether the parameter is required.
	 *
	 * @return self
	 */
	public function set_required( bool $required ): self;

	/**
	 * Sets the location.
	 *
	 * @since 6.9.0
	 *
	 * @param string $location The location.
	 *
	 * @return self
	 */
	public function set_location( string $location ): self;

	/**
	 * Sets the pattern.
	 *
	 * @since 6.9.0
	 *
	 * @param string $pattern The pattern.
	 *
	 * @return self
	 */
	public function set_pattern( string $pattern ): self;

	/**
	 * Sets the example.
	 *
	 * @since 6.9.0
	 *
	 * @param mixed $example The example.
	 *
	 * @return self
	 */
	public function set_example( $example ): self;

	/**
	 * Sets the nullable flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $nullable Whether the parameter is nullable.
	 *
	 * @return self
	 */
	public function set_nullable( bool $nullable ): self;

	/**
	 * Sets the read only flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $read_only Whether the parameter is read only.
	 *
	 * @return self
	 */
	public function set_read_only( bool $read_only ): self;

	/**
	 * Sets the format.
	 *
	 * @since 6.9.0
	 *
	 * @param string $format The format.
	 *
	 * @return self
	 */
	public function set_format( string $format ): self;

	/**
	 * Returns an instance of the items type.
	 *
	 * @since 6.9.0
	 *
	 * @return mixed
	 */
	public function get_an_item();
}
