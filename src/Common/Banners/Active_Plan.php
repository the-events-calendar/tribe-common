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
 * Answers whether the site has at least one paid plugin with an active plan, from stored state only.
 *
 * @since TBD
 */
class Active_Plan {

	/**
	 * The Harbor product a consolidated license for TEC's paid plugins belongs to.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private const HARBOR_PRODUCT = 'the-events-calendar';

	/**
	 * Whether a legacy license key or a consolidated license is active.
	 *
	 * Legacy keys: every PUE checker records its key status in a shared transient on admin_init,
	 * from a status cached for 12 hours. PUE_Checker::is_any_license_valid() is not used because it
	 * revalidates invalid keys with the licensing server. Consolidated licenses: Harbor's stored
	 * product state, which it refreshes on its own schedule.
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
