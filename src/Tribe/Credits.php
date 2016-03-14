<?php

/**
 * Handles output of The Events Calendar credits
 */
class Tribe__Credits {

	public static function init() {
		self::instance()->hook();
	}

	/**
	 * Hook the functionality of this class into the world
	 */
	public function hook() {
		add_filter( 'tribe_events_after_html', array( $this, 'html_comment_credit' ) );
		add_filter( 'admin_footer_text', array( $this, 'rating_nudge' ), 1, 2 );
	}

	/**
	 * Add credit in HTML page source
	 *
	 * @return void
	 **/
	public function html_comment_credit( $after_html ) {
		$html_credit = "\n<!--\n" . esc_html__( 'This calendar is powered by The Events Calendar.', 'tribe-common' ) . "\nhttp://m.tri.be/18wn\n-->\n";
		$after_html .= apply_filters( 'tribe_html_credit', $html_credit );
		return $after_html;
	}

	/**
	 * Add ratings nudge in admin footer
	 *
	 * @param $footer_text
	 *
	 * @return string
	 */
	public function rating_nudge( $footer_text ) {
		$admin_helpers = Tribe__Admin__Helpers::instance();

		// only display custom text on Tribe Admin Pages
		if ( $admin_helpers->is_screen() || $admin_helpers->is_post_type_screen() ) {
			$review_url = 'http://wordpress.org/support/view/plugin-reviews/the-events-calendar?filter=5';

			$footer_text = sprintf(
				esc_html__( 'Rate %3$sThe Events Calendar%4$s %1$s on %2$s to keep this plugin free.  Thanks from the friendly folks at Modern Tribe.', 'tribe-common' ),
				'<a href="' . $review_url . '" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
				'<a href="' . $review_url . '" target="_blank">WordPress.org</a>',
				'<strong>',
				'</strong>'
			);
		}

		return $footer_text;
	}

	/**
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * @return self
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
