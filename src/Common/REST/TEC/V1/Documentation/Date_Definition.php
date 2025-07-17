<?php
/**
 * Date_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Date_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Date_Definition implements Definition_Interface {
	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'Date';
	}

	/**
	 * Returns the priority of the definition.
	 *
	 * @since TBD
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 9;
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
				'date'          => [
					'type'        => 'string',
					'description' => __( 'The date', 'tribe-common' ),
					'format'      => 'date-time',
					'pattern'     => '^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{6}$',
					'example'     => '2025-07-10 08:00:00.000000',
				],
				'timezone_type' => [
					'type'        => 'integer',
					'description' => __( 'The timezone of the date', 'tribe-common' ),
					'example'     => 1,
				],
				'timezone'      => [
					'type'        => 'string',
					'description' => __( 'The timezone of the date', 'tribe-common' ),
					'example'     => 'Europe/Athens',
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for an date in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array           $documentation An associative PHP array in the format supported by Swagger.
		 * @param Date_Definition $this          The Date_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
