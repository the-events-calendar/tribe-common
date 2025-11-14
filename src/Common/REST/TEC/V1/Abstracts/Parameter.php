<?php
/**
 * Abstract parameter class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use Closure;
use Exception;
use TEC\Common\REST\TEC\V1\Contracts\Parameter as Parameter_Contract;
use TEC\Common\REST\TEC\V1\Collections\Collection;
use TEC\Common\REST\TEC\V1\Parameter_Types\Entity;
use TEC\Common\REST\TEC\V1\Parameter_Types\Definition_Parameter;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface as Definition;
use TEC\Common\REST\TEC\V1\Exceptions\InvalidRestArgumentException;

/**
 * Abstract parameter class.
 *
 * @since 6.9.0
 */
abstract class Parameter implements Parameter_Contract {
	/**
	 * The OpenAPI schema keys.
	 *
	 * @since 6.9.0
	 *
	 * @var array
	 */
	public const OPENAPI_SCHEMA_KEYS = [
		'title'                => true,
		'multipleOf'           => true,
		'maximum'              => true,
		'exclusiveMaximum'     => true,
		'minimum'              => true,
		'exclusiveMinimum'     => true,
		'maxLength'            => true,
		'minLength'            => true,
		'pattern'              => true,
		'maxItems'             => true,
		'minItems'             => true,
		'uniqueItems'          => true,
		'maxProperties'        => true,
		'minProperties'        => true,
		'enum'                 => true,
		'type'                 => true,
		'allOf'                => true,
		'oneOf'                => true,
		'anyOf'                => true,
		'not'                  => true,
		'items'                => true,
		'properties'           => true,
		'additionalProperties' => true,
		'format'               => true,
		'default'              => true,
		'nullable'             => true,
		'readOnly'             => true,
		'writeOnly'            => true,
		'deprecated'           => true,
	];

	/**
	 * The parameter location: query.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	public const LOCATION_QUERY = 'query';

	/**
	 * The parameter location: path.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	public const LOCATION_PATH = 'path';

	/**
	 * The parameter location: header.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	public const LOCATION_HEADER = 'header';

	/**
	 * The parameter location: cookie.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	public const LOCATION_COOKIE = 'cookie';

	/**
	 * The name of the parameter.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * The description provider.
	 *
	 * @since 6.9.0
	 *
	 * @var ?Closure
	 */
	protected ?Closure $description_provider;

	/**
	 * The validator.
	 *
	 * @since 6.9.0
	 *
	 * @var ?Closure
	 */
	protected ?Closure $validator = null;

	/**
	 * The example.
	 *
	 * @since 6.9.0
	 *
	 * @var mixed
	 */
	protected $example = null;

	/**
	 * The sanitizer.
	 *
	 * @since 6.9.0
	 *
	 * @var ?Closure
	 */
	protected ?Closure $sanitizer = null;

	/**
	 * The enum values.
	 *
	 * @since 6.9.0
	 *
	 * @var ?array
	 */
	protected ?array $enum = null;

	/**
	 * Whether the parameter is required.
	 *
	 * @since 6.9.0
	 *
	 * @var bool
	 */
	protected bool $required = false;

	/**
	 * The minimum items.
	 *
	 * @since 6.9.0
	 *
	 * @var ?int
	 */
	protected ?int $min_items = null;

	/**
	 * The maximum items.
	 *
	 * @since 6.9.0
	 *
	 * @var ?int
	 */
	protected ?int $max_items = null;

	/**
	 * The maximum value.
	 *
	 * @since 6.9.0
	 *
	 * @var ?int
	 */
	protected ?int $maximum = null;

	/**
	 * The minimum value.
	 *
	 * @since 6.9.0
	 *
	 * @var ?int
	 */
	protected ?int $minimum = null;

	/**
	 * The min length.
	 *
	 * @since 6.9.0
	 *
	 * @var ?int
	 */
	protected ?int $min_length = null;

	/**
	 * The max length.
	 *
	 * @since 6.9.0
	 *
	 * @var ?int
	 */
	protected ?int $max_length = null;

