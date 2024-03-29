<?php

namespace Tribe\Duplicate;

use Tribe__Duplicate__Post as Duplicate;

class PostTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \Tribe__Duplicate__Strategy_Factory
	 */
	protected $factory;

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
		$this->factory = new \Tribe__Duplicate__Strategy_Factory();
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

		$this->assertEquals( $post->ID, $sut->find_for( [
			'post_title' => $post->post_title,
			'foo_bar'    => 'some value'
		] ) );
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
		$post = $this->factory()->post->create( [
			'post_title'   => 'A post',
			'post_content' => 'Some content [shortcode]'
		] );
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

	/**
	 * It should not find duplicates of other post type
	 * @test
	 */
	public function it_should_not_find_duplicates_of_other_post_type() {
		$post = $this->factory()->post->create();
		add_post_meta( $post, 'foo', 'One' );
		add_post_meta( $post, 'foo', 'Two' );
		add_post_meta( $post, 'foo', 'Three' );

		$page = $this->factory()->post->create( [ 'post_type' => 'page' ] );
		add_post_meta( $page, 'foo', 'One' );
		add_post_meta( $page, 'foo', 'Two' );
		add_post_meta( $page, 'foo', 'Three' );

		$sut = $this->make_instance();
		$sut->use_custom_fields( [ 'foo' => [ 'match' => 'like' ] ] );
		$sut->set_post_type( 'page' );

		$this->assertEquals( $page, $sut->find_for( [ 'foo' => 'one' ] ) );
		$this->assertEquals( $page, $sut->find_for( [ 'foo' => [ 'one' ] ] ) );
		$this->assertCount( 1, $sut->find_all_for( [ 'foo' => 'one' ] ) );
	}

	/**
	 * It should allow finding all duplicates in OR logic
	 * @test
	 */
	public function it_should_allow_finding_all_duplicates_in_or_logic() {
		$post_1 = $this->factory()->post->create( [ 'post_title' => 'foo', 'post_content' => 'foo' ] );
		$post_2 = $this->factory()->post->create( [ 'post_title' => 'foo', 'post_content' => 'bar' ] );
		$post_3 = $this->factory()->post->create( [ 'post_title' => 'bar', 'post_content' => 'bar' ] );
		$post_4 = $this->factory()->post->create( [ 'post_title' => 'bar', 'post_content' => 'foo' ] );

		$sut = $this->make_instance();
		$sut->set_where_operator( 'or' );
		$sut->use_post_fields( [ 'post_title', 'post_content' ] );

		$this->assertCount( 2, $sut->find_all_for( [ 'post_title' => 'foo' ] ) );
		$this->assertCount( 2, $sut->find_all_for( [ 'post_title' => 'bar' ] ) );
		$found_1 = $sut->find_all_for( [
			'post_title'   => 'foo',
			// OR
			'post_content' => 'foo'
		] );
		$this->assertEqualSets( [ $post_1, $post_2, $post_4 ], $found_1 );
		$this->assertCount( 3, $found_1 );
		$found_2 = $sut->find_all_for( [
			'post_title'   => 'bar',
			// OR
			'post_content' => 'foo'
		] );
		$this->assertCount( 3, $found_2 );
		$this->assertEqualSets( [ $post_1, $post_3, $post_4 ], $found_2 );
	}

	/**
	 * It should cut the joined query for custom fields down
	 * @test
	 */
	public function it_should_cut_the_joined_query_for_custom_fields_down() {
		$post_fields = [ 'post_title', 'post_content' ];
		$custom_fields = [
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		];
		$post = $this->factory()->post->create( [ 'post_title' => 'foo', 'post_content' => 'foo' ] );
		foreach ( $custom_fields as $field ) {
			add_post_meta( $post, $field, 'foo' );
		}

		$sut = $this->make_instance();
		$sut->use_post_fields( $post_fields );
		$sut->use_custom_fields( $custom_fields );
		$sut->set_join_limit( 2 );

		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$merged = array_merge( $post_fields, $custom_fields );
		$found = $sut->find_for( array_combine(
			$merged,
			array_fill( 0, count( $merged ), 'foo' )
		) );

		$queries_after = $wpdb->num_queries;

		$this->assertEquals( 5, $queries_after - $queries_before );
		$this->assertEquals( $post, $found );
	}

	/**
	 * It should make just one query if AND operator and nothing is found
	 * @test
	 */
	public function it_should_make_just_one_query_if_and_operator_and_nothing_is_found() {
		$post_fields = [ 'post_title', 'post_content' ];
		$custom_fields = [
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		];
		$post = $this->factory()->post->create();

		$sut = $this->make_instance();
		$sut->use_post_fields( $post_fields );
		$sut->use_custom_fields( $custom_fields );
		$sut->set_join_limit( 2 );

		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$merged = array_merge( $post_fields, $custom_fields );
		$found = $sut->find_for( array_combine(
			$merged,
			array_fill( 0, count( $merged ), 'foo' )
		) );

		$queries_after = $wpdb->num_queries;

		$this->assertEquals( 1, $queries_after - $queries_before );
		$this->assertEmpty( $found );
	}

	/**
	 * It should make min number of queries when OR operator
	 * @test
	 */
	public function it_should_make_min_number_of_queries_when_or_operator() {
		$post_fields = [ 'post_title', 'post_content' ];
		$custom_fields = [
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		];
		$post = $this->factory()->post->create();
		foreach ( $custom_fields as $field ) {
			if ( 'six' === $field ) {
				// should find a match on 3rd query
				add_post_meta( $post, $field, 'foo' );
			} else {
				add_post_meta( $post, $field, 'bar' );
			}
		}

		$sut = $this->make_instance();
		$sut->use_post_fields( $post_fields );
		$sut->use_custom_fields( $custom_fields );
		$sut->set_join_limit( 2 );
		$sut->set_where_operator( 'or' );

		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$merged = array_merge( $post_fields, $custom_fields );
		$found = $sut->find_for( array_combine(
			$merged,
			array_fill( 0, count( $merged ), 'foo' )
		) );

		$queries_after = $wpdb->num_queries;

		$this->assertEquals( 3, $queries_after - $queries_before );
		$this->assertEquals( $post, $found );
	}

	/**
	 * It should cut the joined query for custom fields down on find all
	 * @test
	 */
	public function it_should_cut_the_joined_query_for_custom_fields_down_on_find_all() {
		$post_fields = [ 'post_title', 'post_content' ];
		$custom_fields = [
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		];
		$post = $this->factory()->post->create( [ 'post_title' => 'foo', 'post_content' => 'foo' ] );
		foreach ( $custom_fields as $field ) {
			add_post_meta( $post, $field, 'foo' );
		}

		$sut = $this->make_instance();
		$sut->use_post_fields( $post_fields );
		$sut->use_custom_fields( $custom_fields );
		$sut->set_join_limit( 2 );

		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$merged = array_merge( $post_fields, $custom_fields );
		$found = $sut->find_all_for( array_combine(
			$merged,
			array_fill( 0, count( $merged ), 'foo' )
		) );

		$queries_after = $wpdb->num_queries;

		$this->assertEquals( 5, $queries_after - $queries_before );
		$this->assertEquals( [ $post ], $found );
	}

	/**
	 * It should make just one query if AND operator and nothing is found on find all
	 * @test
	 */
	public function it_should_make_just_one_query_if_and_operator_and_nothing_is_found_on_find_all() {
		$post_fields = [ 'post_title', 'post_content' ];
		$custom_fields = [
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		];
		$post = $this->factory()->post->create();

		$sut = $this->make_instance();
		$sut->use_post_fields( $post_fields );
		$sut->use_custom_fields( $custom_fields );
		$sut->set_join_limit( 2 );

		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$merged = array_merge( $post_fields, $custom_fields );
		$found = $sut->find_all_for( array_combine(
			$merged,
			array_fill( 0, count( $merged ), 'foo' )
		) );

		$queries_after = $wpdb->num_queries;

		$this->assertEquals( 1, $queries_after - $queries_before );
		$this->assertEmpty( $found );
	}

	/**
	 * It should make max number of queries when OR operator on find all
	 * @test
	 */
	public function it_should_make_max_number_of_queries_when_or_operator_on_find_all() {
		$post_fields = [ 'post_title', 'post_content' ];
		$custom_fields = [
			'one',
			'two',
			'three',
			'four',
			'five',
			'six',
			'seven',
			'eight',
			'nine',
			'ten',
		];
		$post = $this->factory()->post->create();
		foreach ( $custom_fields as $field ) {
			if ( 'six' === $field ) {
				// should find a match on 3rd query
				add_post_meta( $post, $field, 'foo' );
			} else {
				add_post_meta( $post, $field, 'bar' );
			}
		}

		$sut = $this->make_instance();
		$sut->use_post_fields( $post_fields );
		$sut->use_custom_fields( $custom_fields );
		$sut->set_join_limit( 2 );
		$sut->set_where_operator( 'or' );

		global $wpdb;
		$queries_before = $wpdb->num_queries;

		$merged = array_merge( $post_fields, $custom_fields );
		$found = $sut->find_all_for( array_combine(
			$merged,
			array_fill( 0, count( $merged ), 'foo' )
		) );

		$queries_after = $wpdb->num_queries;

		$this->assertEquals( 5, $queries_after - $queries_before );
		$this->assertEquals( [ $post ], $found );
	}

	/**
	 * It should not find trash or autodraft duplicates.
	 *
	 * @test
	 */
	public function it_should_not_find_trash_or_autodraft_duplicates() {
		$this->factory()->post->create( [ 'post_title' => 'Title', 'post_status' => 'trash' ] );
		$this->factory()->post->create( [ 'post_title' => 'Title', 'post_status' => 'autodraft' ] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' ] );

		$this->assertFalse( $sut->find_for( [ 'post_title' => 'Title' ] ) );
	}

	/**
	 * It should not find filtered status duplicates.
	 *
	 * @test
	 */
	public function it_should_not_find_filtered_status_duplicates() {
		add_filter( 'tribe_duplicate_post_excluded_status', [ $this, 'filter_excluded_status' ] );

		$this->factory()->post->create( [
			'post_title'  => 'Title',
			'post_status' => 'trash',
		] );
		$this->factory()->post->create( [
			'post_title'  => 'Title',
			'post_status' => 'autodraft',
		] );
		$this->factory()->post->create( [
			'post_title'  => 'Title',
			'post_status' => 'publish',
		] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' ] );

		$this->assertFalse( $sut->find_for( [ 'post_title' => 'Title' ] ) );

		remove_filter( 'tribe_duplicate_post_excluded_status', [ $this, 'filter_excluded_status' ] );
	}

	/**
	 * It should find non filtered status duplicates.
	 *
	 * @test
	 */
	public function it_should_find_non_filtered_status_duplicates() {
		add_filter( 'tribe_duplicate_post_excluded_status', '__return_empty_array' );

		$id = $this->factory()->post->create( [
			'post_title'  => 'Title',
			'post_status' => 'trash',
		] );

		$sut = $this->make_instance();
		$sut->use_post_fields( [ 'post_title' ] );

		$this->assertEquals( $id, $sut->find_for( [ 'post_title' => 'Title' ] ) );

		remove_filter( 'tribe_duplicate_post_excluded_status', '__return_empty_array' );
	}

	/**
	 * Filter excluded status.
	 *
	 * @param array $status
	 *
	 * @return array
	 */
	public function filter_excluded_status( $status ) {

		$status[] = 'publish';

		return $status;

	}
}