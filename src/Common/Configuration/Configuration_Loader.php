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

	public function add( Configuration_Provider_Interface $provider ):self {
		if(is_callable([$provider, 'register'])) {
			$provider->register();
		}
		self::$providers[] = $provider;

		return $this;
	}

	/**
	 * @return Configuration_Provider_Interface[]
	 */
	public function all():array {
		return self::$providers;
	}

}