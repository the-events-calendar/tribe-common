<?php

namespace Tribe\Service_Providers;

use Tribe\Utils\Body_Classes as Body_Class_Object;

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
		tribe_singleton( Body_Class_Object::class, Body_Class_Object::class );

		/**
		 * Allows plugins to hook into the register action to register views, etc.
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
		add_filter( 'body_class', [ $this, 'add_body_classes' ] );
		add_filter( 'admin_body_class', [ $this, 'add_admin_body_classes' ] );

		/**
		 * Allows plugins to hook into the hooks action to register their own hooks.
		 *
		 * @since TBD
		 *
		 * @param Tribe\Service_Providers\Dialog $dialog
		 */
		do_action( 'tribe_body_classes_hooks', $this );
	}

	/**
	 * Hook in and add FE body classes.
	 *
	 * @since TBD
	 *
	 * @param array $classes An array of body class names.
	 * @return array The modified array of body class names.
	 */
	public function add_body_classes( $classes = [] ) {
		/** @var Body_Class_Object $body_classes */
		$body_classes = tribe( Body_Class_Object::class );

		return $body_classes->add_body_classes( $classes );
	}

	/**
	 * Hook in and add admin body classes.
	 *
	 * @since TBD
	 *
	 * @param array $classes An array of body class names.
	 * @return array The modified array of body class names.
	 */
	public function add_admin_body_classes( $classes = [] ) {
		/** @var Body_Class_Object $body_classes */
		$body_classes = tribe( Body_Class_Object::class );

		return $body_classes->add_admin_body_classes( $classes );
	}

}
