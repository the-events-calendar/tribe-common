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
	 * Whether a legacy license key or a consolidated license is active, from stored state only.
	 *
	 * @since 6.13.1
	 *
	 * @return bool
	 */
	public function exists(): bool {
		$legacy = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		if ( is_array( $legacy ) && in_array( true, (array) ( $legacy['plugins'] ?? [] ), true ) ) {
			return true;
		}

		/* Harbor's global functions are not defined on sandbox scraping or plugin activation requests. */
		return function_exists( 'lw_harbor_is_product_license_active' )
			&& lw_harbor_is_product_license_active( self::HARBOR_PRODUCT );
	}
}
