<?php

namespace Tribe\Meta;

use Tribe__Meta__Chunker as Chunker;

class ChunkerTest extends \Codeception\TestCase\WPTestCase {

	public $post_types = [ 'post' ];

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
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Chunker::class, $this->make_instance() );
	}

	/**
	 * @return Chunker
	 */
	protected function make_instance() {
		if ( tribe()->isBound( 'chunker' ) ) {
			tribe( 'chunker' )->unhook();
			tribe()->offsetUnset( 'chunker' );
		}
		$instance = new Chunker();
		$instance->set_post_types( $this->post_types );
		$instance->hook();

		return $instance;

	}

	/**
	 * It should allow marking meta as chunkable
	 *
	 * @test
	 */
	public function it_should_allow_marking_meta_as_chunkable() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
	}

	/**
	 * It should store meta key for chunkable meta
	 *
	 * @test
	 */
	public function it_should_store_meta_key_for_chunkable_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		$option = get_option( $sut->get_key_option_name() );
		$this->assertNotEmpty( $option[ $id ] );
		$this->assertEquals( [ 'foo' ], $option[ $id ] );
	}

	/**
	 * It should not store meta for non chunkable meta
	 *
	 * @test
	 */
	public function it_should_not_store_meta_for_non_chunkable_meta() {
		$id = $this->factory()->post->create();
		add_post_meta($id,'bar', 'some value');
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );
		$max_size = 2 * strlen( $meta_value );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) * 2 );
		$sut->register_chunking_for( $id, $meta_key );

		$option = get_option( $sut->get_key_option_name() );
		$this->assertNotEmpty( $option[ $id ] );
		$this->assertNotContains( 'bar' , $option[ $id ] );
	}

	/**
	 * It should store chunkable meta on the database in different chunks when adding meta
	 *
	 * @test
	 */
	public function it_should_store_chunkable_meta_on_the_database_in_different_chunks_when_adding_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$chunks = $sut->get_chunks_for( $id, $meta_key );

		$this->assertNotEmpty( $chunks );
		$this->assertCount( 3, $chunks );
		$this->assertEquals( $meta_value, $sut->glue_chunks( $chunks ) );
	}

	/**
	 * It should replace existing meta when adding same meta two times
	 *
	 * No multiple instances of chunkable meta are supported.
	 *
	 * @test
	 */
	public function it_should_replace_existing_meta_when_adding_same_meta_two_times() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value_1 = str_repeat( 'foo', 20 );
		$meta_value_2 = str_repeat( 'bar', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value_1 ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value_1 );
		add_post_meta( $id, $meta_key, $meta_value_2 );

		$chunks = $sut->get_chunks_for( $id, $meta_key );

		$this->assertNotEmpty( $chunks );
		$this->assertCount( 3, $chunks );
		$this->assertEquals( $meta_value_2, $sut->glue_chunks( $chunks ) );
	}

	/**
	 * It should delete all chunked meta
	 *
	 * @test
	 */
	public function it_should_delete_all_chunked_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertNotEmpty( $sut->get_chunks_for( $id, $meta_key ) );

		delete_post_meta( $id, $meta_key, $meta_value );

		$this->assertEmpty( get_post_meta( $id, $meta_key, true ) );
		$this->assertEmpty( get_post_meta( $id, $meta_key, false ) );
		$this->assertEmpty( $sut->get_chunks_for( $id, $meta_key ) );
	}

	/**
	 * It should allow getting single chunked meta
	 *
	 * @test
	 */
	public function it_should_allow_getting_single_chunked_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertEquals( $meta_value, get_post_meta( $id, $meta_key, true ) );
	}

	/**
	 * It should not chunk meta that is not registered to be chunked
	 *
	 * @test
	 */
	public function it_should_not_chunk_meta_that_is_not_registered_to_be_chunked() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertFalse( $sut->is_chunkable( $id, $meta_key ) );
	}

	/**
	 * It should not chunk meta registered to be chunked that is not large enough
	 *
	 * @test
	 */
	public function it_should_not_chunk_meta_registered_to_be_chunked_that_is_not_large_enough() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) * 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
	}

	/**
	 * It should not chunk meta for non supported post types
	 *
	 * @test
	 */
	public function it_should_not_chunk_meta_for_non_supported_post_types() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$this->post_types = [ 'page' ]; // not posts
		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) * 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertFalse( $sut->is_chunkable( $id, $meta_key ) );
	}

	/**
	 * It should make chunking survive across instances
	 *
	 * @test
	 */
	public function it_should_make_chunking_survive_across_instances() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut_1 = $this->make_instance();
		$sut_1->set_max_chunk_size( $sut_1->get_byte_size( $meta_value ) / 2 );
		$sut_1->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertTrue( $sut_1->is_chunkable( $id, $meta_key ) );

		$sut_1->unhook();
		unset( $sut_1 );

		$sut_2 = $this->make_instance();
		$this->assertTrue( $sut_2->is_chunkable( $id, $meta_key ) );
		$this->assertCount( 3, $sut_2->get_chunks_for( $id, $meta_key ) );
	}

	/**
	 * It should reflect latest content of meta after updates
	 *
	 * @test
	 */
	public function it_should_reflect_latest_content_of_meta_after_updates() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		update_post_meta( $id, $meta_key, $meta_value );

		$this->assertEquals( $meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );

		update_post_meta( $id, $meta_key, 'just this' );

		$this->assertEquals( 'just this', get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
	}

	/**
	 * It should reflect latest state of meta after additions
	 *
	 * @test
	 */
	public function it_should_reflect_latest_state_of_meta_after_additions() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertEquals( $meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );

		add_post_meta( $id, $meta_key, 'just this' );

		$this->assertEquals( 'just this', get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
	}

	/**
	 * It should reflect latest value after meta deletion
	 *
	 * @test
	 */
	public function it_should_reflect_latest_value_after_meta_deletion() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertEquals( $meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );

		delete_post_meta( $id, $meta_key );

		$this->assertEmpty( get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
	}

	/**
	 * It should allow getting the max chunk size from the db max_allowed_packet
	 *
	 * @test
	 */
	public function it_should_allow_getting_the_max_chunk_size_from_the_db_max_allowed_packet() {
		$sut = $this->make_instance();
		$this->assertTrue( is_numeric( $sut->get_max_chunk_size() ) );
	}

	/**
	 * It should set the chunked meta normal meta key too when chunking meta
	 *
	 * @test
	 */
	public function it_should_set_the_chunked_meta_normal_meta_key_too_when_chunking_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertEquals( $meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		/** @var wpdb $wpdb */
		global $wpdb;
		$this->assertCount( 1, $wpdb->get_results( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '{$meta_key}'" ) );

		delete_post_meta( $id, $meta_key );

		$this->assertEmpty( get_post_meta( $id, $meta_key, true ) );
		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
		$this->assertEmpty( $wpdb->get_results( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '{$meta_key}'" ) );
	}

	/**
	 * It should allow getting chunked meta in the context of all meta
	 *
	 * @test
	 */
	public function it_should_allow_getting_chunked_meta_in_the_context_of_all_meta() {
		$id = $this->factory()->post->create( [ 'meta_input' => [ 'bar' => 23, 'baz' => 89 ] ] );
		add_post_meta( $id, 'two', 'one' );
		add_post_meta( $id, 'two', 'two' );
		$meta_key = 'foo';
		$keys = [ 'foo', 'baz', 'bar' ];
		$meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$all_meta = get_post_meta( $id );
		// remove keys we've not created
		$all_meta = array_intersect_key( $all_meta, array_combine( $keys, $keys ) );

		$this->assertCount( 3, $all_meta );
		$this->assertArrayHasKey( 'foo', $all_meta );
		$this->assertArrayHasKey( 'bar', $all_meta );
		$this->assertArrayHasKey( 'baz', $all_meta );
		$this->assertContainsOnly( 'array', $all_meta );
		$this->assertEquals( $meta_value, reset( $all_meta['foo'] ) );
		$this->assertEquals( 23, reset( $all_meta['bar'] ) );
		$this->assertEquals( 89, reset( $all_meta['baz'] ) );
	}

	/**
	 * It should correctly store diminished meta
	 *
	 * @test
	 */
	public function it_should_correctly_store_diminished_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$first_meta_value = str_repeat( 'foo', 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $first_meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $first_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		$this->assertEquals( $first_meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertCount( 3, $sut->get_chunks_for( $id, $meta_key ) );

		$second_meta_value = 'smallervalue';
		update_post_meta( $id, $meta_key, $second_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
		$this->assertEquals( $second_meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertEmpty( $sut->get_chunks_for( $id, $meta_key ) );
	}

	/**
	 * It should correctly store enlarged meta
	 *
	 * @test
	 */
	public function it_should_correctly_store_enlarged_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$first_meta_value = 'foo';
		$second_meta_value = str_repeat( $first_meta_value, 20 );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $second_meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $first_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
		$this->assertEquals( $first_meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertEmpty( $sut->get_chunks_for( $id, $meta_key ) );

		update_post_meta( $id, $meta_key, $second_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		$this->assertEquals( $second_meta_value, get_post_meta( $id, $meta_key, true ) );
		$this->assertCount( 3, $sut->get_chunks_for( $id, $meta_key ) );
	}

	/**
	 * It should correctly store diminished meta when getting all meta
	 *
	 * @test
	 */
	public function it_should_correctly_store_diminished_meta_when_getting_all_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$first_meta_value = str_repeat( 'foo', 20 );
		$second_meta_value = 'smallervalue';
		$is_chunker_meta = function ( $key ) {
			return preg_match( '/^_tribe_chunker_/', $key );
		};

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $first_meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $first_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		$all_meta = get_post_meta( $id );
		$this->assertArrayHasKey( $meta_key, $all_meta );
		$this->assertEquals( $first_meta_value, reset( $all_meta[ $meta_key ] ) );
		$this->assertEmpty( array_filter( $all_meta, $is_chunker_meta, ARRAY_FILTER_USE_KEY ) );
		$this->assertCount( 3, $sut->get_chunks_for( $id, $meta_key ) );

		update_post_meta( $id, $meta_key, $second_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
		$all_meta = get_post_meta( $id );
		$this->assertArrayHasKey( $meta_key, $all_meta );
		$this->assertEquals( $second_meta_value, reset( $all_meta[ $meta_key ] ) );
		$this->assertEmpty( array_filter( $all_meta, $is_chunker_meta, ARRAY_FILTER_USE_KEY ) );
		$this->assertEmpty( $sut->get_chunks_for( $id, $meta_key ) );
	}

	/**
	 * It should correctly store enlarged meta when getting all meta
	 *
	 * @test
	 */
	public function it_should_correctly_store_enlarged_meta_when_getting_all_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$first_meta_value = 'foo';
		$second_meta_value = str_repeat( $first_meta_value, 20 );
		$is_chunker_meta = function ( $key ) {
			return preg_match( '/^_tribe_chunker_/', $key );
		};

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $second_meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $first_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut->is_chunked( $id, $meta_key ) );
		$all_meta = get_post_meta( $id );
		$this->assertArrayHasKey( $meta_key, $all_meta );
		$this->assertEquals( $first_meta_value, reset( $all_meta[ $meta_key ] ) );
		$this->assertEmpty( array_filter( $all_meta, $is_chunker_meta, ARRAY_FILTER_USE_KEY ) );
		$this->assertEmpty( $sut->get_chunks_for( $id, $meta_key ) );

		update_post_meta( $id, $meta_key, $second_meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		$all_meta = get_post_meta( $id );
		$this->assertArrayHasKey( $meta_key, $all_meta );
		$this->assertEquals( $second_meta_value, reset( $all_meta[ $meta_key ] ) );
		$this->assertEmpty( array_filter( $all_meta, $is_chunker_meta, ARRAY_FILTER_USE_KEY ) );
		$this->assertCount( 3, $sut->get_chunks_for( $id, $meta_key ) );
	}

	/**
	 * It should return incoherent chunked meta if chunks are missing
	 *
	 * @test
	 */
	public function it_should_return_incoherent_chunked_meta_if_chunks_are_missing() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = array_fill( 0, 20, 'foo' );
		$serialized_meta_value = serialize( $meta_value );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $serialized_meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		$this->assertEquals( $meta_value, get_post_meta( $id, $meta_key, true ) );

		$sut->__destruct();

		// now compromise the chunks in the db
		/** @var wpdb $wpdb */
		global $wpdb;
		$chunk_meta_key = $sut->get_chunk_meta_key( $meta_key );
		$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id = {$id} AND meta_key = '{$chunk_meta_key}' LIMIT 1" );

		$sut_2 = $this->make_instance();
		$sut_2->set_max_chunk_size( $sut_2->get_byte_size( $serialized_meta_value ) / 2 );
		$sut_2->register_chunking_for( $id, $meta_key );

		$this->assertTrue( $sut_2->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut_2->is_chunked( $id, $meta_key ) );
		$db_meta = get_post_meta( $id, $meta_key, true );
		$this->assertNotEmpty( $db_meta );
		$this->assertNotEquals( $meta_value, $db_meta );
		$this->assertInternalType( 'string', $db_meta );
	}

	/**
	 * It should return incoherent chunked meta if chunks are missing when getting all meta
	 *
	 * @test
	 */
	public function it_should_return_incoherent_chunked_meta_if_chunks_are_missing_when_getting_all_meta() {
		$id = $this->factory()->post->create();
		$meta_key = 'foo';
		$meta_value = array_fill( 0, 20, 'foo' );
		$serialized_meta_value = serialize( $meta_value );

		$sut = $this->make_instance();
		$sut->set_max_chunk_size( $sut->get_byte_size( $serialized_meta_value ) / 2 );
		$sut->register_chunking_for( $id, $meta_key );

		add_post_meta( $id, $meta_key, $meta_value );

		$this->assertTrue( $sut->is_chunkable( $id, $meta_key ) );
		$this->assertTrue( $sut->is_chunked( $id, $meta_key ) );
		$all_meta = get_post_meta( $id );
		$this->assertEquals( $meta_value, unserialize( $all_meta[ $meta_key ][0] ) );

		$sut->__destruct();

		// now compromise the chunks in the db
		/** @var wpdb $wpdb */
		global $wpdb;
		$chunk_meta_key = $sut->get_chunk_meta_key( $meta_key );
		$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id = {$id} AND meta_key = '{$chunk_meta_key}' LIMIT 1" );

		$sut_2 = $this->make_instance();
		$sut_2->set_max_chunk_size( $sut_2->get_byte_size( $serialized_meta_value ) / 2 );
		$sut_2->register_chunking_for( $id, $meta_key );

		$this->assertTrue( $sut_2->is_chunkable( $id, $meta_key ) );
		$this->assertFalse( $sut_2->is_chunked( $id, $meta_key ) );
		$db_meta = get_post_meta( $id );
		$this->assertNotEmpty( $db_meta[ $meta_key ] );
		$this->assertNotEquals( $meta_value, $db_meta[ $meta_key ] );
		$this->assertInternalType( 'string', $db_meta[ $meta_key ][0] );
	}
}
