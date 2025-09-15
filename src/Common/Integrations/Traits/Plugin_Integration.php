<?php
/**
 * File: Plugin_Integration.php
 *
 * @since 5.1.1
 *
 * @package TEC\Common\Integrations\Traits
 */

namespace TEC\Common\Integrations\Traits;

trait Plugin_Integration {
	/**
	 * Gets the integration type.
	 *
	 * @since 5.1.1
	 *
	 * @return string
	 */
	public static function get_type(): string {
		return 'plugin';
	}
}
