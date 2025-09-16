<?php
/**
 * Notifications Template
 *
 * @since 6.4.0
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use Tribe__Main;
use Tribe__Template;
use TEC\Common\Telemetry\Telemetry;

/**
 * Class Template
 *
 * @since 6.4.0
 *
 * @package TEC\Common\Notifications
 */
class Template extends Tribe__Template {

	/**
	 * Notifications Template Constructor
	 *
	 * @since 6.4.0
	 */
	public function __construct() {
		$this->set_template_origin( Tribe__Main::instance() );
		$this->set_template_folder( 'src/admin-views/notifications' );
		$this->set_template_context_extract( true );
		$this->set_template_folder_lookup( false );
	}

	/**
	 * Render the notification sidebar.
	 *
	 * @since 6.4.0
	 *
	 * @param array $args Array of arguments that will ultimately be sent to the template.
	 * @param bool  $output Whether or not to echo the HTML. Defaults to true.
	 *
	 * @return string HTML of notification sidebar.
	 */
	public function render_sidebar( $args, $output = true ) {
		$args = wp_parse_args(
			$args,
			[
				'slug'  => $args['slug'],
				'main'  => Tribe__Main::instance(),
				'optin' => Conditionals::get_opt_in(),
				'url'   => Telemetry::get_permissions_url(),
			]
		);

		return $this->template( 'sidebar', $args, $output );
	}

	/**
	 * Render the notification.
	 *
	 * @since 6.4.0
	 *
	 * @param array $args Array of arguments that will ultimately be sent to the template.
	 * @param bool  $output Whether or not to echo the HTML. Defaults to true.
	 *
	 * @return string HTML of notification.
	 */
	public function render_notification( $args, $output = true ) {
		$args = wp_parse_args(
			$args,
			[
				'type'        => $args['type'] ?? 'notice',
				'id'          => $args['id'] ?? '',
				'dismissible' => $args['dismissible'] ?? true,
				'slug'        => $args['slug'] ?? '',
				'title'       => $args['title'] ?? '',
				'html'        => $args['html'] ?? '',
				'actions'     => $args['actions'] ?? [],
				'read'        => $args['read'] ?? false,
			]
		);

		return $this->template( 'notification', $args, $output );
	}
}
