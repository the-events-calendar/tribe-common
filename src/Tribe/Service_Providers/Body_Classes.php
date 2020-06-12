<?php

namespace Tribe\Service_Providers;

/**
 * Class Body_Classes
 *
 * @since TBD
 *
 * Handles the registration and creation of our async process handlers.
 */
class Body_Classes extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		tribe_singleton( 'body-classes', '\Tribe\Body_Classes' );

		/**
		 * Allows plugins to hook into the register action to register views, etc
		 *
		 * @since TBD
		 *
		 * @param Tribe\Service_Providers\Dialog $dialog
		 */
		do_action( 'tribe_body_classes_register', $this );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	private function hooks() {
		add_action( 'body_class', [ $this, 'add_body_classes' ] );

		/**
		 * Allows plugins to hook into the hooks action to register their own hooks
		 *
		 * @since TBD
		 *
		 * @param Tribe\Service_Providers\Dialog $dialog
		 */
		do_action( 'tribe_body_classes_hooks', $this );
	}

	/**
	 * Undocumented function
	 *
	 * @since TBD
	 *
	 * @param array $classes
	 * @return void
	 */
	public function add_body_classes( $classes = [] ) {
		/** @var Body_Classes $body_classes */
		$body_classes = tribe( 'body-classes' );

		return $body_classes->add_body_classes( $classes );
	  }
}
