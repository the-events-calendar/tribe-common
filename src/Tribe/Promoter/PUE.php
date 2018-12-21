<?php

/**
 * Class Tribe__Promoter__PUE
 *
 * @since TBD
 */
class Tribe__Promoter__PUE {

	/**
	 * @var string
	 */
	private $slug = 'promoter';

	/**
	 * @var Tribe__PUE__Checker
	 */
	private $pue_checker;

	/**
	 * Setup the PUE Checker.
	 */
	public function load() {
		$this->pue_checker = new Tribe__PUE__Checker(
			'http://tri.be/',
			$this->slug,
			array(
				'context'     => 'service',
				'plugin_name' => __( 'Promoter', 'tribe' ),
			)
		);
	}

	/**
	 * Check whether Promoter has a license key set or not.
	 *
	 * @return bool Whether Promoter has a license key set.
	 */
	public function has_license_key() {
		$option_name = 'pue_install_key_' . $this->slug;

		$key = get_option( $option_name );

		if ( is_multisite() ) {
			$network_key = get_network_option( null, $option_name );

			if ( empty( $key ) ) {
				$key = $network_key;
			}
		}

		if ( empty( $key ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check whether Promoter has a valid license key or not.
	 *
	 * @return bool Whether Promoter has a valid license key.
	 */
	public function has_valid_license() {
		$option_name = 'pue_install_key_' . $this->slug;

		$key = get_option( $option_name );

		$is_network_key = false;

		if ( is_multisite() ) {
			$network_key = get_network_option( null, $option_name );

			if ( empty( $key ) ) {
				$key = $network_key;

				$is_network_key = true;
			}
		}

		$response = $this->pue_checker->validate_key( $key, $is_network_key );

		return isset( $response['status'] ) && 1 === (int) $response['status'];
	}

}
