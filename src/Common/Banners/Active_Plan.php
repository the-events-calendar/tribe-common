<?php
/**
 * Whether the site has an active paid plan.
 *
 * @since 6.13.1
 *
 * @package TEC\Common\Banners
 */

namespace TEC\Common\Banners;

use Tribe__PUE__Checker as PUE_Checker;

/**
 * Whether the site has an active paid plan.
 *
 * @since 6.13.1
 */
class Active_Plan {

	/**
	 * Harbor product slug of TEC's paid plugins.
	 *
	 * @since 6.13.1
	 *
	 * @var string
	 */
	private const HARBOR_PRODUCT = 'the-events-calendar';

	/**
	 * Legacy license transient name, for a Tribe__PUE__Checker older than common 6.3.2 that lacks the constant.
	 *
	 * @since 6.13.3
	 *
	 * @var string
	 */
	private const LEGACY_LICENSE_TRANSIENT = 'TEC_IS_ANY_LICENSE_VALID_TRANSIENT';

	/**
	 * Whether a legacy license key or a consolidated license is active, from stored state only.
	 *
	 * @since 6.13.1
	 * @since 6.13.3 Falls back to the literal transient name when an older Tribe__PUE__Checker is loaded.
	 *
	 * @return bool
	 */
	public function exists(): bool {
		$key = defined( PUE_Checker::class . '::IS_ANY_LICENSE_VALID_TRANSIENT_KEY' )
			? PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY
			: self::LEGACY_LICENSE_TRANSIENT;

		$legacy = get_transient( $key );

		if ( is_array( $legacy ) && in_array( true, (array) ( $legacy['plugins'] ?? [] ), true ) ) {
			return true;
		}

		/* Harbor's global functions are not defined on sandbox scraping or plugin activation requests. */
		return function_exists( 'lw_harbor_is_product_license_active' )
			&& lw_harbor_is_product_license_active( self::HARBOR_PRODUCT );
	}
}
