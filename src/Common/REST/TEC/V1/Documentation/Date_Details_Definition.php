<?php
/**
 * Date_Details_Definition class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Abstracts\Definition;
use TEC\Common\REST\TEC\V1\Collections\PropertiesCollection;
use TEC\Common\REST\TEC\V1\Parameter_Types\Definition_Parameter;

/**
 * Date_Details_Definition class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Date_Details_Definition extends Definition {
	/**
	 * Returns the type of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'DateDetails';
	}

	/**
	 * Returns the priority of the definition.
	 *
	 * @since 6.9.0
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 10;
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

		$properties[] = new Definition_Parameter( new Date_Definition(), 'start' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'start_utc' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'start_site' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'start_display' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'end' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'end_utc' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'end_site' );
		$properties[] = new Definition_Parameter( new Date_Definition(), 'end_display' );

		$documentation = [
			'type'       => 'object',
			'properties' => $properties,
		];

		$type = strtolower( $this->get_type() );

		/**
		 * Filters the Swagger documentation generated for an date details in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array                   $documentation An associative PHP array in the format supported by Swagger.
		 * @param Date_Details_Definition $this          The Date_Details_Definition instance.
		 *
		 * @return array
		 */
		$documentation = (array) apply_filters( "tec_rest_swagger_{$type}_definition", $documentation, $this );

		/**
		 * Filters the Swagger documentation generated for a definition in the TEC REST API.
		 *
		 * @since 6.9.0
		 *
		 * @param array                   $documentation An associative PHP array in the format supported by Swagger.
		 * @param Date_Details_Definition $this          The Date_Details_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_definition', $documentation, $this );
	}
}
