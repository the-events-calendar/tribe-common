<?php
/**
 * Handles admin conditional content.
 *
 * @since 4.14.7
 * @package Tribe\Admin\Conditional_Content;
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Contracts\Service_Provider as Provider_Contract;

use Tribe__Main as Common;

/**
 * Conditional Content Controller.
 *
 * @since 6.3.0
 */
class Controller extends Provider_Contract {


	/**
	 * Registers the required objects and filters.
	 *
	 * @since 6.3.0
	 */
	public function register() {
		// This is specifically for the admin, bail if we're not in the admin.
		if ( ! is_admin() ) {
			return;
		}

		$this->container->singleton( Black_Friday::class, Black_Friday::class, [ 'hook' ] );
		$this->container->singleton( Stellar_Sale::class, Stellar_Sale::class, [ 'hook' ] );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since 4.14.7
	 */
	protected function hooks(): void {
		add_action( 'tec_settings_sidebar_sections', [ $this, 'include_sidebar_section' ], 10, 2 );
		add_action( 'tribe_plugins_loaded', [ $this, 'plugins_loaded' ] );
		add_action( 'tec_conditional_content_header_notice', [ $this, 'render_header_notice' ] );
		add_action( 'tec_admin_page_before_wrap_start', [ $this, 'render_header_notice' ] );
		add_action( 'tec_conditional_content_sidebar_notice__help_hub_support', [ $this, 'render_help_hub_sidebar' ] );
	}

	/**
	 * Setup for things that require plugins loaded first.
	 *
	 * @since 6.3.0
	 */
	public function plugins_loaded(): void {
		tribe( Black_Friday::class );
		tribe( Stellar_Sale::class );

		$plugin = Common::instance();

		tec_asset(
			$plugin,
			'tec-conditional-content',
			'admin/conditional-content.js',
			[
				'wp-data',
				'tribe-common',
			],
			'tec_conditional_content_assets',
		);
	}

	/**
	 * Render the header notice.
	 *
	 * @since 6.3.0
	 */
	public function render_header_notice( $page ): void {
		if ( ! empty( $page->has_sidebar ) ) {
			return;
		}

		tribe( Stellar_Sale::class )->render_header_notice();
		tribe( Black_Friday::class )->render_header_notice();
	}

	/**
	 * Include the promo in the settings sidebar.
	 *
	 * @since TBD
	 *
	 * @param Settings_Sidebar $sidebar Sidebar instance.
	 *
	 * @return void
	 */
	public function include_sidebar_section( $sections, $sidebar ): void {
		tribe( Stellar_Sale::class )->include_sidebar_section( $sections, $sidebar );
		tribe( Black_Friday::class )->include_sidebar_section( $sections, $sidebar );
	}

	/**
	 * Render promotional content for help hub sidebar.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_help_hub_sidebar(): void {
		tribe( Stellar_Sale::class )->render_sidebar_content();
		tribe( Black_Friday::class )->render_sidebar_content();
	}
}
