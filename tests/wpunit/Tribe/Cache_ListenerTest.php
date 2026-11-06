<?php

namespace Tribe;

use Codeception\TestCase\WPTestCase;
use Tribe__Cache_Listener as Cache_Listener;

class Cache_ListenerTest extends WPTestCase {
	/**
	 * @before
	 */
	public function invalidate_on_posts(): void {
		add_filter( 'tec_cache_listener_save_post_types', static fn( array $post_types ) => array_merge( $post_types, [ 'post' ] ) );
		delete_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST );
	}

	public function save_post_arguments(): array {
		return [
			'the post ID and the post'  => [ static fn( int $post_id ) => [ $post_id, get_post( $post_id ) ] ],
			'the post ID alone'         => [ static fn( int $post_id ) => [ $post_id ] ],
			'the post ID and null'      => [ static fn( int $post_id ) => [ $post_id, null ] ],
			'the post ID as a string'   => [ static fn( int $post_id ) => [ "{$post_id}", null ] ],
		];
	}

	/**
	 * @test
	 * @dataProvider save_post_arguments
	 */
	public function it_should_record_the_save_of_a_post_whatever_save_post_passes( callable $arguments ): void {
		$post_id = static::factory()->post->create();
		delete_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST );

		( new Cache_Listener() )->save_post( ...$arguments( $post_id ) );

		$this->assertNotFalse( get_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST ) );
	}

	public function invalid_post_ids(): array {
		return [
			'a non-numeric ID' => [ 'not-a-post-id' ],
			'zero'             => [ 0 ],
			'a negative ID'    => [ -1 ],
		];
	}

	/**
	 * `get_post()` answers an empty ID with the global post, which must not be recorded in its place.
	 *
	 * @test
	 * @dataProvider invalid_post_ids
	 */
	public function it_should_not_record_the_global_post_for_an_invalid_post_id( $post_id ): void {
		$GLOBALS['post'] = get_post( static::factory()->post->create() );
		delete_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST );

		( new Cache_Listener() )->save_post( $post_id, 'not-a-post' );

		unset( $GLOBALS['post'] );
		$this->assertFalse( get_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST ) );
	}

	/**
	 * @test
	 */
	public function it_should_record_nothing_when_save_post_passes_no_post(): void {
		( new Cache_Listener() )->save_post( 'not-a-post-id', 'not-a-post' );

		$this->assertFalse( get_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST ) );
	}

	/**
	 * @test
	 */
	public function it_should_record_nothing_for_a_post_type_that_does_not_invalidate_the_cache(): void {
		$page_id = static::factory()->post->create( [ 'post_type' => 'page' ] );

		( new Cache_Listener() )->save_post( $page_id );

		$this->assertFalse( get_option( 'tribe_last_' . Cache_Listener::TRIGGER_SAVE_POST ) );
	}
}
