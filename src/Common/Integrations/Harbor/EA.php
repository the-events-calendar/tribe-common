<?php
/**
 * The Event Aggregator Harbor integration.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations\Harbor
 */
namespace TEC\Common\Integrations\Harbor;

use stdClass;

/**
 * The Event Aggregator Harbor integration.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations\Harbor
 */
class EA extends Integration_Controller {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		add_filter( 'tribe_aggregator_api', [ $this, 'filter_aggregator_api' ] );
		add_filter( 'tec_events_aggregator_consolidation_took_over', [ $this, 'filter_consolidation_took_over' ] );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( 'tribe_aggregator_api', [ $this, 'filter_aggregator_api' ] );
		remove_filter( 'tec_events_aggregator_consolidation_took_over', [ $this, 'filter_consolidation_took_over' ] );
	}

	/**
	 * Filter the aggregator API.
	 *
	 * @since TBD
	 *
	 * @param stdClass $api The API array.
	 *
	 * @return stdClass
	 */
	public function filter_aggregator_api( stdClass $api ): stdClass {
		$consolidated_key = $this->harbor->get_unified_license_key_if_feature_enabled( 'event-aggregator' );
		if ( $consolidated_key ) {
			$api->key = $consolidated_key;
		}

		return $api;
	}

	/**
	 * Filter the consolidation took over.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function filter_consolidation_took_over(): bool {
		return (bool) $this->harbor->get_unified_license_key_if_feature_enabled( 'event-aggregator' );
	}
}
