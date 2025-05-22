<?php
/**
 * Class that holds some data functions for the Wizard.
 *
 * @since 6.7.0
 */

namespace TEC\Common\Admin\Onboarding;

/**
 * Class Data
 *
 * This class is used to store and retrieve the wizard settings.
 * These work much the same as tribe_settings(), but are specifically for the onboarding wizard.
 *
 * @since 6.7.0
 * @package TEC\Common\Admin\Onboarding
 */
abstract class Abstract_Data {

	/**
	 * The option name for the wizard settings.
	 *
	 * @since 6.7.0
	 *
	 * @var string
	 */
	protected const OPTION_NAME = 'tec_onboarding_wizard_data';

	/**
	 * Get the saved wizard settings.
	 *
	 * @since 6.7.0
	 *
	 * @return array
	 */
	public function get_wizard_settings() {
		return get_option( static::OPTION_NAME, [] );
	}

	/**
	 * Update the wizard settings.
	 *
	 * @since 6.7.0
	 *
	 * @param array $settings The settings to update.
	 */
	public function update_wizard_settings( $settings ): bool {
		return update_option( static::OPTION_NAME, $settings );
	}

	/**
	 * Get a specific wizard setting by key.
	 *
	 * @since 6.7.0
	 *
	 * @param string $key           The setting key.
	 * @param mixed  $default_value The default value.
	 *
	 * @return mixed
	 */
	public function get_wizard_setting( $key, $default_value = null ) {
		$settings = $this->get_wizard_settings();

		return $settings[ $key ] ?? $default_value;
	}

	/**
	 * Update a specific wizard setting.
	 *
	 * @since 6.7.0
	 *
	 * @param string $key   The setting key.
	 * @param mixed  $value The setting value.
	 */
	public function update_wizard_setting( $key, $value ) {
		$settings         = $this->get_wizard_settings();
		$settings[ $key ] = $value;

		$this->update_wizard_settings( $settings );
	}
}
