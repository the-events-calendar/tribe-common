<?php
/**
 * The Zapier Swagger Documentation Endpoint.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Documentation∂
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Documentation;

use TEC\Event_Automator\Integrations\REST\V1\Documentation\Integration_Swagger_Documentation;
use TEC\Event_Automator\Zapier\REST\V1\Traits\REST_Namespace as Zapier_REST_Namespace;

/**
 * Class Swagger_Documentation
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @since 6.0.0 Migrated to Common from Event Automator - Utilize Integration_Swagger_Documentation to share coding among integrations.
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Documentation
 */
class Swagger_Documentation extends Integration_Swagger_Documentation {

	use Zapier_REST_Namespace;

	/**
	 * @inerhitDoc
	 */
	protected function get_api_info() {
		return [
			'title'       => __( 'TEC Zapier REST API', 'tribe-common' ),
			'description' => __( 'TEC Zapier REST API allows direct connections to making Zapier Zaps.', 'tribe-common' ),
			'version'     => $this->rest_api_version,
		];
	}
}
