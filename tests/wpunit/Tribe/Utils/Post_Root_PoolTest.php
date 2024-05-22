<?php
namespace Tribe\Utils;

use \Tribe__Utils__Post_Root_Pool as Post_Root_Pool;

class Post_Root_PoolTest extends \Codeception\TestCase\WPTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		Post_Root_Pool::reset_pool();
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'Tribe__Utils__Post_Root_Pool', $sut );
	}

	/**
	 * @test
	 * it should generate a unique post root from the post_name
	 */
	public function it_should_generate_a_unique_post_root_from_the_post_name() {
		$post = $this->factory()->post->create_and_get( [ 'post_title' => 'foo', 'post_name' => 'foo' ] );

		$sut = $this->make_instance();

		$root = $sut->generate_unique_root( $post );

		$this->assertEquals( 'FOO-', $root );
	}

	/**
	 * @test
	 * it should generate different post roots for posts with same post_title
	 */
	public function it_should_generate_different_post_roots_for_posts_with_same_post_title() {
		$post_1 = $this->factory()->post->create_and_get( [ 'post_title' => 'foo', 'post_name' => 'foo' ] );
		$post_2 = $this->factory()->post->create_and_get( [ 'post_title' => 'foo', 'post_name' => 'foo-1' ] );

		$sut = $this->make_instance();

		$root_1 = $sut->generate_unique_root( $post_1 );
		$root_2 = $sut->generate_unique_root( $post_2 );

		$this->assertEquals( 'FOO-', $root_1 );
		$this->assertEquals( 'FOO1-', $root_2 );
	}

	/**
	 * @test
	 * it should generate different post roots for posts with same long titles
	 */
	public function it_should_generate_different_post_roots_for_posts_with_same_long_titles() {
		$post_1 = $this->factory()->post->create_and_get( [ 'post_title' => 'Lorem Ipsum Dolor Sit', 'post_name' => 'lorem-ipsum-dolor-sit' ] );
		$post_2 = $this->factory()->post->create_and_get( [ 'post_title' => 'Lorem Ipsum Dolor Sit', 'post_name' => 'lorem-ipsum-dolor-sit-1' ] );

		$sut = $this->make_instance();

		$root_1 = $sut->generate_unique_root( $post_1 );
		$root_2 = $sut->generate_unique_root( $post_2 );

		$this->assertEquals( 'LIDS-', $root_1 );
		$this->assertEquals( 'LIDS1-', $root_2 );
	}

	public function post_names() {
		return [
			[ 'foo', 'FOO-' ],
			[ 'foo-21', 'FOO21-' ],
			[ 'foo-bar-baz', 'FOOBARBAZ-' ],
			[ 'foo-bar-baz-1', 'FBB1-' ],
			[ 'foo-bar-baz-23', 'FBB23-' ],
			[ 'barbecue-with-john', 'BWJ-' ],
			[ 'barbecue-with-john-1', 'BWJ1-' ],
			[ 'this-post-has-a-very-long-post-name-with-a-lot-of-words-in-it', 'TPHAVLPNWALOWII-' ],
			[ 'oktoberfest-2016', 'O2016-' ],
			[ 'burning-man-2016', 'BM2016-' ],
		];
	}

	/**
	 * @test
	 * it should generate foreseeable post roots
	 * @dataProvider post_names
	 */
	public function it_should_generate_foreseeable_post_roots( $post_name, $expected ) {
		$post = $this->factory()->post->create_and_get( [ 'post_title' => 'Lorem Ipsum Dolor Sit', 'post_name' => $post_name ] );

		$sut = $this->make_instance();

		$root = $sut->generate_unique_root( $post );

		$this->assertEquals( $expected, $root );
	}

	/**
	 * @test
	 * it should avoid root conflicts when generating roots for similarly titled posts
	 */
	public function it_should_avoid_root_conflicts_when_generating_roots_for_similarly_titled_posts() {
		$post_1 = $this->factory()->post->create_and_get( [ 'post_title' => 'An Awesome Event', 'post_name' => 'an-awesome-event' ] );
		$post_2 = $this->factory()->post->create_and_get( [ 'post_title' => 'An Appaling Event', 'post_name' => 'an-appaling-event' ] );
		$post_3 = $this->factory()->post->create_and_get( [ 'post_title' => 'An Astonishing Event', 'post_name' => 'an-astonishing-event' ] );
		$post_4 = $this->factory()->post->create_and_get( [ 'post_title' => 'An Amazing Event', 'post_name' => 'an-amazing-event' ] );

		$sut = $this->make_instance();

		$root_1 = $sut->generate_unique_root( $post_1 );
		$root_2 = $sut->generate_unique_root( $post_2 );
		$root_3 = $sut->generate_unique_root( $post_3 );
		$root_4 = $sut->generate_unique_root( $post_4 );

		$this->assertEquals( 'AAE-', $root_1 );
		$this->assertEquals( 'AAE-1-', $root_2 );
		$this->assertEquals( 'AAE-2-', $root_3 );
		$this->assertEquals( 'AAE-3-', $root_4 );
	}

	/**
	 * @test
	 * it should handle n scale unique pool creation
	 */
	public function it_should_handle_n_scale_unique_pool_creation() {
		$sut = $this->make_instance();

		for ( $i = 0; $i < 200; $i ++ ) {
			$post_name = 'an-awesome-e' . md5( microtime() );
			$post      = $this->factory()->post->create_and_get( [ 'post_title' => 'An Awesome Event', 'post_name' => $post_name ] );
			$root      = $sut->generate_unique_root( $post );
		}

		$this->assertEquals( 'AAE-199-', $root );
	}

	/**
	 * @test
	 * it should not generate a root for the same post twice
	 */
	public function it_should_not_generate_a_root_for_the_same_post_twice() {
		$post = $this->factory()->post->create_and_get( [ 'post_title' => 'An Awesome Event', 'post_name' => 'an-awesome-event' ] );

		$sut = $this->make_instance();

		$root_1 = $sut->generate_unique_root( $post );
		$root_2 = $sut->generate_unique_root( $post );
		$root_3 = $sut->generate_unique_root( $post );

		$this->assertEquals( 'AAE-', $root_1 );
		$this->assertEquals( 'AAE-', $root_2 );
		$this->assertEquals( 'AAE-', $root_3 );
		$this->assertEquals( array( 'AAE' => $post->ID ), $sut->get_pool() );
	}

	private function make_instance() {
		return new Post_Root_Pool();
	}
}