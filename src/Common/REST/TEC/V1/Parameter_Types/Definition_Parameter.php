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
	 * Returns the definition.
	 *
	 * @since TBD
	 *
	 * @param array $data The data to validate.
	 *
	 * @return self
	 */
	public function validate( array $data = [] ): self {
		$this->sanitized_data = $data;
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
}