	/**
	 * The format.
	 *
	 * @since 6.9.0
	 *
	 * @var ?string
	 */
	protected ?string $format = null;

	/**
	 * The items.
	 *
	 * @since 6.9.0
	 *
	 * @var ?string
	 */
	protected ?string $items_type = null;

	/**
	 * The default value.
	 *
	 * @since 6.9.0
	 *
	 * @var mixed
	 */
	protected $default = null;

	/**
	 * The pattern.
	 *
	 * @since 6.9.0
	 *
	 * @var ?string
	 */
	protected ?string $pattern = null;

	/**
	 * The explode.
	 *
	 * @since 6.9.0
	 *
	 * @var ?bool
	 */
	protected ?bool $explode = null;

	/**
	 * The multiple of.
	 *
	 * @since 6.9.0
	 *
	 * @var int|float|null
	 */
	protected $multiple_of = null;

	/**
	 * The location.
	 *
	 * @since 6.9.0
	 *
	 * @var string
	 */
	protected string $location;

	/**
	 * The properties.
	 *
	 * @since 6.9.0
	 *
	 * @var ?Collection
	 */
	protected ?Collection $properties = null;

	/**
	 * Whether the parameter is deprecated.
	 *
	 * @since 6.9.0
	 *
	 * @var ?bool
	 */
	protected ?bool $deprecated = null;

	/**
	 * Whether the parameter is nullable.
	 *
	 * @since 6.9.0
	 *
	 * @var ?bool
	 */
	protected ?bool $nullable = null;

	/**
	 * Whether the parameter is read only.
	 *
	 * @since 6.9.0
	 *
	 * @var ?bool
	 */
	protected ?bool $read_only = null;

	/**
	 * Whether the parameter is write only.
	 *
	 * @since 6.9.0
	 *
	 * @var ?bool
	 */
	protected ?bool $write_only = null;

