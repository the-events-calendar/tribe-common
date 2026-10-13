<?php
/**
 * Whether the site has an active paid plan.
 *
 * @since TBD
 *
 * @package TEC\Common\Banners
 */

namespace TEC\Common\Banners;

use Tribe__PUE__Checker as PUE_Checker;

/**
 * Whether the site has an active paid plan.
 *
 * @since TBD
 */
class Active_Plan {

	/**
	 * Harbor product slug of TEC's paid plugins.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private const HARBOR_PRODUCT = 'the-events-calendar';

	/**
	 * Whether a legacy license key or a consolidated license is active, from stored state only.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function exists(): bool {
		$legacy = get_transient( PUE_Checker::IS_ANY_LICENSE_VALID_TRANSIENT_KEY );

		if ( is_array( $legacy ) && in_array( true, (array) ( $legacy['plugins'] ?? [] ), true ) ) {
			return true;
		}

		return lw_harbor_is_product_license_active( self::HARBOR_PRODUCT );
	}
}
