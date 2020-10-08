<?php
namespace Tribe\Service_Providers;

use Tribe\Widget\Manager;

/**
 * Class Widget
 *
 * @since   tBD
 *
 * @package Tribe\Service_Providers
 */
class Widgets extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since tBD
	 */
	public function register() {
		if ( ! static::is_active() ) {
			return;
		}

		$this->container->singleton( Manager::class, Manager::class );
		$this->container->singleton(
			'widget.manager',
			function() {
				return $this->container->make( Manager::class );
			}
		);

		$this->register_hooks();
		$this->register_assets();

		$this->container->singleton( static::class, $this );

	}

	/**
	 * Static method wrapper around a filter to allow full deactivation of this provider
	 *
	 * @since tBD
	 *
	 * @return boolean If this service provider is active.
	 */
	public static function is_active() {
		/**
		 * Allows filtering to deactivate all widgets loading.
		 *
		 * @since tBD
		 *
		 * @param boolean $is_active If widgets should be loaded or not.
		 */
		return apply_filters( 'tribe_widgets_is_active', true );
	}

	/**
	 * Register all the assets associated with this service provider.
	 *
	 * @since tBD
	 */
	protected function register_assets() {

	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this service provider.
	 *
	 * @since tBD
	 */
	protected function register_hooks() {
		add_action( 'widgets_init', [ $this, 'action_add_widgets' ], 20 );
	}

	/**
	 * Adds the new widgets, this normally will trigger on `init@P20` due to how we the
	 * v1 is added on `init@P10` and we remove them on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since tBD
	 */
	public function action_add_widgets() {
		$this->container->make( Manager::class )->register_widgets();
	}
}
