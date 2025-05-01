<?php
namespace Tribe\Utils;

use Prophecy\Argument;
use Tribe__Utils__Coordinates_Provider as Coordinates_Provider;

class Coordinates_ProviderTest extends \Codeception\TestCase\WPTestCase {

	protected $json_mock_response        = <<<JSON
{
  "results": [
    {
      "address_components": [
        {
          "long_name": "10",
          "short_name": "10",
          "types": [
            "street_number"
          ]
        },
        {
          "long_name": "Downing Street",
          "short_name": "Downing St",
          "types": [
            "route"
          ]
        },
        {
          "long_name": "London",
          "short_name": "London",
          "types": [
            "locality",
            "political"
          ]
        },
        {
          "long_name": "London",
          "short_name": "London",
          "types": [
            "postal_town"
          ]
        },
        {
          "long_name": "Greater London",
          "short_name": "Greater London",
          "types": [
            "administrative_area_level_2",
            "political"
          ]
        },
        {
          "long_name": "United Kingdom",
          "short_name": "GB",
          "type": [
            "country",
            "political"
          ]
        },
        {
          "long_name": "SW1A 2AB",
          "short_name": "SW1A 2AB",
          "types": [
            "postal_code"
          ]
        }
      ],
      "formatted_address": "10 Downing St, London SW1A 2AB, UK",
      "geometry": {
        "location": {
          "lat": 51.5034066,
          "lng": -0.1275923
        },
        "location_type": "ROOFTOP",
        "viewport": {
          "northeast": {
            "lat": 51.50475558029149,
            "lng": -0.126243319708498
          },
          "southwest": {
            "lat": 51.50205761970849,
            "lng": -0.128941280291502
          }
        }
      },
      "place_id": "ChIJHcZsasUEdkgRNJXdCfMeyi4",
      "types": [
        "street_address"
      ]
    }
  ],
  "status": "OK"
}
JSON;
	protected $json_mock_not_ok_response = <<<JSON
{
  "results": [
  ],
  "status": "something that's not ok"
}
JSON;

	protected $json_mock_reponse_missing_location = <<<JSON
{
  "results": [
    {
      "address_components": [
        {
          "long_name": "10",
          "short_name": "10",
          "types": [
            "street_number"
          ]
        },
        {
          "long_name": "Downing Street",
          "short_name": "Downing St",
          "types": [
            "route"
          ]
        },
        {
          "long_name": "London",
          "short_name": "London",
          "types": [
            "locality",
            "political"
          ]
        },
        {
          "long_name": "London",
          "short_name": "London",
          "types": [
            "postal_town"
          ]
        },
        {
          "long_name": "Greater London",
          "short_name": "Greater London",
          "types": [
            "administrative_area_level_2",
            "political"
          ]
        },
        {
          "long_name": "United Kingdom",
          "short_name": "GB",
          "types": [
            "country",
            "political"
          ]
        },
        {
          "long_name": "SW1A 2AB",
          "short_name": "SW1A 2AB",
          "types": [
            "postal_code"
          ]
        }
      ],
      "formatted_address": "10 Downing St, London SW1A 2AB, UK",
      "geometry": {
        "location_type": "ROOFTOP",
        "viewport": {
          "northeast": {
            "lat": 51.50475558029149,
            "lng": -0.126243319708498
          },
          "southwest": {
            "lat": 51.50205761970849,
            "lng": -0.128941280291502
          }
        }
      },
      "place_id": "ChIJHcZsasUEdkgRNJXdCfMeyi4",
      "types": [
        "street_address"
      ]
    }
  ],
  "status": "OK"
}
JSON;


	protected $mock_response_lat  = '51.5034066';
	protected $mock_response_long = '-0.1275923';

