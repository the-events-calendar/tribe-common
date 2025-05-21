<?php
/**
 * Timezone list.
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */

namespace TEC\Common\Lists;

/**
 * Class Timezone
 *
 * @since 6.7.0
 *
 * @package TEC\Common\Lists
 */
class Timezone {


	/**
	 * Get list of timezones. Excludes manual offsets.
	 *
	 * Ruthlessly lifted in part from `wp_timezone_choice()`
	 *
	 * @todo Move this somewhere for reuse!
	 *
	 * @since 6.7.0
	 *
	 * @return array<string,string> The list of timezones.
	 */
	public static function get_timezone_list(): array {
		// phpcs:disable
		static $mo_loaded = false, $locale_loaded = null;
		$locale           = get_user_locale();
		$continents       = [
			'Africa',
			'America',
			'Antarctica',
			'Arctic',
			'Asia',
			'Atlantic',
			'Australia',
			'Europe',
			'Indian',
			'Pacific',
		];

		// Load translations for continents and cities.
		if ( ! $mo_loaded || $locale !== $locale_loaded ) {
			$locale_loaded = $locale ? $locale : get_locale();
			$mofile        = WP_LANG_DIR . '/continents-cities-' . $locale_loaded . '.mo';
			unload_textdomain( 'continents-cities', true );
			load_textdomain( 'continents-cities', $mofile, $locale_loaded );
			$mo_loaded = true;
		}

		$tz_identifiers = timezone_identifiers_list();
		$zonen          = [];

		foreach ( $tz_identifiers as $zone ) {
			// Sections: Continent/City/Subcity.
			$sections = substr_count( $zone, '/' ) + 1;
			$zone     = explode( '/', $zone );

			if ( ! in_array( $zone[0], $continents ) ) {
				continue;
			}

			// Skip UTC offsets.
			if ( $sections <= 1 ) {
				continue;
			}

			$assemble = [];

			if ( $sections > 0 ) {
				$assemble['continent'] = translate( str_replace( '_', ' ', $zone[0] ), 'continents-cities' );
			}

			if ( $sections > 1 ) {
				$assemble['city'] = translate( str_replace( '_', ' ', $zone[1] ), 'continents-cities' );
			}

			if ( $sections > 2 ) {
				$assemble['subcity'] = translate( str_replace( '_', ' ', $zone[2] ), 'continents-cities' );
			}

			if ( empty( $assemble ) ) {
				continue;
			}

			$zonen[] = $assemble;
			// phpcs:enable
		}

		$zones = [];
		foreach ( $continents as $continent ) {
			$zones[ $continent ] = [];
		}

		foreach ( $zonen as $zone ) {
			// Check if subcity is available (i.e. a state + city).
			if ( ! empty( $zone['subcity'] ) ) {
				$city    = str_replace( ' ', '_', $zone['city'] );
				$subcity = str_replace( ' ', '_', $zone['subcity'] );
				$key     = "{$zone['continent']}/{$city}/{$subcity}";
				$value   = "{$zone['city']} - {$zone['subcity']}";
			} else {
				// Format without subcity.
				$city  = str_replace( ' ', '_', $zone['city'] );
				$key   = "{$zone['continent']}/{$city}";
				$value = "{$zone['city']}";
			}

			// Format it as a new associative array.
			$zones[ $zone['continent'] ][ $key ] = $value;
		}

		$zones = array_filter( $zones );

		return apply_filters( 'tec_timezone_list', $zones );
	}
}
