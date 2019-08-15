<?php

namespace Tribe\Utils;

class Post_ThumbnailTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( Post_Thumbnail::class, new Post_Thumbnail( 23 ) );
	}

	/**
	 * It should not fetch a post thumbnail information on construction
	 *
	 * @test
	 */
	public function should_not_fetch_a_post_thumbnail_information_on_construction() {
		list( $post_id ) = $this->given_a_post_with_thumbnail();

		add_filter( 'get_post_metadata', function ( $metadata, $post_id, $meta_key ) {
			if ( $meta_key === '_thumbnail_id' ) {
				$this->fail( 'The post thumbnail ID meta should not be fetched at __construct time.' );
			}
		}, 1, 3 );

		$post_thumbnail = new Post_Thumbnail( $post_id );
	}

	/**
	 * It should fetch  the post thumbnail information when accessing one of the class props
	 *
	 * @test
	 */
	public function should_fetch_the_post_thumbnail_information_when_accessing_one_of_the_class_props() {
		list( $post_id, $thumbnail_id ) = $this->given_a_post_with_thumbnail();

		$post_thumbnail = new Post_Thumbnail( $post_id );

		$this->assertNotEmpty( $post_thumbnail->srcset );

		foreach ( $post_thumbnail->get_image_sizes() as $image_size ) {
			$full_size_url = $post_thumbnail->{$image_size}->url;
			$this->assertTrue( isset( $post_thumbnail[ $image_size ] ) );
			$this->assertEquals( wp_get_attachment_image_url( $thumbnail_id, $image_size ), $full_size_url );
		}
	}

	/**
	 * It should allow filtering the post thumbnail data
	 *
	 * @test
	 */
	public function should_allow_filtering_the_post_thumbnail_data() {
		add_filter( 'tribe_post_thumbnail_data', static function ( array $data ) {
			$data['test_size'] = (object) [
				'url'             => 'http://example.com',
				'width'           => '2389',
				'height'          => '23',
				'is_intermediate' => true,
			];

			return $data;
		} );
		list( $post_id ) = $this->given_a_post_with_thumbnail();

		$post_thumbnail = new Post_Thumbnail( $post_id );

		$this->assertTrue( isset( $post_thumbnail->test_size ) );
		$this->assertEquals( (object) [
			'url'             => 'http://example.com',
			'width'           => '2389',
			'height'          => '23',
			'is_intermediate' => true,
		], $post_thumbnail->test_size );
	}

	/**
	 * It should allow filtering the available image sizes
	 *
	 * @test
	 */
	public function should_allow_filtering_the_available_image_sizes() {
		add_filter( 'tribe_post_thumbnail_image_sizes', static function ( array $image_sizes ) {
			$image_sizes = array_diff( $image_sizes, [ 'full' ] );

			return $image_sizes;
		} );
		list( $post_id ) = $this->given_a_post_with_thumbnail();

		$post_thumbnail = new Post_Thumbnail( $post_id );

		$this->assertFalse( isset( $post_thumbnail->full ) );
		$this->assertNull( $post_thumbnail->full );
	}

	protected function given_a_post_with_thumbnail() {
		$thumbnail_id = static::factory()
			->attachment
			->create_upload_object( codecept_data_dir( 'images/featured-image.jpg' ) );
		$post_id      = static::factory()->post->create( [
			'meta_input' => [
				'_thumbnail_id' => $thumbnail_id,
			],
		] );

		return [ $post_id, $thumbnail_id ];
	}

	/**
	 * It should allow dumping its values to an array
	 *
	 * @test
	 */
	public function should_allow_dumping_its_values_to_an_array() {
		list( $post_id, $thumbnail_id ) = $this->given_a_post_with_thumbnail();
		$post_thumbnail = new Post_Thumbnail( $post_id );

		$array_dump = $post_thumbnail->to_array();

		$this->assertArrayHasKey( 'srcset', $array_dump );
		$this->assertEquals( wp_get_attachment_image_srcset( $thumbnail_id ), $array_dump['srcset'] );

		foreach ( $post_thumbnail->get_image_sizes() as $image_size ) {
			$full_size_url = $post_thumbnail->{$image_size}->url;
			$this->assertTrue( isset( $post_thumbnail[ $image_size ] ) );
			$size_data = wp_get_attachment_image_src( $thumbnail_id, $image_size );
			$this->assertEqualSets( array_combine(
				[ 'url', 'width', 'height', 'is_intermediate' ],
				$size_data
			), $array_dump[ $image_size ] );
		}
	}

	/**
	 * It should allow serializing and unserializing the information
	 *
	 * @test
	 */
	public function should_allow_serializing_and_unserializing_the_information() {
		list( $post_id, $thumbnail_id ) = $this->given_a_post_with_thumbnail();
		$post_thumbnail                = new Post_Thumbnail( $post_id );

		$serialized = serialize( $post_thumbnail );
		$unserialized = unserialize( $serialized );

		$this->assertInstanceOf( Post_Thumbnail::class, $unserialized );
		$this->assertEquals( $post_thumbnail->to_array(), $unserialized->to_array() );
	}

	/**
	 * It should check if a post thumbnail exists
	 *
	 * @test
	 */
	public function should_check_if_a_post_thumbnail_exists() {
		list( $post_w_thumbnail_id, $thumbnail_id ) = $this->given_a_post_with_thumbnail();

		$post_thumbnail = new Post_Thumbnail( $post_w_thumbnail_id );
		$this->assertTrue( $post_thumbnail->exists() );
		$this->assertTrue( $post_thumbnail->exists );
	}

	/**
	 * It should check if a post thumbnail does not exist
	 *
	 * @test
	 */
	public function should_check_if_a_post_thumbnail_does_not_exist() {
		$post_wo_thumbnail_id = static::factory()->post->create();

		$post_thumbnail = new Post_Thumbnail( $post_wo_thumbnail_id );
		$this->assertFalse( $post_thumbnail->exists );
	}

	/**
	 * It should throw if trying to set the exists property
	 *
	 * @test
	 */
	public function should_throw_if_trying_to_set_the_exists_property() {
		$post = static::factory()->post->create();

		$post_thumbnail = new Post_Thumbnail( $post );

		$this->expectException( \InvalidArgumentException::class );

		$post_thumbnail->exists = true;
	}
}