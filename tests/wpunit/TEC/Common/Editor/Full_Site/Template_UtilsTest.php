<?php

namespace TEC\Common\Editor\Full_Site;

use InvalidArgumentException;
use WP_Block_Template;

class Template_UtilsTest extends \Codeception\TestCase\WPTestCase {


	/**
	 * Should create a post and return the template.
	 *
	 * @test
	 */
	public function should_create_block_template_post() {
		$faux_post = [
			'post_name'    => 'bob',
			'tax_input'    => [ 'wp_theme' => 'tec' ],
			'post_content' => 'Lorem ipsum...',
			'post_type'    => 'wp_template',
		];
		$id        = static::factory()->user->create( [ 'role' => 'administrator' ] );
		// Needs access to add term
		wp_set_current_user( $id );
		$templateA = Template_Utils::save_block_template( $faux_post );
		$templateB = Template_Utils::save_block_template( $faux_post );
		$this->assertInstanceOf( WP_Block_Template::class, $templateA );
		$this->assertInstanceOf( WP_Block_Template::class, $templateB );
		$this->assertGreaterThan( 0, $templateA->wp_id );
		$this->assertGreaterThan( 0, $templateB->wp_id );
		$this->assertNotEquals( $templateA->wp_id, $templateB->wp_id );

		wp_set_current_user( 0 );
		wp_delete_user( $id );
	}

	/**
	 * Should hydrate the template from a post.
	 *
	 * @test
	 */
	public function should_hydrate_block_template() {
		$faux_post = [
			'post_name'    => 'bob-burgs',
			'tax_input'    => [ 'wp_theme' => 'tec' ],
			'post_content' => 'Lorem ipsum...',
			'post_type'    => 'wp_template',
			'post_excerpt' => 'Lorem',
			'post_title'   => 'Bobs Burgers',
		];
		$user_id   = static::factory()->user->create( [ 'role' => 'administrator' ] );

		// Needs access to add term
		wp_set_current_user( $user_id );
		$id             = wp_insert_post( $faux_post );
		$post           = get_post( $id );
		$block_template = Template_Utils::hydrate_block_template_by_post( $post );

		$this->assertInstanceOf( WP_Block_Template::class, $block_template );
		$this->assertEquals( $post->ID, $block_template->wp_id );
		$this->assertEquals( 'tec//' . $post->post_name, $block_template->id );
		$this->assertEquals( 'tec', $block_template->theme );
		$this->assertEquals( $post->post_content, $block_template->content );
		$this->assertEquals( $post->post_name, $block_template->slug );
		$this->assertEquals( 'custom', $block_template->source );
		$this->assertEquals( 'wp_template', $block_template->type );
		$this->assertEquals( $post->post_title, $block_template->title );
		$this->assertEquals( $post->post_excerpt, $block_template->description );
		$this->assertEquals( $post->post_status, $block_template->status );
		$this->assertEquals( false, $block_template->has_theme_file );
		$this->assertEquals( true, $block_template->is_custom );
		$this->assertEquals( $user_id, $block_template->author );
		$this->assertEquals( $post->post_modified, $block_template->modified );

		wp_set_current_user( 0 );
		wp_delete_user( $user_id );
	}

	/**
	 * Should hydrate the template from a post.
	 *
	 * @test
	 */
	public function should_find_block_template() {
		$faux_post = [
			'post_name'    => 'bob',
			'tax_input'    => [ 'wp_theme' => 'tec' ],
			'post_content' => 'Lorem ipsum...',
			'post_type'    => 'wp_template',
		];
		$user_id   = static::factory()->user->create( [ 'role' => 'administrator' ] );

		// Needs access to add term
		wp_set_current_user( $user_id );
		$id             = wp_insert_post( $faux_post );
		$post           = get_post( $id );
		$block_template = Template_Utils::find_block_template_by_post( $post->post_name );

		$this->assertInstanceOf( WP_Block_Template::class, $block_template );
		$this->assertEquals( $post->ID, $block_template->wp_id );

		wp_set_current_user( 0 );
		wp_delete_user( $user_id );
	}

	/**
	 * The `wp_theme` taxonomy inherits the `edit_posts` capability for `assign_terms`, so a front-end
	 * resolution by a visitor used to store a termless row that no lookup could ever find again.
	 *
	 * @test
	 */
	public function should_attach_theme_term_without_the_assign_terms_capability() {
		wp_set_current_user( 0 );

		$template = Template_Utils::save_block_template(
			[
				'post_name'    => 'termless',
				'tax_input'    => [ 'wp_theme' => 'tec' ],
				'post_content' => 'Lorem ipsum...',
			]
		);

		$this->assertInstanceOf( WP_Block_Template::class, $template );
		$this->assertEquals( 'tec', $template->theme );
		$this->assertEquals( 'tec//termless', $template->id );
		$this->assertEquals( [ 'tec' ], wp_get_object_terms( $template->wp_id, 'wp_theme', [ 'fields' => 'names' ] ) );
	}

