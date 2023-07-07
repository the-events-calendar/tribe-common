<?php
/**
 * Handles loading configuration services.
 *
 * @since 5.1.3
 *
 * @package TEC\Common\Configuration;
 */

namespace TEC\Common\Configuration;

/**
 * Class Configuration_Loader.
 *
 * @since 5.1.3
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
	 * @since 5.1.3
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
	 * @since 5.1.3
	 *
	 * @return Configuration_Provider_Interface[]
	 */
	public function all(): array {
		return self::$providers;
	}

	/**
	 * Remove the providers.
	 *
	 * @since 5.1.3
	 *
	 * @return $this
	 */
	public function reset(): self {
		self::$providers = [];

		return $this;
	}
}
