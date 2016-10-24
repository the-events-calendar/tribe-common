<?php
if ( ! class_exists( 'Tribe__Container' ) ) {
	/**
	 * Class Tribe__Container
	 *
	 * Tribe Dependency Injection Container.
	 */
	class Tribe__Container extends tad_DI52_Container {

		/**
		 * @var Tribe__Container
		 */
		protected static $instance;

		/**
		 * @return Tribe__Container
		 */
		public static function instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

if ( ! function_exists( 'tribe_singleton' ) ) {
	/**
	 * Registers a class as a singleton.
	 *
	 * Each call to obtain an instance of this class made using the `tribe( $slug )` function
	 * will return the same instance; the instances are built just in time (if not passing an
	 * object instance or callback function) and on the first request.
	 * The container will call the class `__construct` method on the class (if not passing an object
	 * or a callback function) and will try to automagically resolve dependencies.
	 *
	 * Example use:
	 *
	 *      tribe_singleton( 'tec.admin.class', 'Tribe__Admin__Class' );
	 *
	 *      // some code later...
	 *
	 *      // class is built here
	 *      tribe( 'tec.admin.class' )->doSomething();
	 *
	 * Need the class built immediately? Build it and register it:
	 *
	 *      tribe_singleton( 'tec.admin.class', new Tribe__Admin__Class() );
	 *
	 *      // some code later...
	 *
	 *      tribe( 'tec.admin.class' )->doSomething();
	 *
	 * Need a very custom way to build the class? Register a callback:
	 *
	 *      tribe_singleton( 'tec.admin.class', array( Tribe__Admin__Class__Factory, 'make' ) );
	 *
	 *      // some code later...
	 *
	 *      tribe( 'tec.admin.class' )->doSomething();
	 *
	 * Or register the methods that should be called on the object after its construction:
	 *
	 *      tribe_singleton( 'tec.admin.class', 'Tribe__Admin__Class', array( 'hook', 'register' ) );
	 *
	 *      // some code later...
	 *
	 *      // the `hook` and `register` methods will be called on the built instance.
	 *      tribe( 'tec.admin.class' )->doSomething();
	 *
	 * The class will be built only once (if passing the class name or a callback function), stored
	 * and the same instance will be returned from that moment on.
	 *
	 * @param string                 $slug                The human-readable and catchy name of the class.
	 * @param string|object|callable $class               The full class name or an instance of the class
	 *                                                    or a callback that will return the instance of the class.
	 * @param array                  $after_build_methods An array of methods that should be called on
	 *                                                    the built object after the `__construct` method; the methods
	 *                                                    will be called only once after the singleton instance
	 *                                                    construction.
	 */
	function tribe_singleton( $slug, $class, array $after_build_methods = null ) {
		Tribe__Container::instance()->singleton( $slug, $class, $after_build_methods );
	}
}

if ( ! function_exists( 'tribe_register' ) ) {
	/**
	 * Registers a class.
	 *
	 * Each call to obtain an instance of this class made using the `tribe( $slug )` function
	 * will return a new instance; the instances are built just in time (if not passing an
	 * object instance, in that case it will work as a singleton) and on the first request.
	 * The container will call the class `__construct` method on the class (if not passing an object
	 * or a callback function) and will try to automagically resolve dependencies.
	 *
	 * Example use:
	 *
	 *      tribe_register( 'tec.some', 'Tribe__Some' );
	 *
	 *      // some code later...
	 *
	 *      // class is built here
	 *      $some_one = tribe( 'tec.some' )->doSomething();
	 *
	 *      // $some_two !== $some_one
	 *      $some_two = tribe( 'tec.some' )->doSomething();
	 *
	 * Need the class built immediately? Build it and register it:
	 *
	 *      tribe_register( 'tec.admin.class', new Tribe__Admin__Class() );
	 *
	 *      // some code later...
	 *
	 *      // $some_two === $some_one
	 *      // acts like a singleton
	 *      $some_one = tribe( 'tec.some' )->doSomething();
	 *      $some_two = tribe( 'tec.some' )->doSomething();
	 *
	 * Need a very custom way to build the class? Register a callback:
	 *
	 *      tribe_register( 'tec.some', array( Tribe__Some__Factory, 'make' ) );
	 *
	 *      // some code later...
	 *
	 *      // $some_two !== $some_one
	 *      $some_one = tribe( 'tec.some' )->doSomething();
	 *      $some_two = tribe( 'tec.some' )->doSomething();
	 *
	 * Or register the methods that should be called on the object after its construction:
	 *
	 *      tribe_singleton( 'tec.admin.class', 'Tribe__Admin__Class', array( 'hook', 'register' ) );
	 *
	 *      // some code later...
	 *
	 *      // the `hook` and `register` methods will be called on the built instance.
	 *      tribe( 'tec.admin.class' )->doSomething();
	 *
	 * @param string                 $slug                The human-readable and catchy name of the class.
	 * @param string|object|callable $class               The full class name or an instance of the class
	 *                                                    or a callback that will return the instance of the class.
	 * @param array                  $after_build_methods An array of methods that should be called on
	 *                                                    the built object after the `__construct` method; the methods
	 *                                                    will be called each time after the instance contstruction.
	 */
	function tribe_register( $slug, $class, array $after_build_methods = null ) {
		Tribe__Container::instance()->bind( $slug, $class, $after_build_methods );
	}
}

if ( ! function_exists( 'tribe' ) ) {
	/**
	 * Returns a ready to use instance of the requested class.
	 *
	 * Example use:
	 *
	 *      tribe_singleton( 'common.main', 'Tribe__Main');
	 *
	 *      // some code later...
	 *
	 *      tribe( 'common.main' )->do_something();
	 *
	 * @param string $slug_or_class Either the slug of a binding previously registered using
	 *                              `tribe_singleton` or `tribe_register` or the full class
	 *                              name that should be automagically created.
	 *
	 * @return mixed|object The instance of the requested class. Please note that the cardinality of
	 *                      the class is controlled registering it as a singleton using `tribe_singleton`
	 *                      or `tribe_register`.
	 */
	function tribe( $slug_or_class ) {
		return Tribe__Container::instance()->make( $slug_or_class );
	}
}

if ( ! function_exists( 'tribe_set_var' ) ) {
	/**
	 * Registers a value under a slug in the container.
	 *
	 * Example use:
	 *
	 *      tribe_set_var( 'tec.url', 'http://example.com' );
	 *
	 * @param string $slug  The human-readable and catchy name of the var.
	 * @param mixed  $value The variable value.
	 */
	function tribe_set_var( $slug, $value ) {
		$container = Tribe__Container::instance();
		$container->setVar( $slug, $value );
	}
}

if ( ! function_exists( 'tribe_get_var' ) ) {
	/**
	 * Returns the value of a registered variable.
	 *
	 * Example use:
	 *
	 *      tribe_set_var( 'tec.url', 'http://example.com' );
	 *
	 *      $url = tribe_get_var( 'tec.url' );
	 *
	 * @param string $slug    The slug of the variable registered using `tribe_set_var`.
	 * @param null   $default The value that should be returned if the variable slug
	 *                        is not a registered one.
	 *
	 * @return mixed Either the registered value or the default value if the variable
	 *               is not registered.
	 */
	function tribe_get_var( $slug, $default = null ) {
		$container = Tribe__Container::instance();

		try {
			$var = $container->getVar( $slug );
		} catch ( InvalidArgumentException $e ) {
			return $default;
		}

		return $var;
	}
}
