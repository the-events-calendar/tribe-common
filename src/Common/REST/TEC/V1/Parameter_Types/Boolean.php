<?php
/**
 * Boolean parameter type.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Parameter_Types
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Parameter_Types;

use TEC\Common\REST\TEC\V1\Abstracts\Parameter;
use Closure;

/**
 * Boolean parameter type.
 *
 * @since 6.9.0
 */
class Boolean extends Parameter {

	/**
	 * Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param string   $name                 The name of the parameter.
	 * @param ?Closure $description_provider The description provider.
	 * @param bool     $required             Whether the parameter is required.
	 * @param mixed    $by_default           The default value.
	 * @param string   $location             The parameter location.
	 * @param bool     $deprecated           Whether the parameter is deprecated.
	 * @param ?bool    $nullable             Whether the parameter is nullable.
	 * @param ?bool    $read_only            Whether the parameter is read only.
	 * @param ?bool    $write_only           Whether the parameter is write only.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		bool $required = false,
		$by_default = null,
		string $location = self::LOCATION_QUERY,
		?bool $deprecated = null,
		?bool $nullable = null,
		?bool $read_only = null,
		?bool $write_only = null
	) {
		$this->name                 = $name;
		$this->description_provider = $description_provider;
		$this->required             = $required;
		$this->default              = $by_default;
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
		return 'boolean';
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): Closure {
		// Anything is accepted and will be converted to a boolean.
		return $this->validator ?? fn(): bool => true;
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): Closure {
		return $this->sanitizer ?? fn( $value ): bool => (bool) $value;
	}

	/**
	 * @inheritDoc
	 */
	public function get_default(): ?bool {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public function get_example(): bool {
		return $this->example ?? true;
	}
}
