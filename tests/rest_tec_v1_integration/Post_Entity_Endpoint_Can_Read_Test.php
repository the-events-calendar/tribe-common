<?php
/**
 * Integration tests for Post_Entity_Endpoint read permissions.
 *
 * @since TBD
 */

namespace TEC\Common\Tests\REST\TEC\V1;

use Codeception\TestCase\WPRestApiTestCase;
use TEC\Common\Tests\REST\V1\Fake_Post_Endpoint;
use WP_REST_Request;

class Post_Entity_Endpoint_Can_Read_Test extends WPRestApiTestCase {


    protected function setUp(): void {
		parent::setUp();
		register_post_type(
			'tec_fake_post',
			[
				'label'        => 'Fake Posts',
				'public'       => true,
				'show_in_rest' => true,
			]
		);
	}

	public function test_can_read_published_post_as_guest(): void {
        $post_id = wp_insert_post(
            [
                'post_type'   => 'tec_fake_post',
                'post_status' => 'publish',
                'post_title'  => 'Published',
            ]
        );

		$endpoint = new Fake_Post_Endpoint();
		$request  = new WP_REST_Request( 'GET', '/tec/v1/fake-posts/' . $post_id );
		$request->set_param( 'id', $post_id );

		$this->assertTrue( $endpoint->can_read( $request ) );
	}

	public function test_cannot_read_private_post_as_guest(): void {
        $post_id = wp_insert_post(
            [
                'post_type'   => 'tec_fake_post',
                'post_status' => 'private',
                'post_title'  => 'Private',
            ]
        );

		$endpoint = new Fake_Post_Endpoint();
		$request  = new WP_REST_Request( 'GET', '/tec/v1/fake-posts/' . $post_id );
		$request->set_param( 'id', $post_id );

		$this->assertFalse( $endpoint->can_read( $request ) );
	}

	public function test_cannot_read_password_protected_without_password(): void {
        $post_id = wp_insert_post(
            [
                'post_type'     => 'tec_fake_post',
                'post_status'   => 'publish',
                'post_title'    => 'Protected',
                'post_password' => 'secret',
            ]
        );

		$endpoint = new Fake_Post_Endpoint();
		$request  = new WP_REST_Request( 'GET', '/tec/v1/fake-posts/' . $post_id );
		$request->set_param( 'id', $post_id );

		$this->assertFalse( $endpoint->can_read( $request ) );
	}

	public function test_can_read_password_protected_with_password(): void {
        $post_id = wp_insert_post(
            [
                'post_type'     => 'tec_fake_post',
                'post_status'   => 'publish',
                'post_title'    => 'Protected',
                'post_password' => 'secret',
            ]
        );

		$endpoint = new Fake_Post_Endpoint();
		$request  = new WP_REST_Request( 'GET', '/tec/v1/fake-posts/' . $post_id );
		$request->set_param( 'id', $post_id );
		$request->set_param( 'password', 'secret' );

		$this->assertTrue( $endpoint->can_read( $request ) );
	}
}


