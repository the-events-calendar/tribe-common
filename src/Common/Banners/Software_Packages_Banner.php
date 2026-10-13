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
 * The Nexcess software packages banner.
 *
 * @since TBD
 */
class Software_Packages_Banner {

	/**
	 * Shared by every Nexcess plugin that shows this banner.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const ID = 'nexcess-software-packages';

	/**
	 * When the banner starts showing, in New York time.
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
	 * Registers the banner.
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

		( new Registry() )->register( new Banner( self::ID, '1.0.0', $fields, $package_url, $start, null ) );
	}

	/**
	 * Renders the banner at the top of the page.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_at_top(): void {
		if ( $this->is_sidebar_settings_page() ) {
			return;
		}

		$this->render();
	}

	/**
	 * Renders the banner in the settings sidebar.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_in_sidebar(): void {
		$this->render();
	}

	/**
	 * Renders the banner.
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
