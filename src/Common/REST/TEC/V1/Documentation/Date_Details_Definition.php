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

use TEC\Common\REST\TEC\V1\Abstracts\Definition;

/**
 * Date_Details_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Date_Details_Definition extends Definition {
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
				'start'         => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The start date', 'tribe-common' ),
						],
					],
				],
				'start_utc'     => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The start date in UTC', 'tribe-common' ),
						],
					],
				],
				'start_site'    => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The start date in the site timezone', 'tribe-common' ),
						],
					],
				],
				'start_display' => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The start date in the display timezone', 'tribe-common' ),
						],
					],
				],
				'end'           => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The end date', 'tribe-common' ),
						],
					],
				],
				'end_utc'       => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The end date in UTC', 'tribe-common' ),
						],
					],
				],
				'end_site'      => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The end date in the site timezone', 'tribe-common' ),
						],
					],
				],
				'end_display'   => [
					'allOf' => [
						[
							'$ref' => '#/components/schemas/Date',
						],
						[
							'description' => __( 'The end date in the display timezone', 'tribe-common' ),
						],
					],
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
