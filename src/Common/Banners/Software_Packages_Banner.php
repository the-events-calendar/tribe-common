<?php
/**
 * The Nexcess software packages banner.
 *
 * @since 6.13.1
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
 * @since 6.13.1
 */
class Software_Packages_Banner {

	/**
	 * Shared by every Nexcess plugin that shows this banner.
	 *
	 * @since 6.13.1
	 *
	 * @var string
	 */
	public const ID = 'nexcess-software-packages-2026-10-14';

	/**
	 * When the banner starts showing, in New York time.
	 *
	 * @since 6.13.1
	 *
	 * @var string
	 */
	private const START = '2026-10-14 12:00:00';

	/**
	 * The Help Hub pages, which render the banner in their sidebar.
	 *
	 * @since 6.13.1
	 *
	 * @var string[]
	 */
	private const HELP_HUB_PAGES = [ 'tec-events-help-hub', 'tec-tickets-help-hub' ];

	/**
	 * Who may see the banner, and where.
	 *
	 * @since 6.13.1
	 *
	 * @var Audience
	 */
	private Audience $audience;

	/**
	 * The banners every Nexcess plugin registers.
	 *
	 * @since 6.13.1
	 *
	 * @var Registry
	 */
	private Registry $registry;

	/**
	 * Software_Packages_Banner constructor.
	 *
	 * @since 6.13.1
	 *
	 * @param Audience $audience Who may see the banner, and where.
	 * @param Registry $registry The banners every Nexcess plugin registers.
	 */
	public function __construct( Audience $audience, Registry $registry ) {
		$this->audience = $audience;
		$this->registry = $registry;
	}

	/**
	 * Registers the banner.
	 *
	 * @since 6.13.1
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

		$this->registry->register( new Banner( self::ID, '1.0.0', $fields, $package_url, $start, null ) );
	}

	/**
	 * Renders the banner at the top of the page.
	 *
	 * @since 6.13.1
	 *
	 * @return void
	 */
	public function render_at_top(): void {
		if ( $this->has_sidebar() ) {
			return;
		}

		$this->render();
	}

	/**
	 * Renders the banner in the page's sidebar.
	 *
	 * @since 6.13.1
	 *
	 * @return void
	 */
	public function render_in_sidebar(): void {
		$this->render();
	}

	/**
	 * Renders the banner.
	 *
	 * @since 6.13.1
	 *
	 * @return void
	 */
	private function render(): void {
		if ( ! $this->registry->has( self::ID ) ) {
			return;
		}

		$this->registry->get( self::ID )->render( [ $this->audience, 'should_display' ] );
	}

	/**
	 * Whether the current page has a sidebar the banner renders in instead.
	 *
	 * @since 6.13.1
	 *
	 * @return bool
	 */
	private function has_sidebar(): bool {
		/** This filter is documented in src/Tribe/Settings_Tab.php */
		$settings_page = apply_filters( 'tec_sidebar_allowed_admin_page', 'tec-events-settings' );

		return tribe( 'admin.pages' )->get_current_page() === $settings_page
			|| in_array( tribe_get_request_var( 'page' ), self::HELP_HUB_PAGES, true );
	}
}
