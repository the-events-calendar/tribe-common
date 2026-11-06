<?php
/**
 * Loads the editor canvas styles into the iframed block editor canvas.
 *
 * @since TBD
 *
 * @package TEC\Common\Editor
 */

namespace TEC\Common\Editor;

use TEC\Common\StellarWP\Assets\Assets;

/**
 * Class Canvas_Styles.
 *
 * Runs on `enqueue_block_assets`, where WordPress collects the canvas styles against a fresh styles
 * queue after `enqueue_block_editor_assets` has run for the editor page. The asset library flags an
 * asset as enqueued on its first pass and never enqueues it again, so the handles of the group are
 * enqueued directly, reusing the decision the editor page made. It never adds a style the editor page
 * did not load, and does nothing outside the admin.
 *
 * @since TBD
 */
final class Canvas_Styles {
	/**
	 * Asset group of the stylesheets the iframed block editor canvas needs as well as the editor page.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const GROUP = 'tec-block-editor-canvas-styles';

	/**
	 * Adds the group styles the editor page loaded to the iframed block editor canvas.
	 *
	 * The asset library returns the assets sorted by priority, so the canvas loads them in the editor
	 * page's order and equal-specificity rules resolve as they do there.
	 *
	 * @since TBD
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function enqueue(): void {
		if ( ! is_admin() ) {
			return;
		}

		foreach ( Assets::init()->get() as $asset ) {
			if (
				'css' !== $asset->get_type()
				|| ! $asset->is_enqueued()
				|| ! in_array( self::GROUP, $asset->get_groups(), true )
			) {
				continue;
			}

			wp_enqueue_style( $asset->get_slug() );
		}
	}
}
