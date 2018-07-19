<?php

abstract class Tribe__Repository implements Tribe__Repository__Interface {
	/**
	 * @var array
	 */
	protected $read_schema = array();

	/**
	 * @var array
	 */
	protected $create_schema = array();

	/**
	 * @var array
	 */
	protected $default_args = array( 'post_type' => 'post' );

	/**
	 * @var array A map that will be used to redirect calls from the
	 *            magic `__call` method to the right sub-repository.
	 *            The map has the shape [ <method> => <sub_repo_method> ];
	 *            e.g. `[ 'by_foo' => 'fetch' ]`
	 *
	 * @see Tribe__Repository::__call
	 */
	protected $__call_map = array();

	/**
	 * {@inheritdoc}
	 */
	public function update( Tribe__Repository__Read_Interface $read = null ) {
		$read       = null !== $read ? $read : $this->fetch();
		$post_types = (array) Tribe__Utils__Array::get( $this->default_args, 'post_type', array() );

		return new Tribe__Repository__Update( $read, $post_types );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_args() {
		return $this->default_args;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_default_args( array $default_args ) {
		$this->default_args = $default_args;
	}

	/**
	 * Returns the value of a protected property.
	 *
	 * @since TBD
	 *
	 * @param string $name
	 *
	 * @return mixed|null
	 * @throws Tribe__Repository__Usage_Error If trying to access a non defined property.
	 */
	public function __get( $name ) {
		if ( ! property_exists( $this, $name ) ) {
			throw Tribe__Repository__Usage_Error::because_property_is_not_defined( $name, $this );
		}

		return $this->{$name};
	}

	/**
	 * Magic method to set protected properties.
	 *
	 * @since TBD
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws Tribe__Repository__Usage_Error As properties have to be set extending
	 * the class, using setter methods or via constructor injection
	 */
	public function __set( $name, $value ) {
		throw Tribe__Repository__Usage_Error::because_properties_should_be_set_correctly( $name, $this );
	}

	/**
	 * Whether the class has a property with the specific name or not.
	 *
	 * @since TBD
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return property_exists( $this, $name ) && isset( $this->{$name} );
	}

	/**
	 * {@inheritdoc}
	 */
	public function where( $key, $value ) {
		$read_repository = $this->fetch();
		$call_args = func_get_args();

		return call_user_func_array( array( $read_repository, 'where' ), $call_args );
	}

	/**
	 * {@inheritdoc}
	 */
	public function fetch() {
		return new Tribe__Repository__Read(
			$this->read_schema,
			tribe()->make( 'Tribe__Repository__Query_Filters' ),
			$this->default_args,
			$this
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function by( $key, $value ) {
		$read_repository = $this->fetch();
		$call_args = func_get_args();

		return call_user_func_array( array( $read_repository, 'by' ), $call_args );
	}

	/**
	 * Redirects calls to sub-repositories.
	 *
	 * @since TBD
	 *
	 * @param       string $name
	 * @param array        $args
	 *
	 * @return mixed A sub-repository instance
	 * @throws Tribe__Repository__Usage_Error If no interface defines the method
	 *                                        and the method is not defined in the
	 *                                        `call_map`
	 */
	public function __call( $name, array $args ) {
		if ( method_exists( 'Tribe__Repository__Read_Interface', $name ) ) {
			$read_repository = $this->fetch();

			return call_user_func_array( array( $read_repository, $name ), $args );
		}

		// @todo add Create repositories methods here

		$sub_repo_builder = Tribe__Utils__Array::get( $this->__call_map, $name, false );

		if ( false !== $sub_repo_builder ) {
			$sub_repository = $this->{$sub_repo_builder};

			return call_user_func_array( array( $sub_repository, $name ), $args );
		}

		throw Tribe__Repository__Usage_Error::because_the_called_method_was_not_found( $name, $this );
	}
}
