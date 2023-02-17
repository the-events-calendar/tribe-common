<?php

namespace TEC\Common\Compatibility\Pigeon;

use StellarWP\ContainerContract\ContainerInterface;
use StellarWP\Pigeon\Pigeon;
use \tad_DI52_Container as DI52Container;

class Container extends DI52Container implements ContainerInterface {
	/**
	 * @var DI52Container
	 */
	protected $container;

	/**
	 * Container constructor.
	 *
	 * @param object $container The container to use.
	 */
	public function __construct( $container = null ) {
		$this->container = $container ?: new Pigeon();
	}

	/**
	 * @inheritDoc
	 */
	public function bind($classOrInterface, $implementation = null, ?array $afterBuildMethods = null) {
		$this->container->bind( $classOrInterface, $implementation, $afterBuildMethods );
	}

	/**
	 * @inheritDoc
	 */
	public function get( string $id ) {
		return $this->container->getVar( $id );
	}

	/**
	 * @return DI52Container
	 */
	public function get_container() {
		return $this->container;
	}

	/**
	 * @inheritDoc
	 */
	public function has( string $id ) {
		return (bool) $this->container->getVar( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function singleton( $classOrInterface, $implementation = null, array $afterBuildMethods = null ) {
		$this->container->singleton( $classOrInterface, $implementation, $afterBuildMethods );
	}

	/**
	 * Defer all other calls to the container object.
	 */
	public function __call( $name, $args ) {
		return $this->container->{$name}( ...$args );
	}
}