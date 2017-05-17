<?php

namespace Tribe\REST;

use Tribe__REST__Validator as Validator;

class ValidatorTest extends \Codeception\TestCase\WPTestCase {

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

	public function is_user_bad_users(  ) {
		return [
			[null],
			[false],
			[23],
			['23'],
			[array(23)],
			[array('user' => 23)],
		];
}
	/**
	 * Test is_user bad users
	 *
	 * @test
	 * @dataProvider is_user_bad_users
	 */
	public function test_is_user_bad_users($bad_user) {
		$this->assertFalse( $this->make_instance()->is_user( $bad_user ) );
	}

	/**
	 * Test is_user with good user
	 *
	 * @test
	 */
	public function test_is_user_with_good_user() {
		$user_id = $this->factory()->user->create();
		$this->assertTrue( $this->make_instance()->is_user( $user_id ) );
	}
}