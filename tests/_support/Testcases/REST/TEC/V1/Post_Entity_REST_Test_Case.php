<?php
/**
 * Base test case for Post Entity REST API endpoints.
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\TestCases\REST\TEC\V1
 */

namespace TEC\Common\Tests\TestCases\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Contracts\Post_Entity_Endpoint_Interface as Post_Entity_Endpoint;

/**
 * Class Post_Entity_REST_Test_Case
 *
 * @since TBD
 *
 * @package TEC\Common\Tests\TestCases\REST\TEC\V1
 */
abstract class Post_Entity_REST_Test_Case extends REST_Test_Case {
	/**
	 * The endpoint instance.
	 *
	 * @var Post_Entity_Endpoint
	 */
	protected $endpoint;

	abstract public function test_get_formatted_entity();

	abstract public function test_instance_of_orm();

	abstract public function test_get_model_class();

	public function test_validate_status() {
		$this->assertTrue( $this->endpoint->validate_status( 'publish' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'draft' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'pending' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'private' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'future' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'inherit' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'any' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'random' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'random,publish' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,random' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,trash' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,draft' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,pending' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,private' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,future' ) );
		$this->assertTrue( $this->endpoint->validate_status( 'publish,draft,pending,private,future' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any,random' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any,random,trash' ) );
		$this->assertFalse( $this->endpoint->validate_status( 'publish,draft,pending,private,future,trash,inherit,any,random,trash,inherit' ) );
	}
}
