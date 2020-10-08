<?php
namespace Tribe\Service_Providers;

use Tribe\Widget\Manager;

/**
 * Class Widget
 *
 * @since   TBD
 *
 * @package Tribe\Service_Providers
 */
class Widgets extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
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

		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'widgets', $this );
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
		 * Allows filtering to deactivate all widgets loading.
		 *
		 * @since TBD
		 *
		 * @param boolean $is_active If widgets should be loaded or not.
		 */
		return apply_filters( 'tribe_widgets_is_active', true );
	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for this service provider.
	 *
	 * @since TBD
	 */
	protected function register_hooks() {
		add_action( 'widgets_init', [ $this, 'register_widgets_with_wp' ], 20 );
	}

	/**
	 * Adds the new widgets, this normally will trigger on `init@P20` due to how we the
	 * v1 is added on `init@P10` and we remove them on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since TBD
	 */
	public function register_widgets_with_wp() {
		$this->container->make( Manager::class )->register_widgets_with_wp();
	}
}
