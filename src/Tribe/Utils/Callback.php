<?php

class Tribe__Utils__Callback {

	/**
	 * Where we store all the Callbacks to allow removing of hooks
	 *
	 * @since  TBD
	 *
	 * @var array
	 */
	public $items = array();

	/**
	 * The Prefix we use for the Overloading replacement
	 *
	 * @since  TBD
	 *
	 * @var string
	 */
	protected $prefix = 'callback_';

	/**
	 * Returns a callable for on this class that doesn't exist, but passes in the Key for Di52 Slug and it's method
	 * and arguments. It will relayed via overloading __call() on this same class.
	 *
	 * The lambda function suitable to use as a callback; when called the function will build the implementation
	 * bound to `$classOrInterface` and return the value of a call to `$method` method with the call arguments.
	 *
	 * @since  TBD
	 *
	 * @param string $slug                   A class or interface fully qualified name or a string slug.
	 * @param string $method                 The method that should be called on the resolved implementation with the
	 *                                       specified array arguments.
	 *
	 * @return array The callable
	 */
	public function get( $slug, $method ) {
		$container = Tribe__Container::init();
		$arguments = func_get_args();
		$is_empty = 2 === count( $arguments );

		// Remove Slug and Method
		array_shift( $arguments );
		array_shift( $arguments );

		$item = (object) array(
			'slug' => $slug,
			'method' => $method,
			'arguments' => $arguments,
			'is_empty' => $is_empty,
		);

		$key = md5( json_encode( $item ) );

		// Prevent this from been reset
		if ( isset( $this->items[ $key ] ) ) {
			return $this->items[ $key ];
		}

		$item->callback = $container->callback( $item->slug, $item->method );

		$this->items[ $key ] = $item;

		return array( $this, $this->prefix . $key );
	}

	/**
	 * Returns the Value passed as a simple Routing method for tribe_callback_return
	 *
	 * @since  TBD
	 *
	 * @param  mixed  $value  Value to be Routed
	 *
	 * @return mixed
	 */
	public function return_value( $value ) {
		return $value;
	}

	/**
	 * Calls the Lambda function provided by Di52 to allow passing of Params without having to create more
	 * methods into classes for simple callbacks that will only have a pre-determined value.
	 *
	 * @since  TBD
	 *
	 * @param string $slug                   A class or interface fully qualified name or a string slug.
	 * @param string $method                 The method that should be called on the resolved implementation with the
	 *                                       specified array arguments.
	 *
	 * @return mixed  The Return value used
	 */
	public function __call( $method, $args ) {
		$key = str_replace( $this->prefix, '', $method );

		if ( ! isset( $this->items[ $key ] ) ) {
			return false;
		}

		$item = $this->items[ $key ];

		// Allow for previous compatibility with tribe_callback
		if ( ! $item->is_empty ) {
			$args = $item->arguments;
		}

		return call_user_func_array( $item->callback, $args );
	}
}