<?php
/**
 * Provides a constants value for the Configuration_Loader.
 *
 * @since 5.1.3
 *
 * @package TEC\Common\Configuration;
 */

namespace TEC\Common\Configuration;

/**
 * Class Constants_Provider.
 *
 * @since 5.1.3
 *
 * @package TEC\Common\Configuration;
 */
class Constants_Provider implements Configuration_Provider_Interface {

	/**
	 * @inheritDoc
	 */
	public function has( $key ): bool {
		return defined( $key );
	}

	/**
	 * @inheritDoc
	 */
	public function get( $key ) {
		if ( $this->has( $key ) ) {
			return constant( $key );
		}
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function all(): array {
		return get_defined_constants( false );
	}
}
