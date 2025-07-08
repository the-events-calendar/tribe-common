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
use TEC\Common\Admin\Abstract_Admin_Page;
use TEC\Common\Admin\Settings_Sidebar;
use TEC\Common\Admin\Settings_Sidebar_Section;

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
		add_action( 'tec_settings_sidebar_start', [ $this, 'add_sidebar_objects' ] );
		add_filter( 'tec_settings_sidebar_sections', [ $this, 'add_sidebar_sections' ], 10, 2 );
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
		// Those need to be initialized here in order for their hooks to be registered.
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
	 *
	 * @param Abstract_Admin_Page|null $page The page object we are rendering on.
	 */
	public function render_header_notice( $page = null ): void {
		if ( ! empty( $page->has_sidebar ) ) {
			return;
		}

		tribe( Stellar_Sale::class )->render_header_notice();
		tribe( Black_Friday::class )->render_header_notice();
	}

	/**
	 * Add sidebar objects from each promotional content class.
	 *
	 * @since 6.8.2
	 *
	 * @param Settings_Sidebar $sidebar The sidebar instance.
	 *
	 * @return void
	 */
	public function add_sidebar_objects( $sidebar ): void {
		foreach ( $this->get_promotional_classes() as $class ) {
			$instance = tribe( $class );
			if ( is_callable( [ $instance, 'include_sidebar_object' ] ) ) {
				$instance->include_sidebar_object( $sidebar );
			}
		}
	}

	/**
	 * Add sidebar sections from each promotional content class.
	 *
	 * @since 6.8.2
	 *
	 * @param Settings_Sidebar_Section[] $sections The sidebar sections.
	 * @param Settings_Sidebar           $sidebar  The sidebar instance.
	 *
	 * @return Settings_Sidebar_Section[]
	 */
	public function add_sidebar_sections( $sections, $sidebar ): array {
		foreach ( $this->get_promotional_classes() as $class ) {
			$instance = tribe( $class );
			if ( is_callable( [ $instance, 'add_sidebar_sections' ] ) ) {
				$sections = $instance->add_sidebar_sections( $sections, $sidebar );
			}
		}

		return $sections;
	}

	/**
	 * Get the promotional content classes.
	 *
	 * @since 6.8.2
	 *
	 * @return string[]
	 */
	protected function get_promotional_classes(): array {
		return [
			Stellar_Sale::class,
			Black_Friday::class,
		];
	}

	/**
	 * Render promotional content for help hub sidebar.
	 *
	 * @since 6.8.2
	 *
	 * @return void
	 */
	public function render_help_hub_sidebar(): void {
		tribe( Stellar_Sale::class )->render_sidebar_content();
		tribe( Black_Friday::class )->render_sidebar_content();
	}
}
