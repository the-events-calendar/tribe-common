<?php
/**
 * Hooks the Nexcess banners into TEC plugin admin pages.
 *
 * @since 6.13.1
 *
 * @package TEC\Common\Banners
 */

namespace TEC\Common\Banners;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Wires the Nexcess software packages banner into wp-admin.
 *
 * @since 6.13.1
 */
class Controller extends Controller_Contract {

	/**
	 * Only wp-admin shows the banner.
	 *
	 * @since 6.13.1
	 *
	 * @return bool
	 */
	public function is_active(): bool {
		return is_admin();
	}

	/**
	 * Hooks the banner's registration and the places it renders.
	 *
	 * @since 6.13.1
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( Software_Packages_Banner::class );

		add_action( 'init', $this->container->callback( Software_Packages_Banner::class, 'register' ) );
		add_action( 'admin_notices', $this->container->callback( Software_Packages_Banner::class, 'render_at_top' ) );
		add_action( 'tec_settings_sidebar_start', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
		add_action( 'tec_conditional_content_sidebar_notice__help_hub_support', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
		add_action( 'tec_admin_page_sidebar_start', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
	}

	/**
	 * Removes the hooks added by the controller.
	 *
	 * @since 6.13.1
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( 'init', $this->container->callback( Software_Packages_Banner::class, 'register' ) );
		remove_action( 'admin_notices', $this->container->callback( Software_Packages_Banner::class, 'render_at_top' ) );
		remove_action( 'tec_settings_sidebar_start', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
		remove_action( 'tec_conditional_content_sidebar_notice__help_hub_support', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
		remove_action( 'tec_admin_page_sidebar_start', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
	}
}
