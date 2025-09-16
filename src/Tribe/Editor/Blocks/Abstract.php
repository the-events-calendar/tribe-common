<?php

abstract class Tribe__Editor__Blocks__Abstract
implements Tribe__Editor__Blocks__Interface {

	/**
	 * Namespace for Blocks from tribe
	 *
	 * @since 4.8
	 *
	 * @var string
	 */
	protected $namespace = 'tribe';

	/**
	 * Whether the block should register assets
	 *
	 * @since 6.7.0
	 *
	 * @return bool
	 */
	public function should_register_assets(): bool {
		return true;
	}

	/**
	 * Builds the name of the Block
	 *
	 * @since 4.8
	 *
	 * @return string
	 */
	public function name() {
		if ( false === strpos( $this->slug(), $this->namespace . '/' ) ) {
			return $this->namespace . '/' . $this->slug();
		} else {
			return $this->slug();
		}
	}

	/**
	 * Return the namespace to child or external sources
	 *
	 * @since 4.8
	 *
	 * @return string
	 */
	public function get_namespace(): string {
		return $this->namespace;
	}

	/**
	 * Return the block attributes
	 *
	 * @since 4.8
	 *
	 * @param array $params The parameters to parse into the block attributes.
	 *
	 * @return array The parsed attributes.
	*/
	public function attributes( $params = [] ) {

		// Get the default attributes.
		$default_attributes = $this->default_attributes();

		// Parse the attributes with the default ones.
		$attributes = wp_parse_args(
			$params,
			$default_attributes
		);

		/**
		 * Filters the default attributes for the block.
		 *
		 * @param array  $attributes The attributes.
		 * @param object $instance   The current object.
		 */
		$attributes = apply_filters( 'tribe_block_attributes_defaults_' . $this->slug(), $attributes, $this );

		return $attributes;
	}

	/**
	 * Return the block default attributes
	 *
	 * @since 4.8
	 *
	 * @return array The default attributes.
	*/
	public function default_attributes() {

		$attributes = [];

		/**
		 * Filters the default attributes.
		 *
		 * @param array  $attributes The attributes.
		 * @param object $instance   The current object.
		 */
		$attributes = apply_filters( 'tribe_block_attributes_defaults', $attributes, $this );

		return $attributes;
	}

	/**
	 * Since we are dealing with a Dynamic type of Block we need a PHP method to render it.
	 *
	 * @since 4.8
	 *
	 * @param array $attributes The attributes to render.
	 *
	 * @return string The rendered block. The default implementation returns a placeholder text.
	 */
	public function render( $attributes = [] ) {
		$json_string = json_encode( $attributes, JSON_PRETTY_PRINT );

		return
		'<pre class="tribe-placeholder-text-' . $this->name() . '">' .
			'Block Name: ' . $this->name() . "\n" .
			'Block Attributes: ' . "\n" . $json_string .
		'</pre>';
	}

	/**
	 * Sends a valid JSON response to the AJAX request for the block contents.
	 *
	 * @since 4.8
	 *
	 * @return void
	 */
	public function ajax() {
		wp_send_json_error( esc_attr__( 'Problem loading the block, please remove this block to restart.', 'tribe-common' ) );
	}

	/**
	 * Does the registration for PHP rendering for the Block, important due to being a dynamic Block.
	 *
	 * @since 4.8
	 *
	 * @return void
	 */
	public function register() {
		$block_args = $this->get_registration_args( [
			'render_callback' => [ $this, 'render' ],
		] );

		// Prevents a block from being registered twice.
		if ( ! class_exists( 'WP_Block_Type_Registry' ) || WP_Block_Type_Registry::get_instance()->is_registered( $this->name() ) ) {
			return;
		}

		register_block_type( $this->get_registration_block_type(), $block_args );
	}

	/**
	 * Registering the block and loading the assets and hooks should be handled separately.
	 *
	 * @since 4.14.13
	 */
	public function load() {
		add_action( 'wp_ajax_' . $this->get_ajax_action(), [ $this, 'ajax' ] );

		$this->assets();
		$this->hook();
	}

	/**
	 * Determine whether a post or content string has this block.
	 *
	 * This test optimizes for performance rather than strict accuracy, detecting
	 * the pattern of a block but not validating its structure. For strict accuracy
	 * you should use the block parser on post content.
	 *
	 * @since 4.8
	 * @since 5.1.5 Added a has_block filter.
	 *
	 * @see gutenberg_parse_blocks()
	 *
	 * @param int|string|WP_Post|null $post Optional. Post content, post ID, or post object. Defaults to global $post.
	 *
	 * @return bool Whether the post has this block.
	 */
	public function has_block( $post = null ) {
		$wp_post = null;
		$post_id = null;

		if ( is_numeric( $post ) || $post === null ) {
			$wp_post = get_post( $post );
		} elseif ( $post instanceof WP_Post ) {
			$wp_post = $post;
		}

		if ( $wp_post instanceof WP_Post ) {
			$post    = $wp_post->post_content;
			$post_id = $wp_post->ID;
		}

		$has_block = false !== strpos( (string) $post, '<!-- wp:' . $this->name() );

		/**
		 * Filters whether the post has this block.
		 *
		 * @since 5.1.5
		 *
		 * @param bool                            $has_block  Whether the post has this block.
		 * @param WP_Post|null                    $wp_post    The post object.
		 * @param int|null                        $post_id    The post ID.
		 * @param string                          $block_name The block name.
		 * @param Tribe__Editor__Blocks__Abstract $instance   The block object.
		 */
		$has_block = (bool) apply_filters( 'tec_block_has_block', $has_block, $wp_post, $post_id, $this->name(), $this );
		$block_name = $this->name();

		/**
		 * Filters whether the post has this block.
		 *
		 * @since 5.1.5
		 *
		 * @param bool                            $has_block  Whether the post has this block.
		 * @param WP_Post|null                    $wp_post    The post object.
		 * @param int|null                        $post_id    The post ID.
		 * @param Tribe__Editor__Blocks__Abstract $instance The block object.
		 */
		return (bool) apply_filters( "tec_block_{$block_name}_has_block", $has_block, $wp_post, $post_id, $this );
	}

	/**
	 * Fetches the name for the block we are working with and converts it to the
	 * correct `wp_ajax_{$action}` string for us to Hook.
	 *
	 * @since 4.8
	 *
	 * @return string
	 */
	public function get_ajax_action() {
		return str_replace( 'tribe/', 'tribe_editor_block_', $this->name() );
	}

	/**
	 * Used to include any Assets for the Block we are registering.
	 *
	 * @since 4.8
	 *
	 * @return void
	 */
	public function assets() {
	}

	/**
	 * Attach any particular hook for the specific block.
	 *
	 * @since 4.8
	 */
	public function hook() {
	}

	/**
	 * Returns the block data for the block editor.
	 *
	 * @since 4.12.0
	 *
	 * @return array<string,mixed> The block editor data.
	 */
	public function block_data() {
		$block_data = [
			'id' => $this->slug(),
		];

		/**
		 * Filters the block data.
		 *
		 * @since 4.12.0
		 *
		 * @param array  $block_data The block data.
		 * @param object $instance   The current object.
		 */
		$block_data = apply_filters( 'tribe_block_block_data', $block_data, $this );

		/**
		 * Filters the block data for the block.
		 *
		 * @since 4.12.0
		 *
		 * @param array  $block_data The block data.
		 * @param object $instance   The current object.
		 */
		$block_data = apply_filters( 'tribe_block_block_data_' . $this->slug(), $block_data, $this );

		return $block_data;
	}

	/**
	 * Returns the block type argument that should be used to register the block in the `register_block_type`
	 * function.
	 *
	 * @see register_block_type() for the values that can be used in the `block_type` argument.
	 *
	 * @since 5.2.0
	 *
	 * @return string|WP_Block_Type The block type argument that will be used to register the block.
	 */
	public function get_registration_block_type() {
		return $this->name();
	}

	/**
	 * Allows extending blocks to modify and update the arguments used to register the block
	 * in the `register_block_type` function.
	 *
	 * @since 5.2.0
	 *
	 * @param array<string,mixed> $args The default arguments the block would be registered with if this method is not
	 *                                  overridden.
	 *
	 * @return array<string,mixed> The arguments to use when registering the block.
	 */
	public function get_registration_args( array $args ): array {
		return $args;
	}
}
