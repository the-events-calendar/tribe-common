<?php
/**
 * The Zapier REST Namespace Trait.
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Traits
 */

namespace TEC\Common\Zapier\REST\V1\Traits;

/**
 * Abstract REST Endpoint Zapier
 *
 * @since   TBD
 *
 * @package TEC\Common\Zapier\REST\V1\Traits
 */
trait REST_Namespace {

	/**
	 * The REST API endpoint path.
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_version() {
		return 'v1';
	}

	/**
	 * Returns the events REST API namespace string that should be used to register a route.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_events_route_namespace() {
		return $this->get_namespace() . '/zapier/' . $this->get_version();
	}
}