<?php
/**
 * Term_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Term_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Term_Definition implements Definition_Interface {

	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'Term';
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
				'id'          => [
					'type'        => 'integer',
					'description' => __( 'The WordPress term ID', 'tribe-common' ),
				],
				'name'        => [
					'type'        => 'string',
					'description' => __( 'The term name', 'tribe-common' ),
				],
				'slug'        => [
					'type'        => 'string',
					'description' => __( 'The term slug', 'tribe-common' ),
				],
				'taxonomy'    => [
					'type'        => 'string',
					'description' => __( 'The taxonomy the term belongs to', 'tribe-common' ),
				],
				'description' => [
					'type'        => 'string',
					'description' => __( 'The term description', 'tribe-common' ),
				],
				'parent'      => [
					'type'        => 'integer',
					'description' => __( 'The term parent term if any', 'tribe-common' ),
				],
				'count'       => [
					'type'        => 'integer',
					'description' => __( 'The number of posts associated with the term', 'tribe-common' ),
				],
				'url'         => [
					'type'        => 'string',
					'description' => __( 'The URL to the term archive page', 'tribe-common' ),
				],
				'urls'        => [
					'type'        => 'array',
					'items'       => [ 'type' => 'string' ],
					'description' => __( 'A list of links to the term own, archive and parent REST URL', 'tribe-common' ),
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for an term in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array           $documentation An associative PHP array in the format supported by Swagger.
		 * @param Term_Definition $this          The Term_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
