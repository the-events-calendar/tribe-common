<?php
/**
 * Inline Upsell Component
 *
 * Simple, reusable component for displaying inline upsell notices in admin.
 * Unlike the Conditional_Content system, these are:
 * - Always visible (not dismissible)
 * - Context-specific (appear where feature would be used)
 * - Simple text + link (no large banners)
 * - Not time-based
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content
 */

namespace TEC\Common\Admin\Conditional_Content;

/**
 * Class Inline_Upsell
 *
 * Provides a simple API for rendering inline upsell notices throughout the admin.
 *
 * @since 6.9.8
 *
 * @package TEC\Common\Admin\Conditional_Content
 */
class Inline_Upsell {

	/**
	 * Stores the instance of the template engine.
	 *
	 * @since 6.9.8
	 *
	 * @var \Tribe__Template
	 */
	protected $template;

	/**
	 * Get template object.
	 *
	 * @since 6.9.8
	 *
	 * @return \Tribe__Template
	 */
	private function get_template() {
		if ( empty( $this->template ) ) {
			$this->template = new \Tribe__Template();
			$this->template->set_template_origin( \Tribe__Main::instance() );
			$this->template->set_template_folder( 'src/admin-views/notices/upsell' );
			$this->template->set_template_context_extract( true );
			$this->template->set_template_folder_lookup( false );
		}

		return $this->template;
	}

