<?php

namespace Tribe\Repository;

use Tribe__Repository__Query_Filters as Query_Filters;
use Tribe__Repository as Update_Repository;

class UpdateTest extends \Codeception\TestCase\WPTestCase {
	protected $class;

	public function setUp() {
		parent::setUp();
		register_post_type( 'book' );
		register_taxonomy( 'genre', 'book' );
		$this->class = new class extends \Tribe__Repository {
			protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];
		};
	}

	/**
	 * It should allow udpating a post fields
	 *
	 * @test
	 */
	public function should_allow_udpating_a_post_fields() {
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
			$this->repository()->where( 'post__in', $ids )->set( $post_field, $value )->save();

			foreach ( $ids as $id ) {
				clean_post_cache( $id );
				$this->assertEquals( $value, get_post( $id )->{$post_field}, "{$post_field} does not match for post {$id}" );
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

}