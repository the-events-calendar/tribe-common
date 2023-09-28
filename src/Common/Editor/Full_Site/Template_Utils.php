<?php

namespace TEC\Common\Editor\Full_Site;

use InvalidArgumentException;
use WP_Block_Template;
use WP_Post;
use WP_Query;

/**
 * Class Template_Utils.
 *
 * @since   4.14.18
 *
 * @package TEC\Common\Editor\Full_Site
 */
class Template_Utils {
	/**
	 * Returns an array containing the references of the passed blocks and their inner blocks.
	 *
	 * When we return we are replacing/overwriting $blocks with $all_blocks so we pass-by-reference.
	 * If we don't pass-by-reference the non-event blocks get lost (ex: header and footer)
	 *
	 * @since 4.14.18
	 *
	 * @param array<array<string,mixed>> $blocks Array of parsed block objects.
	 *
	 * @return array<array<string,mixed>> Block references to the passed blocks and their inner blocks.
	 */
	public static function flatten_blocks( &$blocks ) {
		$all_blocks = [];
		$queue      = [];

		foreach ( $blocks as &$block ) {
			$queue[] = &$block;
		}

		$queue_count = count( $queue );

		while ( $queue_count > 0 ) {
			$block = &$queue[0];
			array_shift( $queue );
			$all_blocks[] = &$block;

			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as &$inner_block ) {
					$queue[] = &$inner_block;
				}
			}

			$queue_count = count( $queue );
		}

		return $all_blocks;
	}

	/**
	 * Parses wp_template content and injects the current theme's stylesheet as a theme attribute into
	 * each wp_template_part.
	 *
	 * @since 4.14.18
	 *
	 * @param string $template_content serialized wp_template content.
	 *
	 * @return string Updated wp_template content.
	 */
	public static function inject_theme_attribute_in_content( $template_content ) {
		$has_updated_content = false;
		$new_content         = '';
		$template_blocks     = parse_blocks( $template_content );

		$blocks = static::flatten_blocks( $template_blocks );
		foreach ( $blocks as &$block ) {
			if (
				'core/template-part' === $block['blockName'] &&
				! isset( $block['attrs']['theme'] )
			) {
				$block['attrs']['theme'] = wp_get_theme()->get_stylesheet();
				$has_updated_content     = true;
			}
		}

		if ( $has_updated_content ) {
			foreach ( $template_blocks as &$block ) {
				$new_content .= serialize_block( $block );
			}

			return $new_content;
		}

		return $template_content;
	}

	public static function find_block_template_by_post(string $post_name, string $terms =  'tec'):?WP_Block_Template {
		// Let's see if we have a saved template?
		$wp_query_args  = [
			'post_name__in'  => [ $post_name ],
			'post_type'      => 'wp_template',
			'post_status'    => [ 'auto-draft', 'draft', 'publish', 'trash' ],
			'posts_per_page' => 1,
			'no_found_rows'  => true,
			'tax_query'      => [
				[
					'taxonomy' => 'wp_theme',
					'field'    => 'name',
					'terms'    => $terms,
				],
			],
		];
		$template_query = new WP_Query( $wp_query_args );
		$posts          = $template_query->posts;
		
		// If empty, this is our first time loading our Block Template. Let's create it.
		if ( empty( $posts ) ) {
			return null;
		} 
		
		$post = $posts[0] ?? null;
		
		// Validate our query result.
		if ( ! $post instanceof WP_Post ) {
			do_action( 'tribe_log', 'error',
				'Failed locating our Post for the Block Template', [
					'method' => __METHOD__,
					'post_name' =>$post_name,
					'terms' => $terms
				] );

			// Might as well bail, avoid errors below.
			return null;
		}

		return self::hydrate_block_template_by_post($post);
	}

	public static function create_block_template($post_array) {
		if(empty($post_array['post_name']) ) {
			throw new InvalidArgumentException("Must have both `post_name` parameter to denote this template uniquely.");
		}
		// @todo will this work if our namespace isn't in term yet ...?
		if(empty($post_array['tax_input'])) {
			throw new InvalidArgumentException("Must have both `tax_input` parameter to include the term of the `wp_theme` this template is under.");
		}

		// Merge with default params.
		$insert = array_merge([
			'post_type'    => 'wp_template',
			'post_status'  => 'publish',
		], $post_array);

		// Create this template.
		$id = wp_insert_post( $insert );
		if(!$id) {
			return null;
		}

		return self::hydrate_block_template_by_post( get_post( $id ));
	}

	public static function hydrate_block_template_by_post( $post ):?WP_Block_Template {
		$terms                  = get_the_terms( $post, 'wp_theme' );
		if ( is_wp_error( $terms ) || empty($terms) ) {
			return null;
		}

		// Hydrate our template with the saved data.
		$template      = new WP_Block_Template();
		$template->wp_id          = $post->ID;
		$template->id             = $terms[0]->name . '//' . $post->post_name;
		$template->theme          = $terms[0]->name;
		$template->content        = $post->post_content;
		$template->slug           = $post->post_name;
		$template->source         = 'custom';
		$template->type           = 'wp_template';
		$template->title          = $post->post_title;
		$template->description    = $post->post_excerpt;
		$template->status         = $post->post_status;
		$template->has_theme_file = false;
		$template->is_custom      = true;
		$template->author         = $post->post_author;

		return $template;
	}
}