	/**
	 * Check if a specific plugin is active.
	 *
	 * @since 6.9.8
	 *
	 * @param string $plugin_path Plugin path (e.g., 'event-tickets-plus/event-tickets-plus.php').
	 *
	 * @return bool Whether the plugin is active.
	 */
	protected function is_plugin_active( string $plugin_path ): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( $plugin_path );
	}

	/**
	 * Check if a specific paid plugin is installed via Plugins API.
	 *
	 * @since 6.9.8
	 *
	 * @param string $plugin_slug Plugin slug (e.g., 'events-calendar-pro').
	 *
	 * @return bool Whether the paid plugin is installed.
	 */
	protected function is_paid_plugin_installed( string $plugin_slug ): bool {
		$products = tribe( 'plugins.api' )->get_products();

		foreach ( $products as $product ) {
			// Skip free plugins.
			if ( ! empty( $product['free'] ) ) {
				continue;
			}

			// Check if this is the plugin we're looking for.
			if ( isset( $product['slug'] ) && $product['slug'] === $plugin_slug ) {
				return ! empty( $product['is_installed'] );
			}
		}

		return false;
	}

	/**
	 * Check if upsell should be rendered.
	 *
	 * @since 6.9.8
	 *
	 * @param string $slug Optional. Unique identifier for this upsell (for filtering).
	 *
	 * @return bool Whether the upsell should render.
	 */
	protected function should_render( string $slug = '' ): bool {
		// Check global hide filter.
		if ( function_exists( 'tec_should_hide_upsell' ) ) {
			if ( tec_should_hide_upsell( $slug ) ) {
				return false;
			}
		}

		// Check legacy constant.
		if ( defined( 'TRIBE_HIDE_UPSELL' ) ) {
			return ! tribe_is_truthy( constant( 'TRIBE_HIDE_UPSELL' ) );
		}

		return true;
	}

	/**
	 * Render inline upsell notice.
	 *
	 * @since 6.9.8
	 *
	 * @param array $args {
	 *     Array of arguments for the upsell.
	 *
	 *     @type string   $slug         Unique identifier for this upsell (for filtering).
	 *     @type string[] $classes      Array of CSS classes for the container.
	 *     @type string   $text         The upsell message text.
	 *     @type string   $link_target  Link target attribute. Default '_blank'.
	 *     @type string   $icon_url     URL to icon image.
	 *     @type array    $link {
	 *         Link configuration.
	 *
	 *         @type string[] $classes Array of CSS classes for the link.
	 *         @type string   $text    Link text.
	 *         @type string   $url     Link URL.
	 *         @type string   $target  Link target. Default '_blank'.
	 *         @type string   $rel     Link rel attribute. Default 'noopener noreferrer'.
	 *     }
	 *     @type array    $conditions {
	 *         Optional conditions to check before rendering.
	 *
	 *         @type string   $plugin_not_active Plugin path that must NOT be active.
	 *         @type string   $plugin_slug       Plugin slug to check if installed via API.
	 *         @type callable $callback          Custom callback for conditional logic.
	 *     }
	 * }
	 * @param bool  $render Whether to render(echo) the HTML. Defaults to true.
	 *                      If false, the HTML will be returned as a string.
	 *
	 * @return string|void HTML of upsell notice.
	 */
	public function render( array $args, bool $render = true ): string {
		// Extract slug for filtering.
		$slug = $args['slug'] ?? '';

		// Check if upsell should be rendered globally.
		if ( ! $this->should_render( $slug ) ) {
			return '';
		}

		// Check conditions if provided.
		if ( ! empty( $args['conditions'] ) ) {
			if ( ! $this->check_conditions( $args['conditions'] ) ) {
				return '';
			}
		}

		// Default args for the container.
		$args = wp_parse_args(
			$args,
			[
				'slug'        => '',
				'classes'     => [],
				'text'        => '',
				'link_target' => '_blank',
				'icon_url'    => tribe_resource_url( 'images/icons/circle-bolt.svg', false, null, \Tribe__Main::instance() ),
				'link'        => [],
			]
		);

		// Default args for the link.
		$args['link'] = wp_parse_args(
			$args['link'],
			[
				'classes' => [],
				'text'    => '',
				'url'     => '',
				'target'  => '_blank',
				'rel'     => 'noopener noreferrer',
			]
		);

		$template = $this->get_template();

		return $template->template( 'main', $args, $render );
	}

	/**
	 * Check conditions before rendering.
	 *
	 * @since 6.9.8
	 *
	 * @param array $conditions Conditions to check.
	 *
	 * @return bool Whether all conditions pass.
	 */
	protected function check_conditions( array $conditions ): bool {
		// Check if plugin should NOT be active.
		if ( ! empty( $conditions['plugin_not_active'] ) ) {
			if ( $this->is_plugin_active( $conditions['plugin_not_active'] ) ) {
				return false;
			}
		}

		// Check if paid plugin is installed.
		if ( ! empty( $conditions['plugin_not_installed'] ) ) {
			if ( $this->is_paid_plugin_installed( $conditions['plugin_not_installed'] ) ) {
				return false;
			}
		}

		// Custom callback check.
		if ( ! empty( $conditions['callback'] ) && is_callable( $conditions['callback'] ) ) {
			if ( ! call_user_func( $conditions['callback'] ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Quick helper to render an upsell for a specific plugin.
	 *
	 * @since 6.9.8
	 *
	 * @param string $plugin_path  Plugin path to check (e.g., 'event-tickets-plus/event-tickets-plus.php').
	 * @param string $text         The upsell message with %s placeholder for link.
	 * @param string $link_text    The link text.
	 * @param string $link_url     The link URL.
	 * @param array  $extra_args   Optional. Additional arguments to merge.
	 * @param bool   $render         Whether to echo. Default true.
	 *
	 * @return string HTML of upsell notice.
	 */
	public function render_for_plugin(
		string $plugin_path,
		string $text,
		string $link_text,
		string $link_url,
		array $extra_args = [],
		bool $render = true
	): string {
		$args = array_merge(
			[
				'slug'       => sanitize_title( $plugin_path ),
				'text'       => $text,
				'link'       => [
					'text' => $link_text,
					'url'  => $link_url,
				],
				'conditions' => [
					'plugin_not_active' => $plugin_path,
				],
			],
			$extra_args
		);

		return $this->render( $args, $render );
	}
}
