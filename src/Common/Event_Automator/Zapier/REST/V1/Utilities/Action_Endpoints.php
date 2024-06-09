<?php
/**
 * The Zapier Action Endpoint Utilities
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\ZapierREST\V1\Utilities
 */

namespace TEC\Event_Automator\Zapier\REST\V1\Utilities;

/**
 * Class Actions
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier\REST\V1\Utilities
 */
class Action_Endpoints {

	/**
	 * Filters the Zapier endpoint details.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 *
	 * @param array<string,array>    $endpoint An array of the Zapier endpoint details.
	 * @param Abstract_REST_Endpoint $this     An instance of the endpoint.
	 */
	public function filter_details( $endpoint, $endpoint_obj ) {
		if ( in_array( $endpoint_obj->get_id(), [ 'new_events', 'updated_events', 'canceled_events', 'create_events' ], true ) ) {
			if ( ! class_exists( 'Tribe__Events__REST__V1__Validator__Base', false ) ) {
				// Disable if Tribe__Events__REST__V1__Validator__Base class does not exist.
				$endpoint['missing_dependency'] = true;
				$endpoint['dependents'] = [ 'tec' ];
			}
		} elseif ( in_array( $endpoint_obj->get_id(), [ 'attendees', 'updated_attendees', 'checkin', 'orders', 'refunded_orders' ], true ) ) {
			if ( ! class_exists( 'Tribe__Tickets__REST__V1__Validator__Base', false ) ) {
				// Disable if Tribe__Tickets__REST__V1__Validator__Base class does not exist.
				$endpoint['missing_dependency'] = true;
				$endpoint['dependents'] = [ 'et' ];
			}
		}

		return $endpoint;
	}
}