	/**
	 * @var \WP_Http
	 */
	protected $http;

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
		$this->http = $this->prophesize( 'WP_Http' );
	}

	public function tearDown(): void {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( 'Tribe__Utils__Coordinates_Provider', $sut );
	}

	/**
	 * @test
	 * it should init the http object to the WP one if not provided
	 */
	public function it_should_init_the_http_object_to_the_wp_one_if_not_provided() {
		$sut = new Coordinates_Provider();

		$this->assertSame( _wp_http_get_object(), $sut->get_http() );
	}

	/**
	 * @test
	 * it should make the right http get request to google api
	 */
	public function it_should_make_the_right_http_get_request_to_google_api() {
		$address      = '10, Downing Street, London, UK';
		$expected_url = esc_url( add_query_arg( array( 'address' => $address ), Coordinates_Provider::$google_api_base . Coordinates_Provider::$google_api_json_format ) );
		$this->http->get( $expected_url )->shouldBeCalled();

		$sut = $this->make_instance();

		$sut->provide_coordinates_for_address( $address );
	}

	/**
	 * @test
	 * it should accept array addresses as arguments
	 */
	public function it_should_accept_array_addresses_as_well() {
		$string_address      = '10, Downing Street, London, UK';
		$address      = [
			'10, Downing Street',
			'London',
			'UK',
		];

		$expected_url = esc_url( add_query_arg( array( 'address' => $string_address ), Coordinates_Provider::$google_api_base . Coordinates_Provider::$google_api_json_format ) );
		$this->http->get( $expected_url )->shouldBeCalled();

		$sut = $this->make_instance();

		$sut->provide_coordinates_for_address( $address );
	}

	/**
	 * @test
	 * it should filter out empty strings from the address
	 */
	public function it_should_filter_out_empty_strings_from_the_address() {
		$string_address      = '10, Downing Street, London, UK';
		$address      = [
			'10, Downing Street',
			'',
			'London',
			'       ',
			'UK',
			'  ',
		];

		$expected_url = esc_url( add_query_arg( array( 'address' => $string_address ), Coordinates_Provider::$google_api_base . Coordinates_Provider::$google_api_json_format ) );
		$this->http->get( $expected_url )->shouldBeCalled();

		$sut = $this->make_instance();

		$sut->provide_coordinates_for_address( $address );
	}

	/**
	 * @test
	 * it should return false if request fails
	 */
	public function it_should_return_false_if_request_fails() {
		$address = '10, Downing Street, London, UK';
		$this->http->get( Argument::type( 'string' ) )->willReturn( new \WP_Error( 400 ) );

		$sut = $this->make_instance();

		$this->assertFalse( $sut->provide_coordinates_for_address( $address ) );
	}

	/**
	 * @test
	 * it should return false if return status of response is not OK
	 */
	public function it_should_return_false_if_return_status_of_response_is_not_ok() {
		$address = '10, Downing Street, London, UK';
		$this->http->get( Argument::type( 'string' ) )->willReturn( array( 'body' => $this->json_mock_not_ok_response ) );

		$sut = $this->make_instance();

		$this->assertFalse( $sut->provide_coordinates_for_address( $address ) );
	}

	/**
	 * @test
	 * it should return false if location lat or long is missing
	 */
	public function it_should_return_false_if_location_lat_or_long_is_missing() {
		$address = '10, Downing Street, London, UK';
		$this->http->get( Argument::type( 'string' ) )->willReturn( array( 'body' => $this->json_mock_not_ok_response ) );

		$sut = $this->make_instance();

		$this->assertFalse( $sut->provide_coordinates_for_address( $address ) );
	}

	/**
	 * @test
	 * it should cache resolved addresses
	 */
	public function it_should_cache_resolved_addresses() {
		$address = '10, Downing Street, London, UK';
		$this->http->get( Argument::type( 'string' ) )->willReturn( array( 'body' => $this->json_mock_response ) );

		$sut = $this->make_instance();

		$coordinates = $sut->provide_coordinates_for_address( $address );

		$cached = get_transient( Coordinates_Provider::$transient_name );
		$this->assertEquals( array( $address => $coordinates ), $cached );
	}

	/**
	 * @test
	 * it should fetch resolved addresses from cache
	 */
	public function it_should_fetch_resolved_addresses_from_cache() {
		$address = '10, Downing Street, London, UK';
		$this->http->get( Argument::type( 'string' ) )->willReturn( array( 'body' => $this->json_mock_response ) )->shouldBeCalledTimes( 1 );

		$sut = $this->make_instance();

		$coordinates = $sut->provide_coordinates_for_address( $address );

		$cached = get_transient( Coordinates_Provider::$transient_name );
		$this->assertEquals( array( $address => $coordinates ), $cached );

		$coordinates2 = $sut->provide_coordinates_for_address( $address );

		$this->assertEquals( $coordinates, $coordinates2 );
	}

	/**
	 * @test
	 * it should provide coordinates for string addresses
	 */
	public function it_should_provide_coordinates_for_string_addresses() {
		$address = '10, Downing Street, London, UK';
		$this->http->get( Argument::type( 'string' ) )->willReturn( array( 'body' => $this->json_mock_response ) );

		$sut = $this->make_instance();

		$coordinates = $sut->provide_coordinates_for_address( $address );

		$this->assertArrayHasKey( 'lat', $coordinates );
		$this->assertArrayHasKey( 'lng', $coordinates );
		$this->assertEquals( $this->mock_response_lat, $coordinates['lat'] );
		$this->assertEquals( $this->mock_response_long, $coordinates['lng'] );
	}

	private function make_instance() {
		return new Coordinates_Provider( $this->http->reveal() );
	}
}
