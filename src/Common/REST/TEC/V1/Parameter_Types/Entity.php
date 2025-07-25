<?php
/**
 * Entity parameter type.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use Closure;
use TEC\Common\REST\TEC\V1\Collections\Collection;

/**
 * Entity parameter type.
 *
 * @since TBD
 */
class Entity extends Parameter {
	/**
	 * Entity Constructor.
	 *
	 * @since TBD
	 *
	 * @param string      $name               The name of the parameter.
	 * @param ?Closure    $description_provider The description provider.
	 * @param ?Collection $properties         The properties.
	 * @param bool        $required           Whether the parameter is required.
	 * @param ?Closure    $validator          The validator.
	 * @param ?Closure    $sanitizer          The sanitizer.
	 * @param string      $location           The location.
	 * @param ?bool       $deprecated         Whether the parameter is deprecated.
	 * @param ?bool       $nullable           Whether the parameter is nullable.
	 * @param ?bool       $read_only          Whether the parameter is read only.
	 * @param ?bool       $write_only         Whether the parameter is write only.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		?Collection $properties = null,
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
		return $this->validator ?? fn() => true;
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $entity ) => $entity;
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
