<?php
/**
 * Notifications Template
 *
 * @since TBD
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
 * @since TBD
 *
 * @package TEC\Common\Notifications
 */
class Template extends Tribe__Template {

	/**
	 * Stores the instance of the template engine that we will use for rendering the page.
	 *
	 * @since TBD
	 *
	 * @var Tribe__Template
	 */
	protected $template;

	/**
	 * Get template object.
	 *
	 * @since TBD
	 *
	 * @return \Tribe__Template
	 */
	private function get_template() {
		if ( empty( $this->template ) ) {
			$this->template = new Tribe__Template();
			$this->template->set_template_origin( Tribe__Main::instance() );
			$this->template->set_template_folder( 'src/admin-views/notifications' );
			$this->template->set_template_context_extract( true );
			$this->template->set_template_folder_lookup( false );
		}

		return $this->template;
	}

	/**
	 * Render the notification icon.
	 *
	 * @since TBD
	 *
	 * @param array $args Array of arguments that will ultimately be sent to the template.
	 * @param bool  $output Whether or not to echo the HTML. Defaults to true.
	 *
	 * @return string HTML of notification icon.
	 */
	public function render_icon( $args, $output = true ) {
		$args = wp_parse_args(
			$args,
			[
				'slug'  => $args['slug'],
				'main'  => Tribe__Main::instance(),
				'optin' => Conditionals::get_opt_in(),
				'url'   => Telemetry::get_permissions_url(),
			]
		);

		$template = $this->get_template();
		return $template->template( 'icon', $args, $output );
	}

	/**
	 * Render the notification.
	 *
	 * @since TBD
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
			]
		);

		$template = $this->get_template();
		return $template->template( 'notification', $args, $output );
	}
}
