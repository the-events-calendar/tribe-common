<?php
/**
 * The Power Automate REST Namespace Trait.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Traits
 */

namespace TEC\Event_Automator\Power_Automate\REST\V1\Traits;

/**
 * Abstract REST Endpoint Power Automate
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Power_Automate\REST\V1\Traits
 */
trait REST_Namespace {

	/**
	 * The REST API endpoint path.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $namespace = 'tribe';

	/**
	 * Returns the namespace of REST APIs.
	 *
	 * @return string
	 */
	public function get_namespace() {
		return $this->namespace;
	}

	/**
	 * Returns the string indicating the REST API version.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string
	 */
	public function get_version() {
		return 'v1';
	}

	/**
	 * Returns the events REST API namespace string that should be used to register a route.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string
	 */
	public function get_events_route_namespace() {
		return $this->get_namespace() . '/power-automate/' . $this->get_version();
	}
}
