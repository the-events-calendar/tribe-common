<?php
/**
 * Utility functions for safely handling locale.
 *
 * @package Tribe\Traits
 *
 * @since TBD
 */

namespace Tribe\Traits;

/**
 * Trait With_Locale
 *
 * @since   TBD
 *
 * @package Tribe\Traits
 */
trait With_Locale {

	public static $original = [];

	public static $current = [];

	/**
	 * Grabs the original locale, stores it as an array for later use,
	 * then returns it for immediate use.
	 *
	 * @since TBD
	 *
	 * @return array|boolean The locale data as an array, false if nothing found.
	 */
	private static function get_original_locale() {
		$original    = explode( ";", setlocale( LC_ALL, 0 ) );
		$placeholder = [];

		/* Break this up into an array. This serves two purposes:
		 * 1) It's easier to grab one piece if we don't need to change _everything_
		 * 2) It's easier to parse for our reset, where we can't just pass the string back.
		 */
		array_walk(
			$original,
			function ( &$val, $key ) use ( &$placeholder ) {
				$pieces                    = explode( '=', $val );
				$placeholder[ $pieces[0] ] = empty( $pieces[1] ) ? '' : $pieces[1];
			}
		);

		// Don't overwrite stored value if we get nothing.
		if ( empty( $placeholder ) ) {
			return false;
		}

		static::$original = $placeholder;

		return static::$original;
	}

	/**
	 * Resets the locale to the stored original locale
	 *
	 * @since TBD
	 *
	 * @return boolean True, or false if there is no "original" to restore.
	 */
	private static function restore_original_locale() {
		// Nothing stored, bail.
		if ( empty( static::$original ) ) {
			return false;
		}

		foreach ( static::$original as $locale_setting ) {
			if ( strpos( $locale_setting, "=" ) !== false ) {
			  list ( $category, $locale ) = explode( "=", $locale_setting );
			}
			else {
			  $category = LC_ALL;
			  $locale   = $locale_setting;
			}

			setlocale( $category, $locale );
		  }

		  // Empty current, as it no longer applies.
		  static::$current = [];

		  return true;
	}

	/**
	 * Sets the current locale for use.
	 *
	 * @since TBD
	 *
	 * @param string|array<string> $category The category (string) or categories (array) we want to set.
	 * @param string|array<string> $locale   The locale we want to use.
	 *                                       Pass an array if you have multiple possible values for the same locale.
	 * @return void
	 */
	private static function set_current_locale( $category = 'LC_ALL', $locale = '' ) {
		// Before we set, save the original value.
		if ( empty( static::$original ) ) {
			static::get_original_locale();
		}

		if ( ! is_array( $category ) ) {
			static::$current = setlocale( $category, $locale );
		} else {
			foreach( $category as $cat ) {
				setlocale( $cat, $locale );
			}

			static::$current = setlocale( 0, $locale );
		}
	}

	public function set_locale( $locale, $category = 'LC_ALL' ) {
		// Don't use this w/o a locale set!
		if ( empty( $locale ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				"Do not use this function to reset the value! Use reset_locale().",
				'TBD'
			);

			return false;
		}

		if (is_numeric( $locale ) && 0 === (int) $locale) {
			_doing_it_wrong(
				__FUNCTION__,
				"Do not use this function to get the values! Use get_locale().",
				'TBD'
			);

			return false;
		}

		return static::set_current_locale( $category, $locale );
	}

	public static function reset_locales() {
		return static::restore_original_locale();
	}

	public static function get_locale( $category = 'LC_ALL' ) {
		if ( empty( static::$current ) ) {
			return setlocale( 0, $category );
		}

		return static::$current;
	}

	public static function get_local_number_formats( $locale = '' ) {
		// Just get the default.
		if ( empty( $locale ) ) {
			return localeconv();
		}

		// Temporarily set the locale while we get the format info.
		static::set_current_locale(
			[
				'LC_MONETARY',
				'LC_NUMERIC',
			],
			$locale
		);

		$formats = localeconv();

		// Set things back the way they were.
		static::reset_locale();

		return $formats;
	}
}
