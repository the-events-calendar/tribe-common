<?php
/**
 * Date_Definition class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Abstracts\Definition;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use TEC\Common\REST\TEC\V1\Parameter_Types\Text;
use TEC\Common\REST\TEC\V1\Parameter_Types\Date_Time;
use TEC\Common\REST\TEC\V1\Parameter_Types\Positive_Integer;

/**
 * Date_Definition class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Date_Definition extends Definition {
	/**
	 * Returns the type of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'Date';
	}

	/**
	 * Returns the priority of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 9;
	}

	/**
	 * Returns an array in the format used by Swagger.
	 *
	 * @since 6.9.0
	 *
	 * @return array An array description of a Swagger supported component.
	 */
	public function get_documentation(): array {
		$properties = new PropertiesCollection();

		$properties[] = (
			new Date_Time(
				'date',
				fn() => __( 'The date', 'tribe-common' ),
			)
		)->set_example( '2025-07-10 08:00:00.000000' )->set_pattern( '^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{6}$' )->set_required( true );

		$properties[] = (
			new Positive_Integer(
				'timezone_type',
				fn() => __( 'The timezone type', 'tribe-common' ),
			)
		)->set_example( 1 )->set_required( true );

		$properties[] = (
			new Text(
				'timezone',
				fn() => __( 'The timezone of the date', 'tribe-common' ),
			)
		)->set_example( 'Europe/Athens' )->set_required( true );

		$documentation = [
			'type'       => 'object',
			'properties' => $properties,
		];

		$type = strtolower( $this->get_type() );

		/**
		 * Filters the Swagger documentation generated for an date in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array           $documentation An associative PHP array in the format supported by Swagger.
		 * @param Date_Definition $this          The Date_Definition instance.
		 *
		 * @return array
		 */
		$documentation = (array) apply_filters( "tec_rest_swagger_{$type}_definition", $documentation, $this );

		/**
		 * Filters the Swagger documentation generated for a definition in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array           $documentation An associative PHP array in the format supported by Swagger.
		 * @param Date_Definition $this          The Date_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_definition', $documentation, $this );
	}
}
