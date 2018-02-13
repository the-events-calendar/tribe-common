<?php

namespace Tribe;

use Tribe__Context as Context;

class ContextTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Context::class, $sut );
	}

	/**
	 * @return Context
	 */
	private function make_instance() {
		return new Context();
	}

	/**
	 * It should correctly detect when editing a post
	 *
	 * @test
	 */
	public function should_correctly_detect_when_editing_a_post() {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_editing_post() );

		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$this->go_to( "/wp-admin/post.php?post={$post_id}&action=edit" );
		global $pagenow;
		$pagenow = 'post.php';

		$this->assertFalse( $sut->is_editing_post( 'page' ) );
		$this->assertTrue( $sut->is_editing_post( 'post' ) );
		$this->assertTrue( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertTrue( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );
	}

	/**
	 * It should be editing a post when creating a new post
	 *
	 * @test
	 */
	public function should_be_editing_a_post_when_creating_a_new_post() {
		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_editing_post() );

		$this->go_to( "/wp-admin/post-new.php" );
		global $pagenow;
		$pagenow = 'post-new.php';

		$this->assertTrue( $sut->is_editing_post() );
		$this->assertTrue( $sut->is_editing_post( 'post' ) );
		$this->assertFalse( $sut->is_editing_post( 'page' ) );
		$this->assertFalse( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertFalse( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );

		$this->go_to( "/wp-admin/post-new.php?post_type=page" );
		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'page';

		$this->assertTrue( $sut->is_editing_post() );
		$this->assertFalse( $sut->is_editing_post( 'post' ) );
		$this->assertTrue( $sut->is_editing_post( 'page' ) );
		$this->assertFalse( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertFalse( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );
	}

	/**
	 * It should correctly identify new posts
	 *
	 * @test
	 */
	public function should_correctly_identify_new_posts() {
		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_new_post() );

		$this->go_to( "/wp-admin/post-new.php" );
		global $pagenow;
		$pagenow = 'post-new.php';

		$this->assertTrue( $sut->is_new_post() );
		$this->assertTrue( $sut->is_new_post( 'post' ) );
		$this->assertFalse( $sut->is_new_post( 'page' ) );
		$this->assertFalse( $sut->is_new_post( $post_id ) );
		$this->assertTrue( $sut->is_new_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_new_post( $second_post_id ) );
		$this->assertFalse( $sut->is_new_post( 2389 ) );
		$this->assertFalse( $sut->is_new_post( array( $post_id, $second_post_id ) ) );

		$this->go_to( "/wp-admin/post-new.php?post_type=page" );
		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'page';

		$this->assertTrue( $sut->is_new_post() );
		$this->assertFalse( $sut->is_new_post( 'post' ) );
		$this->assertTrue( $sut->is_new_post( 'page' ) );
		$this->assertFalse( $sut->is_new_post( $post_id ) );
		$this->assertTrue( $sut->is_new_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_new_post( $second_post_id ) );
		$this->assertFalse( $sut->is_new_post( 2389 ) );
		$this->assertFalse( $sut->is_new_post( array( $post_id, $second_post_id ) ) );
	}

	function setUp() {
		parent::setUp();
		global $pagenow;
		$pagenow = null;
		unset( $_GET['post_type'] );
	}
}
