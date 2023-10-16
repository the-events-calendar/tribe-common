<?php

namespace TEC\Common\Integrations\Traits;

trait Server_Integration {
	/**
	 * Gets the integration type.
	 *
	 * @since  5.1.1
	 *
	 * @return string
	 */
	public static function get_type(): string {
		return 'server';
	}
}
