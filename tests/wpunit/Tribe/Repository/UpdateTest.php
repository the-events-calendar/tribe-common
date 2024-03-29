<?php

namespace Tribe\Repository;

use Tribe__Promise as Promise;
use Tribe__Repository as Update_Repository;
use Tribe__Repository__Decorator as Decorator;

class UpdateTest extends \Codeception\TestCase\WPTestCase {
	protected $class;

	public function setUp(): void {
		parent::setUp();
		register_post_type( 'book' );
		register_taxonomy( 'genre', 'book' );
		$this->class = new class extends \Tribe__Repository {
			protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];
			protected $create_args = [ 'post_type' => 'book' ];
			protected $filter_name = 'books';

			public function filter_postarr_for_update( array $postarr, $post_id ) {
				unset( $postarr['meta_input']['nope_key'] );
				if ( isset( $postarr['meta_input']['legit_key'] ) ) {
					$postarr['meta_input']['legit_key'] .= '-postfix';
				}

				return parent::filter_postarr_for_update( $postarr, $post_id );
			}
		};
	}

	/**
	 * It should allow updating a post fields
	 *
	 * @test
	 */
	public function should_allow_updating_a_post_fields() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$date     = new \DateTime( '2013-01-01 09:34:56', new \DateTimeZone( 'America/New_York' ) );
		$gmt_date = new \DateTime( '2013-01-01 09:34:56', new \DateTimeZone( 'UTC' ) );
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );
		$other_author = $this->factory()->user->create( [ 'role' => 'editor' ] );

		$post_fields = [
			'post_author'           => $other_author,
			'post_date'             => $date->format( 'Y-m-d H:i:s' ),
			'post_date_gmt'         => $gmt_date->format( 'Y-m-d H:i:s' ),
			'post_content'          => 'Lorem Content',
			'post_title'            => 'Lorem Title',
			'post_excerpt'          => 'Lorem Excerpt',
			'post_status'           => 'draft',
			'comment_status'        => 'yes',
			'ping_status'           => 'nope',
			'post_name'             => 'foo-bar',
			'to_ping'               => false,
			'pinged'                => true,
			'post_content_filtered' => 'Lorem Dolor Filter',
			'post_parent'           => 23,
			'menu_order'            => 23,
			'post_mime_type'        => 'image/png',
			'post_password'         => 'lorem-secret',
		];

		foreach ( $post_fields as $post_field => $value ) {
			$this->repository()->where( 'post__in', $ids )->where( 'status', 'any' )->set( $post_field, $value )->save();

			foreach ( $ids as $id ) {
				clean_post_cache( $id );
				$this->assertEquals( $value, get_post( $id )->{$post_field}, "{$post_field} does not match for post {$id}" );
			}
		}
	}

	/**
	 * It should allow updating empty post fields
	 *
	 * @test
	 */
	public function should_allow_updating_empty_post_fields() {
		$ids = $this->factory()->post->create_many( 1, [ 'post_type' => 'book' ] );

		$date     = new \DateTime( '2013-01-01 09:34:56', new \DateTimeZone( 'America/New_York' ) );
		$gmt_date = new \DateTime( '2013-01-01 09:34:56', new \DateTimeZone( 'UTC' ) );
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );
		$other_author = $this->factory()->user->create( [ 'role' => 'editor' ] );

		$post_fields = [
			'post_author'           => $other_author,
			'post_date'             => $date->format( 'Y-m-d H:i:s' ),
			'post_date_gmt'         => $gmt_date->format( 'Y-m-d H:i:s' ),
			'post_content'          => '',
			'post_title'            => 'Lorem Title',
			'post_excerpt'          => '',
			'post_content_filtered' => '',
			'post_parent'           => 0,
			'menu_order'            => 0,
			'post_mime_type'        => '',
			'post_password'         => '',
		];

		foreach ( $post_fields as $post_field => $value ) {
			$this->repository()->where( 'post__in', $ids )->set( $post_field, $value )->save();

			foreach ( $ids as $id ) {
				clean_post_cache( $id );
				$this->assertEquals( $value, get_post( $id )->{$post_field}, "{$post_field} does not match for post {$id}" );
			}
		}
	}

	/**
	 * It should allow updating using aliased fields
	 *
	 * @test
	 */
	public function should_allow_updating_using_aliased_fields() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$date     = new \DateTime( '2013-01-01 09:34:56', new \DateTimeZone( 'America/New_York' ) );
		$gmt_date = new \DateTime( '2013-01-01 09:34:56', new \DateTimeZone( 'UTC' ) );
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );
		$other_author = $this->factory()->user->create( [ 'role' => 'editor' ] );

		$image_id = $this->factory()->attachment->create_upload_object( codecept_data_dir( 'images/featured-image.jpg' ) );

		$post_fields = [
			'author'   => $other_author,
			'date'     => $date->format( 'Y-m-d H:i:s' ),
			'date_gmt' => $gmt_date->format( 'Y-m-d H:i:s' ),
			'content'  => 'Lorem Content',
			'title'    => 'Lorem Title',
			'excerpt'  => 'Lorem Excerpt',
			'status'   => 'draft',
			'parent'   => 23,
			'slug'     => 'test',
			'image'    => $image_id,
		];

		$other_fields = [
			'slug'  => 'post_name',
			'image' => '_thumbnail_id',
		];

		foreach ( $post_fields as $post_field => $value ) {
			$this->repository()->where( 'post__in', $ids )->where( 'status' , 'any' )->set( $post_field, $value )->save();

			foreach ( $ids as $id ) {
				clean_post_cache( $id );

				$real_field = 'post_' . $post_field;

				if ( isset( $other_fields[ $post_field ] ) ) {
					$real_field = $other_fields[ $post_field ];
				}

				$this->assertEquals( $value, get_post( $id )->{$real_field}, "{$post_field} does not match for post {$id}" );
			}
		}
	}

	/**
	 * @return Update_Repository
	 */
	protected function repository() {
		return new $this->class();
	}

	/**
	 * It should allow adding taxonomy terms to a post
	 *
	 * @test
	 */
	public function should_allow_adding_taxonomy_terms_to_a_post() {
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$this->repository()->where( 'post__in', $ids )->set( 'genre', 'fantasy' )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );
			$this->assertEquals( [ 'fantasy' ], wp_get_object_terms( $id, 'genre', [ 'fields' => 'names' ] ) );
		}
	}

	/**
	 * It should set a non registered post tax as custom field
	 *
	 * @test
	 */
	public function should_set_a_non_registered_post_tax_as_custom_field() {
		register_taxonomy( 'wow-factor', 'post' );
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$this->repository()->where( 'post__in', $ids )->set( 'wow-factor', 'noice' )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );
			$this->assertEquals( [], wp_get_object_terms( $id, 'wow-factor', [ 'fields' => 'names' ] ) );
			$this->assertEquals( 'noice', get_post_meta( $id, 'wow-factor', true ) );
		}
	}

	/**
	 * It should allow setting custom fields on a post
	 *
	 * @test
	 */
	public function should_allow_setting_custom_fields_on_a_post() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$this->repository()->where( 'post__in', $ids )->set( 'custom-field', 'some-value' )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );
			$this->assertEquals( 'some-value', get_post_meta( $id, 'custom-field', true ) );
		}
	}

	/**
	 * It should allow setting featured image with URL using method
	 *
	 * @test
	 */
	public function should_allow_setting_featured_image_with_url_using_method() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$name = 'images/featured-image-' . uniqid() . '.jpg';
		// Create a copy of the file as the file will be removed after the upload is completed
		@copy(
			codecept_data_dir( 'images/featured-image.jpg' ),
			codecept_data_dir( $name )
		);

		$image = codecept_data_dir( $name );

		add_filter( 'tribe_image_uploader_local_urls', '__return_true' );
		$this->repository()->where( 'post__in', $ids )->set_featured_image( $image )->save();
		remove_filter( 'tribe_image_uploader_local_urls', '__return_true' );

		foreach ( $ids as $id ) {
			clean_post_cache( $id );

			$this->assertTrue( has_post_thumbnail( $id ), "Post does not have a featured image for post {$id}" );
		}
	}

	/**
	 * It should to set an image without extension
	 *
	 * @test
	 */
	public function it_should_to_set_an_image_without_extension() {
		$post = $this->factory()->post->create( [ 'post_type' => 'book' ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$name = 'images/featured-image';
		// Create a copy of the file without an extension
		@copy(
			codecept_data_dir( 'images/featured-image.jpg' ),
			codecept_data_dir( $name )
		);

		$image = codecept_data_dir( $name );

		add_filter( 'tribe_image_uploader_local_urls', '__return_true' );
		$this->repository()->where( 'id', $post )->set_featured_image( $image )->save();
		remove_filter( 'tribe_image_uploader_local_urls', '__return_true' );

		clean_post_cache( $post );

		$this->assertTrue( has_post_thumbnail( $post ), "Post does not have a featured image for post {$post}" );

		$parts = explode( '.', get_the_post_thumbnail_url( $post ) );

		$this->assertEquals(
			'jpg',
			end( $parts ),
			'The extension of the file was not able to set as .jpg'
		);
	}

	/**
	 * It should allow setting featured image with ID using method
	 *
	 * @test
	 */
	public function should_allow_setting_featured_image_with_ID_using_method() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$image = codecept_data_dir( 'images/featured-image.jpg' );
		$image = $this->factory()->attachment->create_upload_object( $image );

		$this->repository()->where( 'post__in', $ids )->set_featured_image( $image )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );

			$this->assertTrue( has_post_thumbnail( $id ), "Post does not have a featured image for post {$id}" );
		}
	}

	/**
	 * It should allow removing featured image with null using method
	 *
	 * @test
	 */
	public function should_allow_removing_featured_image_with_null_using_method() {
		$image = codecept_data_dir( 'images/featured-image.jpg' );
		$image = $this->factory()->attachment->create_upload_object( $image );

		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book', 'meta_input' => [ '_thumbnail_id' => $image ] ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$image = null;

		$this->repository()->where( 'post__in', $ids )->set_featured_image( $image )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );

			$this->assertFalse( has_post_thumbnail( $id ), "Post does not have a featured image for post {$id}" );
		}
	}

	/**
	 * It should allow removing featured image with zero using method
	 *
	 * @test
	 */
	public function should_allow_removing_featured_image_with_zero_using_method() {
		$image = codecept_data_dir( 'images/featured-image.jpg' );
		$image = $this->factory()->attachment->create_upload_object( $image );

		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book', 'meta_input' => [ '_thumbnail_id' => $image ] ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$image = 0;

		$this->repository()->where( 'post__in', $ids )->set_featured_image( $image )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );

			$this->assertFalse( has_post_thumbnail( $id ), "Post does not have a featured image for post {$id}" );
		}
	}

	/**
	 * It should not allow removing featured image with false using method
	 *
	 * @test
	 */
	public function should_not_allow_removing_featured_image_with_false_using_method() {
		$image = codecept_data_dir( 'images/featured-image.jpg' );
		$image = $this->factory()->attachment->create_upload_object( $image );

		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book', 'meta_input' => [ '_thumbnail_id' => $image ] ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$image = false;

		$this->repository()->where( 'post__in', $ids )->set_featured_image( $image )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );

			$this->assertTrue( has_post_thumbnail( $id ), "Post does not have a featured image for post {$id}" );
		}
	}

	/**
	 * It should not allow removing featured image with empty string using method
	 *
	 * @test
	 */
	public function should_not_allow_removing_featured_image_with_empty_string_using_method() {
		$image = codecept_data_dir( 'images/featured-image.jpg' );
		$image = $this->factory()->attachment->create_upload_object( $image );

		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book', 'meta_input' => [ '_thumbnail_id' => $image ] ] );

		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$image = '';

		$this->repository()->where( 'post__in', $ids )->set_featured_image( $image )->save();

		foreach ( $ids as $id ) {
			clean_post_cache( $id );

			$this->assertTrue( has_post_thumbnail( $id ), "Post does not have a featured image for post {$id}" );
		}
	}

	/**
	 * It should throw if trying to set a blocked field
	 *
	 * @test
	 *
	 * @dataProvider blocked_fields
	 */
	public function should_throw_if_trying_to_set_a_blocked_field( $field ) {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$this->expectException( \Tribe__Repository__Usage_Error::class );

		$this->repository()->where( 'post__in', $ids )->set( $field, 23 )->save();
	}

	public function blocked_fields() {
		return array_map( function ( $v ) {
			return [ $v ];
		}, [
			'ID',
			'post_type',
			'post_modified',
			'post_modified_gmt',
			'guid',
			'comment_count',
		] );
	}

	/**
	 * It should support bulk setting fields with a map
	 *
	 * @test
	 */
	public function should_support_bulk_setting_fields_with_a_map() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$map = [
			'post_title'   => 'Updated title',
			'post_content' => 'Updated content',
		];
		$this->repository()->where( 'post__in', $ids )->set_args( $map )->save();

		foreach ( $ids as $id ) {
			$post = get_post( $id );
			$this->assertEquals( 'Updated title', $post->post_title );
			$this->assertEquals( 'Updated content', $post->post_content );
		}
	}

	/**
	 * It should allow updating fields using aliases for custom fields
	 *
	 * @test
	 */
	public function should_allow_updating_fields_using_aliases_for_custom_fields() {
		$ids = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$repository = $this->repository();
		$repository->add_update_field_alias( 'title', 'post_title' );
		$repository->add_update_field_alias( 'content', 'post_content' );
		$map = [
			'title'   => 'Updated title',
			'content' => 'Updated content',
		];
		$repository->where( 'post__in', $ids )->set_args( $map )->save();

		foreach ( $ids as $id ) {
			$post = get_post( $id );
			$this->assertEquals( 'Updated title', $post->post_title );
			$this->assertEquals( 'Updated content', $post->post_content );
		}
	}

	/**
	 * It should update posts in background if over threshold
	 *
	 * @test
	 */
	public function should_update_posts_in_background_if_over_threshold() {
		add_filter( 'tribe_repository_books_update_background_activated', '__return_true' );
		add_filter( 'tribe_repository_books_update_background_threshold', function () {
			return 1;
		} );
		$ids     = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );
		$promise = $this->repository()->where( 'post__in', $ids )
		                ->set( 'post_title', 'updated' )
		                ->save( true );

		$this->assertInstanceOf( Promise::class, $promise );
		foreach ( $ids as $id ) {
			$this->assertInstanceOf( \WP_Post::class, get_post( $id ) );
		}
	}

	/**
	 * It should allow filtering update payloads
	 *
	 * @test
	 */
	public function should_allow_filtering_update_payloads() {
		$ids        = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );
		$repository = $this->repository();
		$repository->where( 'post__in', $ids )
		           ->set_args( [ 'nope_key' => 'foo', 'legit_key' => 'bar' ] )
		           ->save();

		foreach ( $ids as $id ) {
			$this->assertEmpty( get_post_meta( $id, 'nope_key' ), true );
			$this->assertEquals( 'bar-postfix', get_post_meta( $id, 'legit_key', true ) );
		}
	}

	/**
	 * It should allow filtering update payloads from decorator
	 *
	 * @test
	 */
	public function should_allow_filtering_update_payloads_from_decorator() {
		$ids       = $this->factory()->post->create_many( 2, [ 'post_type' => 'book' ] );
		$decorator = new class( $this->repository() ) extends Decorator {
			public function __construct( \Tribe__Repository__Interface $decorated ) {
				$this->decorated = $decorated;
				$filter_name     = $decorated->get_filter_name();
				add_filter( "tribe_repository_{$filter_name}_update_postarr", [
					$this,
					'filter_postarr_for_update'
				], 10, 2 );
			}

			public function filter_postarr_for_update( array $postarr, $post_id ) {
				$postarr['meta_input']['decorator_key'] = 'set';

				return $postarr;
			}
		};

		$decorator->where( 'post__in', $ids )
		          ->set_args( [ 'nope_key' => 'foo', 'legit_key' => 'bar' ] )
		          ->save();

		foreach ( $ids as $id ) {
			$this->assertEmpty( get_post_meta( $id, 'nope_key' ), true );
			$this->assertEquals( 'bar-postfix', get_post_meta( $id, 'legit_key', true ) );
			$this->assertEquals( 'set', get_post_meta( $id, 'decorator_key', true ) );
		}
	}

	/**
	 * It should return a promise when requesting it on empty matches
	 *
	 * @test
	 */
	public function should_return_a_promise_when_requesting_it_on_empty_matches() {
		$promise = $this->repository()->where( 'author', 23 )->set( 'foo', 'bar' )->save( true );

		$this->assertInstanceOf( \Tribe__Promise::class, $promise );
	}

	/**
	 * Sample title for instance.
	 *
	 * @return string
	 */
	public function get_instance_title():string {
		return 'hello instance';
	}

	/**
	 * Sample title for static methods.
	 *
	 * @return string
	 */
	public static function get_static_title():string {
		return 'hello static';
	}

	public function callable_title_data_provider(): array {
		return [
			'title is a non-callable string' => [ 'rafsun', 'rafsun' ],
			'title is a callable string'     => [ 'max', 'max' ],
			'title is a Closure'             => [
				static function () {
					return 'hello there';
				},
				'hello there'
			],
			'title is a Closure Object'      => [ \Closure::fromCallable( 'tribe_is_truthy' ), '' ],
			'title is an instance callable'  => [ [ $this, 'get_instance_title' ], 'hello instance' ],
			'title is an static callable'    => [ [ self::class, 'get_static_title' ], 'hello static' ],
		];
	}

	/**
	 * It should handle callable options properly and avoid processing texts as callables when not intended.
	 *
	 * @test
	 *
	 * @dataProvider callable_title_data_provider
	 */
	public function should_allow_proper_callable_input( $title, string $expected ) {
		$post = $this->repository()->set_args( [
			'title'  => $title,
			'status' => 'publish'
		] )->create();

		$this->assertEquals( $expected, $post->post_title );
	}
}