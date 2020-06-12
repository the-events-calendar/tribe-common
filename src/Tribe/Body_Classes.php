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
	 * Stores all the admin classes.
	 * In the format: ['class' => true, 'class => false ]
	 *
	 * @var array
	 */
	protected $admin_classes = [];


	/**
	 * Queue-aware method to get the classes array.
	 *
	 * @since TBD
	 *
	 * @param string $queue The queue we want to get 'admin', 'display', 'all'
	 * @return array
	 */
	public function get_classes_for_queue( $queue = 'display' ) {
		switch( $queue ) {
			case 'admin':
				return $this->admin_classes;
				break;
			case 'all':
				return array_merge( $this->classes, $this->admin_classes );
				break;
			default:
				return $this->classes;
				break;
		}
	}

	/**
	 * Returns the array of classes to add.
	 *
	 * @since TBD
	 *
	 * @param string $queue The queue we want to get 'admin', 'display', 'all'
	 * @return array
	 */
	public function get_classes( $queue = 'display' ) {
		return $this->get_classes_for_queue( $queue );
	}

	/**
	 * Returns the array of classnames to add
	 *
	 * @since TBD
	 *
	 * @param string $queue The queue we want to get 'admin', 'display', 'all'
	 * @return array
	 */
	public function get_class_names( $queue = 'display' ) {
		$classes = $this->get_classes_for_queue( $queue );

		return array_keys(
			array_filter(
				$classes,
				function( $v ) {
					return $v;
				},
				ARRAY_FILTER_USE_KEY
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
	 * @param string $queue The queue we want to check 'admin', 'display', 'all'
	 * @return boolean
	 */
	public function class_exists( $class, $queue = 'display' ) {
		$classes = $this->get_classes_for_queue( $queue );

		return array_key_exists( $class, $classes );
	}

	/**
	 * Checks if a class is in the queue and going to be added.
	 *
	 * @since TBD
	 *
	 * @param string $class The class we are checking for.
	 * @param string $queue The queue we want to check 'admin', 'display', 'all'
	 * @return boolean
	 */
	public function class_is_enqueued( $class, $queue = 'display' ) {
		$classes = $this->get_classes_for_queue( $queue );
		if ( ! $this->class_exists( $class ) ) {
			return false;
		}

		return $classes[ $class ];
	}

	/**
	 * Dequeues a class.
	 *
	 * @since TBD
	 *
	 * @param string $class
	 * @param string $queue The queue we want to alter 'admin', 'display', 'all'
	 * @return void|false
	 */
	public function dequeue_class( $class, $queue = 'display' ) {
		$classes = $this->get_classes_for_queue( $queue );
		if ( ! $this->class_exists( $class ) ) {
			return false;
		}

		$classes[ $class ] = false;
	}

	/**
	 * Enqueues a class.
	 *
	 * @since TBD
	 *
	 * @param string $class
	 * @param string $queue The queue we want to alter 'admin', 'display', 'all'
	 * @return void|false
	 */
	public function enqueue_class( $class, $queue = 'display' ) {
		$classes = $this->get_classes_for_queue( $queue );
		if ( ! $this->class_exists( $class ) ) {
			return false;
		}

		$classes[ $class ] = true;
	}

	/**
	 * Add a single class to the queue.
	 *
	 * @since TBD
	 *
	 * @param string $class The class to add.
	 * @param string $queue The queue we want to alter 'admin', 'display', 'all'
	 * @return void
	 */
	public function add_class( $class, $queue = 'display' ) {
		if ( empty( $class ) ) {
			return;
		}

		if ( is_array( $class ) ) {
			$this->add_classes( $class, $queue );
		} elseif ( $this->should_add_body_class_to_queue( $class, $queue ) ) {

			$class   = sanitize_html_class( $class );

			if ( 'admin' !== $queue ) {
				$classes = $this->get_classes_for_queue();
				$classes[ $class ] = true ;
			}

			if ( 'display' !== $queue ) {
				$classes = $this->get_classes_for_queue( 'admin' );
				$classes[ $class ] = true ;
			}

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
	public function add_classes( array $classes, $queue = 'display' ) {
		$classes = array_map( 'sanitize_html_class', $classes );

		foreach ( $classes as $key => $value ) {
			// If the classes are passed as class => bool, only add ones set to true.
			if ( ! is_string( $value ) && false !== $value  ) {
				$this->add_class( $key, $queue );
			} else {
				$this->add_class( $value, $queue );
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
	public function remove_class( $class, $queue = 'display' ) {
		$classes = $this->get_classes_for_queue( $queue );

		if ( 'admin' !== $queue ) {
			$this->classes = array_filter(
				$this->classes,
				function( $k ) use ( $class ) {
					return $k !== sanitize_html_class( $class );
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		if ( 'display' !== $queue ) {
			$this->admin_classes = array_filter(
				$this->admin_classes,
				function( $k ) use ( $class ) {
					return $k !== sanitize_html_class( $class );
				},
				ARRAY_FILTER_USE_KEY
			);
		}
	}

	/**
	 * Remove an array of classes from the queue.
	 *
	 * @since TBD
	 *
	 * @param array<string> $classes The classes to remove.
	 * @return void
	 */
	public function remove_classes( array $classes, $queue = 'display' ) {
		if ( empty( $classes ) || ! is_array( $classes) ) {
			return;
		}

		foreach ( $classes as $class ) {
			$this->remove_class( $class, $queue );
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
		// Make sure they should be added.
		if( ! $this->should_add_body_classes( $this->get_class_names(), $classes ) ) {
			return $classes;
		}

		return array_merge( $classes, $this->get_class_names() );
	}

	/**
	 * Adds the enqueued classes to the body class array.
	 *
	 * @since TBD
	 *
	 * @param array<string> $classes An array of body class names.
	 * @return void
	 */
	private function add_admin_body_classes( $classes = [] ) {
		// Make sure they should be added.
		if( ! $this->should_add_body_classes( $this->get_class_names( 'admin' ), $classes, 'admin' ) ) {
			return $classes;
		}

		return array_merge( $classes, $this->get_class_names( 'admin' ) );
	}

	/**
	 * Should a individual class be added to the queue.
	 *
	 * @since TBD
	 *
	 * @param string $class The body class we wish to add.
	 * @return boolean
	 */
	private function should_add_body_class_to_queue( string $class, $queue = 'display' ) {
		global $post;
		// default to false!
		$add = false;
		// If we are doing an event query, or on an event single, set to true.
		if (
			tribe_is_event_query()
			|| ( $post instanceof \WP_Post && has_shortcode( $post->post_content, 'tribe_events' ) )
		) {
			$add = true;
		}

		if (
			'admin' === $queue
			&& ! is_admin()
		) {
			$add = false;
		}

		/**
		 * Filter whether to add the body class to the queue or not.
		 *
		 * @since TBD
		 *
		 * @param boolean Whether to add the class to the queue or not.
		 * @param array $class The array of body class names to add.
		 */
		return apply_filters( 'tribe_should_add_body_class_to_queue', $add, $class, $queue );
	}

	/**
	 * Logic for whether the body classes, as a whole, should be added.
	 *
	 * @since TBD
	 *
	 * @param array $add_classes      An array of body class names to add.
	 * @param array $existing_classes An array of existing body class names from WP.
	 * @return boolean
	 */
	private function should_add_body_classes( array $add_classes, array $existing_classes, $queue = 'display' ) {
		global $post;
		// default to false!
		$add = false;
		// If we are doing an event query, or on an event single, set to true.
		if (
			tribe_is_event_query()
			|| ( $post instanceof \WP_Post && has_shortcode( $post->post_content, 'tribe_events' ) )
		) {
			$add = true;
		}

		if (
			'admin' === $queue
			&& ! is_admin()
		) {
			$add = false;
		}

		/**
		 * Filter whether to add tribe body classes or not.
		 *
		 * @since TBD
		 *
		 * @param boolean Whether to add classes or not.
		 * @param array $classes The array of body class names to add.
		 */
		return apply_filters( 'tribe_should_add_body_classes', $add, $add_classes, $existing_classes, $queue );
	}
}
