<?php
/**
 * Handles loading feature flags and other configuration values.
 *
 * @since   TBD
 *
 * @package TEC\Common\Configuration;
 */

namespace TEC\Common\Configuration;

/**
 * Class Configuration.
 *
 * @since   TBD
 *
 * @package TEC\Common\Configuration;
 */
class Configuration implements Configuration_Provider_Interface {
	public function __construct(Configuration_Loader $loader) {
		$this->loader = $loader;
	}

	public function all(): array {
		$configs = [];
		foreach ($this->loader->all() as $provider) {
			$configs = array_merge( $configs,$provider->all());
		}

		return $configs;
	}

	public function get( $key ) {
		foreach ($this->loader->all() as $provider) {
			if($provider->has($key)) {
				return $provider->get($key);
			}
		}

		return null;
	}

	public function has( $key ):bool {
		foreach ($this->loader->all() as $provider) {
			if($provider->has($key)) {
				return true;
			}
		}

		return false;
	}
}