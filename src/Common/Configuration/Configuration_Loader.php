<?php
/**
 * Handles loading configuration services.
 *
 * @since   TBD
 *
 * @package TEC\Common\Configuration;
 */

namespace TEC\Common\Configuration;

/**
 * Class Configuration_Loader.
 *
 * @since   TBD
 *
 * @package TEC\Common\Configuration;
 */
class Configuration_Loader {
	/**
	 * @var array<Configuration_Provider_Interface>
	 */
	protected static $providers = [];

	/**
	 * Add a var provider to the list of providers referenced when accessing a variable
	 * from within the Configuration object.
	 *
	 * @since TBD
	 *
	 * @param Configuration_Provider_Interface $provider
	 *
	 * @return $this
	 */
	public function add( Configuration_Provider_Interface $provider ): self {
		if ( is_callable( [ $provider, 'register' ] ) ) {
			$provider->register();
		}
		self::$providers[] = $provider;

		return $this;
	}

	/**
	 * Retrieve a list of all Configuration_Provider_Interface providers loaded.
	 *
	 * @since TBD
	 *
	 * @return Configuration_Provider_Interface[]
	 */
	public function all(): array {
		return self::$providers;
	}

	/**
	 * Remove the providers.
	 *
	 * @since TBD
	 *
	 * @return $this
	 */
	public function reset(): self {
		self::$providers = [];

		return $this;
	}
}