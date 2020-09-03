<?php

namespace Tribe\Traits;

class With_Meta_Updates_HandlingTest extends \Codeception\TestCase\WPTestCase {
	use With_Meta_Updates_Handling;

	/**
	 * It should not unpack meta by default
	 *
	 * @test
	 */
	public function should_not_unpack_meta_by_default() {
		$post_id = static::factory()->post->create();
		wp_update_post( [
			'ID'         => $post_id,
			'meta_input' => [
				'test' => [ 'one', 'two' ],
			],
		] );

		$this->assertEquals( [ 'one', 'two' ], get_post_meta( $post_id, 'test', true ) );
		$this->assertEquals( [ [ 'one', 'two' ] ], get_post_meta( $post_id, 'test', false ) );
	}

	/**
	 * It should unpack meta using trait
	 *
	 * @test
	 */
	public function should_unpack_meta_using_trait() {
		$post_id_1 = static::factory()->post->create();
		$post_id_2 = static::factory()->post->create();
		$this->unpack_meta_on_update( 'test', $post_id_2 );
		wp_update_post( [
			'ID'         => $post_id_1,
			'meta_input' => [
				'test' => [ 'one', 'two' ],
			],
		] );
		wp_update_post( [
			'ID'         => $post_id_2,
			'meta_input' => [
				'test' => [ 'one', 'two' ],
			],
		] );

		$this->assertEquals( [ 'one', 'two' ], get_post_meta( $post_id_1, 'test', true ) );
		$this->assertEquals( [ [ 'one', 'two' ] ], get_post_meta( $post_id_1, 'test', false ) );
		$this->assertEquals( 'one', get_post_meta( $post_id_2, 'test', true ) );
		$this->assertEquals( [ 'one', 'two' ], get_post_meta( $post_id_2, 'test', false ) );
	}

	/**
	 * It should unpack next using trait if post ID not set
	 *
	 * @test
	 */
	public function should_unpack_next_using_trait_if_post_id_not_set() {
		$post_id_1 = static::factory()->post->create();
		$post_id_2 = static::factory()->post->create();
		$this->unpack_meta_on_update( 'test' );
		wp_update_post( [
			'ID'         => $post_id_1,
			'meta_input' => [
				'test' => [ 'one', 'two' ],
			],
		] );
		wp_update_post( [
			'ID'         => $post_id_2,
			'meta_input' => [
				'test' => [ 'one', 'two' ],
			],
		] );

		$this->assertEquals( 'one', get_post_meta( $post_id_1, 'test', true ) );
		$this->assertEquals( [ 'one', 'two' ], get_post_meta( $post_id_1, 'test', false ) );
		$this->assertEquals( [ 'one', 'two' ], get_post_meta( $post_id_2, 'test', true ) );
		$this->assertEquals( [ [ 'one', 'two' ] ], get_post_meta( $post_id_2, 'test', false ) );
	}
}
