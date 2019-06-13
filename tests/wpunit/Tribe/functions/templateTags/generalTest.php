<?php
namespace Tribe\functions\templateTags;

class generalTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * Test tribe_format_currency
	 */
	public function test_tribe_format_currency() {
		$post_id = $this->factory->post->create();
		add_filter( 'tribe_currency_symbol', function () {
			return 'Q';
		} );
		add_filter( 'tribe_reverse_currency_position', function () {
			return false;
		} );

		$this->assertEquals( 'Q12', tribe_format_currency( 12, $post_id ) );
		$this->assertEquals( 'F12', tribe_format_currency( 12, $post_id, 'F' ) );
		$this->assertEquals( '12F', tribe_format_currency( 12, $post_id, 'F', true ) );
		$this->assertEquals( '12Q', tribe_format_currency( 12, $post_id, 'Q', true ) );
	}

	public function test_tribe_post_checksum_w_post() {
		$post = $this->factory()->post->create_and_get();

		$this->assertEquals(
			md5( $post->ID . '|' . $post->post_modified ),
			tribe_post_checksum( $post )
		);
		$this->assertEquals(
			md5( $post->ID . '|' . $post->post_title ),
			tribe_post_checksum( $post, [ 'ID', 'post_title' ] )
		);
		$this->assertEquals(
			md5( $post->post_title . '|' . $post->ID ),
			tribe_post_checksum( $post, [ 'post_title', 'ID' ] )
		);
	}

	/**
	 * @dataProvider tribe_posts_checksum_bad_inputs
	 */
	public function test_tribe_post_checksum_w_bad_input( $input ) {
		$this->assertNull( tribe_post_checksum( $input ) );
		$this->assertNull( tribe_posts_checksum( $input ) );
	}

	public function tribe_posts_checksum_bad_inputs() {
		return [
			'empty-string' => [ '' ],
			'empty-array' => [ [] ],
			'none-a-post-1' => [ 'foo', 'bar', 'baz' ],
			'none-a-post-2' => [ 23, 89, 2389 ],
			'null' => [ null ],
			'not-a-post' => [ 23 ],
			'string' => [ 'doo' ],
			'array' => [ [] ],
		];
	}

	public function test_tribe_posts_checksum_w_posts() {
		$posts = $this->factory()->post->create_many( 3 );

		$expected = md5(
			implode(
				'|',
				array_map(
					function ( $post ) {
						return $post . '|' . get_post( $post )->post_modified;
					},
					$posts
				)
			)
		);

		$this->assertEquals( $expected, tribe_posts_checksum( $posts ) );

		$expected = md5(
			implode(
				'|',
				array_map(
					function ( $post ) {
						return $post . '|' . get_post( $post )->post_title;
					},
					$posts
				)
			)
		);

		$this->assertEquals( $expected, tribe_posts_checksum( $posts, [ 'ID', 'post_title' ] ) );
	}

	/**
	 * Test tribe_posts_checksum w post objects
	 */
	public function test_tribe_posts_checksum_w_post_objects() {
		$posts = $id_ordered = array_map( 'get_post', $this->factory()->post->create_many( 3 ) );
		shuffle( $posts );

		$expected = md5(
			implode(
				'|',
				array_map(
					function ( $post ) {
						return $post->ID . '|' . $post->post_modified;
					},
					$id_ordered
				)
			)
		);

		$this->assertEquals( $expected, tribe_posts_checksum( $posts ) );
	}

	/**
	 * Test tribe_posts_checksum_w_mixed_post_objects
	 */
	public function test_tribe_posts_checksum_w_mixed_post_objects() {
		$posts = $id_ordered = array_map( 'get_post', $this->factory()->post->create_many( 3 ) );
		shuffle( $posts );

		$expected = md5(
			implode(
				'|',
				array_map(
					function ( $post ) {
						return $post->ID . '|' . $post->post_modified;
					},
					$id_ordered
				)
			)
		);

		$this->assertEquals(
			$expected,
			tribe_posts_checksum(
				[
					$posts[0],
					$posts[1]->ID,
					$posts[2]->ID,
				]
			)
		);
	}
}
