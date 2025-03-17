<?php
/**
 * Provides tabbed interface functionality for admin pages.
 *
 * @since TBD
 */

namespace TEC\Common\Admin\Traits;

/**
 * Trait Tabbed_View
 *
 * @since TBD
 */
trait Tabbed_View {
	/**
	 * The current active tab.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected $current_tab;

	/**
	 * Array of registered tabs.
	 *
	 * @since TBD
	 *
	 * @var array<string,array{
	 *     label: string,
	 *     url: string,
	 *     active: bool,
	 *     visible: bool,
	 *     capability: string,
	 *     render_callback: callable|null
	 * }>
	 */
	protected $tabs = [];

	/**
	 * Register a new tab.
	 *
	 * @since TBD
	 *
	 * @param string $slug       The tab's slug (used in URL and as key).
	 * @param string $label      The tab's label.
	 * @param array  $args       {
	 *     Optional. Array of tab arguments.
	 *
	 *     @type bool     $visible         Whether the tab should be visible. Default true.
	 *     @type string   $capability      The capability required to see this tab. Default 'manage_options'.
	 *     @type bool     $active          Whether this is the active tab. Default false.
	 *     @type callable $render_callback Callback function to render the tab content. Default null.
	 * }
	 */
	protected function register_tab( string $slug, string $label, array $args = [] ): void {
		$args = wp_parse_args(
			$args,
			[
				'visible'         => true,
				'capability'      => 'manage_options',
				'active'          => false,
				'render_callback' => null,
			]
		);

		$this->tabs[ $slug ] = [
			'label'           => $label,
			'url'             => $this->get_tab_url( $slug ),
			'active'          => $args['active'],
			'visible'         => $args['visible'],
			'capability'      => $args['capability'],
			'render_callback' => $args['render_callback'],
		];
	}

	/**
	 * Get the URL for a specific tab.
	 *
	 * @since TBD
	 *
	 * @param string $tab The tab slug.
	 *
	 * @return string The complete URL for the tab.
	 */
	protected function get_tab_url( string $tab ): string {
		$url = add_query_arg( [ 'tab' => $tab ], $this->get_url() );

		/**
		 * Filter the URL for a specific tab.
		 *
		 * @since TBD
		 *
		 * @param string $url The tab URL.
		 * @param string $tab The tab slug.
		 */
		return apply_filters( 'tec_common_admin_tab_url', $url, $tab );
	}

	/**
	 * Get the current active tab.
	 *
	 * @since TBD
	 *
	 * @return string The current tab's slug.
	 */
	public function get_current_tab(): string {
		if ( ! isset( $this->current_tab ) ) {
			$tab = tec_get_request_var( 'tab', $this->get_default_tab() );

			// Make sure the requested tab exists and user has access. Else return the default tab.
			if ( ! $this->is_visible_tab( $tab ) || ! $this->has_permission_to_view_tab( $tab ) ) {
				$tab = $this->get_default_tab();
			}

			$this->current_tab = $tab;
		}

		return $this->current_tab;
	}

	/**
	 * Get the default tab (first registered tab).
	 *
	 * @since TBD
	 *
	 * @return string The default tab's slug.
	 */
	protected function get_default_tab(): string {
		$tabs = array_keys( $this->tabs );
		return reset( $tabs ) ?: '';
	}

	/**
	 * Check if a tab is visible.
	 *
	 * @since TBD
	 *
	 * @param string $tab The tab slug.
	 *
	 * @return bool Whether the tab is visible.
	 */
	public function is_visible_tab( string $tab ): bool {
		if ( ! isset( $this->tabs[ $tab ]['visible'] ) ) {
			return false;
		}

		return (bool) $this->tabs[ $tab ]['visible'];
	}

	/**
	 * Check if the user has permission to view the tab.
	 *
	 * @since TBD
	 *
	 * @param string $tab The tab slug.
	 *
	 * @return bool Whether the user has permission to view the tab.
	 */
	public function has_permission_to_view_tab( string $tab ): bool {
		// No required permissions.
		if ( ! isset( $this->tabs[ $tab ]['capability'] ) ) {
			return true;
		}

		return current_user_can( $this->tabs[ $tab ]['capability'] );
	}

