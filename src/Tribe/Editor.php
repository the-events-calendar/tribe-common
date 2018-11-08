<?php
/**
 * Initialize Gutenberg editor blocks
 *
 * @since TBD
 */
class Tribe__Editor {

	/**
	 * Meta key for flaging if a post is from classic editor
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $key_flag_classic_editor = '_tribe_is_classic_editor';

	/**
	 * Utility function to check if we should load the blocks or not based on two assumptions
	 *
	 * a) Is gutenberg active?
	 * b) Is the blocks editor active?
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function should_load_blocks() {
		return (
			$this->is_gutenberg_active()
			|| $this->is_wp_version()
		)
		&& $this->is_blocks_editor_active();
	}

	/**
	 * Checks if we are on version 5.0-alpha or higher where we no longer have
	 * Gutenberg Project, but the Blocks Editor
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function is_wp_version() {
		global $wp_version;

		return version_compare( $wp_version, '5.0-alpha', '>=' );
	}

	/**
	 * Checks if we have Gutenberg Project online, only useful while
	 * its a external plugin
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function is_gutenberg_active() {
		return function_exists( 'the_gutenberg_project' );
	}

	/**
	 * Checks if we have Editor Block active
	 *
	 * @since TBD
	 *
	 * @return boolean
	 */
	public function is_blocks_editor_active() {
		return function_exists( 'register_block_type' ) && function_exists( 'unregister_block_type' );
	}

	/**
	 * Adds the required fields into the Events Post Type so that we can use Block Editor
	 *
	 * @since TBD
	 *
	 * @param  array $args Arguments used to setup the Post Type
	 *
	 * @return array
	 */
	public function add_support( $args = array() ) {
		// Make sure we have the Support argument and it's an array
		if ( ! isset( $args['supports'] ) || ! is_array( $args['supports'] ) ) {
			$args['supports'] = array();
		}

		// Add Editor Support
		if ( ! in_array( 'editor', $args['supports'] ) ) {
			$args['supports'][] = 'editor';
		}

		return $args;
	}

	/**
	 * Adds the required fields into the Post Type so that we can the Rest API to update it
	 *
	 * @since TBD
	 *
	 * @param  array $args Arguments used to setup the Post Type
	 *
	 * @return array
	 */
	public function add_rest_support( $args = array() ) {
		// Blocks Editor requires REST support
		$args['show_in_rest'] = true;

		// Make sure we have the Support argument and it's an array
		if ( ! isset( $args['supports'] ) || ! is_array( $args['supports'] ) ) {
			$args['supports'] = array();
		}

		if ( ! in_array( 'revisions', $args['supports'] ) ) {
			$args['supports'][] = 'revisions';
		}

		// Add Custom Fields (meta) Support
		if ( ! in_array( 'custom-fields', $args['supports'] ) ) {
			$args['supports'][] = 'custom-fields';
		}

		// Add Post Title Support
		if ( ! in_array( 'title', $args['supports'] ) ) {
			$args['supports'][] = 'title';
		}

		// Add Post Excerpt Support
		if ( ! in_array( 'excerpt', $args['supports'] ) ) {
			$args['supports'][] = 'excerpt';
		}

		// Add Post Content Support
		if ( ! in_array( 'editor', $args['supports'] ) ) {
			$args['supports'][] = 'editor';
		}

		// Add Post Author Support
		if ( ! in_array( 'author', $args['supports'] ) ) {
			$args['supports'][] = 'author';
		}

		// Add Thumbnail Support
		if ( ! in_array( 'thumbnail', $args['supports'] ) ) {
			$args['supports'][] = 'thumbnail';
		}

		return $args;
	}

	/**
	 * Check if post is from classic editor
	 *
	 * @since TBD
	 *
	 * @param int|WP_Post $post
	 *
	 * @return bool
	 */
	public function post_is_from_classic_editor( $post ) {
		if ( ! $post instanceof WP_Post ) {
			$post = get_post( $post );
		}

		if ( empty( $post ) ) {
			return false;
		}

		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		return tribe_is_truthy( get_post_meta( $post->ID, $this->key_flag_classic_editor, true ) );
	}
}
