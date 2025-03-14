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
	 *     capability: string
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
	 *     @type bool   $visible    Whether the tab should be visible. Default true.
	 *     @type string $capability The capability required to see this tab. Default 'manage_options'.
	 *     @type bool   $active     Whether this is the active tab. Default false.
	 * }
	 */
	protected function register_tab( string $slug, string $label, array $args = [] ): void {
		$args = wp_parse_args(
			$args,
			[
				'visible'    => true,
				'capability' => 'manage_options',
				'active'     => false,
			]
		);

		$this->tabs[ $slug ] = [
			'label'      => $label,
			'url'        => $this->get_tab_url( $slug ),
			'active'     => $args['active'],
			'visible'    => $args['visible'],
			'capability' => $args['capability'],
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
	protected function get_current_tab(): string {
		if ( ! isset( $this->current_tab ) ) {
			$tab = tec_get_request_var( 'tab', $this->get_default_tab() );

			// Make sure the requested tab exists and user has access.
			if ( ! isset( $this->tabs[ $tab ] ) || ! current_user_can( $this->tabs[ $tab ]['capability'] ) ) {
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
	 * Abstract method that must be implemented by the class using this trait.
	 * Should return the base URL for the admin page.
	 *
	 * @since TBD
	 *
	 * @return string The base URL for the admin page.
	 */
	abstract protected function get_url(): string;
}
