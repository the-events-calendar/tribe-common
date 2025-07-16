<?php
/**
 * Image_Size_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Documentation;

use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;

/**
 * Image_Size_Definition class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1\Documentation
 */
class Image_Size_Definition implements Definition_Interface {
	/**
	 * Returns the type of the definition.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_type(): string {
		return 'ImageSize';
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
				'width'     => [
					'type'        => 'integer',
					'description' => __( 'The image width in pixels in the specified size', 'tribe-common' ),
				],
				'height'    => [
					'type'        => 'integer',
					'description' => __( 'The image height in pixels in the specified size', 'tribe-common' ),
				],
				'mime-type' => [
					'type'        => 'string',
					'description' => __( 'The image mime-type', 'tribe-common' ),
				],
				'url'       => [
					'type'        => 'string',
					'format'      => 'uri',
					'description' => __( 'The link to the image in the specified size on the site', 'tribe-common' ),
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for an image size in the TEC REST API.
		 *
		 * @since TBD
		 *
		 * @param array              $documentation An associative PHP array in the format supported by Swagger.
		 * @param Image_Size_Definition $this          The Image_Size_Definition instance.
		 *
		 * @return array
		 */
		return (array) apply_filters( 'tec_rest_swagger_' . $this->get_type() . '_definition', $documentation, $this );
	}
}
