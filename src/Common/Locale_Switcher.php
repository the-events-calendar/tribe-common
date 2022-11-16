<?php
/**
 * Handle the switching of the site locale with awareness of the plugin's context.
 *
 * @since   TBD
 *
 * @package TEC\Common;
 */

namespace TEC\Common;

/**
 * Class Locale_Switcher.
 *
 * @since   TBD
 *
 * @package TEC\Common;
 */
class Locale_Switcher {
	/**
	 * The override locale that should be used.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $override_locale = '';
	/**
	 * Whether the locale has been switched or not.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	private $is_locale_switched;

	/**
	 * Switches the locale to the one specified.
	 *
	 * @since TBD
	 *
	 * @param string $locale The locale to switch to.
	 *
	 * @return bool Whether the locale was switched or not; this is the WordPress function return value.
	 */
	public function switch_to_locale( string $locale ) {
		$this->override_locale = $locale;

		// During the following call to `switch_to_locale` the plugins will determine locale using this filter.
		add_filter( 'plugin_locale', [ $this, 'override_locale' ] );
		// Code executing after this might restore the locale, so we need to keep track of the switch.
		$this->is_locale_switched = true;

		return switch_to_locale( $locale );
	}

	/**
	 * A proxy method to return the current override locale if set, or the input locale otherwise.
	 *
	 * @since TBD
	 *
	 * @param string $locale The locale to override.
	 *
	 * @return string The overridden locale.
	 */
	public function override_locale( $locale ) {
		return $this->override_locale ?: $locale;
	}

	/**
	 * Returns whether the locale has been switched or not.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the locale has been switched or not.
	 */
	public function is_locale_switched(): bool {
		return is_locale_switched() || $this->is_locale_switched;
	}

	/**
	 * Restored the locale to the previous one and removes the class filters.
	 *
	 * @since TBD
	 *
	 * @return false|string The restored locale string on success, `false` if there is no locale to restore.
	 */
	public function restore_previous_locale() {
		remove_filter( 'plugin_locale', [ $this, 'override_locale' ] );
		$this->override_locale = '';

		// This call will be a no-op if the locale has not been already restored.
		return restore_previous_locale();
	}
}
