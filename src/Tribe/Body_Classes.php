<?php
/**
 * Class used to manage and body classes across our plugins.
 *
 * @since TBD
 */
namespace Tribe;

class Body_Classes {
	/**
	 * Stores all the classes.
	 * In the format: ['class' => true, 'class => false ]
	 *
	 * @var array
	 */
	protected $classes = [];

	/**
	 * Register our methods in the correct places.
	 *
	 * @since TBD
	 */
	public function __construct() {
		// Hook the actual adding of body classes.

	}

	/**
	 * Returns the array of classes to add.
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_classes() {
		return $this->classes;
	}

	/**
	 * Returns the array of classnames to add
	 *
	 * @since TBD
	 *
	 * @return array
	 */
	public function get_class_names() {
		return array_keys(
			array_filter(
				$this->classes,
				function( $v ) {
					return $v;
				}
			)
		);
	}

	/**
	 * Checks if a class is in the queue,
	 * wether it's going to be added or not.
	 *
	 * @since TBD
	 *
	 * @param string $class The class we are checking for.
	 * @return boolean
	 */
	public function class_exists( $class ) {
		return array_key_exists( $class, $this->classes );
	}

	/**
	 * Checks if a class is in the queue and going to be added.
	 *
	 * @since TBD
	 *
	 * @param string $class The class we are checking for.
	 * @return boolean
	 */
	public function class_is_enqueued( $class ) {
		if ( ! $this->class_exists( $class ) ) {
			return false;
		}

		return $this->classes[$class];
	}

	/**
	 * Dequeues a class.
	 *
	 * @since TBD
	 *
	 * @param string $class
	 * @return void|false
	 */
	public function dequeue_class( $class ) {
		if ( ! $this->class_exists( $class ) ) {
			return false;
		}

		$this->classes[$class] = false;
	}

	/**
	 * Enqueues a class.
	 *
	 * @since TBD
	 *
	 * @param string $class
	 * @return void|false
	 */
	public function enqueue_class( $class ) {
		if ( ! $this->class_exists( $class ) ) {
			return false;
		}

		$this->classes[$class] = true;
	}

	/**
	 * Add a single class to the queue.
	 *
	 * @since TBD
	 *
	 * @param string $class The class to add.
	 * @return void
	 */
	public function add_class( $class ) {
		if ( empty( $class ) ) {
			return;
		}

		if ( is_array( $class ) ) {
			$this->add_classes( $class );
		} else {
			$class = sanitize_html_class( $class );
			$this->classes[$class] = true ;
		}
	}

	/**
	 * Add an array of classes to the queue.
	 *
	 * @since TBD
	 *
	 * @param array<string> $class The classes to add.
	 * @return void
	 */
	public function add_classes( array $classes ) {
		$classes = array_map( 'sanitize_html_class', $classes );

		foreach ( $classes as $key => $value ) {
			// Just in case the classes are passed as class => bool, only add ones set to true.
			if ( ! is_string( $value ) && false !== $value  ) {
				$this->add_class( $key );
			} else {
				$this->add_class( $value );
			}
		}
	}

	/**
	 * Remove a single class from the queue.
	 *
	 * @since TBD
	 *
	 * @param string $class The class to remove.
	 * @return void
	 */
	public function remove_class( $class ) {
		$this->classes = array_filter(
			$this->classes,
			function( $v ) use ( $class ) {
				return $v !== sanitize_html_class( $class );
			}
		);
	}

	/**
	 * Remove an array of classes from the queue.
	 *
	 * @since TBD
	 *
	 * @param array<string> $classes The classes to remove.
	 * @return void
	 */
	public function remove_classes( array $classes ) {
		if ( empty( $classes ) || ! is_array( $classes) ) {
			return;
		}

		foreach ( $classes as $class ) {
			$this->remove_class( $class );
		}
	}

	/**
	 * Adds the enqueued classes to the body class array.
	 *
	 * @since TBD
	 *
	 * @param array<string> $classes An array of body class names.
	 * @return void
	 */
	private function add_body_classes( $classes = [] ) {
		return array_merge( $classes, $this->get_class_names() );
	}
}
