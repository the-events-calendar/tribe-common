<?php
/**
 * The Controller to set up the Uplink library.
 */

namespace TEC\Common\Libraries\Uplink;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Libraries_Provider;
use TEC\Common\StellarWP\Arrays\Arr;
use TEC\Common\StellarWP\Uplink\Admin\Fields\Field;
use TEC\Common\StellarWP\Uplink\Config;
use TEC\Common\StellarWP\Uplink\Resources\Collection;
use TEC\Common\StellarWP\Uplink\Uplink;

/**
 * Controller for setting up the stellarwp/uplink library.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries\Uplink
 */
class Controller extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since TBD
	 */
	public function do_register(): void {
		$this->add_actions();
	}

	/**
	 * Unregister the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		$this->remove_actions();
	}

	/**
	 * Add the action hooks.
	 *
	 * @since TBD
	 */
	public function add_actions(): void {
		add_action( 'init', [ $this, 'register_uplink' ], 8 );
		add_filter( 'tribe_license_fields', [ $this, 'register_license_fields' ], 20 );
	}

	/**
	 * Remove the action hooks.
	 *
	 * @since TBD
	 */
	public function remove_actions(): void {
		remove_action( 'init', [ $this, 'register_uplink' ], 8 );
		remove_filter( 'tribe_license_fields', [ $this, 'register_license_fields' ], 20 );
	}

	/**
	 * Register the license fields.
	 *
	 * @since TBD
	 *
	 * @param array $fields_array The array of fields.
	 *
	 * @return array
	 */
	public function register_license_fields( $fields_array ) {
		$collection = tribe( Collection::class );
		$plugins    = $collection->get_plugins();

		$fields_to_inject = [];

		foreach ( $plugins as $plugin ) {
			$legacy_slug = str_replace( '-', '_', $plugin->get_slug() );

			$field = new Field( $plugin->get_slug() );
			$field->set_field_name( 'pue_install_key_' . $legacy_slug )->show_label( false );

			$field_html = $field->render();

			// Remove duplicate entries of plugins migrated to uplink.
			if ( isset( $fields_array[ 'pue_install_key_' . $legacy_slug . '-heading' ] ) ) {
				unset( $fields_array[ 'pue_install_key_' . $legacy_slug . '-heading' ] );
			}

			// Remove duplicate entries of plugins migrated to uplink.
			if ( isset( $fields_array[ 'pue_install_key_' . $legacy_slug ] ) ) {
				unset( $fields_array[ 'pue_install_key_' . $legacy_slug ] );
			}

			$fields_to_inject[ 'stellarwp-uplink_' . $plugin->get_slug() . '-heading' ] = [
				'type'  => 'heading',
				'label' => $plugin->get_name(),
			];

			$fields_to_inject[ 'stellarwp-uplink_' . $plugin->get_slug() ] = [
				'type'  => 'html',
				'label' => '',
				'html'  => $field_html,
			];
		}

		$fields_array = Arr::insert_after_key( 'tribe-form-content-start', $fields_array, $fields_to_inject );

		return $fields_array;
	}

	/**
	 * Register the uplink library.
	 *
	 * @since TBD
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
