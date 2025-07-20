<?php
/**
 * Abstract parameter class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Abstracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Abstracts;

use Closure;
use TEC\Common\REST\TEC\V1\Contracts\Parameter as Parameter_Contract;
use TEC\Common\REST\TEC\V1\Parameter_Types\Collection;

/**
 * Abstract parameter class.
 *
 * @since TBD
 */
abstract class Parameter implements Parameter_Contract {

	/**
	 * The name of the parameter.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * The description provider.
	 *
	 * @since TBD
	 *
	 * @var Closure
	 */
	protected Closure $description_provider;

	/**
	 * The validator.
	 *
	 * @since TBD
	 *
	 * @var ?Closure
	 */
	protected ?Closure $validator = null;

	/**
	 * The sanitizer.
	 *
	 * @since TBD
	 *
	 * @var ?Closure
	 */
	protected ?Closure $sanitizer = null;

	/**
	 * The enum values.
	 *
	 * @since TBD
	 *
	 * @var ?array
	 */
	protected ?array $enum = null;

	/**
	 * Whether the parameter is required.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	protected bool $required = false;

	/**
	 * The minimum items.
	 *
	 * @since TBD
	 *
	 * @var ?int
	 */
	protected ?int $min_items = null;

	/**
	 * The maximum items.
	 *
	 * @since TBD
	 *
	 * @var ?int
	 */
	protected ?int $max_items = null;

	/**
	 * The maximum value.
	 *
	 * @since TBD
	 *
	 * @var ?int
	 */
	protected ?int $maximum = null;

	/**
	 * The minimum value.
	 *
	 * @since TBD
	 *
	 * @var ?int
	 */
	protected ?int $minimum = null;

	/**
	 * The min length.
	 *
	 * @since TBD
	 *
	 * @var ?int
	 */
	protected ?int $min_length = null;

	/**
	 * The max length.
	 *
	 * @since TBD
	 *
	 * @var ?int
	 */
	protected ?int $max_length = null;

	/**
	 * The format.
	 *
	 * @since TBD
	 *
	 * @var ?string
	 */
	protected ?string $format = null;

	/**
	 * The items.
	 *
	 * @since TBD
	 *
	 * @var ?string
	 */
	protected ?string $items_type = null;

	/**
	 * The default value.
	 *
	 * @since TBD
	 *
	 * @var mixed
	 */
	protected $default = null;

	/**
	 * The pattern.
	 *
	 * @since TBD
	 *
	 * @var ?string
	 */
	protected ?string $pattern = null;

	/**
	 * The explode.
	 *
	 * @since TBD
	 *
	 * @var ?bool
	 */
	protected ?bool $explode = null;

	/**
	 * The multiple of.
	 *
	 * @since TBD
	 *
	 * @var int|float|null
	 */
	protected $multiple_of = null;

	/**
	 * The properties.
	 *
	 * @since TBD
	 *
	 * @var ?Collection
	 */
	protected ?Collection $properties = null;

	/**
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @param string         $name                 The name of the parameter.
	 * @param Closure        $description_provider The description provider.
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
	 */
	public function __construct(
		string $name,
		Closure $description_provider,
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
		?int $max_items = null
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
	}

	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * @inheritDoc
	 */
	public function get_description(): string {
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
		return 'array' === $this->get_type() ? true : null;
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
	public function jsonSerialize(): array {
		return [
			$this->get_name() => array_filter(
				[
					'description' => $this->get_description(),
					'type'        => $this->get_type(),
					'items'       => $this->get_items(),
					'default'     => $this->get_default(),
					'minLength'   => $this->get_min_length(),
					'maxLength'   => $this->get_max_length(),
					'maximum'     => $this->get_maximum(),
					'minimum'     => $this->get_minimum(),
					'minItems'    => $this->get_min_items(),
					'maxItems'    => $this->get_max_items(),
					'format'      => $this->get_format(),
					'required'    => $this->is_required(),
					'pattern'     => $this->get_pattern(),
					'explode'     => $this->get_explode(),
					'multipleOf'  => $this->get_multiple_of(),
					'uniqueItems' => $this->is_unique_items(),
					'properties'  => $this->get_properties(),
					'enum'        => 'array' === $this->get_type() ? null : $this->get_enum(),
				],
				static fn( $value ) => null !== $value
			),
		];
	}
}
