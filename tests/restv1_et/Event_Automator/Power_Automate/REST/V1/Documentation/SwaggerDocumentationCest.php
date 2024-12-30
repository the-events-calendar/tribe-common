<?php

namespace TEC\Event_Automator\Power_Automate\REST\V1\Documentation;

use TEC\Event_Automator\Tests\Testcases\REST\V1\BaseRestETCest;
use Restv1_etTester;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class SwaggerDocumentationCest extends BaseRestETCest {

	use SnapshotAssertions;

	/**
	 * @test
	 */
	public function it_should_expose_a_swagger_documentation_endpoint( Restv1_etTester $I ) {
		$I->sendGET( $this->documentation_url );

		$I->seeResponseCodeIs( 200 );
	}

	/**
	 * @test
	 */
	public function it_should_return_a_json_array_containing_headers_in_swagger_format( Restv1_etTester $I ) {
		$I->sendGET( $this->documentation_url );

		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = (array) json_decode( $I->grabResponse() );
		$I->assertArrayHasKey( 'openapi', $response );
		$I->assertArrayHasKey( 'info', $response );
		$I->assertArrayHasKey( 'servers', $response );
		$I->assertArrayHasKey( 'paths', $response );
		$I->assertArrayHasKey( 'components', $response );

		$this->assertMatchesJsonSnapshot( json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_the_correct_information( Restv1_etTester $I ) {
		$I->sendGET( $this->documentation_url );

		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = (array) json_decode( $I->grabResponse() );
		$I->assertArrayHasKey( 'info', $response );
		$info = (array) $response['info'];
		//version
		$I->assertArrayHasKey( 'version', $info );
		// title
		$I->assertArrayHasKey( 'title', $info );
		//description
		$I->assertArrayHasKey( 'description', $info );

		$this->assertMatchesJsonSnapshot( json_encode( $info, JSON_PRETTY_PRINT ) );
	}

	/**
	 * @test
	 */
	public function it_should_return_the_site_url_as_host( Restv1_etTester $I ) {
		$I->sendGET( $this->documentation_url );

		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = (array) json_decode( $I->grabResponse(), true );
		$I->assertArrayHasKey( 'url', $response['servers'][0] );
		$valid_url = filter_var( $response['servers'][0]['url'], FILTER_VALIDATE_URL );
		$I->assertEquals( $response['servers'][0]['url'], $valid_url );
	}

	/**
	 * @test
	 */
	public function it_should_contain_information_about_the_authorize_endpoint( Restv1_etTester $I ) {
		$I->sendGET( $this->documentation_url );

		$I->seeResponseCodeIs( 200 );
		$I->seeResponseIsJson();
		$response = (array) json_decode( $I->grabResponse() );
		$I->assertArrayHasKey( 'paths', $response );
		$paths = (array) $response['paths'];
		$I->assertArrayHasKey( '/authorize', $paths );
	}
}
