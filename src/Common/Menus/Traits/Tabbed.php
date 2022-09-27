<?php
/**
 * Provides methods and properties for tabbed menu pages.
 *
 * @since TBD
 *
 * @package TEC\Common\Menus
 */

namespace TEC\Common\Menus\Traits;

trait Tabbed {
	/**
	 * Add tabbed page hooks.
	 *
	 * @since TBD
	 */
	protected function tabbed_hooks() : void {}

	/**
	 * {@inheritDoc}
	 */
	public function render() : void {
		$this->tab_nav();
		$this->tabbed_content();
	}

	/**
	 * Output the tab links.
	 *
	 * @since TBD
	 */
	public function tab_nav() : void {
		/**
		 * Hook: execute callback before the tab nav for a given tab group.
		 *
		 * @hook tec_before_tab_nav_<menu_slug>
		 * @since TBD
		 */
		do_action( 'tec_menus_before_tab_nav_' . $this->get_slug() );
		?>
		<div class="tec-tabbed-nav" role="tablist">
			<?php
			$i = 0;
			foreach ( $this->tabs as $tab_data ) {
				$item_classes = 'tec-nav__item';

				if ( ! empty( $tab_data['class'] ) ) {
					$item_classes .= implode( ' ', (array) $tab_data['class'] );
				}

				$selected = 'false';
				if ( 0 === $i ) {
					$selected = 'true';
					$i++;
				}
				?>
					<button
						id="tab-<?php echo esc_attr( $tab_data['id'] ); ?>"
						class="<?php echo esc_attr( $item_classes ); ?>"
						role="tab"
						aria-controls="tabpanel-<?php echo esc_attr( $tab_data['id'] ); ?>"
						aria-selected="<?php echo esc_attr( $selected ); ?>"
						<?php
							if ( 'true' !== $selected ) { ?>
								tabindex="-1"
						<?php }?>
					>
						<span class="focus"><?php echo esc_html( $tab_data['title'] ); ?></span>
					</button>
				<?php
			}
			?>
		</div>
		<?php
		/**
		 * Hook: execute callback after the tab nav for a given tab group.
		 *
		 * @hook tec_after_tab_nav_<menu_slug>
		 * @since TBD
		 */
		do_action( 'tec_menus_after_tab_nav_' . $this->get_slug() );
	}

	/**
	 * Tabbed Content sections
	 *
	 * @since TBD
	 */
	public function tabbed_content() : void {
		/**
		 * Hook: execute callback before the tab content for a given tab group.
		 *
		 * @hook tec_before_tab_nav_<menu_slug>
		 * @since TBD
		 */
		do_action( 'tec_menus_before_tab_content_' . $this->get_slug() );

		$i = 0;
		foreach ( $this->tabs as $tab_data ) {
			$tab_panel_classes = "tec-section tec-tab tec-tab--{$tab_data['id']}";

			?>
			<div
				id="tabpanel-<?php echo esc_attr( $tab_data['id'] ); ?>"
				class="<?php echo esc_attr( $tab_panel_classes ); ?>"
				role="tabpanel"
				tabindex="0"
				aria-labelledby="tab-<?php echo esc_attr( $tab_data['id'] ); ?>"
			>
				<?php
					if ( is_callable( $tab_data['content'], false, $callable_name ) ) {
						call_user_func( $callable_name );
					} else {
						echo wp_kses_post( $tab_data['content'] );
					}
				?>
			</div>
			<?php
		}

		/**
		 * Hook: execute callback after the tab content for a given tab group.
		 *
		 * @hook tec_after_tab_nav_<menu_slug>
		 * @since TBD
		 */
		do_action( 'tec_menus_after_tab_content_' . $this->get_slug() );
	}

	public function register_assets() {
		if ( ! $this->is_current_page() ) {
			return;
		}
		$foo = plugins_url( '../Assets/tabs.js', __FILE__ );
		wp_enqueue_style(
			$this->get_slug() . '_css',
			plugins_url( '../Assets/tabs.css', __FILE__ )
		);

		wp_enqueue_script(
			$this->get_slug() . '_js',
			plugins_url( '../Assets/tabs.js', __FILE__ )
		);
		$baz = '';
	}
}
