<?php

use TEC\Common\Contracts\Service_Provider;

class Tribe__Editor__Provider extends Service_Provider {

	/**
	 * Whether the service provider will be a deferred one or not.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function isDeferred() {
		return true;
	}

	/**
	 * Returns an array of the class or interfaces bound and provided by the service provider.
	 *
	 * @since TBD
	 *
	 * @return array<string> A list of fully-qualified implementations provided by the service provider.
	 */
	public function provides() {
		return [
			'editor',
			'editor.utils',
			'common.editor.configuration',
			'editor.assets',
		];
	}

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

		tribe_register_provider( Tribe\Editor\Compatibility::class );

		$this->container->singleton( 'editor.assets', 'Tribe__Editor__Assets', [ 'hook' ] );

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
		// Setup the registration of Blocks
		add_action( 'init', [ $this, 'register_blocks' ], 20 );
	}

	/**
	 * Prevents us from using `init` to register our own blocks, allows us to move
	 * it when the proper place shows up
	 *
	 * @since 4.8.2
	 *
	 * @return void
	 */
	public function register_blocks() {
		/**
		 * Internal Action used to register blocks for Events
		 *
		 * @since 4.8.2
		 */
		do_action( 'tribe_editor_register_blocks' );
	}
}
