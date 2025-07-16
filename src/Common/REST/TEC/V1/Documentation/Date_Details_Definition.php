<?php
/**
 * Date_Details_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Date_Details_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Date_Details_Definition implements Definition_Interface {
	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'DateDetails';
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
				'year'    => [
					'type'        => 'integer',
					'description' => __( 'The date year', 'tribe-common' ),
				],
				'month'   => [
					'type'        => 'integer',
					'description' => __( 'The date month', 'tribe-common' ),
				],
				'day'     => [
					'type'        => 'integer',
					'description' => __( 'The date day', 'tribe-common' ),
				],
				'hour'    => [
					'type'        => 'integer',
					'description' => __( 'The date hour', 'tribe-common' ),
				],
				'minutes' => [
					'type'        => 'integer',
					'description' => __( 'The date minutes', 'tribe-common' ),
				],
				'seconds' => [
					'type'        => 'integer',
					'description' => __( 'The date seconds', 'tribe-common' ),
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for an date details in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array              $documentation An associative PHP array in the format supported by Swagger.
		 * @param Date_Details_Definition $this          The Date_Details_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
