<?php
/**
 * Hooks the block editor features of common.
 *
 * @since TBD
 *
 * @package TEC\Common\Editor
 */

namespace TEC\Common\Editor;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;

/**
 * Class Controller.
 *
 * @since TBD
 */
final class Controller extends Controller_Contract {
	/**
	 * Priority of the canvas styles on `enqueue_block_assets`: after the other canvas styles, so the
	 * editor stylesheets override them in the canvas as they do on the editor page.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	private const CANVAS_STYLES_PRIORITY = 1000;

	/**
	 * Removes the hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function unregister(): void {
		remove_action(
			'enqueue_block_assets',
			$this->container->callback( Canvas_Styles::class, 'enqueue' ),
			self::CANVAS_STYLES_PRIORITY
		);
	}

	/**
	 * Binds the editor services and hooks them.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		$this->container->singleton( Canvas_Styles::class );

		add_action(
			'enqueue_block_assets',
			$this->container->callback( Canvas_Styles::class, 'enqueue' ),
			self::CANVAS_STYLES_PRIORITY
		);
	}
}
