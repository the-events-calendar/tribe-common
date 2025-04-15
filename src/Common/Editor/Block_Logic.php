<?php
/**
 * Block_Logic controller.
 *
 * @since TBD
 *
 * @package TEC\Common\Editor
 */

declare( strict_types=1 );

namespace TEC\Common\Editor;

use Exception;
use TEC\Common\Contracts\Provider\Controller;
use WP_Screen;

/**
 * Class Block_Logic
 *
 * @since TBD
 */
class Block_Logic extends Controller {

	/** @var ?WP_Screen */
	private ?WP_Screen $screen = null;

	/**
	 * Registers the filters and actions hooks added by the controller.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function do_register(): void {
		add_action( 'current_screen', [ $this, 'store_screen' ], 1 );
	}

	/**
	 * Removes the filters and actions hooks added by the controller.
	 *
	 * Bound implementations should not be removed in this method!
	 *
	 * @since TBD
	 *
	 * @return void Filters and actions hooks added by the controller are be removed.
	 */
	public function unregister(): void {
		$this->screen = null;
		remove_action( 'current_screen', [ $this, 'store_screen' ], 1 );
	}

	/**
	 * Store the current screen object.
	 *
	 * @since TBD
	 *
	 * @param WP_Screen $screen The current screen object.
	 *
	 * @return void
	 */
	public function store_screen( WP_Screen $screen ): void {
		// Store the current screen object as a clone to prevent any side effects.
		$this->screen = clone $screen;
	}

	/**
	 * Determine if blocks should be loaded.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_load_blocks(): bool {
		/**
		 * Filter to determine if blocks should be loaded by default.
		 *
		 * @since TBD
		 *
		 * @param bool $default Default value.
		 */
		$default = (bool) apply_filters( 'tec_common_should_load_blocks_default', true );

		// When not in the admin area, allow anything to load blocks for rendering.
		if ( ! is_admin() ) {
			return $this->return_should_load_blocks( $default, 'not_admin' );
		}

		// If we don't have the screen object set, we can't determine if we should load blocks.
		if ( null === $this->screen ) {
			return $this->return_should_load_blocks( $default, 'no_screen' );
		}

		// If this isn't an editor screen, we don't need to load blocks.
		if ( 'edit' !== $this->screen->base ) {
			return $this->return_should_load_blocks( false, 'not_editor' );
		}

		// If this is an editor screen, but not a block editor screen, we don't need to load blocks.
		if ( ! $this->screen->is_block_editor() ) {
			return $this->return_should_load_blocks( false, 'not_block_editor' );
		}

		// If this isn't a supported post type, we don't need to load blocks.
		if ( ! $this->should_load_blocks_for_post_type( $this->screen->post_type ) ) {
			return $this->return_should_load_blocks( false, 'not_supported_post_type' );
		}

		return $this->return_should_load_blocks( true, 'block_editor' );
	}

	/**
	 * Return the value of should_load_blocks passed through a filter.
	 *
	 * @since TBD
	 *
	 * @param bool   $should_load The value of should_load_blocks.
	 * @param string $reason      The reason why we are returning this value.
	 *
	 * @return bool
	 */
	private function return_should_load_blocks( bool $should_load, string $reason ): bool {
		/**
		 * Filter to determine if blocks should be loaded.
		 *
		 * This filter is used to determine if blocks should be loaded in the editor. The
		 * $reason parameter can be used to determine why we are returning this value.
		 *
		 * @see self::should_load_blocks() for the reasons.
		 *
		 * @since TBD
		 *
		 * @param bool       $should_load Whether blocks should be loaded.
		 * @param string     $reason      The reason why we are returning this value.
		 * @param ?WP_Screen $screen      The current screen object, if it has been set.
		 */
		return (bool) apply_filters( 'tec_common_should_load_blocks', $should_load, $reason, $this->screen );
	}

	/**
	 * Determine if blocks should be loaded for a specific post type.
	 *
	 * @since TBD
	 *
	 * @param string $post_type The post type to check.
	 *
	 * @return bool
	 */
	public function should_load_blocks_for_post_type( string $post_type = '' ): bool {
		// Set up a static variable to ensure we don't check the same post type multiple times.
		static $checked_post_types = [];

		// If we weren't provided a post type, try to determine the current post type.
		if ( empty( $post_type ) ) {
			try {
				$post_type = $this->determine_current_post_type();
			} catch ( Exception $e ) {
				return false;
			}
		}

		// If we have already checked this post type, return the cached value.
		if ( isset( $checked_post_types[ $post_type ] ) ) {
			return $checked_post_types[ $post_type ];
		}

		/**
		 * Filter to determine what post types should have blocks loaded.
		 *
		 * @since TBD
		 *
		 * @param array $load_blocks_post_types The post types that should have blocks loaded.
		 */
		$load_blocks_post_types = (array) apply_filters( 'tec_common_load_blocks_post_types', [] );

		// Store the post type in the static variable to avoid checking it again.
		$checked_post_types[ $post_type ] = in_array( $post_type, $load_blocks_post_types, true );

		return $checked_post_types[ $post_type ];
	}

	/**
	 * Determine the current post type.
	 *
	 * @since TBD
	 *
	 * @return string The current post type.
	 * @throws Exception If the current post type cannot be determined.
	 */
	private function determine_current_post_type(): string {
		// If we have the screen object, use its post type.
		if ( null !== $this->screen ) {
			return $this->screen->post_type;
		}

		// Try to use the global post object.
		if ( isset( $GLOBALS['post']->post_type ) ) {
			return $GLOBALS['post']->post_type;
		}

		throw new Exception( 'Unable to determine current post type.' );
	}
}
