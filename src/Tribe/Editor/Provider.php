<?php

class Tribe__Editor__Provider extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 *
	 */
	public function register() {
		// Setup to check if gutenberg is active
		$this->container->singleton( 'common.editor', 'Tribe__Editor' );
		$this->container->singleton( 'common.editor.utils', 'Tribe__Editor__Utils' );

		if ( ! tribe( 'common.editor' )->should_load_blocks() ) {
			return;
		}

		$this->container->singleton( 'common.editor.assets', 'Tribe__Editor__Assets', array( 'register' ) );

		$this->hook();

		// Initialize the correct Singletons
		tribe( 'common.editor.assets' );
	}

	/**
	 * Any hooking any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since TBD
	 *
	 */
	protected function hook() {

	}

	/**
	 * Binds and sets up implementations at boot time.
	 *
	 * @since TBD
	 */
	public function boot() {
		// no ops
	}
}
