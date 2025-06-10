<?php

namespace TEC\Tests\Common\Classy\REST;

use TEC\Common\Classy\REST\Controller;
use TEC\Common\Tests\Provider\Controller_Test_Case;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Languages__Locations as Locations;

class Controller_Test extends Controller_Test_Case {
	use With_Uopz;

	protected $controller_class = Controller::class;

	public function test_options_country_get(): void {
		/** @var Locations $locations */
		$locations     = tribe( 'languages.locations' );
		$country_array = $locations->build_country_array();

		$this->make_controller()->register();

		// A first request where the user is not set.
		$request = new \WP_REST_Request(
			'GET',
			'/tec/classy/v1/options/country'
		);

		$response = rest_get_server()->dispatch( $request );

		$this->assertEquals( 401, $response->status, 'Unauthenticated users cannot fetch the country options.' );

		wp_set_current_user( static::factory()->user->create( [ 'role' => 'subscriber' ] ) );

		$response = rest_get_server()->dispatch( $request );

		$this->assertEquals( 403, $response->status, 'Users need the edit_posts cap to fetch the country options.' );

		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );

		$response = rest_get_server()->dispatch( $request );

		$this->assertEquals( 200, $response->status );
		$this->assertCount( count( $country_array ), $response->get_data() );
		foreach ( $response->get_data() as $key => $entry ) {
			$this->assertEquals( $key, $entry['value'] );
			$this->assertEquals( $country_array[ $key ], $entry['name'] );
		}
	}
}
