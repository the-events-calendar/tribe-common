<?php

use TEC\Event_Automator\Tests\Traits\REST\Auth;


/**
 * Inherited Methods
 * @method void wantToTest( $text )
 * @method void wantTo( $text )
 * @method void execute( $callable )
 * @method void expectTo( $prediction )
 * @method void expect( $prediction )
 * @method void amGoingTo( $argumentation )
 * @method void am( $role )
 * @method void lookForwardTo( $achieveValue )
 * @method void comment( $description )
 * @method \Codeception\Lib\Friend haveFriend( $name, $actorClass = null )
 *
 * @SuppressWarnings(PHPMD)
 */
class Restv1_etTester extends \Codeception\Actor {

	use _generated\Restv1_etTesterActions;
	use Auth;

	/**
	 * Define custom actions here
	 */

	/**
	 * Test Last Access.
	 *
	 * @since 6.0.0
	 *
	 * @param array<string|mixed> $data An array of details for an API key pair or an endpoint.
	 */
	public function test_et_last_access( array $data = [] ) {
		$this->assertArrayHasKey( 'last_access', $data );
		$this->assertNotEquals( '-', $data['last_access'] );
		$last_access_array = explode( '|', $data['last_access'] );
		$this->assertEquals( 'Event Tickets App', $last_access_array[0] );
		$this->assertIsObject( new \DateTime( $last_access_array[1] ) );
	}

	/**
	 * Test Last Access.
	 *
	 * @since 6.0.0
	 *
	 * @param array<string|mixed> $data An array of details for an API key pair or an endpoint.
	 */
	public function test_tec_last_access( array $data = [] ) {
		$this->assertArrayHasKey( 'last_access', $data );
		$this->assertNotEquals( '-', $data['last_access'] );
		$last_access_array = explode( '|', $data['last_access'] );
		$this->assertEquals( 'The Events Calendar App', $last_access_array[0] );
		$this->assertIsObject( new \DateTime( $last_access_array[1] ) );
	}
}
