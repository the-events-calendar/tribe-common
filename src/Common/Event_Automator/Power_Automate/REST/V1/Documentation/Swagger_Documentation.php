<?php
/**
 * The Power Automate Swagger Documentation Endpoint.
 *
 * @since   1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate\REST\V1\Documentation∂
 */

namespace TEC\Common\Event_Automator\Power_Automate\REST\V1\Documentation;

use TEC\Common\Event_Automator\Integrations\REST\V1\Documentation\Integration_Swagger_Documentation;
use TEC\Common\Event_Automator\Power_Automate\REST\V1\Traits\REST_Namespace as Power_Automate_REST_Namespace;

/**
 * Class Swagger_Documentation
 *
 * @since   1.4.0
 * @since   1.4.0 - Utilize Integration_Swagger_Documentation to share coding among integrations.
 *
 * @package TEC\Common\Event_Automator\Power_Automate\REST\V1\Documentation
 */
class Swagger_Documentation extends Integration_Swagger_Documentation {

	use Power_Automate_REST_Namespace;

	/**
	 * @inerhitDoc
	 */
	protected function get_api_info() {
		return [
			'title'       => __( 'TEC Power Automate REST API', 'tribe-common' ),
			'description' => __( 'TEC Power Automate REST API allows direct connections to making Power Automate Connectors.', 'tribe-common' ),
			'version'     => $this->rest_api_version,
		];
	}
}
