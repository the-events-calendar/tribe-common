<?php
/**
 * Parameter interface.
 *
 * @since TBD
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
 * @since TBD
 */
interface Parameter extends JsonSerializable {
	/**
	 * Returns the parameter name.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Returns the parameter description.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * Returns the parameter type.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Returns the parameter default value.
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	#[ReturnTypeWillChange]
	public function get_default();

	/**
	 * Returns the parameter validator.
	 *
	 * @since TBD
	 *
	 * @return ?Closure
	 */
	public function get_validator(): ?Closure;

	/**
	 * Returns the parameter sanitizer.
	 *
	 * @since TBD
	 *
	 * @return ?Closure
	 */
	public function get_sanitizer(): ?Closure;

	/**
	 * Returns the parameter enum values.
	 *
	 * @since TBD
	 *
	 * @return ?array
	 */
	public function get_enum(): ?array;

	/**
	 * Returns the parameter maximum value.
	 *
	 * @since TBD
	 *
	 * @return ?int
	 */
	public function get_maximum(): ?int;

	/**
	 * Returns the parameter minimum value.
	 *
	 * @since TBD
	 *
	 * @return ?int
	 */
	public function get_minimum(): ?int;

	/**
	 * Returns the parameter format.
	 *
	 * @since TBD
	 *
	 * @return ?string
	 */
	public function get_format(): ?string;

	/**
	 * Returns the parameter items.
	 *
	 * @since TBD
	 *
	 * @return ?array
	 */
	public function get_items(): ?array;

	/**
	 * Returns the parameter required.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_required(): bool;

	/**
	 * Returns the parameter min items.
	 *
	 * @since TBD
	 *
	 * @return ?int
	 */
	public function get_min_items(): ?int;

	/**
	 * Returns the parameter max items.
	 *
	 * @since TBD
	 *
	 * @return ?int
	 */
	public function get_max_items(): ?int;

	/**
	 * Returns the parameter min length.
	 *
	 * @since TBD
	 *
	 * @return ?int
	 */
	public function get_min_length(): ?int;

	/**
	 * Returns the parameter max length.
	 *
	 * @since TBD
	 *
	 * @return ?int
	 */
	public function get_max_length(): ?int;

	/**
	 * Returns the parameter explode.
	 *
	 * @since TBD
	 *
	 * @return ?string
	 */
	public function get_pattern(): ?string;

	/**
	 * Returns the parameter explode.
	 *
	 * @since TBD
	 *
	 * @return ?bool
	 */
	public function get_explode(): ?bool;

	/**
	 * Returns the parameter multiple of.
	 *
	 * @since TBD
	 *
	 * @return ?int|float
	 */
	public function get_multiple_of();

	/**
	 * Returns the parameter unique items.
	 *
	 * @since TBD
	 *
	 * @return ?bool
	 */
	public function is_unique_items(): ?bool;

	/**
	 * Returns the parameter properties.
	 *
	 * @since TBD
	 *
	 * @return ?Collection
	 */
	public function get_properties(): ?Collection;

	/**
	 * Returns the parameter location.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_location(): string;

	/**
	 * Returns the parameter deprecated.
	 *
	 * @since TBD
	 *
	 * @return ?bool
	 */
	public function is_deprecated(): ?bool;

	/**
	 * Returns the parameter example.
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function get_example();

	/**
	 * Returns the parameter nullable.
	 *
	 * @since TBD
	 *
	 * @return ?bool
	 */
	public function is_nullable(): ?bool;

	/**
	 * Returns the parameter read only.
	 *
	 * @since TBD
	 *
	 * @return ?bool
	 */
	public function is_read_only(): ?bool;

	/**
	 * Returns the parameter write only.
	 *
	 * @since TBD
	 *
	 * @return ?bool
	 */
	public function is_write_only(): ?bool;

	/**
	 * Returns the parameter as an array.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_array(): array;

	/**
	 * Returns the parameter as an OpenAPI schema.
	 *
	 * @link https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.4.md#parameter-object
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function to_openapi_schema(): array;
}
