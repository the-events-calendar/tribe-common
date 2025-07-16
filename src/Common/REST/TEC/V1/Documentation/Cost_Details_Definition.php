<?php
/**
 * Cost_Details_Definition_Provider class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Cost_Details_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Cost_Details_Definition implements Definition_Interface {

	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'CostDetails';
	}

	/**
	 * Returns the priority of the definition.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 10;
	}

	/**
	 * Returns an array in the format used by Swagger.
	 *
	 * @since TBD
	 *
	 * @return array An array description of a Swagger supported component.
	 */
	public function get_documentation(): array {
		$documentation = [
			'type'       => 'object',
			'properties' => [
				'currency_symbol'    => [
					'type'        => 'string',
					'description' => __( 'The cost currency symbol', 'tribe-common' ),
				],
				'currency_position ' => [
					'type'        => 'string',
					'description' => __( 'The position of the currency symbol in the cost string', 'tribe-common' ),
					'enum'        => [ 'prefix', 'postfix' ],
				],
				'values'             => [
					'type'        => 'array',
					'items'       => [ 'type' => 'integer' ],
					'description' => __( 'A sorted array of all the numeric values for the cost', 'tribe-common' ),
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for a cost details in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array              $documentation An associative PHP array in the format supported by Swagger.
		 * @param Cost_Details_Definition $this          The Cost_Details_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
