<?php

/**
 * Events Gutenberg Utils
 *
 * @since 4.8
 */
class Tribe__Editor__Utils {

	/**
	 * Adds the required prefix of a tribe block with the wp: prefix as well and escaped.
	 *
	 * @since 4.8
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function to_tribe_block_name( $name = '' ) {
		return 'wp:tribe\/' . $name;
	}

	/**
	 * Remove all invalid characters in string that are used to set the name of a block
	 *
	 * @since 4.8
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function to_block_name( $name = '' ) {
		return preg_replace( '/[^a-zA-Z0-9-]/', '', $name );
	}

	/**
	 * Replaces the content of a post where a block is located, removes the space before and after on the same line where
	 * the block is located, it replaces the content of the block with an empty string
	 *
	 * @since 4.8
	 *
	 * @param        $post_id
	 * @param string $block_name
	 *
	 * @return bool
	 */
	public function remove_block( $post_id, $block_name = '' ) {
		$content = get_post_field( 'post_content', $post_id );

		if ( empty( $content ) ) {
			return false;
		}

		$args = array(
			'ID'           => $post_id,
			'post_content' => preg_replace(
				'/^\s*<!-- ' . $block_name . ' .* \/-->\s*$/gm',
				'',
				$content
			),
		);

		return wp_update_post( $args );
	}
}
