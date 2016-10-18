<?php


abstract class tad_DI52_ReferredArgValue {

	protected $alias;
	protected $container;

	public function __construct( $alias, tad_DI52_Container $container ) {
		$this->alias = $alias;
		$this->container = $container;
	}

	abstract public function getValue();
}
