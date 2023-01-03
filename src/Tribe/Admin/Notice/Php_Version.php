<?php
/**
 * Shows an admin notice for Php_Version
 */
class Tribe__Admin__Notice__Php_Version {

	public function hook() {

		// display the PHP version notice
		tribe_notice(
			'php-deprecated-74',
			[ $this, 'display_notice' ],
			[
				'type'    => 'warning',
				'dismiss' => 1,
				'wrap'    => 'p',
			],
			[ $this, 'should_display' ]
		);

	}

	/**
	 * Return the list of the Tribe active plugins
	 *
	 * @since 4.7.16
	 *
	 * @return string String of items
	 */
	public function get_active_plugins() {

		$active_plugins = Tribe__Dependency::instance()->get_active_plugins();

		foreach ( $active_plugins as $active_plugin ) {

			if ( ! $active_plugin['path'] ) {
				continue;
			}

			$plugin_data = get_plugin_data( $active_plugin['path'] );
			$plugins[]   = $plugin_data['Name'];

		}

		return $this->implode_with_grammar( $plugins );

	}

	/**
	 * Implodes a list items using 'and' as the final separator and a comma everywhere else
	 *
	 * @param array $items List of items to implode
	 * @since 4.7.16
	 *
	 * @return string String of items
	 */
	public function implode_with_grammar( $items ) {

		$separator   = _x( ', ', 'separator used in a list of items', 'tribe-common' );
		$conjunction = _x( ' and ', 'the final separator in a list of two or more items', 'tribe-common' );
		$output      = $last_item = array_pop( $items );

		if ( $items ) {
			$output = implode( $separator, $items ) . $conjunction . $last_item;
		}

		return $output;
	}

	/**
	 * We only want to display notices for users
	 * who are in PHP < 5.6
	 *
	 * @since  4.7.16
	 *
	 * @return boolean
	 */
	public function should_display() {
		// Bail if the user is not admin or can manage plugins
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return false;
		}

		$i = version_compare( PHP_VERSION, '7.4.0' );

		return version_compare( PHP_VERSION, '7.4.0' ) < 0;
	}

	/**
	 * HTML for the PHP notice
	 *
	 * @since  4.7.16
	 *
	 * @return string
	 */
	public function display_notice() {
		// PHP versions older than 7.4
		$text = __( 'Starting February 2023, %1$s will no longer work with versions prior to PHP 7.4. Currently your site is using PHP version %2$s. For best results, we recommend using PHP 7.4 or above.', 'tribe-common' );

		$plugins = $this->get_active_plugins();

		return sprintf( $text, $plugins, PHP_VERSION );
	}
}
