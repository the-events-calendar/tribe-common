<?php
/**
 * Handles Telemetry opt-in logic.
 *
 * @since 5.1.13
 *
 * @package TEC\Common\Telemetry
 */
namespace TEC\Common\Telemetry;

use TEC\Common\StellarWP\Telemetry\Config;
use TEC\Common\StellarWP\Telemetry\Opt_In\Status as Opt_In_Status;
use TEC\Common\StellarWP\DB\DB;
use WP_User;

/**
 * Class Opt_In
 *
 * @since 5.1.13

 * @package TEC\Common\Telemetry
 */
class Opt_In {
	/**
	 * Build the opt-in user data, store it, and fetch it.
	 *
	 * @since 5.1.13
	 *
	 * @return array
	 */
	public function build_opt_in_user(): array {
		$stellar_slug = Config::get_stellar_slug();

		if ( empty( $stellar_slug ) ) {
			return [];
		}

		$opt_in_user = get_option( Opt_In_Status::OPTION_NAME_USER_INFO, [] );

		// If we already have a stored opt-in user, just return that.
		if ( count( $opt_in_user ) > 0 && ! empty( $opt_in_user['user'] ) ) {
			$stored_data = json_decode( $opt_in_user['user'], true );

			if ( is_array( $stored_data ) ) {
				return $stored_data;
			}
		}

		$user = $this->get_generated_opt_in_user();

		$opt_in_user_data = [
			'name'        => null,
			'email'       => null,
			'opt_in_text' => null,
			'plugin_slug' => $stellar_slug,
		];

		if ( ! empty( $user ) && ! empty( $user->user_email ) ) {
			$opt_in_user_data['name']  = $user->display_name;
			$opt_in_user_data['email'] = $user->user_email;
		}

		update_option( Opt_In_Status::OPTION_NAME_USER_INFO, [ 'user' => wp_json_encode( $opt_in_user_data ) ] );

		return $opt_in_user_data;
	}

	/**
	 * Get the opt-in user to be used in the opt_in_user telemetry field.
	 *
	 * @since 5.1.13
	 *
	 * @return WP_User|null
	 */
	protected function get_generated_opt_in_user(): ?WP_User {
		$admin_user = $this->get_admin_user_by_admin_email();

		if ( $admin_user ) {
			return $admin_user;
		}

		$admin_user = $this->get_first_admin_user();

		return $admin_user;
	}

	/**
	 * Get an admin user based on the admin email for the site.
	 *
	 * @since 5.1.13
	 *
	 * @return WP_User|null
	 */
	protected function get_admin_user_by_admin_email(): ?WP_User {
		$admin_email = get_option( 'admin_email' );

		if ( empty( $admin_email ) ) {
			return null;
		}

		$user = get_user_by( 'email', $admin_email );

		if ( ! $user || ! $user->exists() ) {
			return null;
		}

		return $user;
	}

	/**
	 * Get the first admin user from the first 5,000 users of the site.
	 *
	 * @since 5.1.13
	 *
	 * @return WP_User|null
	 */
	protected function get_first_admin_user(): ?WP_User {
		global $wpdb;

		$results = DB::table( 'usermeta' )
			->select( 'user_id', 'meta_value' )
			->where( 'meta_key', $wpdb->prefix . 'capabilities' )
			->orderBy( 'user_id' )
			->limit( 5000 )
			->getAll();

		// Let's only grab administrators.
		$results = array_filter( $results, static function( $row ) {
			return strpos( $row->meta_value, '"administrator"' ) !== false;
		} );

		if ( empty( $results ) ) {
			return null;
		}

		$user_row = current( $results );

		if ( empty( $user_row ) || empty( $user_row->user_id ) ) {
			return null;
		}

		$user_id = absint( $user_row->user_id );
		$user    = get_userdata( $user_id );

		if ( empty( $user ) || ! $user->exists() ) {
			return null;
		}

		return $user;
	}
}
