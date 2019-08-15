<?php

namespace Tribe\Service_Providers;

/**
 * Class Dialog
 *
 * @since TBD
 *
 * Handles the registration and creation of our async process handlers.
 */
class Dialog extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		tribe_singleton( 'dialog.view', \Tribe\Dialog\View::class );

		/**
		 * Allows plugins to hook into the register action to register views, etc
		 *
		 * @since TBD
		 *
		 * @param Tribe\Service_Providers\Dialog $dialog
		 */
		do_action( 'tribe_dialog_register', $this );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	private function hooks() {
		add_action( 'tribe_common_loaded', [ $this, 'add_dialog_assets' ] );
		/**
		 * Allows plugins to hook into the hooks action to register their own hooks
		 *
		 * @since TBD
		 *
		 * @param Tribe\Service_Providers\Dialog $dialog
		 */
		do_action( 'tribe_dialog_hooks', $this );
	}

	/**
	 * Register assets associated with dialog
	 *
	 * @since TBD
	 */
	public function add_dialog_assets() {
		$main = \Tribe__Main::instance();

		tribe_asset(
			$main,
			'tribe-dialog-css',
			'dialog.css',
			[],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ]
		);

		tribe_asset(
			$main,
			'mt-a11y-dialog',
			'vendor/mt-a11y-dialog/a11y-dialog.js',
			[ 'underscore', 'tribe-common' ],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ]
		);

		tribe_asset(
			$main,
			'tribe-dialog-js',
			'dialog.js',
			[ 'mt-a11y-dialog' ],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ]
		);

		/**
		 * Allows plugins to hook into the assets action to register their own assets
		 *
		 * @since TBD
		 *
		 * @param Tribe\Service_Providers\Dialog $dialog
		 */
		do_action( 'tribe_dialog_assets', $this );
	}
}
