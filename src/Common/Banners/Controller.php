<?php
/**
 * Hooks the Nexcess banners into TEC plugin admin pages.
 *
 * @since TBD
 *
 * @package TEC\Common\Banners
 */

namespace TEC\Common\Banners;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Wires the Nexcess software packages banner into wp-admin. The banner, who sees it and the
 * site's plan are each their own class.
 *
 * @since TBD
 */
class Controller extends Controller_Contract {

	/**
	 * Only wp-admin shows the banner.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_active(): bool {
		return is_admin();
	}

	/**
	 * Hooks the banner's registration and the places it renders.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		// A singleton, so the container returns the same callbacks to unregister().
		$this->container->singleton( Software_Packages_Banner::class );

		add_action( 'init', $this->container->callback( Software_Packages_Banner::class, 'register' ) );
		add_action( 'admin_notices', $this->container->callback( Software_Packages_Banner::class, 'render_at_top' ) );
		add_action( 'tec_settings_sidebar_start', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
	}

	/**
	 * Removes the hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action( 'init', $this->container->callback( Software_Packages_Banner::class, 'register' ) );
		remove_action( 'admin_notices', $this->container->callback( Software_Packages_Banner::class, 'render_at_top' ) );
		remove_action( 'tec_settings_sidebar_start', $this->container->callback( Software_Packages_Banner::class, 'render_in_sidebar' ) );
	}
}
