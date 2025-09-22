<?php
/**
 * The Controller to set up the Uplink library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Libraries_Provider;
use TEC\Common\StellarWP\Arrays\Arr;
use TEC\Common\StellarWP\Uplink\Config;
use TEC\Common\StellarWP\Uplink\Resources\Resource;
use TEC\Common\StellarWP\Uplink\Uplink;

use function TEC\Common\StellarWP\Uplink\get_field;
use function TEC\Common\StellarWP\Uplink\get_plugins;

/**
 * Controller for setting up the stellarwp/uplink library.
 *
 * @since 6.3.0
 *
 * @package TEC\Common\Libraries\Uplink
 */
class Uplink_Controller extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since 6.3.0
	 */
	public function do_register(): void {
		$this->add_actions();
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.3.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->remove_actions();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since 6.3.0
	 */
	public function add_actions(): void {
		add_action( 'init', [ $this, 'register_uplink' ], 8 );
		add_filter( 'tribe_license_fields', [ $this, 'register_license_fields' ], 20 );
		add_action( 'tribe_settings_save', [ $this, 'save_empty_license_keys' ] );
	}

	/**
	 * Remove the action hooks.
	 *
	 * @since 6.3.0
	 */
	public function remove_actions(): void {
		remove_action( 'init', [ $this, 'register_uplink' ], 8 );
		remove_filter( 'tribe_license_fields', [ $this, 'register_license_fields' ], 20 );
	}

	/**
	 * Register the license fields.
	 *
	 * @since 6.3.0
	 * @since 6.9.4 Added upsell links for Seating.
	 *
	 * @param array $fields_array The array of fields.
	 *
	 * @return array
	 */
	public function register_license_fields( $fields_array ) {
		$plugins = get_plugins();

		$fields_to_inject = [];

		foreach ( $plugins as $plugin ) {
			$legacy_slug = str_replace( '-', '_', $plugin->get_slug() );

			$field = get_field( $plugin->get_slug() );
			$field->set_field_name( 'pue_install_key_' . $legacy_slug )->show_label( false );

			$prefix = tribe( Libraries_Provider::class )->get_hook_prefix();

			// If there is a license registered prior Uplink but not with Uplink. Return license registered prior Uplink.
			add_filter(
				'stellarwp/uplink/' . $prefix . '/' . $plugin->get_slug() . '/license_get_key',
				function ( $license, Resource $plugin ) {
					if ( $license ) {
						return $license;
					}

					return get_option( 'pue_install_key_' . str_replace( '-', '_', $plugin->get_slug() ), '' );
				},
				10,
				2
			);

			$field_html = $field->get_render_html();

			// Allow customization of the field HTML for specific plugins.
			$field_html = apply_filters( 'stellarwp_uplink_' . $prefix . '_' . $plugin->get_slug() . '_field_html', $field_html, $plugin );


			// Skip if the field HTML is empty to prevent empty containers.
			if ( empty( trim( $field_html ) ) ) {
				continue;
			}

			// Remove all related entries for a plugin migrated to uplink.
			$keys_to_check = [
				'pue_install_key_' . $legacy_slug . '-heading',
				'pue_install_key_' . $legacy_slug,
				'pue_install_key_' . $legacy_slug . '-section-open',
				'pue_install_key_' . $legacy_slug . '-section-close',
			];

			foreach ( $keys_to_check as $key ) {
				if ( isset( $fields_array[ $key ] ) ) {
					unset( $fields_array[ $key ] );
				}
			}

			// Create a single wrapped field containing both the heading and the license field.
			$wrapped_html  = '<div class="tec-settings-form__content-section">';
			$wrapped_html .= '<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">' . $plugin->get_name() . '</h3>';
			$wrapped_html .= $field_html;
			$wrapped_html .= '</div>';

			// Add as a single HTML field instead of separate heading and HTML fields.
			$fields_to_inject[ 'stellarwp-uplink_' . $plugin->get_slug() ] = [
				'type'  => 'html',
				'label' => '',
				'html'  => $wrapped_html,
			];
		}

		// Only inject fields if we have something to inject.
		if ( ! empty( $fields_to_inject ) ) {
			$fields_array = Arr::insert_after_key( 'tribe-form-content-start', $fields_array, $fields_to_inject );
		}

		return $fields_array;
	}

	/**
	 * Save empty license keys.
	 *
	 * @since 6.3.0
	 */
	public function save_empty_license_keys() {
		$plugins = get_plugins();

		foreach ( $plugins as $plugin ) {
			$legacy_slug = str_replace( '-', '_', $plugin->get_slug() );

			if ( ! isset( $_POST[ 'pue_install_key_' . $legacy_slug ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				continue;
			}

			$license_key = sanitize_text_field( $_POST[ 'pue_install_key_' . $legacy_slug ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			// If the license key has a value, it will be validated and stored by uplink.
			// We only want to give our users the option to remove a license key if they want to.
			if ( $license_key ) {
				continue;
			}

			$plugin->set_license_key( '' );
		}
	}

	/**
	 * Register the uplink library.
	 *
	 * @since 6.3.0
	 *
	 * @return void
	 */
	public function register_uplink(): void {
		$prefix = tribe( Libraries_Provider::class )->get_hook_prefix();

		Config::set_container( tribe() );
		Config::set_hook_prefix( $prefix );
		Config::set_token_auth_prefix( $prefix );
		Uplink::init();
	}
}
