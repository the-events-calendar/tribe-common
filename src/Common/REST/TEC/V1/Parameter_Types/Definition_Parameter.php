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
	 * Constructor.
	 *
	 * @since TBD
	 *
	 * @param Definition $definition The definition.
	 */
	public function __construct( Definition $definition ) {
		$this->definition = $definition;
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
}
