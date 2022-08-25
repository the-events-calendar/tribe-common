<?php
/**
 * The Zapier New Events Documentation Provider.
 *
 * @package TEC\Common\Zapier\REST\V1\Documentation
 * @since   TBD
 */

namespace TEC\Common\Zapier\REST\V1\Documentation;

use Tribe__Documentation__Swagger__Provider_Interface as Swagger_Provider_Interface;

class Authorize_Definition_Provider
	implements Swagger_Provider_Interface {

	/**
	 * Returns an array in the format used by Swagger 2.0.
	 *
	 * @since TBD
	 *
	 * While the structure must conform to that used by v2.0 of Swagger the structure can be that of a full document
	 * or that of a document part.
	 * The intelligence lies in the "gatherer" of informations rather than in the single "providers" implementing this
	 * interface.
	 *
	 * @link http://swagger.io/
	 *
	 * @return array An array description of a Swagger supported component.
	 */
	public function get_documentation() {
		$documentation = [
			'type'       => 'object',
			'properties' => [
				'consumer_id'       => [
					'type'        => 'string',
					'description' => __( 'The API Key to authorize Zapier.', 'tribe-common' ),
				],
			],
		];

		/**
		 * Filters the Swagger documentation generated for the Zapier New Events endpoint.
		 *
		 * @since TBD
		 *
		 * @param array $documentation An associative PHP array in the format supported by Swagger.
		 *
		 * @link http://swagger.io/
		 */
		$documentation = apply_filters( 'tec_rest_swagger_new_events_documentation', $documentation );

		return $documentation;
	}
}
