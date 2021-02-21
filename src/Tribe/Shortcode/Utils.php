<?php
namespace Tribe\Shortcode;

/**
 * Class Utils.
 *
 * @since   TBD
 *
 * @package Tribe\Shortcode
 */
class Utils {
	/**
	 * Convert settings to a set of shortcode attributes.
	 *
	 * @since TBD
	 *
	 * @param array<string,mixed> $settings Widget settings.
	 * @param array<string>       $allowed  Allowed settings for shortcode.
	 *
	 * @return string
	 */
	public static function get_attributes_string( $settings, $allowed = [] ) {
		$settings_string = '';

		$allowed = array_flip( $allowed );

		foreach ( $settings as $key => $value ) {
			if ( ! empty( $allowed ) && ! isset( $allowed[ $key ] ) ) {
				continue;
			}

			$key = esc_attr( $key );

			if ( is_array( $value ) ) {
				$value = implode( ', ', $value );
			}

			$value = esc_attr( $value );

			$settings_string .= " {$key}=\"{$value}\"";
		}

		return $settings_string;
	}
}