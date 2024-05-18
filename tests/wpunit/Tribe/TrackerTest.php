<?php

namespace Tribe;

use Tribe__Tracker as Tracker;

class TrackerTest extends \Codeception\TestCase\WPTestCase {

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown(): void {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * It should be instantiatable
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Tracker::class, $this->make_instance() );
	}


	/**
	 * It should mark terms as modified when modified
	 * @test
	 */
	public function mark_terms_as_modified_when_modified() {
		$post = $this->factory()->post->create();
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$baz = $this->factory()->tag->create_and_get( [ 'name' => 'baz' ] );

		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];
		$old_dd_ids = [ $baz->term_taxonomy_id ];
		$sut->track_taxonomy_term_changes( $post, $terms, $tt_ids, 'post_tag', false, $old_dd_ids );

		$modified = get_post_meta( $post, Tracker::$field_key, true );
		$this->assertArrayHasKey( 'post_tag', $modified );
	}

	/**
	 * It should mark taxonomy as modified when appending terms
	 * @test
	 */
	public function mark_taxonomy_as_modified_when_appending_terms() {
		$post = $this->factory()->post->create();
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$baz = $this->factory()->tag->create_and_get( [ 'name' => 'baz' ] );

		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = [ $baz->term_taxonomy_id, $foo->term_taxonomy_id, $bar->term_taxonomy_id ];
		$old_dd_ids = [ $baz->term_taxonomy_id ];
		$exit = $sut->track_taxonomy_term_changes( $post, $terms, $tt_ids, 'post_tag', true, $old_dd_ids );

		$this->assertTrue( $exit );
		$modified = get_post_meta( $post, Tracker::$field_key, true );
		$this->assertArrayHasKey( 'post_tag', $modified );
	}

	/**
	 * It should not mark terms changed if not changed
	 * @test
	 */
	public function not_mark_terms_changed_if_not_changed() {
		$post = $this->factory()->post->create();
		$original_mod = time() - HOUR_IN_SECONDS;
		update_post_meta( $post, Tracker::$field_key, [ 'post_tag' => $original_mod ] );
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );

		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = $old_dd_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];
		$exit = $sut->track_taxonomy_term_changes( $post, $terms, $tt_ids, 'post_tag', true, $old_dd_ids );

		$this->assertTrue( $exit );
		$modified = get_post_meta( $post, Tracker::$field_key, true );
		$this->assertArrayHasKey( 'post_tag', $modified );
		$this->assertEquals( $original_mod, $modified['post_tag'] );
	}

	/**
	 * It should not track changes if tracking of terms is disabled
	 * @test
	 */
	public function not_track_changes_if_tracking_of_terms_is_disabled() {
		$post = $this->factory()->post->create();
		$original_mod = time() - HOUR_IN_SECONDS;
		update_post_meta( $post, Tracker::$field_key, [ 'post_tag' => $original_mod ] );
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = $old_dd_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];
		add_filter( 'tribe_tracker_enabled_for_terms', '__return_false' );

		$exit = $sut->track_taxonomy_term_changes( $post, $terms, $tt_ids, 'post_tag', true, $old_dd_ids );

		$this->assertFalse( $exit );
	}

	/**
	 * It should not track changes if post type is not tracked
	 * @test
	 */
	public function not_track_changes_if_post_type_is_not_tracked() {
		$post = $this->factory()->post->create();
		$original_mod = time() - HOUR_IN_SECONDS;
		update_post_meta( $post, Tracker::$field_key, [ 'post_tag' => $original_mod ] );
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'page' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = $old_dd_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];

		$exit = $sut->track_taxonomy_term_changes( $post, $terms, $tt_ids, 'post_tag', true, $old_dd_ids );

		$this->assertFalse( $exit );
	}

	/**
	 * It should not track changes if the object is not a post
	 * @test
	 */
	public function not_track_changes_if_the_object_is_not_a_post() {
		$original_mod = time() - HOUR_IN_SECONDS;
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = $old_dd_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];

		$exit = $sut->track_taxonomy_term_changes( 2389, $terms, $tt_ids, 'post_tag', true, $old_dd_ids );

		$this->assertFalse( $exit );
	}

	/**
	 * It should not track changes if taxonomy is not tracked
	 * @test
	 */
	public function not_track_changes_if_taxonomy_is_not_tracked() {
		$object = $this->factory()->post->create();
		$original_mod = time() - HOUR_IN_SECONDS;
		update_post_meta( $object, Tracker::$field_key, [ 'post_tag' => $original_mod ] );
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'category' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = $old_dd_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];

		$exit = $sut->track_taxonomy_term_changes( $object, $terms, $tt_ids, 'some-tax', true, $old_dd_ids );

		$this->assertFalse( $exit );
	}

	/**
	 * It should not track changes if taxonomy is not tracked by filter
	 * @test
	 */
	public function not_track_changes_if_taxonomy_is_not_tracked_by_filter() {
		$object = $this->factory()->user->create();
		$original_mod = time() - HOUR_IN_SECONDS;
		update_post_meta( $object, Tracker::$field_key, [ 'post_tag' => $original_mod ] );
		$foo = $this->factory()->tag->create_and_get( [ 'name' => 'foo' ] );
		$bar = $this->factory()->tag->create_and_get( [ 'name' => 'bar' ] );
		$sut = $this->make_instance();
		$sut->set_tracked_post_types( [ 'post' ] );
		$sut->set_tracked_taxonomies( [ 'post_tag' ] );
		$terms = [ $foo->name, $bar->name ];
		$tt_ids = $old_dd_ids = [ $foo->term_taxonomy_id, $bar->term_taxonomy_id ];
		add_filter( 'tribe_tracker_taxonomies', function () {
			return [ 'category' ];
		} );

		$exit = $sut->track_taxonomy_term_changes( $object, $terms, $tt_ids, 'post_tag', true, $old_dd_ids );

		$this->assertFalse( $exit );
	}

	/**
	 * @return Tracker
	 */
	protected function make_instance() {
		return new Tracker();
	}
}
