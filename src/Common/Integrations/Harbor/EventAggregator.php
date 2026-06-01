<?php
/**
 * The Event Aggregator Harbor integration.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Integrations\Harbor
 */

namespace TEC\Common\Integrations\Harbor;

// phpcs:disable StellarWP.Classes.ValidClassName.NotSnakeCase

/**
 * The Event Aggregator Harbor integration.
 *
 * @since 6.11.0
 *
 * @package TEC\Common\Integrations\Harbor
 */
class EventAggregator extends Integration_Controller {
	/**
	 * Register the controller.
	 *
	 * @since 6.11.0
	 *
	 * @return void
	 */
	protected function do_register(): void {
		add_filter( 'tec_events_aggregator_harbor_took_over', [ $this, 'filter_harbor_took_over' ] );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.11.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_filter( 'tec_events_aggregator_harbor_took_over', [ $this, 'filter_harbor_took_over' ] );
	}

	/**
	 * Filter the consolidation took over.
	 *
	 * @since 6.11.0
	 *
	 * @return bool
	 */
	public function filter_harbor_took_over(): bool {
		return (bool) $this->harbor->get_unified_license_key_if_feature_enabled( 'event-aggregator' );
	}
}
