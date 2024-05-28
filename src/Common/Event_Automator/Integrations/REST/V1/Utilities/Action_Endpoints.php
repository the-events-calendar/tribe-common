<?php
/**
 * The Integrations Action Endpoint Utilities
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\REST\V1\Utilities
 */

namespace TEC\Event_Automator\Integrations\REST\V1\Utilities;

/**
 * Class Actions
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Integrations\REST\V1\Utilities
 */
class Action_Endpoints {

	/**
	 * Filters the endpoint details.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 *
	 * @param array<string,array>    $endpoint An array of the Zapier endpoint details.
	 * @param Abstract_REST_Endpoint $this     An instance of the endpoint.
	 */
	public function filter_details( $endpoint, $endpoint_obj ) {
		if ( $endpoint_obj->get_id() !== 'create_events' ) {
			return $endpoint;
		}

		if ( class_exists( 'Tribe__Events__REST__V1__Validator__Base', false ) ) {
			return $endpoint;
		}

		// Disable if Tribe__Events__REST__V1__Validator__Base class does not exist.
		$endpoint['missing_dependency'] = true;
		$endpoint['dependents'] = $endpoint_obj->get_dependents();

		return $endpoint;
	}
}