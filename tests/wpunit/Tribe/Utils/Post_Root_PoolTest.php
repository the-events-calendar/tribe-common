<?php
namespace Tribe\Utils;

class Post_Root_PoolTest extends \Codeception\TestCase\WPTestCase {

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

	private function make_instance() {
		return new \Tribe__Utils__Post_Root_Pool();
	}
}