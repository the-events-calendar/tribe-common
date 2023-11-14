<?php

/**
 * Base Plugin register
 *
 * Register all plugins to Dependency Class
 *
 * @package Tribe
 * @since 4.9
 *
 */
abstract class Tribe__Abstract_Plugin_Register {

	/**
	 * The absolute path to the plugin file, the one that contains the plugin header.
	 *
	 * @var string
	 */
	protected $base_dir;

	/**
	 * @var string
	 */
	protected $main_class;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @since 4.9.17
	 *
	 * @var array
	 */
	protected $classes_req = [];

	/**
	 * @var array
	 */
	protected $dependencies = [
		'parent-dependencies' => [],
		'co-dependencies'     => [],
		'addon-dependencies'  => [],
	];

	/**
	 * Registers a plugin with dependencies
	 */
	public function register_plugin() {
		tribe_register_plugin(
			$this->base_dir,
			$this->main_class,
			$this->version,
			$this->classes_req,
			$this->dependencies
		);
	}
}