<?php

namespace TEC\Common\Integrations\Traits;

trait Plugin_Integration {
	/**
	 * Gets the integration type.
	 *
	 * @since  TBD
	 *
	 * @return string
	 */
	public static function get_type(): string {
		return 'plugin';
	}
}
