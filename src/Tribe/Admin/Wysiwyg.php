<?php

namespace Tribe\Admin;

/**
 * Admin Wysiwyg class.
 *
 * @since 5.0.12
 */

class Wysiwyg {

	/**
	 * Unique name given to editor in case more than one is being used on the same page.
	 *
	 * @since 5.0.12
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Initial HTML of the editor.
	 *
	 * @since 5.0.12
	 *
	 * @var string
	 */
	protected $value = '';

	/**
	 * Settings to pass into the editor.
	 *
	 * @since 5.0.12
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Create a new Wysiwyg object.
	 *
	 * @since 5.0.12
	 *
	 * @param string $name  Unique name given to editor.
	 * @param string $value Initial value/HTML.
	 * @param array  $args  Array of settings.
	 *
	 * @return void
	 */
	function __construct( $name, $value = '', $args = [] ) {
		$this->name = $name;
		$this->value = $value;
		$default_args = [
			'teeny'   => true,
			'wpautop' => true,
			'textarea_name' => $name,
		];
		$this->args = wp_parse_args( $args, $default_args );
	}

	/**
	 * Filters editor buttons.
	 *
	 * @since 5.0.12
	 *
	 * @param  array $buttons Array of buttons to include.
	 *
	 * @return array Filtered array of buttons.
	 */
	public function filter_buttons( $buttons ) {
		if (
			empty( $this->args )
			|| ! isset( $this->args['buttons'] )
			|| empty( $this->args['buttons'] )
		) {
			return $buttons;
		}

		return $this->args['buttons'];
	}

	/**
	 * Filter 2nd row of buttons.
	 *
	 * @since 5.0.12
	 *
	 * @param  array $buttons Array of buttons to include.
	 *
	 * @return array Filtered array of buttons.
	 */
	public function maybe_filter_buttons_2( $buttons ) {
		if (
			empty( $this->args ) ||
			! isset( $this->args['buttons_2'] ) ||
			empty( $this->args['buttons_2'] )
		) {
			return $buttons;
		}

		return $this->args['buttons_2'];
	}

	/**
	 * Get HTML of editor.
	 *
	 * @since 5.0.12
	 *
	 * @return string HTML of editor
	 */
	public function get_html() {
		// Add button filters.
		add_filter( 'teeny_mce_buttons', [ $this, 'filter_buttons' ] );
		add_filter( 'tiny_mce_buttons', [ $this, 'filter_buttons' ] );
		add_filter( 'mce_buttons', [ $this, 'filter_buttons' ] );
		add_filter( 'mce_buttons_2', [ $this, 'maybe_filter_buttons_2' ] );

		// Get HTML of editor.
		ob_start();
		wp_editor( html_entity_decode( ( $this->value ) ), sanitize_html_class( $this->name ), $this->args );
		$html = ob_get_clean();

		// Remove button filters.
		remove_filter( 'teeny_mce_buttons', [ $this, 'filter_buttons' ] );
		remove_filter( 'tiny_mce_buttons', [ $this, 'filter_buttons' ] );
		remove_filter( 'mce_buttons', [ $this, 'filter_buttons' ] );
		remove_filter( 'mce_buttons_2', [ $this, 'maybe_filter_buttons_2' ] );

		return $html;
	}

	/**
	 * Renders editor HTML.
	 *
	 * @since 5.0.12
	 *
	 * @return void
	 */
	public function render_html() {
		echo $this->get_html();
	}

}
