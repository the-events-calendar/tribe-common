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
	 * {@inheritdoc}
	 */
	public function fetch() {
		return new Tribe__Repository__Read(
			$this->read_schema,
			tribe()->make( 'Tribe__Repository__Query_Filters' ),
			$this->default_args
		);
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
	 * @param mixed $value
	 *
	 * @throws Tribe__Repository__Usage_Error As properties have to be set extending
	 * the class, using setter methods or via constructor injection
	 */
	public function __set( $name, $value ) {
		throw Tribe__Repository__Usage_Error::because_properties_should_be_set_correctly( $name, $this );
	}

	/**
	 * Whether the class as a property with the specific name or not.
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
	}
}
