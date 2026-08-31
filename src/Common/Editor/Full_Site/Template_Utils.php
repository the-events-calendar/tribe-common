<?php

namespace TEC\Common\Editor\Full_Site;

use InvalidArgumentException;
use WP_Block_Template;
use WP_Post;
use WP_Query;

/**
 * Class Template_Utils.
 *
 * @since 4.14.18
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

	/**
	 * Fetch a WP_Block_Template by the post_name and terms field.
	 *
	 * @since 5.1.14
	 *
	 * @param string $post_name The post_name field to search by.
	 * @param string $terms     The terms field to search by, default to 'tec'.
	 *
	 * @return WP_Block_Template|null The newly created WP_Block_Template, or null on failure to locate.
	 */
	public static function find_block_template_by_post( string $post_name, string $terms = 'tec' ): ?WP_Block_Template {
		// Let's see if we have a saved template?
		$wp_query_args  = [
			'post_name__in'  => [ $post_name ],
			'post_type'      => 'wp_template',
			'post_status'    => [ 'auto-draft', 'draft', 'publish' ],
			'posts_per_page' => -1,
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

		// If empty, our Block Template has not been created in `wp_posts` yet.
		if ( empty( $posts ) ) {
			return null;
		}

		$posts = self::sort_block_template_claimants( $posts );
		$post  = array_shift( $posts );

		/*
		 * More than one row claiming the slug is what makes the Site Editor save ambiguous: core's own
		 * unique-slug guard then renames the row being saved, so the edit lands on a slug nothing
		 * resolves. Move the losers aside so a single row owns the slug from here on.
		 */
		foreach ( $posts as $duplicate ) {
			self::rename_duplicate_block_template( $duplicate, $post_name );
		}

		// Validate our query result.
		if ( ! $post instanceof WP_Post ) {
			do_action( 'tribe_log', 'error',
				'Failed locating our Post for the Block Template', [
					'method'    => __METHOD__,
					'post_name' => $post_name,
					'terms'     => $terms
				] );

			// Might as well bail, avoid errors below.
			return null;
		}

		return self::hydrate_block_template_by_post( $post );
	}

	/**
	 * Create a post for the wp_theme and return the hydrated WP_Block_Template.
	 *
	 * @since 5.1.14
	 *
	 * @param array $post_array Post array for insert.
	 *
	 * @return WP_Block_Template|null The newly created WP_Block_Template, or null on error.
	 * @throws InvalidArgumentException
	 */
	public static function save_block_template( $post_array ): ?WP_Block_Template {
		if ( empty( $post_array['post_name'] ) ) {
			throw new InvalidArgumentException( "Must have `post_name` parameter to denote this template uniquely." );
		}

		if ( empty( $post_array['tax_input'] ) ) {
			throw new InvalidArgumentException( "Must have `tax_input` parameter to include the term of the `wp_theme` this template is under." );
		}

		// Merge with default params.
		$insert = array_merge( [
			'post_type'   => 'wp_template',
			'post_status' => 'publish',
		], $post_array );

		// Create this template.
		$id = wp_insert_post( $insert );

		if ( ! $id ) {
			return null;
		}

		/*
		 * `wp_insert_post()` only honours `tax_input` for a user who can `assign_terms`, and `wp_theme`
		 * declares no capabilities so it inherits `edit_posts`. A resolution served to a visitor would
		 * otherwise store a termless row that no lookup can ever match, and insert another one on every
		 * subsequent request.
		 */
		if ( is_array( $insert['tax_input'] ) ) {
			foreach ( $insert['tax_input'] as $taxonomy => $terms ) {
				wp_set_object_terms( $id, $terms, $taxonomy );
			}
		}

		return self::hydrate_block_template_by_post( get_post( $id ) );
	}

	/**
	 * Hydrate a WP_Block_Template from a WP_Post object.
	 *
	 * @since 5.1.14
	 *
	 * @param WP_Post $post The post to hydrate the WP_Block_Template.
	 *
	 * @return WP_Block_Template|null The newly created WP_Block_Template, or null if missing required data.
	 */
	public static function hydrate_block_template_by_post( WP_Post $post ): ?WP_Block_Template {
		$terms = get_the_terms( $post, 'wp_theme' );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return null;
		}

		// Hydrate our template with the saved data.
		$template                 = new WP_Block_Template();
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
		$template->modified       = $post->post_modified;

		return $template;
	}

	/**
	 * Orders the posts claiming a template slug so the canonical one comes first.
	 *
	 * Published rows outrank the rest, then the oldest row wins: when this bug has been reseeding
	 * templates the newest row is the plugin's default markup and the oldest one still holds the
	 * customization.
	 *
	 * @since TBD
	 *
	 * @param array<WP_Post> $posts The posts claiming the slug.
	 *
	 * @return array<WP_Post> The posts, canonical one first.
	 */
	private static function sort_block_template_claimants( array $posts ): array {
		usort(
			$posts,
			static function ( WP_Post $a, WP_Post $b ): int {
				$a_rank = 'publish' === $a->post_status ? 0 : 1;
				$b_rank = 'publish' === $b->post_status ? 0 : 1;

				if ( $a_rank !== $b_rank ) {
					return $a_rank <=> $b_rank;
				}

				if ( $a->post_date_gmt !== $b->post_date_gmt ) {
					return strcmp( $a->post_date_gmt, $b->post_date_gmt );
				}

				return $a->ID <=> $b->ID;
			}
		);

		return $posts;
	}

	/**
	 * Moves a duplicate template off the slug it is contesting.
	 *
	 * The row is renamed rather than trashed: a site running with `EMPTY_TRASH_DAYS` at 0 deletes on
	 * trash, which would destroy the only copy of a layout this bug has stranded on a duplicate.
	 *
	 * The ID is part of the new slug because core returns from `wp_unique_post_slug()` before the
	 * `wp_template` dedupe filter for draft statuses, so several renamed drafts would otherwise land
	 * on one slug and recreate the ambiguity being cleaned up here.
	 *
	 * @since TBD
	 *
	 * @param WP_Post $post      The duplicate to rename.
	 * @param string  $post_name The slug being contested.
	 *
	 * @return void
	 */
	private static function rename_duplicate_block_template( WP_Post $post, string $post_name ): void {
		wp_update_post(
			[
				'ID'        => $post->ID,
				'post_name' => $post_name . '-duplicate-' . $post->ID,
			]
		);
	}
}
