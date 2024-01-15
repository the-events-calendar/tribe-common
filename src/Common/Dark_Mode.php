<?php
/**
 * Handle Dark Mode theming.
 *
 * @since   TBD
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

use \Tribe__Main;

/**
 * Class Dark_Mode.
 *
 * @since   TBD
 *
 * @package TEC\Common;
 */
class Dark_Mode extends Contracts\Service_Provider {

	/**
	 * The slug used for settings and customizer controls.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $slug = 'dark_mode';

	/**
	 * The default value for the setting(s).
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $default = 'default';

	/**
	 * The string used for the "dark mode" setting value.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $dark_setting = 'dark';

	/**
	 * {@inheritDoc}
	 *
	 * @since TBD
	 */
	public function register() {
		add_action( 'customize_save_after', [ $this, 'save_customizer_control' ] );

		add_filter( 'tribe_customizer_section_global_elements_default_settings', [ $this, 'add_customizer_defaults' ] );
		add_filter( 'tribe_customizer_section_global_elements_content_settings', [ $this, 'add_customizer_content' ] );
	}

	/**
	 * Get the normalized value of the setting.
	 * The option (via Event Settings) overrides the Customizer setting.
	 * We handle changing via the Customizer in the `save_customizer_control` method.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_value(): string {
		return tribe_get_option( 'dark_mode', static::$default );
	}

	/**
	 * Handles the value logic around saving the setting.
	 *
	 * @since TBD
	 */
	public function save_setting(): void {}

	/**
	 * Handles the value logic around saving the customizer control.
	 *
	 * @since TBD
	 *
	 * @param \WP_Customize_Manager $manager The customizer manager.
	 */
	public function save_customizer_control( $manager ): void {}

	/**
	 * Adds the default value for the "dark mode" setting to the customizer.
	 *
	 * @since TBD
	 *
	 * @param array $defaults The defaults for the global_elements section.
	 *
	 * @return array
	 */
	public function add_customizer_defaults( $defaults ): array {
		$defaults['dark_mode'] = static::$default;

		return $defaults;
	}

	/**
	 * Adds the default value for the "dark mode" setting to the customizer.
	 *
	 * @since TBD
	 *
	 * @param array $content The content for the global_elements section.
	 *
	 * @return array
	 */
	public function add_customizer_content_controls( $content ): array {
		Tribe__Main::array_insert_after_key(
			'stylesheet_mode',
			$content,
			[
				'dark_mode',
				[
					'label'       => esc_html__( 'Dark Mode', 'tribe-common' ),
					'description' => esc_html__( 'Choose whether to use dark mode for the site.', 'tribe-common' ),
					'type'        => 'select',
					'choices'     => [
						static::$default      => esc_html__( 'Default', 'tribe-common' ),
						static::$dark_setting => esc_html__( 'Dark', 'tribe-common' ),
					],
					'priority'    => 10,
				]
			]
		);

		return $content;
	}

	/**
	 * Adds the content settings for the live refresh and sanitization.
	 *
	 * @since TBD
	 *
	 * @param array $content The content for the global_elements section.
	 */
	public function add_customizer_content_settings( $content ): array {
		$content['dark_mode'] = [
			'sanitize_callback'    => 'sanitize_key',
			'sanitize_js_callback' => 'sanitize_key',
			'transport'            => 'postMessage',
		];

		return $content;
	}
}
