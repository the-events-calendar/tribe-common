<?php
/**
 * This is a mock of the main Classic Editor plugin class.
 */

if ( class_exists( Classic_Editor::class ) ) {
	return;
}

class Classic_Editor {
	public static function choose_editor( $use_block_editor, $post ): bool {
		// By default, the class will return true: use the Block Editor.
		return true;
	}
}
