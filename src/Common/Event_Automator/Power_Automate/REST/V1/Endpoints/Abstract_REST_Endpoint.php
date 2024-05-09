<?php
/**
 * The Power Automate API Key Endpoint.
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate\REST\V1\Endpoints
 */

namespace TEC\Common\Event_Automator\Power_Automate\REST\V1\Endpoints;

use TEC\Common\Event_Automator\Integrations\REST\V1\Endpoints\Queue\Integration_REST_Endpoint;
use TEC\Common\Event_Automator\Power_Automate\Api;
use TEC\Common\Event_Automator\Power_Automate\REST\V1\Documentation\Swagger_Documentation;
use TEC\Common\Event_Automator\Power_Automate\REST\V1\Traits\REST_Namespace as Power_Automate_REST_Namespace;

/**
 * Abstract REST Endpoint Power Automate
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate\REST\V1\Endpoints
 */
abstract class Abstract_REST_Endpoint extends Integration_REST_Endpoint {
	use Power_Automate_REST_Namespace;

	/**
	 * @inheritDoc
	 */
	protected static $endpoint_details_prefix = '_tec_power_automate_endpoint_details_';

	/**
	 * @inheritDoc
	 */
	protected static $service_id = 'power-automate';

	/**
	 * Abstract_REST_Endpoint constructor.
	 *
	 * @since 1.4.0
	 *
	 * @param Api $api An instance of the Power Automate API handler.
	 * @param Swagger_Documentation $documentation An instance of the Power Automate Swagger_Documentation handler.
	 */
	public function __construct( Api $api, Swagger_Documentation $documentation ) {
		$this->api                = $api;
		$this->documentation      = $documentation;
		$this->details            = $this->get_endpoint_details();
		$this->enabled            = empty( $this->details['enabled'] ) ? false : true;
		$this->missing_dependency = empty( $this->details['missing_dependency'] ) ? false : true;
	}
}
