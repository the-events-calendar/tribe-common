<?php
/**
 * The Nexcess software packages banner.
 *
 * @since TBD
 *
 * @package TEC\Common\Banners
 */

namespace TEC\Common\Banners;

use DateTimeImmutable;
use DateTimeZone;
use TEC\Common\Nexcess\Banners\Banner;
use TEC\Common\Nexcess\Banners\BannerFields;
use TEC\Common\Nexcess\Banners\Registry;
use Tribe__Main as Common;

/**
 * What the banner says, when it starts, and where it renders: at the top of a page, or in the
 * settings sidebar on the settings page that has one.
 *
 * @since TBD
 */
class Software_Packages_Banner {

	/**
	 * Shared with every Nexcess plugin that shows this banner, so one dismissal hides it in all of them.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const ID = 'nexcess-software-packages';

	/**
	 * When the banner starts showing: noon Eastern time, which is EDT (UTC-4) on that date. Every Nexcess
	 * plugin registers the same start.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private const START = '2026-10-14 12:00:00';

	/**
	 * Who may see the banner, and where.
	 *
	 * @since TBD
	 *
	 * @var Audience
	 */
	private Audience $audience;

	/**
	 * Software_Packages_Banner constructor.
	 *
	 * @since TBD
	 *
	 * @param Audience $audience Who may see the banner, and where.
	 */
	public function __construct( Audience $audience ) {
		$this->audience = $audience;
	}

	/**
	 * Registers the banner. Runs on init so the copy can be translated.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register(): void {
		$fields               = new BannerFields();
		$fields->heading      = __( 'Changes are coming to your plan at your next renewal.', 'tribe-common' );
		$fields->body         = __( 'Watch your inbox for details.', 'tribe-common' );
		$fields->ctaLabel     = __( 'Learn more', 'tribe-common' );
		$fields->ctaUrl       = 'https://explore.nexcess.com/software-packages';
		$fields->dismissLabel = __( 'Dismiss this banner', 'tribe-common' );

		$package_url = plugins_url( 'vendor/vendor-prefixed/stellarwp/banners/', Common::instance()->plugin_path . 'tribe-common.php' );
		$start       = new DateTimeImmutable( self::START, new DateTimeZone( 'America/New_York' ) );

		// No end: the banner shows until the user dismisses it.
		( new Registry() )->register( new Banner( self::ID, '1.0.0', $fields, $package_url, $start, null ) );
	}

	/**
	 * Renders the wide banner on admin_notices, which wp-admin moves under the page heading.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_at_top(): void {
		// The settings page with a sidebar shows the banner there instead.
		if ( $this->is_sidebar_settings_page() ) {
			return;
		}

		$this->render();
	}

	/**
	 * Renders the banner at the top of the settings sidebar, where it stacks vertically.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_in_sidebar(): void {
		$this->render();
	}

	/**
	 * Renders the banner, which sizes itself to the space it is given.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	private function render(): void {
		$registry = new Registry();

		if ( ! $registry->has( self::ID ) ) {
			return;
		}

		$registry->get( self::ID )->render( [ $this->audience, 'should_display' ] );
	}

	/**
	 * Whether this is the settings page that shows the settings sidebar.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	private function is_sidebar_settings_page(): bool {
		/** This filter is documented in src/Tribe/Settings_Tab.php */
		$sidebar_page = apply_filters( 'tec_sidebar_allowed_admin_page', 'tec-events-settings' );

		return tribe( 'admin.pages' )->get_current_page() === $sidebar_page;
	}
}
