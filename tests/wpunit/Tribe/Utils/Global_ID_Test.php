<?php

namespace Tribe\Utils;

use \Tribe__Utils__Global_ID as Global_ID;

/**
 * Tests general Meetup functionality
 */
class Global_ID_Test extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function it_should_be_instantiatable() {
		$global_id = new Global_ID;

		$this->assertInstanceOf( 'Tribe__Utils__Global_ID', $global_id );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function is_ics_invalid_type() {
		$global_id = new Global_ID;

		$global_id->type( 'ics' );
		$this->assertFalse( $global_id->type(), 'Test "ics" as a global ID type' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function is_ical_invalid_type() {
		$global_id = new Global_ID;

		$global_id->type( 'ical' );
		$this->assertFalse( $global_id->type(), 'Test "ical" as a global ID type' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function is_gcal_invalid_type() {
		$global_id = new Global_ID;

		$global_id->type( 'gcal' );
		$this->assertFalse( $global_id->type(), 'Test "gcal" as a global ID type' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function is_facebook_valid_type() {
		$global_id = new Global_ID;

		$global_id->type( 'facebook' );
		$this->assertEquals( $global_id->type(), 'facebook', 'Test "facebook" as a global ID type' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function is_meetup_valid_type() {
		$global_id = new Global_ID;

		$global_id->type( 'meetup' );
		$this->assertEquals( $global_id->type(), 'meetup', 'Test "meetup" as a global ID type' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function is_invalid_type() {
		$global_id = new Global_ID;

		$global_id->type( 'devel' );
		$this->assertFalse( $global_id->type(), 'Test if invalid type return false' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function verify_type_based_origins() {
		$global_id = new Global_ID;

		$global_id->type( 'meetup' );
		$this->assertEquals( $global_id->origin(), 'meetup.com', 'Check if an Meetup Type ID will return origin as "meetup.com"' );

		$global_id->type( 'facebook' );
		$this->assertEquals( $global_id->origin(), 'facebook.com', 'Check if an Facebook Type ID will return origin as "facebook.com"' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function verify_url_based_origin() {
		$global_id = new Global_ID;
		$global_id->type( 'url' );

		$global_id->origin( 'http://example.com' );
		$this->assertEquals( $global_id->origin(), 'example.com', 'Check if the origin get setup correctly for host' );

		$global_id->origin( 'http://example.com/my/path' );
		$this->assertEquals( $global_id->origin(), 'example.com/my/path', 'Check if the origin get setup correctly for host + path' );

		$global_id->origin( 'http://example.com/my/path?one=1&two=2' );
		$this->assertEquals( $global_id->origin(), 'example.com/my/path?one=1&two=2', 'Check if the origin get setup correctly for host + path + query' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function verify_facebook_id_generation() {
		$global_id = new Global_ID;
		$global_id->type( 'facebook' );
		$this->assertEquals( $global_id->generate( [ 'id' => '1234567890' ] ), 'facebook.com?id=1234567890', 'Check if facebook id gets set correctly' );
	}

	/**
	 * @test
	 *
	 * @group utils
	 */
	public function verify_url_id_generation() {
		$global_id = new Global_ID;
		$global_id->type( 'url' );
		$global_id->origin( 'example.com' );

		$this->assertEquals( $global_id->generate( [ 'id' => '1234567890' ] ), 'example.com?id=1234567890', 'Check if url id gets set correctly' );
	}

}
