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
	}

	/**
	 * @test
	 */
	public function should_throw_exception_missing_params_on_create_block_template_post() {
		$this->expectException( InvalidArgumentException::class );
		Template_Utils::save_block_template( [ 'tax_input' => 'bob' ] );
		Template_Utils::save_block_template( [ 'post_name' => 'bob' ] );
	}
}