	/**
	 * Constructor.
	 *
	 * @since 6.9.0
	 *
	 * @param string         $name                 The name of the parameter.
	 * @param ?Closure       $description_provider The description provider.
	 * @param bool           $required             Whether the parameter is required.
	 * @param ?string        $items_type           The items type.
	 * @param ?Collection    $properties           The properties.
	 * @param mixed          $by_default           The default value.
	 * @param ?array         $available_enum       The enum values.
	 * @param ?int           $maximum              The maximum value.
	 * @param ?int           $minimum              The minimum value.
	 * @param ?int           $min_length           The min length.
	 * @param ?int           $max_length           The max length.
	 * @param ?Closure       $validator            The validator.
	 * @param ?Closure       $sanitizer            The sanitizer.
	 * @param ?string        $format               The format.
	 * @param ?string        $pattern              The pattern.
	 * @param ?bool          $explode              Whether to explode the parameter.
	 * @param int|float|null $multiple_of          The multiple of.
	 * @param ?int           $min_items            The minimum items.
	 * @param ?int           $max_items            The maximum items.
	 * @param string         $location             The parameter location.
	 * @param bool           $deprecated           Whether the parameter is deprecated.
	 * @param ?bool          $nullable             Whether the parameter is nullable.
	 * @param ?bool          $read_only            Whether the parameter is read only.
	 * @param ?bool          $write_only           Whether the parameter is write only.
	 */
	public function __construct(
		string $name = 'example',
		?Closure $description_provider = null,
		bool $required = true,
		?string $items_type = null,
		?Collection $properties = null,
		$by_default = null,
		?array $available_enum = null,
		?int $maximum = null,
		?int $minimum = null,
		?int $min_length = null,
		?int $max_length = null,
		?Closure $validator = null,
		?Closure $sanitizer = null,
		?string $format = null,
		?string $pattern = null,
		?bool $explode = null,
		$multiple_of = null,
		?int $min_items = null,
		?int $max_items = null,
		string $location = self::LOCATION_QUERY,
		?bool $deprecated = null,
		?bool $nullable = null,
		?bool $read_only = null,
		?bool $write_only = null
	) {
		$this->name                 = $name;
		$this->description_provider = $description_provider;
		$this->required             = $required;
		$this->enum                 = $available_enum;
		$this->default              = $by_default;
		$this->properties           = $properties;
		$this->maximum              = $maximum;
		$this->minimum              = $minimum;
		$this->min_length           = $min_length;
		$this->max_length           = $max_length;
		$this->validator            = $validator;
		$this->sanitizer            = $sanitizer;
		$this->items_type           = $items_type;
		$this->format               = $format;
		$this->pattern              = $pattern;
		$this->explode              = $explode;
		$this->multiple_of          = $multiple_of;
		$this->min_items            = $min_items;
		$this->max_items            = $max_items;
		$this->location             = $location;
		$this->deprecated           = $deprecated;
		$this->nullable             = $nullable;
		$this->read_only            = $read_only;
		$this->write_only           = $write_only;
	}

	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Returns an instance of the items type.
	 *
	 * @since 6.9.0
	 *
	 * @return mixed
	 */
	public function get_an_item() {
		if ( 'array' !== $this->get_type() ) {
			return null;
		}

		if ( ! ( $this->items_type && class_exists( $this->items_type ) ) ) {
			return null;
		}

		try {
			$class_string = $this->items_type;
			return new $class_string();
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function get_description(): string {
		if ( null === $this->description_provider ) {
			return '';
		}

		return call_user_func( $this->description_provider );
	}

	/**
	 * @inheritDoc
	 */
	public function is_required(): bool {
		return $this->required;
	}

	/**
	 * @inheritDoc
	 */
	public function get_enum(): ?array {
		return $this->enum;
	}

	/**
	 * @inheritDoc
	 */
	public function get_maximum(): ?int {
		return $this->maximum;
	}

	/**
	 * @inheritDoc
	 */
	public function get_minimum(): ?int {
		return $this->minimum;
	}

	/**
	 * @inheritDoc
	 */
	public function get_min_items(): ?int {
		return $this->min_items;
	}

	/**
	 * @inheritDoc
	 */
	public function get_max_items(): ?int {
		return $this->max_items;
	}

	/**
	 * @inheritDoc
	 */
	public function get_min_length(): ?int {
		return $this->min_length;
	}

	/**
	 * @inheritDoc
	 */
	public function get_max_length(): ?int {
		return $this->max_length;
	}

	/**
	 * @inheritDoc
	 */
	public function get_format(): ?string {
		return $this->format;
	}

	/**
	 * @inheritDoc
	 */
	public function get_items(): ?array {
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function get_pattern(): ?string {
		return $this->pattern;
	}

	/**
	 * @inheritDoc
	 */
	public function get_explode(): ?bool {
		return 'array' === $this->get_type() ? false : $this->explode;
	}

	/**
	 * @inheritDoc
	 */
	public function get_multiple_of() {
		return $this->multiple_of;
	}

	/**
	 * @inheritDoc
	 */
	public function is_unique_items(): ?bool {
		$item = $this->get_an_item();
		return $item && ! $item instanceof Entity && ! $item instanceof Definition && ! $item instanceof Definition_Parameter ? true : null;
	}

	/**
	 * @inheritDoc
	 */
	public function get_properties(): ?Collection {
		return $this->properties;
	}

	/**
	 * @inheritDoc
	 */
	public function get_validator(): ?Closure {
		return $this->validator;
	}

	/**
	 * @inheritDoc
	 */
	public function get_sanitizer(): ?Closure {
		return $this->sanitizer;
	}

	/**
	 * @inheritDoc
	 */
	public function is_nullable(): ?bool {
		return $this->nullable;
	}

	/**
	 * @inheritDoc
	 */
	public function is_read_only(): ?bool {
		return $this->read_only;
	}

	/**
	 * @inheritDoc
	 */
	public function is_write_only(): ?bool {
		return $this->write_only;
	}

	/**
	 * @inheritDoc
	 */
	public function to_array(): array {
		return array_filter(
			[
				'description'       => $this->get_description(),
				'nullable'          => $this->is_nullable(),
				'readOnly'          => $this->is_read_only(),
				'writeOnly'         => $this->is_write_only(),
				'type'              => $this->get_type(),
				'items'             => $this->get_items(),
				'default'           => $this->get_default(),
				'minLength'         => $this->get_min_length(),
				'maxLength'         => $this->get_max_length(),
				'maximum'           => $this->get_maximum(),
				'minimum'           => $this->get_minimum(),
				'minItems'          => $this->get_min_items(),
				'maxItems'          => $this->get_max_items(),
				'format'            => $this->get_format(),
				'required'          => $this->is_required(),
				'pattern'           => $this->get_pattern(),
				'explode'           => $this->get_explode(),
				'multipleOf'        => $this->get_multiple_of(),
				'uniqueItems'       => $this->is_unique_items(),
				'properties'        => $this->get_properties(),
				'enum'              => 'array' === $this->get_type() ? null : $this->get_enum(),
				'validate_callback' => function () {
					return function ( $value ) {
						try {
							return $this->get_validator()( $value );
						} catch ( InvalidRestArgumentException $e ) {
							return $e->to_wp_error();
						}
					};
				},
				'sanitize_callback' => $this->get_sanitizer(),
			],
			static fn( $value ) => null !== $value
		);
	}

	/**
	 * Sets the example.
	 *
	 * @since 6.9.0
	 *
	 * @param mixed $example The example.
	 *
	 * @return self
	 */
	public function set_example( $example ): self {
		$this->example = $example;
		return $this;
	}

	/**
	 * Sets the pattern.
	 *
	 * @since 6.9.0
	 *
	 * @param string $pattern The pattern.
	 *
	 * @return self
	 */
	public function set_pattern( string $pattern ): self {
		$this->pattern = $pattern;
		return $this;
	}

	/**
	 * Sets the required flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $required Whether the parameter is required.
	 *
	 * @return self
	 */
	public function set_required( bool $required ): self {
		$this->required = $required;
		return $this;
	}

	/**
	 * Sets the location.
	 *
	 * @since 6.9.0
	 *
	 * @param string $location The location.
	 *
	 * @return self
	 */
	public function set_location( string $location ): self {
		$this->location = $location;
		return $this;
	}

	/**
	 * Sets the nullable flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $nullable Whether the parameter is nullable.
	 *
	 * @return self
	 */
	public function set_nullable( bool $nullable ): self {
		$this->nullable = $nullable;
		return $this;
	}

	/**
	 * Sets the format.
	 *
	 * @since 6.9.0
	 *
	 * @param string $format The format.
	 *
	 * @return self
	 */
	public function set_format( string $format ): self {
		$this->format = $format;
		return $this;
	}

	/**
	 * Sets the read only flag.
	 *
	 * @since 6.9.0
	 *
	 * @param bool $read_only Whether the parameter is read only.
	 *
	 * @return self
	 */
	public function set_read_only( bool $read_only ): self {
		$this->read_only = $read_only;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_location(): string {
		return $this->location;
	}

	/**
	 * @inheritDoc
	 */
	public function is_deprecated(): ?bool {
		return $this->deprecated;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		return $this->to_openapi_schema();
	}

	/**
	 * @inheritDoc
	 */
	public function to_openapi_schema(): array {
		$schema = array_filter( array_intersect_key( $this->to_array(), self::OPENAPI_SCHEMA_KEYS ), static fn( $value ) => null !== $value );

		return array_filter(
			[
				'name'        => $this->get_name(),
				'in'          => $this->get_location(),
				'description' => $this->get_description(),
				'required'    => $this->is_required(),
				'deprecated'  => $this->is_deprecated(),
				'explode'     => $this->get_explode(),
				'schema'      => $schema,
				'example'     => $this->get_example(),
			],
			static fn( $value ) => null !== $value
		);
	}
}
