<?php
/**
 * The interface implemented by all PHP classes providing the information to register a block server-side.
 *
 * @since   TBD
 *
 * @package Common\Blocks;
 */

namespace Common\Blocks;

/**
 * Interface Block_Interface.
 *
 * @since   TBD
 *
 * @package Common\Blocks;
 */
interface Block_Interface {
	/**
	 * Returns the name the block is registered with in the `registerBlockType` function.
	 *
	 * The name should have a format like `vendor/block-name`.
	 *
	 * @since TBD
	 *
	 * @return string The name the block is registered with in the `registerBlockType` function.
	 */
	public static function getName(): string;

	/**
	 * Returns the arguments required to register the block.
	 *
	 * The arguments should include the localized version of the block `title` and `description`; the block
	 * attributes and anything that cannot live in the `block.json` file.
	 * If a value provided by this method could live in the `block.json` file, then it should be moved there.
	 *
	 * @since TBD
	 *
	 * @return array<string,mixed> The arguments required to register the block.
	 */
	public function get_block_registration_args(): array;
}