<?php

namespace Tribe\Utils;

use Tribe__Main as Main;

class Post_ID_HelperTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test When passing a WP_Post object, get the ID from it.
	 */
	public function it_should_return_post_ids_when_passed_post_objects() {
		$post = self::factory()->post->create_and_get();

		$actual = Main::post_id_helper( $post );

		$this->assertEquals( $post->ID, $actual );
	}

	/**
	 * @test When passing zero, it should return bool false.
	 */
	public function it_should_return_false_when_passed_zero() {
		$actual = Main::post_id_helper( 0 );

		$this->assertFalse( $actual );
	}

	/**
	 * @test When passing null, get the current post ID.
	 */
	public function it_should_return_global_post_id_when_passed_null() {
		global $post;
		$post = self::factory()->post->create_and_get( [ 'post_title' => 'Event: Passing Null' ] );

		$actual = Main::post_id_helper( null );

		$this->assertEquals( $post->ID, $actual );
	}

	/**
	 * @test When no arguments are passed, get the current post ID.
	 */
	public function it_should_return_global_post_id_when_passed_nothing() {
		global $post;
		$post = self::factory()->post->create_and_get( [ 'post_title' => 'Event: Passing Nothing' ] );

		$actual = Main::post_id_helper();

		$this->assertEquals( $post->ID, $actual );
	}

	/**
	 * @test It should return false when passed non existing post ID
	 *
	 * @param int $post_id
	 *
	 * @dataProvider integers
	 */
	public function it_should_return_false_when_passed_non_existing_post_id( int $post_id ) {
		$actual = Main::post_id_helper( $post_id );

		$this->assertFalse( $actual );
	}

	/**
	 * It should return false when passed negative integers
	 *
	 * @test
	 * @dataProvider negative_integers
	 */
	public function should_return_false_when_passed_negative_integers( $negative_integer ) {
		$actual = Main::post_id_helper( $negative_integer );

		$this->assertFalse( $actual );
	}

	/**
	 * It should return false when passed a numeric string for non existing post IDs.
	 *
	 * @test
	 * @dataProvider numeric_string_inputs
	 */
	public function it_should_return_int_when_passed_numeric_strings_for_non_existing_post_ids( string $numeric_string ) {
		$actual = Main::post_id_helper( $numeric_string );

		$this->assertFalse( $actual );
	}

	/**
	 * When passing a string of any kind, return false.
	 *
	 * @test
	 * @dataProvider non_numeric_strings
	 */
	public function it_should_return_false_when_passed_non_numeric_string( string $non_numeric_string ) {
		$actual = Main::post_id_helper( $non_numeric_string );

		$this->assertFalse( $actual );
	}

	public function integers() {
		return [
			'1'     => [ 1 ],
			'666'   => [ 666 ],
			'50000' => [ 50000 ],
		];
	}

	public function negative_integers() {
		return [
			'-1'     => [ - 1 ],
			'-666'   => [ - 666 ],
			'-50000' => [ - 50000 ],
		];
	}

	public function numeric_string_inputs() {
		return [
			'1'   => [ '1' ],
			'666' => [ '666' ],
		];
	}

	public function non_numeric_strings() {
		return [
			'schmootzy' => [ 'schmootzy' ],
			':-]'       => [ ':-]' ],
			'π'         => [ 'π' ],
		];
	}

	/**
	 * It should return false when passed zero
	 *
	 * @test
	 */
	public function should_return_false_when_passed_zero() {
		$this->assertFalse( Main::post_id_helper( 0 ) );
	}

	/**
	 * It should return the post ID when passed numeric string of existing post ID
	 *
	 * @test
	 */
	public function should_return_the_post_id_when_passed_numeric_string_of_existing_post_id() {
		$post_id = static::factory()->post->create();

		$this->assertEquals( $post_id, Main::post_id_helper( (string) $post_id ) );
	}

	/**
	 * It should return false if global post is set to non post object
	 *
	 * @test
	 */
	public function should_return_false_if_global_post_is_set_to_non_post_object() {
		$GLOBALS['post'] = 'not-a-post-object';

		$this->assertFalse( Main::post_id_helper() );
	}
}