	/**
	 * @test
	 */
	public function should_return_the_oldest_published_claimant() {
		$oldest = $this->given_a_claimant( 'many-claimants', 'publish', '2020-01-01 00:00:00' );
		$middle = $this->given_a_claimant( 'many-claimants', 'publish', '2021-01-01 00:00:00' );
		$newest = $this->given_a_claimant( 'many-claimants', 'publish', '2022-01-01 00:00:00' );

		$template = Template_Utils::find_block_template_by_post( 'many-claimants' );

		$this->assertInstanceOf( WP_Block_Template::class, $template );
		$this->assertEquals( $oldest, $template->wp_id );
		$this->assertNotEquals( $middle, $template->wp_id );
		$this->assertNotEquals( $newest, $template->wp_id );
	}

	/**
	 * @test
	 */
	public function should_rename_the_losing_claimants_without_trashing_them() {
		$winner = $this->given_a_claimant( 'renamed-losers', 'publish', '2020-01-01 00:00:00' );
		$loser  = $this->given_a_claimant( 'renamed-losers', 'publish', '2021-01-01 00:00:00' );

		Template_Utils::find_block_template_by_post( 'renamed-losers' );

		$this->assertEquals( 'renamed-losers', get_post( $winner )->post_name );
		$this->assertStringStartsWith( 'renamed-losers-duplicate', get_post( $loser )->post_name );
		$this->assertEquals( 'publish', get_post( $loser )->post_status );
	}

	/**
	 * The draft is the newer row, so the default `post_date DESC` ordering would hand it back.
	 *
	 * @test
	 */
	public function should_prefer_a_published_claimant_over_a_newer_draft() {
		$published = $this->given_a_claimant( 'draft-and-publish', 'publish', '2020-01-01 00:00:00' );
		$draft     = $this->given_a_claimant( 'draft-and-publish', 'draft', '2021-01-01 00:00:00' );

		$template = Template_Utils::find_block_template_by_post( 'draft-and-publish' );

		$this->assertInstanceOf( WP_Block_Template::class, $template );
		$this->assertEquals( $published, $template->wp_id );
		$this->assertNotEquals( $draft, $template->wp_id );
	}

	/**
	 * The row is stored as trash rather than run through `wp_trash_post()`, which would append the
	 * `__trashed` suffix and take the row out of the slug query on its own.
	 *
	 * @test
	 */
	public function should_never_resolve_to_a_trashed_claimant() {
		$live    = $this->given_a_claimant( 'trashed-claimant', 'publish', '2020-01-01 00:00:00' );
		$trashed = $this->given_a_claimant( 'trashed-claimant', 'trash', '2021-01-01 00:00:00' );

		$template = Template_Utils::find_block_template_by_post( 'trashed-claimant' );

		$this->assertInstanceOf( WP_Block_Template::class, $template );
		$this->assertEquals( $live, $template->wp_id );
		$this->assertNotEquals( $trashed, $template->wp_id );
	}

	/**
	 * @test
	 */
	public function should_return_null_when_every_claimant_is_trashed() {
		$this->given_a_claimant( 'only-trash', 'trash', '2020-01-01 00:00:00' );

		$this->assertNull( Template_Utils::find_block_template_by_post( 'only-trash' ) );
	}

	/**
	 * Core returns early from `wp_unique_post_slug()` for draft statuses, before the `wp_template`
	 * dedupe filter runs, so nothing else keeps the renamed losers apart.
	 *
	 * @test
	 */
	public function should_give_each_renamed_draft_claimant_a_distinct_slug() {
		$winner  = $this->given_a_claimant( 'draft-claimants', 'draft', '2020-01-01 00:00:00' );
		$loser_a = $this->given_a_claimant( 'draft-claimants', 'draft', '2021-01-01 00:00:00' );
		$loser_b = $this->given_a_claimant( 'draft-claimants', 'draft', '2022-01-01 00:00:00' );

		Template_Utils::find_block_template_by_post( 'draft-claimants' );

		$names = [
			get_post( $winner )->post_name,
			get_post( $loser_a )->post_name,
			get_post( $loser_b )->post_name,
		];

		$this->assertEquals( 'draft-claimants', $names[0] );
		$this->assertCount( count( $names ), array_unique( $names ) );
	}

	/**
	 * @test
	 */
	public function should_throw_exception_missing_params_on_create_block_template_post() {
		$this->expectException( InvalidArgumentException::class );
		Template_Utils::save_block_template( [ 'tax_input' => 'bob' ] );
		Template_Utils::save_block_template( [ 'post_name' => 'bob' ] );
	}

	/**
	 * Stores a `wp_template` row claiming a slug under the `tec` theme.
	 *
	 * The term is attached directly rather than through `tax_input` so the fixture does not depend on
	 * the behaviour under test.
	 *
	 * @param string $slug   The `post_name` the row claims.
	 * @param string $status The `post_status` to store the row with.
	 * @param string $date   The `post_date` to store the row with, in site time.
	 *
	 * @return int The stored post ID.
	 */
	private function given_a_claimant( string $slug, string $status, string $date ): int {
		$id = wp_insert_post(
			[
				'post_name'     => $slug,
				'post_type'     => 'wp_template',
				'post_status'   => $status,
				'post_content'  => 'Lorem ipsum...',
				'post_date'     => $date,
				'post_date_gmt' => get_gmt_from_date( $date ),
			]
		);

		wp_set_object_terms( $id, 'tec', 'wp_theme' );

		return $id;
	}
}
