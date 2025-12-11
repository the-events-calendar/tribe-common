<?php
/**
 * Editor_Utils implementation that provides compatibility with the legacy editor.
 *
 * @since TBD
 * @package TEC\Common\Classy\Back_Compatibility
 */

declare( strict_types=1 );

namespace TEC\Common\Classy\Back_Compatibility;

/**
 * Class Editor_Utils
 *
 * @see Tribe__Editor__Utils
 *
 * @since TBD
 */
class Editor_Utils {
	/**
	 * Placeholder for the Editor_Utils instance.
	 *
	 * This just returns the content as-is.
	 *
	 * @since TBD
	 *
	 * @param string $content The content to be processed.
	 *
	 * @return string The content as-is.
	 */
	public function exclude_tribe_blocks( string $content ): string {
		return $content;
	}
	
	/**
	 * Remove all invalid characters in string that are used to set the name of a block.
	 *
	 * @since TBD
	 *
	 * @param string $name The name of the block.
	 *
	 * @see Tribe__Editor__Utils::to_block_name()
	 *
	 * @return string The block name.
	 */
	public function to_block_name( $name = '' ) {
		return preg_replace( '/[^a-zA-Z0-9-]/', '', $name );
	}
}
