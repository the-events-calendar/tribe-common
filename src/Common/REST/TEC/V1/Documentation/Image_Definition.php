<?php
/**
 * Image_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Image_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Image_Definition implements Definition_Interface {
	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'Image';
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
				'url'       => [
					'type'        => 'string',
					'format'      => 'uri',
					'description' => __( 'The URL to the full size version of the image', 'tribe-common' ),
				],
				'id'        => [
					'type'        => 'integer',
					'description' => __( 'The image WordPress post ID', 'tribe-common' ),
				],
				'extension' => [
					'type'        => 'string',
					'description' => __( 'The image file extension', 'tribe-common' ),
				],
				'width'     => [
					'type'        => 'integer',
					'description' => __( 'The image natural width in pixels', 'tribe-common' ),
				],
				'height'    => [
					'type'        => 'integer',
					'description' => __( 'The image natural height in pixels', 'tribe-common' ),
				],
				'sizes'     => [
					'type'        => 'array',
					'description' => __( 'The details about each size available for the image', 'tribe-common' ),
					'items'       => [
						'$ref' => '#/components/schemas/ImageSize',
					],
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for an image deatails in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array              $documentation An associative PHP array in the format supported by Swagger.
		 * @param Image_Definition $this          The Image_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
