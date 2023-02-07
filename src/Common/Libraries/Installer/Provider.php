<?php

namespace TEC\Common\Libraries\Installer;

use TEC\Common\Libraries;
use TEC\Common\StellarWP\Installer;

class Provider extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.0.10
	 */
	public function register() {
		$this->container->singleton( static::class, $this );

		$hook_prefix = $this->container->make( Libraries\Provider::class )->get_hook_prefix();

		Installer\Config::set_hook_prefix( $hook_prefix );

		add_filter( "stellarwp/installer/{$hook_prefix}/button_classes", [ $this, 'filter_button_classes' ] );
	}

	/**
	 * Filters the installer button classes.
	 *
	 * @since 5.0.10
	 *
	 * @param array|mixed $classes The button classes.
	 *
	 * @return array
	 */
	public function filter_button_classes( $classes ) {
		if ( ! is_array( $classes ) ) {
			$classes = (array) $classes;
		}

		$classes[] = 'components-button';
		$classes[] = 'is-primary';
		$classes[] = 'tec-admin__notice-install-content-button';
		return $classes;
	}
}