	/**
	 * Render the tabs navigation.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function render_tabs(): void {
		if ( empty( $this->tabs ) ) {
			return;
		}

		// Filter visible tabs.
		$visible_tabs = array_filter(
			$this->tabs,
			function ( string $slug ) {
				return $this->is_visible_tab( $slug ) && $this->has_permission_to_view_tab( $slug );
			},
			ARRAY_FILTER_USE_KEY
		);

		// If only one tab is visible, don't show the navigation.
		if ( count( $visible_tabs ) <= 1 ) {
			return;
		}

		echo '<div class="nav-tab-wrapper">';
		foreach ( $visible_tabs as $slug => $tab ) {
			$class = [ 'nav-tab' ];
			if ( $this->get_current_tab() === $slug ) {
				$class[] = 'nav-tab-active';
			}

			printf(
				'<a href="%1$s" class="%2$s">%3$s</a>',
				esc_url( $tab['url'] ),
				esc_attr( implode( ' ', $class ) ),
				esc_html( $tab['label'] )
			);
		}
		echo '</div>';
	}

	/**
	 * Render the content for the current tab.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function render_tab_content(): void {
		$current_tab = $this->get_current_tab();
		$class_name = static::class;

		// Get the class base name for more specific hooks
		$parts = explode('\\', $class_name);
		$base_name = strtolower(end($parts));

		// Extract portion of namespace for more specific hooks
		$namespace_parts = explode('\\', $class_name);
		$is_tickets = false;
		$is_tickets_admin = false;

		if (count($namespace_parts) >= 3) {
			// Check if this is in the Tickets namespace
			$is_tickets = ($namespace_parts[0] === 'TEC' && $namespace_parts[1] === 'Tickets');
			// Check if this is in the Tickets Admin namespace
			$is_tickets_admin = $is_tickets && isset($namespace_parts[2]) && $namespace_parts[2] === 'Admin';
		}

		/**
		 * Action that fires before rendering the tab content.
		 *
		 * @since TBD
		 *
		 * @param string $current_tab The current tab slug.
		 * @param object $this        The current page instance.
		 */
		do_action( 'tec_common_admin_before_tab_content', $current_tab, $this );

		// Allow for namespaced hooks based on the class name
		if ($base_name) {
			/**
			 * Action that fires before rendering the tab content for a specific page.
			 *
			 * @since TBD
			 *
			 * @param string $current_tab The current tab slug.
			 * @param object $this        The current page instance.
			 */
			do_action( "tec_{$base_name}_before_tab_content", $current_tab, $this );
		}

		// Compatibility for Tickets Admin specific hooks
		if ($is_tickets_admin) {
			/**
			 * Action that fires before rendering the tab content on the Tickets admin page.
			 *
			 * @since TBD
			 *
			 * @param string $current_tab The current tab slug.
			 * @param object $this        The current page instance.
			 */
			do_action( 'tec_tickets_admin_tickets_page_before_tab_content', $current_tab, $this );
		}

		// Check if the tab has a render callback.
		if ( ! empty( $this->tabs[ $current_tab ]['render_callback'] ) && is_callable( $this->tabs[ $current_tab ]['render_callback'] ) ) {
			// Call the render callback.
			call_user_func( $this->tabs[ $current_tab ]['render_callback'], $current_tab, $this );
		} else {
			// Otherwise, try to call a method based on the tab slug.
			$method = 'render_' . str_replace( '-', '_', $current_tab ) . '_tab_content';
			if ( method_exists( $this, $method ) ) {
				$this->$method();
			} else {
				/**
				 * Action that fires for rendering custom tab content.
				 *
				 * @since TBD
				 *
				 * @param string $current_tab The current tab slug.
				 * @param object $this        The current page instance.
				 */
				do_action( 'tec_common_admin_custom_tab_content', $current_tab, $this );

				// Allow for namespaced hooks based on the class name
				if ($base_name) {
					/**
					 * Action that fires for rendering custom tab content for a specific page.
					 *
					 * @since TBD
					 *
					 * @param string $current_tab The current tab slug.
					 * @param object $this        The current page instance.
					 */
					do_action( "tec_{$base_name}_custom_tab_content", $current_tab, $this );
				}

				// Compatibility for Tickets Admin specific hooks
				if ($is_tickets_admin) {
					/**
					 * Action that fires to render content for custom tabs on the Tickets admin page.
					 *
					 * @since TBD
					 *
					 * @param string $current_tab The current tab slug.
					 * @param object $this        The current page instance.
					 */
					do_action( 'tec_tickets_admin_tickets_page_custom_tab_content', $current_tab, $this );
				}
			}
		}

		/**
		 * Action that fires after rendering the tab content.
		 *
		 * @since TBD
		 *
		 * @param string $current_tab The current tab slug.
		 * @param object $this        The current page instance.
		 */
		do_action( 'tec_common_admin_after_tab_content', $current_tab, $this );

		// Allow for namespaced hooks based on the class name
		if ($base_name) {
			/**
			 * Action that fires after rendering the tab content for a specific page.
			 *
			 * @since TBD
			 *
			 * @param string $current_tab The current tab slug.
			 * @param object $this        The current page instance.
			 */
			do_action( "tec_{$base_name}_after_tab_content", $current_tab, $this );
		}

		// Compatibility for Tickets Admin specific hooks
		if ($is_tickets_admin) {
			/**
			 * Action that fires after rendering the tab content on the Tickets admin page.
			 *
			 * @since TBD
			 *
			 * @param string $current_tab The current tab slug.
			 * @param object $this        The current page instance.
			 */
			do_action( 'tec_tickets_admin_tickets_page_after_tab_content', $current_tab, $this );
		}
	}

	/**
	 * Abstract method that must be implemented by the class using this trait.
	 * Should return the base URL for the admin page.
	 *
	 * @since TBD
	 *
	 * @return string The base URL for the admin page.
	 */
	abstract protected function get_url(): string;
}
