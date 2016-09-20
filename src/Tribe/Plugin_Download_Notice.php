<?php
// Don't load directly
defined( 'WPINC' ) or die;

if ( ! class_exists( 'Tribe__Plugin_Download_Notice' ) ) {

	/**
	 * Shows an admin notice telling users which requisite plugins they need to download
	 *
	 * @TODO This whole thing could be reworked in post 4.3 or possibly removed with the introduction of tribe_notice()
	 */
	class Tribe__Plugin_Download_Notice {

		private $plugin_path;

		private $plugins_required = array();

		/**
		 * @param string $plugin_path Path to the plugin file we're showing a notice for
		 */
		public function __construct( $plugin_path ) {
			$this->plugin_path = $plugin_path;
			add_action( 'admin_notices', array( $this, 'show_inactive_plugins_alert' ) );
		}

		/**
		 * Add a required plugin to the notice
		 *
		 * @param string $name         Name of the required plugin
		 * @param null   $thickbox_url Download or purchase URL for plugin from within /wp-admin/ thickbox
		 */
		public function add_required_plugin( $name, $thickbox_url = null ) {
			$this->plugins_required[ $name ] = array(
				'name'         => $name,
				'thickbox_url' => $thickbox_url,
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

				$req_plugins[] = $item;
			}

			printf(
				'<div class="error"><p>' . esc_html__( 'To begin using %1$s, please install and activate the latest version(s) of %2$s.', 'tribe-common' ) . '</p></div>',
				$plugin_data['Name'],
				implode( ', ', $req_plugins )
			);

		}


	}

}
