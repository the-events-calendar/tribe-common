<?php
namespace Tribe\Service_Providers;

use Tribe\Shortcode\Manager;

/**
 * Class Shortcode
 *
 * @since   TBD
 *
 * @package Tribe\Service_Providers
 */
class Shortcode extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		if ( static::is_active() ) {
			return;
		}

		$this->container->singleton( Manager::class, Manager::class );

		$this->register_hooks();
		$this->register_assets();

		$this->container->singleton( static::class, $this );
	}

	/**
	 * Static method wrapper around a filter to allow full deactivation of this provider
	 *
	 * @since TBD
	 *
	 * @return boolean If this service provider is active.
	 */
	public static function is_active() {
		/**
		 * Allows filtering to deactivate all shortcodes loading.
		 *
		 * @since TBD
		 *
		 * @param boolean $is_active If shortcodes should be loaded or not.
		 */
		return apply_filters( 'tribe_shortcodes_is_active', true );
	}

	/**
	 * Registers the provider handling all the assets for this service provider.
	 *
	 * @since TBD
	 */
	protected function register_assets() {

	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this service provider.
	 *
	 * @since TBD
	 */
	protected function register_hooks() {
		add_action( 'init', [ $this, 'action_add_shortcodes' ], 20 );
	}

	/**
	 * Adds the new shortcodes, this normally will trigger on `init@P20` due to how we the
	 * v1 is added on `init@P10` and we remove them on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since TBD
	 */
	public function action_add_shortcodes() {
		$this->container->make( Manager::class )->add_shortcodes();
	}
}