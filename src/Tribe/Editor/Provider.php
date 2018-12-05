<?php

class Tribe__Editor__Provider extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.8
	 *
	 */
	public function register() {
		// Setup to check if gutenberg is active
		$this->container->singleton( 'editor', 'Tribe__Editor' );
		$this->container->singleton( 'editor.utils', 'Tribe__Editor__Utils' );
		$this->container->singleton( 'common.editor.configuration', 'Tribe__Editor__Configuration' );

		if ( ! tribe( 'editor' )->should_load_blocks() ) {
			return;
		}

		$this->container->singleton( 'editor.assets', 'Tribe__Editor__Assets', array( 'hook' ) );

		$this->hook();

		// Initialize the correct Singletons
		tribe( 'editor.assets' );
	}

	/**
	 * Any hooking any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since 4.8
	 *
	 */
	protected function hook() {

	}

	/**
	 * Binds and sets up implementations at boot time.
	 *
	 * @since 4.8
	 */
	public function boot() {
		// no ops
	}
}
