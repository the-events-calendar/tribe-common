<?php

namespace TEC\Common\Configuration;

class ConfigurationTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 */
	public function should_add_loader() {
		// Setup services.
		$loader = new Configuration_Loader();
		$loader->reset();
		$configuration = new Configuration( $loader );
		$provider      = $this->given_a_faux_configuration_provider();
		// Should be fine without a provider.
		$this->assertNull( $configuration->get( 'faux_val' ) );
		// Should be able to add and attempt access still.
		$loader->add( $provider );
		$this->assertNull( $configuration->get( 'faux_val' ) );

	}

	/**
	 * @test
	 */
	public function should_access_values() {
		// Setup services.
		$loader = new Configuration_Loader();
		$loader->reset();
		$configuration = new Configuration( $loader );
		$provider      = $this->given_a_faux_configuration_provider();

		// Setup a config value.
		$config_val          = mt_rand();
		$GLOBALS['faux_val'] = $config_val;

		// Verify we are pulling the value from source.
		$this->assertNull( $configuration->get( 'faux_val' ) );
		$loader->add( $provider );
		$this->assertEquals( $GLOBALS['faux_val'], $configuration->get( 'faux_val' ) );
		unset( $GLOBALS['faux_val'] );
		$this->assertNull( $configuration->get( 'faux_val' ) );
	}

	public function given_a_faux_configuration_provider() {
		return new class implements Configuration_Provider_Interface {
			public function get( string $key ) {
				if ( $this->has( $key ) ) {
					return $GLOBALS[ $key ];
				}
			}

			public function has( string $key ): bool {
				return isset( $GLOBALS[ $key ] );
			}

			public function all(): array {
				return $GLOBALS;
			}
		};
	}
}