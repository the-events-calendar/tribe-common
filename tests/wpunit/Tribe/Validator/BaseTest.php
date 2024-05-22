<?php

namespace Tribe\Validator;

use Tribe__Validator__Base as Validator;

class BaseTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Validator::class, $this->make_instance() );
	}

	/**
	 * @return Validator
	 */
	protected function make_instance() {
		return new Validator();
	}

	public function is_string_data() {
		return [
			[ '', false ],
			[ null, false ],
			[ array( 'foo' => 'bar' ), false ],
			[ array( 'foo', 'bar' ), false ],
			[ new \StdClass(), false ],
			[ 'f', true ],
			[ 'foo bar', true ],
		];
	}

	/**
	 * Test is_string
	 *
	 * @test
	 * @dataProvider is_string_data
	 */
	public function test_is_string( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_string( $value ) );
	}

	public function is_string_or_empty_data() {
		return [
			[ '', true ],
			[ null, true ],
			[ array( 'foo' => 'bar' ), false ],
			[ array( 'foo', 'bar' ), false ],
			[ new \StdClass(), false ],
			[ 'f', true ],
			[ 'foo bar', true ],
		];
	}

	/**
	 * Test is_string_or_empty
	 *
	 * @test
	 * @dataProvider is_string_or_empty_data
	 */
	public function test_is_string_or_empty( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_string_or_empty( $value ) );
	}

	public function is_not_null_data() {
		return [
			[ '', true ],
			[ null, false ],
			[ array( 'foo' => 'bar' ), true ],
			[ array( 'foo', 'bar' ), true ],
			[ new \StdClass(), true ],
			[ 'f', true ],
			[ 'foo bar', true ],
			[ '0', true ],
			[ 0, true ],
		];
	}

	/**
	 * Test is_not_null
	 *
	 * @test
	 * @dataProvider is_not_null_data
	 */
	public function test_is_not_null( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_not_null( $value ) );
	}

	public function is_null_data() {
		return [
			[ '', false ],
			[ null, true ],
			[ array( 'foo' => 'bar' ), false ],
			[ array( 'foo', 'bar' ), false ],
			[ new \StdClass(), false ],
			[ 'f', false ],
			[ 'foo bar', false ],
			[ '0', false ],
			[ 0, false ],
		];
	}

	/**
	 * Test is_null
	 *
	 * @test
	 * @dataProvider is_null_data
	 */
	public function test_is_null( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_null( $value ) );
	}

	public function is_numeric_data() {
		return [
			[ '', false ],
			[ null, false ],
			[ array( 'foo' => 'bar' ), false ],
			[ array( 'foo', 'bar' ), false ],
			[ new \StdClass(), false ],
			[ '23', true ],
			[ 23, true ],
			[ '23 89', false ],
		];
	}

	/**
	 * Test is_numeric
	 *
	 * @test
	 * @dataProvider is_numeric_data
	 */
	public function test_is_numeric( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_numeric( $value ) );
	}

	public function is_time_data() {
		return [
			[ '', false ],
			[ null, false ],
			[ array( 'foo' => 'bar' ), false ],
			[ array( 'foo', 'bar' ), false ],
			[ new \StdClass(), false ],
			[ '23', true ],
			[ 23, true ],
			[ 'tomorrow 9am', true ],
			[ '+5 days', true ],
			[ 'yesterday', true ],
			[ strtotime( 'tomorrow 8am' ), true ],
		];
	}

	/**
	 * Test is_time
	 *
	 * @test
	 * @dataProvider is_time_data
	 */
	public function test_is_time( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_time( $value ) );
	}

	public function is_user_bad_users() {
		return [
			[ null ],
			[ false ],
			[ 23 ],
			[ '23' ],
			[ array( 23 ) ],
			[ array( 'user' => 23 ) ],
		];
	}

	/**
	 * Test is_user bad users
	 *
	 * @test
	 * @dataProvider is_user_bad_users
	 */
	public function test_is_user_bad_users( $bad_user ) {
		$this->assertFalse( $this->make_instance()->is_user_id( $bad_user ) );
	}

	/**
	 * Test is_user with good user
	 *
	 * @test
	 */
	public function test_is_user_with_good_user() {
		$user_id = $this->factory()->user->create();
		$this->assertTrue( $this->make_instance()->is_user_id( $user_id ) );
	}

	public function is_positive_int_inputs() {
		return [
			[ 3, true ],
			[ 0, false ],
			[ - 1, false ],
			[ '3', true ],
			[ '0', false ],
			[ '-1', false ],
		];
	}

	/**
	 * Test is_positive_int
	 *
	 * @test
	 * @dataProvider is_positive_int_inputs
	 */
	public function test_is_positive_int( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->is_positive_int( $value ) );
	}

	public function trim_inputs() {
		return [
			[ 'foo', 'foo' ],
			[ 'foo ', 'foo' ],
			[ ' foo ', 'foo' ],
			[ ' foo  ', 'foo' ],
			[ [ 'foo' => 'bar' ], [ 'foo' => 'bar' ] ],
			[ 23, 23 ],
		];
	}

	/**
	 * Test trim
	 *
	 * @test
	 * @dataProvider trim_inputs
	 */
	public function test_trim( $value, $expected ) {
		$this->assertEquals( $expected, $this->make_instance()->trim( $value ) );
	}

	public function bad_post_tags() {
		return [
			[ 0 ],
			[ '0' ],
			[ 'foo' ],
			[ 23 ], // not present
			[ '23' ], // not present
		];
	}

	/**
	 * Test is_post_tag with bad tags
	 *
	 * @test
	 * @dataProvider bad_post_tags
	 */
	public function test_is_post_tag_with_bad_tags( $tag ) {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_post_tag( $tag ) );
	}

	/**
	 * Test is_post_tag with good tags
	 *
	 * @test
	 */
	public function test_is_post_tag_with_good_tags() {
		$tag_1 = $this->factory()->tag->create( [ 'slug' => 'foo' ] );
		$tag_2 = $this->factory()->tag->create();

		$sut = $this->make_instance();

		$this->assertTrue( $sut->is_post_tag( $tag_1 ) );
		$this->assertTrue( $sut->is_post_tag( $tag_2 ) );
		$this->assertTrue( $sut->is_post_tag( [ $tag_1, $tag_2 ] ) );
		$this->assertTrue( $sut->is_post_tag( "{$tag_1},{$tag_2}" ) );
	}

	/**
	 * Test is_post_tag with multiple tags
	 *
	 * @test
	 */
	public function test_is_post_tag_with_multiple_tags() {
		$tag_1 = $this->factory()->tag->create( [ 'slug' => 'foo' ] );
		$tag_2 = $this->factory()->tag->create();
		$category = $this->factory()->category->create();

		$sut = $this->make_instance();

		$this->assertTrue( $sut->is_post_tag( [ $tag_1, $tag_2 ] ) );
		$this->assertFalse( $sut->is_post_tag( [ $tag_1, $tag_2, $category ] ) );
	}

	public function test_is_image_bad_inputs() {
		return [
			[ '' ],
			[ null ],
			[ false ],
			[ 'foo' ],
			[ '23' ],
			[ 23 ],
			[ 0 ],
			[ '0' ],
		];
	}

	/**
	 * Test is_image with bad inputs
	 *
	 * @test
	 * @dataProvider test_is_image_bad_inputs
	 */
	public function test_is_image_with_bad_inputs( $bad_input ) {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_image( $bad_input ) );
	}

	/**
	 * Test is_image with good inputs
	 *
	 * @test
	 */
	public function test_is_image_with_good_inputs() {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$image_url = plugins_url( 'common/tests/_data/images/featured-image.jpg', \Tribe__Events__Main::instance()->plugin_file );
		$bad_image_url = plugins_url( 'common/tests/_data/images/featured-image.raw', \Tribe__Events__Main::instance()->plugin_file );
		$image_uploader = new \Tribe__Image__Uploader( $image_url );
		$image_id = $image_uploader->upload_and_get_attachment_id();

		$sut = $this->make_instance();

		$this->assertTrue( $sut->is_image( $image_url ) );
		$this->assertTrue( $sut->is_image( $image_id ) );
		$this->assertFalse( $sut->is_image( $bad_image_url ) );
	}

	/**
	 * Test is_image with good inputs
	 *
	 * @test
	 */
	public function test_is_image_with_good_inputs_but_invalid_user() {
		$image_url = plugins_url( 'common/tests/_data/images/featured-image.jpg', \Tribe__Events__Main::instance()->plugin_file );
		$bad_image_url = plugins_url( 'common/tests/_data/images/featured-image.raw', \Tribe__Events__Main::instance()->plugin_file );
		$image_uploader = new \Tribe__Image__Uploader( $image_url );
		$image_id = $image_uploader->upload_and_get_attachment_id();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_image( $image_url ) );
		$this->assertFalse( $sut->is_image( $image_id ) );
	}

	public function test_is_image_or_empty_bad_inputs() {
		return [
			[ 'foo' ],
			[ '23' ],
			[ 23 ],
		];
	}

	/**
	 * Test is_image_or_empty with bad inputs
	 *
	 * @test
	 * @dataProvider test_is_image_or_empty_bad_inputs
	 */
	public function test_is_image_or_empty_with_bad_inputs( $bad_input ) {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_image_or_empty( $bad_input ) );
	}

	public function test_is_image_or_empty_good_inputs() {
		return [
			[ '' ],
			[ null ],
			[ false ],
			[ 0 ],
			[ '0' ],
		];
	}

	/**
	 * Test is_image_or_empty with good inputs
	 *
	 * @test
	 * @dataProvider test_is_image_or_empty_good_inputs
	 */
	public function test_is_image_or_empty_with_good_inputs( $bad_input ) {
		$sut = $this->make_instance();

		$this->assertTrue( $sut->is_image_or_empty( $bad_input ) );
	}

	/**
	 * Test is_image_or_empty with images
	 *
	 * @test
	 */
	public function test_is_image_or_empty_with_images() {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$image_url = plugins_url( 'common/tests/_data/images/featured-image2.jpg', \Tribe__Events__Main::instance()->plugin_file );
		$bad_image_url = plugins_url( 'common/tests/_data/images/featured-image.raw', \Tribe__Events__Main::instance()->plugin_file );
		$image_uploader = new \Tribe__Image__Uploader( $image_url );
		$image_id = $image_uploader->upload_and_get_attachment_id();

		$sut = $this->make_instance();

		$this->assertTrue( $sut->is_image_or_empty( $image_url ) );
		$this->assertTrue( $sut->is_image_or_empty( $image_id ) );
		$this->assertFalse( $sut->is_image_or_empty( $bad_image_url ) );
	}

	/**
	 * Test is_image_or_empty with images
	 *
	 * @test
	 */
	public function test_is_image_or_empty_with_images_but_invalid_user() {
		$image_url = plugins_url( 'common/tests/_data/images/featured-image2.jpg', \Tribe__Events__Main::instance()->plugin_file );
		$bad_image_url = plugins_url( 'common/tests/_data/images/featured-image.raw', \Tribe__Events__Main::instance()->plugin_file );
		$image_uploader = new \Tribe__Image__Uploader( $image_url );
		$image_id = $image_uploader->upload_and_get_attachment_id();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_image_or_empty( $image_url ) );
		$this->assertFalse( $sut->is_image_or_empty( $image_id ) );
	}

	public function is_url_inputs() {
		return [
			[ '', false ],
			[ 'foo', false ],
			[ 23, false ],
			[ '23', false ],
			[ array( 'foo' => 'http://example.com' ), false ],
			[ 'http://foo.bar', true ],
			[ 'http://foo.com', true ],
			[ 'http://foo.com/foo/bar/baz', true ],
			[ 'https://foo.bar', true ],
			[ 'https://foo.com', true ],
			[ 'https://foo.com/foo/bar/baz', true ],
			[ 'http://foo.bar:8080', true ],
			[ 'http://foo.com:8080', true ],
			[ 'http://foo.com:8080/foo/bar/baz', true ],
			[ 'https://foo.bar:8080', true ],
			[ 'https://foo.com:8080', true ],
			[ 'https://foo.com:8080/foo/bar/baz', true ],
			[ 'foo/bar/baz', false ],
			[ '/foo/bar/baz', false ],
		];
	}

	/**
	 * Test is_url
	 *
	 * @test
	 * @dataProvider is_url_inputs
	 */
	public function test_is_url( $input, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->is_url( $input ) );
	}

	public function is_url_or_empty_inputs() {
		return [
			[ '', true ],
			[ 'foo', false ],
			[ 23, false ],
			[ '23', false ],
			[ array( 'foo' => 'http://example.com' ), false ],
			[ 'http://foo.bar', true ],
			[ 'http://foo.com', true ],
			[ 'http://foo.com/foo/bar/baz', true ],
			[ 'https://foo.bar', true ],
			[ 'https://foo.com', true ],
			[ 'https://foo.com/foo/bar/baz', true ],
			[ 'http://foo.bar:8080', true ],
			[ 'http://foo.com:8080', true ],
			[ 'http://foo.com:8080/foo/bar/baz', true ],
			[ 'https://foo.bar:8080', true ],
			[ 'https://foo.com:8080', true ],
			[ 'https://foo.com:8080/foo/bar/baz', true ],
			[ 'foo/bar/baz', false ],
			[ '/foo/bar/baz', false ],
		];
	}

	/**
	 * Test is_url_or_empty
	 *
	 * @test
	 * @dataProvider is_url_or_empty_inputs
	 */
	public function test_is_url_or_empty( $input, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->is_url_or_empty( $input ) );
	}

	public function is_post_status_inputs() {
		return [
			[ 'publish', true ],
			[ 'foo', false ],
			[ 'draft', true ],
			[ 23, false ],
			[ '23', false ],
			[ 'foo publish', false ],
			[ 'future', true ],
		];
	}

	/**
	 * Test is_post_status
	 *
	 * @test
	 * @dataProvider is_post_status_inputs
	 */
	public function test_is_post_status( $input, $expected ) {
		$sut = $this->make_instance();

		$this->assertEquals( $expected, $sut->is_post_status( $input ) );
	}
}
