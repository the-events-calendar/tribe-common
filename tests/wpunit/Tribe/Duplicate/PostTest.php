<?php

namespace Tribe\Duplicate;

use Tribe__Duplicate__Post as Duplicate;

class PostTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \Tribe__Duplicate__Strategy_Factory
	 */
	protected $factory;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->factory = new \Tribe__Duplicate__Strategy_Factory();
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Duplicate::class, $this->make_instance() );
	}

	/**
	 * @return Duplicate
	 */
	protected function make_instance() {
		return new Duplicate( $this->factory );
	}

	/**
	 * It should mark a post as a duplicate of itself
	 *
	 * @test
	 */
	public function it_should_mark_a_post_as_a_duplicate_of_itself() {
		$post = $this->factory()->post->create_and_get();
		$postarr = (array) $post;

		$sut = $this->make_instance();
		$sut->use_post_fields( array_keys( $postarr ) );

		$duplicate = $sut->find_for( $postarr );

		$this->assertEquals( $post->ID, $duplicate );
	}

	/**
	 * It should mark a post as duplicate of itself if setting match strategy to 'same'
	 *
	 * @test
	 */
	public function it_should_mark_a_post_as_duplicate_of_itself_if_setting_match_strategy_to_same() {
		$post = $this->factory()->post->create_and_get();
		$postarr = (array) $post;

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' => [ 'match' => 'same' ] ] );

		$duplicate = $sut->find_for( $postarr );

		$this->assertEquals( $post->ID, $duplicate );
	}

	/**
	 * It should mark a post as duplicate of itself when trying to match title in different case
	 *
	 * Due to how SQL comparison works
	 *
	 * @test
	 */
	public function it_should_mark_a_post_as_duplicate_of_itself_when_trying_to_match_title_in_different_case() {
		$post = $this->factory()->post->create_and_get( [ 'post_title' => 'Original Title' ] );
		$postarr = (array) $post;

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' => [ 'match' => 'same' ] ] );

		$postarr['post_title'] = 'original title';
		$duplicate = $sut->find_for( $postarr );

		$this->assertEquals( $post->ID, $duplicate );
	}

	/**
	 * It should find duplicates when using same match strategy
	 *
	 * @test
	 */
	public function it_should_find_duplicates_when_using_same_match_strategy() {
		$id = $this->factory()->post->create( [ 'post_title' => 'Original Title' ] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' ] );

		$this->assertFalse( $sut->find_for( [ 'post_title' => 'Original' ] ) );
		$this->assertFalse( $sut->find_for( [ 'post_title' => 'Title' ] ) );
		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'original title' ] ) );
	}

	/**
	 * It should allow finding a match by 'similar' criteria
	 *
	 * @test
	 */
	public function it_should_allow_finding_a_match_by_similar_criteria() {
		$id = $this->factory()->post->create( [ 'post_title' => 'Original Title' ] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' => [ 'match' => 'like' ] ] );

		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'Original' ] ) );
		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'Title' ] ) );
		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'original title' ] ) );
	}

	/**
	 * It should remove non words, numbers and hyphens in 'like' match
	 *
	 * @test
	 */
	public function it_should_remove_non_words_numbers_and_hyphens_in_like_match() {
		$id = $this->factory()->post->create( [ 'post_title' => 'Original Title with SomeCamelCase and some_snake_case' ] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' => [ 'match' => 'like' ] ] );

		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'original_title' ] ) );
		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'someCamelCase_some_snake_case' ] ) );
		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'Some Snake Case' ] ) );
		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'some snake case' ] ) );
		$this->assertFalse( $sut->find_for( [ 'post_title' => 'some-snake-case-foo' ] ) ); // foo is not in the title
	}

	/**
	 * It should fallback to equality when trying to match on numeric post fields
	 *
	 * @test
	 */
	public function it_should_fallback_to_equality_when_trying_to_match_on_numeric_post_fields() {
		$editor = $this->factory()->user->create( [ 'role' => 'editor' ] );
		$id = $this->factory()->post->create( [ 'post_author' => $editor ] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_author' => [ 'match' => 'like' ] ] );

		$this->assertEquals( $id, $sut->find_for( [ 'post_author' => $editor ] ) );
		// if editor ID is '3' we are looking for '323'
		$this->assertFalse( $sut->find_for( [ 'post_author' => intval( $editor . '23' ) ] ) );
	}

	/**
	 * It should return false if trying to match not setting any post field
	 *
	 * @test
	 */
	public function it_should_return_false_if_trying_to_match_not_setting_any_post_field() {
		$post = $this->factory()->post->create_and_get();

		$sut = $this->make_instance();
		$sut->use_post_fields( [] );

		$this->assertFalse( $sut->find_for( [ 'post_title' => $post->post_title ] ) );
	}

	/**
	 * It should remove non post or meta fields from the match candidate
	 *
	 * @test
	 */
	public function it_should_remove_non_post_or_meta_fields_from_the_match_candidate() {
		$post = $this->factory()->post->create_and_get();

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' ] );

		$this->assertEquals( $post->ID, $sut->find_for( [ 'post_title' => $post->post_title, 'foo_bar' => 'some value' ] ) );
	}

	/**
	 * It should allow finding a duplicate matching a custom field exactly
	 *
	 * @test
	 */
	public function it_should_allow_finding_a_duplicate_matching_a_custom_field_exactly() {
		$post = $this->factory()->post->create();
		update_post_meta( $post, 'foo', 'some Value' );

		$sut = $this->make_instance();
		$sut->use_custom_fields( [ 'foo' => [ 'match' => 'same' ] ] );

		$this->assertEquals( $post, $sut->find_for( [ 'foo' => 'some value' ] ) );
		$this->assertFalse( $sut->find_for( [ 'foo' => 'some' ] ) );
		$this->assertFalse( $sut->find_for( [ 'foo' => 'Value' ] ) );
		$this->assertFalse( $sut->find_for( [ 'foo' => 'bar' ] ) );
	}

	/**
	 * It should allow finding a duplicate with custom field with like value
	 *
	 * @test
	 */
	public function it_should_allow_finding_a_duplicate_with_custom_field_with_like_value() {
		$post = $this->factory()->post->create();
		update_post_meta( $post, 'foo', 'some Value' );

		$sut = $this->make_instance();
		$sut->use_custom_fields( [ 'foo' => [ 'match' => 'like' ] ] );

		$this->assertEquals( $post, $sut->find_for( [ 'foo' => 'some value' ] ) );
		$this->assertEquals( $post, $sut->find_for( [ 'foo' => 'some' ] ) );
		$this->assertEquals( $post, $sut->find_for( [ 'foo' => 'Value' ] ) );
		$this->assertFalse( $sut->find_for( [ 'foo' => 'bar' ] ) );
	}

	public function mixed_match_criteria_inputs() {
		return [
			[ true, [ 'post_title' => 'A post' ] ],
			[ true, [ 'post_title' => 'a post' ] ],
			[
				true,
				[
					'post_title'   => 'a post',
					'post_content' => 'Some content',
				],
			],
			[
				true,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content',
				],
			],
			[
				true,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content',
					'foo'          => 'foo',
					'bar'          => 'bar value',
				],
			],
			[
				true,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content',
					'foo'          => 'foo value',
					'bar'          => 'bar value',
				],
			],
			[
				false,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content',
					'foo'          => 'foo',
					'bar'          => 'value', // not a match
				],
			],
			[
				false,
				[
					'post_title'   => 'post', // not a match
					'post_content' => 'some content',
					'foo'          => 'foo',
					'bar'          => 'bar value',
				],
			],
			[
				false,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content of mine', // not a match
					'foo'          => 'foo value',
					'bar'          => 'bar value',
				],
			],
			[
				true,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content',
					'foo'          => 'value foo', // inverted is ok
					'bar'          => 'bar value',
				],
			],
			[
				false,
				[
					'post_title'   => 'a post',
					'post_content' => 'some content',
					'foo'          => 'foo value',
					'bar'          => 'value bar', // inverted is not ok here
				],
			],
		];
	}

	/**
	 * It should allow finding duplicates using mixed post fields and meta and different strategies
	 *
	 * @test
	 * @dataProvider mixed_match_criteria_inputs
	 */
	public function it_should_allow_finding_duplicates_using_mixed_post_fields_and_meta_and_different_strategies( $should_match, $input ) {
		$post = $this->factory()->post->create( [ 'post_title' => 'A post', 'post_content' => 'Some content [shortcode]' ] );
		update_post_meta( $post, 'foo', 'Foo Value' );
		update_post_meta( $post, 'bar', 'Bar value' );

		$sut = $this->make_instance();
		$sut->use_post_fields( [
			'post_title'   => [ 'match' => 'same' ],
			'post_content' => [ 'match' => 'like' ],
		] );
		$sut->use_custom_fields( [
			'foo' => [ 'match' => 'like' ],
			'bar' => [ 'match' => 'same' ],
		] );

		if ( $should_match ) {
			$this->assertEquals( $post, $sut->find_for( $input ) );
		} else {
			$this->assertFalse( $sut->find_for( $input ) );
		}

	}

	/**
	 * It should return false is querying by not use post field
	 *
	 * @test
	 */
	public function it_should_return_false_is_querying_by_not_use_post_field() {
		$post = $this->factory()->post->create_and_get();

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' ] );

		$this->assertFalse( $sut->find_for( [ 'post_author' => $post->post_author ] ) );
	}

	/**
	 * It should allow passing an array of custom fields in the data
	 *
	 * @test
	 */
	public function it_should_allow_passing_an_array_of_custom_fields_in_the_data() {
		$post = $this->factory()->post->create();
		add_post_meta( $post, 'foo', 'One' );
		add_post_meta( $post, 'foo', 'Two' );
		add_post_meta( $post, 'foo', 'Three' );

		$sut = $this->make_instance();
		$sut->use_custom_fields( [ 'foo' => [ 'match' => 'like' ] ] );

		$this->assertEquals( $post, $sut->find_for( [ 'foo' => 'one' ] ) );
		$this->assertEquals( $post, $sut->find_for( [ 'foo' => [ 'one' ] ] ) );
		$this->assertEquals( $post, $sut->find_for( [ 'foo' => [ 'one', 'two' ] ] ) ); // will only use the first
	}
}