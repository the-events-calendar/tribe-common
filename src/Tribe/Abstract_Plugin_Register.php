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
	protected $main_class;
	protected $version;
	protected $dependencies = array(
		'parent-dependencies' => array(),
		'co-dependencies' => array(),
		'addon-dependencies' => array(),
	);

	/**
	 * Registers a plugin with dependencies
	 */
	public function register_plugin() {
		return tribe_register_plugin(
			$this->base_dir,
			$this->main_class,
			$this->version,
			array(),
			$this->dependencies
		);
	}

	/**
	 * Returns whether or not the dependencies have been met
	 *
	 * This is basically an aliased function - register_plugins, upon
	 * second calling, returns whether or not a plugin should load.
	 */
	public function has_valid_dependencies() {
		return $this->register_plugin();
	}
}