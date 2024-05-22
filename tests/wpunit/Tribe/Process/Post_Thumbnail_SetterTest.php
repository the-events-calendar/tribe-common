<?php

namespace Tribe\Process;

use Tribe__Process__Post_Thumbnail_Setter as Post_Thumbnail_Setter;

class Post_Thumbnail_SetterTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Post_Thumbnail_Setter::class, $sut );
	}

	/**
	 * @return Post_Thumbnail_Setter
	 */
	private function make_instance() {
		return new Post_Thumbnail_Setter();
	}

	/**
	 * It should set the post thumbnail
	 *
	 * @test
	 */
	public function should_set_the_post_thumbnail() {
		$image                   = codecept_data_dir( 'images/featured-image.jpg' );
		$attachment_id           = $this->factory()->attachment->create_upload_object( $image );
		$attachment              = get_post( $attachment_id );
		$post_id                 = $this->factory()->post->create();
		$_POST['post_id']        = $post_id;
		$_POST['post_thumbnail'] = $attachment->guid;

		$sut = $this->make_instance();
		$sut->sync_handle();

		$thumbnails = get_post_meta( $post_id, '_thumbnail_id' );
		$this->assertCount( 1, $thumbnails );
		$this->assertEquals( 'attachment', get_post( $thumbnails[0] )->post_type );
	}

	/**
	 * It should throw if trying to dispatch without setting args
	 *
	 * @test
	 */
	public function should_throw_if_trying_to_dispatch_without_setting_args() {
		$this->expectException( \InvalidArgumentException::class );

		$sut = $this->make_instance();
		$sut->dispatch();
	}

	/**
	 * It should set the thumbnail if post_thumbnail is id
	 *
	 * @test
	 */
	public function should_set_the_thumbail_if_post_thumbnail_is_id() {
		$image                   = codecept_data_dir( 'images/featured-image.jpg' );
		$attachment_id           = $this->factory()->attachment->create_upload_object( $image );
		$post_id                 = $this->factory()->post->create();
		$_POST['post_id']        = $post_id;
		$_POST['post_thumbnail'] = $attachment_id;

		$sut = $this->make_instance();
		$sut->sync_handle();

		$thumbnails = get_post_meta( $post_id, '_thumbnail_id' );
		$this->assertCount( 1, $thumbnails );
		$this->assertEquals( $attachment_id, $thumbnails[0] );
	}

	function setUp() {
		parent::setUp();
		unset( $_POST['post_id'], $_POST['post_thumbnail'] );
	}
}
