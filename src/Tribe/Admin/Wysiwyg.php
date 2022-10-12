<?php

namespace Tribe\Admin;

/**
 * Admin Wysiwyg class.
 * 
 * @since TBD
 */

class Wysiwyg {

	/**
	 * Unique name given to editor in case more than one is being used on the same page.
	 * 
	 * @since TBD
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Initial HTML of the editor.
	 *
	 * @since TBD
	 * 
	 * @var string
	 */
	protected $value = '';

	/**
	 * Settings to pass into the editor.
	 *
	 * @since TBD
	 * 
	 * @var array
	 */
	protected $args = [];

	/**
	 * Create a new Wywiwyg object.
	 *
	 * @since TBD
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
		];
		$this->args = wp_parse_args( $args, $default_args );
	}

	/**
	 * Filters editor buttons.
	 * 
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
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
		wp_editor( html_entity_decode( ( $this->value ) ), $this->name, $this->args );
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
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_html() {
		echo $this->get_html();
	}
	
}