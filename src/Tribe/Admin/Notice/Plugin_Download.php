<?php

/**
 * Shows an admin notice telling users which requisite plugins they need to download
 */
class Tribe__Admin__Notice__Plugin_Download {

	private $plugin_path;

	private $plugins_required = array();

	/**
	 * @param string $plugin_path Path to the plugin file we're showing a notice for
	 */
	public function __construct( $plugin_path ) {
		$this->plugin_path = $plugin_path;

		tribe_notice(
			plugin_basename( $plugin_path ),
			array( $this, 'show_inactive_plugins_alert' )
		);
	}

	/**
	 * Add a required plugin to the notice
	 *
	 * @param string $name         Name of the required plugin
	 * @param null   $thickbox_url Download or purchase URL for plugin from within /wp-admin/ thickbox
	 * @param bool   $is_active    Indicates if the plugin is installed and active or not
	 */
	public function add_required_plugin( $name, $thickbox_url = null, $is_active = null ) {
		$this->plugins_required[ $name ] = array(
			'name'         => $name,
			'thickbox_url' => $thickbox_url,
			'is_active'    => $is_active,
		);
	}

	/**
	 * Echoes the admin notice, attach to admin_notices
	 */
	public function show_inactive_plugins_alert() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin_data = get_plugin_data( $this->plugin_path );

		$req_plugins = array();

		foreach ( $this->plugins_required as $req_plugin ) {

			$item = esc_html( $req_plugin['name'] );

			if ( ! empty( $req_plugin['thickbox_url'] ) ) {
				$item = sprintf(
					'<a href="%1$s" class="thickbox" title="%2$s">%3$s</a>',
					esc_attr( $req_plugin['thickbox_url'] ),
					esc_attr( $req_plugin['name'] ),
					$item
				);
			}

			if ( false === $req_plugin['is_active'] ) {
				$item = sprintf(
					'<strong class="tribe-inactive-plugin">%1$s</strong>',
					$item
				);
			}

			$req_plugins[] = $item;
		}

		printf(
			'<div class="error"><p>' . esc_html__( 'To begin using %1$s, please install and activate the latest version of %2$s.', 'tribe-common' ) . '</p></div>',
			$plugin_data['Name'],
			$this->implode_with_grammar( $req_plugins )
		);

	}

	/**
	 * Implodes a list items using 'and' as the final separator and a comma everywhere else
	 *
	 * @param array $items List of items to implode
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

}
