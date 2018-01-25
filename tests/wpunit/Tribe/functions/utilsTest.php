<?php

namespace Tribe\functions;

class utilsTest extends \Codeception\TestCase\WPTestCase {

	public function urls() {
		return [
			[ 'http://some.dev', 'foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev', 'foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev', '/foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev', '/foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', 'foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', 'foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', '/foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', '/foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev?bar=baz', 'foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', 'foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz&another=value', 'foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', 'foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev#frag', 'foo', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', 'foo/', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', '/foo', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', '/foo/', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev', 'some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', 'some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', '/some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', '/some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', 'some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', 'some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', '/some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', '/some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev?bar=baz', 'some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', 'some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz&another=value', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev#frag', 'some/foo', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', 'some/foo/', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', '/some/foo', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', '/some/foo/', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value#p1' ],
		];
	}

	/**
	 * @test
	 * it should allow appending path to various urls
	 * @dataProvider urls
	 */
	public function it_should_allow_appending_path_to_various_urls( $url, $path, $expected ) {
		$this->assertEquals( $expected, tribe_append_path( $url, $path ) );
	}

	/**
	 * Test tribe_post_exists
	 */
	public function test_tribe_post_exists() {
		$this->assertFalse( tribe_post_exists( 2323 ) );
		$this->assertFalse( tribe_post_exists( 2323, 'post' ) );
		$this->assertFalse( tribe_post_exists( 2323, 'page' ) );

		$post = $this->factory->post->create_and_get();

		$this->assertEquals( $post->ID, tribe_post_exists( $post ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->ID ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->ID, 'post' ) );
		$this->assertFalse( tribe_post_exists( $post->ID, 'page' ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->post_name ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->post_name, 'post' ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, 'page' ) );
	}

	/**
	 * Test tribe_post_exists with deleted post
	 */
	public function test_tribe_post_exists_with_deleted_post() {
		$post = $this->factory->post->create_and_get();

		wp_delete_post( $post->ID, true );

		$this->assertFalse( tribe_post_exists( $post ) );
		$this->assertFalse( tribe_post_exists( $post->ID ) );
		$this->assertFalse( tribe_post_exists( $post->ID, 'post' ) );
		$this->assertFalse( tribe_post_exists( $post->ID, 'page' ) );
		$this->assertFalse( tribe_post_exists( $post->post_name ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, 'post' ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, 'page' ) );
	}

	/**
	 * Test tribe_post_exists with user
	 */
	public function test_tribe_post_exists_with_user() {
		$user_id = $this->factory->user->create();

		$this->assertFalse( tribe_post_exists( $user_id ) );
		$this->assertFalse( tribe_post_exists( $user_id, 'post' ) );
		$this->assertFalse( tribe_post_exists( $user_id, 'page' ) );
	}
}