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
 * Class Translations_Loader.
 *
 * @since   TBD
 *
 * @package TEC\Common;
 */
class Translations_Loader {
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
	protected $has_loaded_translations = false;

	/**
	 * A list of the text domains translations have been loaded for.
	 *
	 * @since TBD
	 *
	 * @var array<string>
	 */
	protected $loaded_domains = [];

	/**
	 * Switches the locale to the one specified.
	 *
	 * Note: the method will not check what the current locale is and will just load the
	 * translations specified. The burden of checking the current locale is on the caller.
	 *
	 * @since TBD
	 *
	 * @param string               $locale  The locale to switch to.
	 * @param array<string,string> $domains A map from text domains to the directory containing the translations.
	 *
	 * @return bool Whether the locale was switched or not.
	 */
	public function load( string $locale, array $domains = [] ): bool {
		if ( empty( $domains ) ) {
			return false;
		}

		/**
		 * Fires before the locale translations are loaded.
		 *
		 * @since TBD
		 *
		 * @param string        $locale  The locale that will be loaded.
		 * @param array<string> $domains The list of domains translations will be loaded for.
		 */
		do_action( 'tec_locale_translations_load_before', $locale, $domains );

		$this->has_loaded_translations = true;
		$this->override_locale = $locale;
		$this->loaded_domains = $domains;

		/*
		 * The `plugin_locale` filter will be applied in `load_plugin_textdomain()` to determine
		 * the language file to load.
		 */
		add_filter( 'plugin_locale', [ $this, 'override_locale' ] );

		$this->load_locale_translations( $domains, $locale );

		remove_filter( 'plugin_locale', [ $this, 'override_locale' ] );

		/**
		 * Fires after the locale translations are loaded.
		 *
		 * @since TBD
		 *
		 * @param string        $locale  The locale that has been loaded.
		 * @param array<string> $domains The list of domains translations have been loaded for.
		 */
		do_action( 'tec_locale_translations_load_after', $locale, $domains );

		return true;
	}

	/**
	 * A proxy method to return the current override locale if set, or the input locale otherwise.
	 *
	 * Used during filter application.
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
	public function has_loaded_translations(): bool {
		return $this->has_loaded_translations;
	}

	/**
	 * Restored the locale to the previous one and removes the class filters.
	 *
	 * @since TBD
	 *
	 * @return void Translations for each domain will be reloaded.
	 */
	public function restore() {
		if(!$this->has_loaded_translations){
			return;
		}

		$this->override_locale = '';

		/**
		 * Fires before the locale translations are restored.
		 *
		 * @since TBD
		 *
		 * @param array<string> $domains The list of domains translations will be loaded for.
		 */
		do_action( 'tec_locale_translations_restore_before', $this->loaded_domains );

		// Reload the translations using the currently determined locale.
		$this->load_locale_translations( $this->loaded_domains, determine_locale() );

		/**
		 * Fires after the locale translations are restored.
		 *
		 * @since TBD
		 *
		 * @param array<string> $domains The list of domains translations have been loaded for.
		 */
		do_action( 'tec_locale_translations_restore_after', $this->loaded_domains );
	}

	/**
	 * Load the translations for the map of domains for the current locale.
	 *
	 * @since TBD
	 *
	 * @param array<string,string> $domains A map from text domains to the directory containing the translations.
	 * @param string               $locale  The locale to load the translations for.
	 *
	 * @return void Translations for each domain will be loaded for the current plugin locale.
	 */
	protected function load_locale_translations( array $domains, string $locale ): void {
		global $l10n;

		if ( ! is_array( $l10n ) ) {
			$l10n = [];
		}

		foreach ( $domains as $domain => $lang_dir ) {
			unload_textdomain( $domain, true );

			if ( $locale === 'en_US' ) {
				// There is no `en_US` language pack since it's the default, no-op the translations.
				$l10n[ $domain ] = new \NOOP_Translations();
			} else {
				// Load the translations using the wrapper Common function.
				$dir = is_string( $lang_dir ) && ! empty( $lang_dir ) ? $lang_dir : false;
				\Tribe__Main::instance()->load_text_domain( $domain, $dir );
			}
		}
	}
}
