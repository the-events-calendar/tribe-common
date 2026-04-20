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
class EventAggregator extends Integration_Controller {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
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
		remove_filter( 'tec_events_aggregator_consolidation_took_over', [ $this, 'filter_consolidation_took_over' ] );
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
