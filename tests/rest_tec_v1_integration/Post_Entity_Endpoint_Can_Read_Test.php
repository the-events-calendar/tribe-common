<?php
/**
 * Integration tests for Post_Entity_Endpoint read permissions.
 *
 * @since 6.9.0
 */

namespace TEC\Common\Tests\REST\TEC\V1;

use Codeception\TestCase\WPRestApiTestCase;
use TEC\Common\Tests\REST\V1\Fake_Post_Endpoint;
use WP_REST_Request;
use WP_REST_Posts_Controller;

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

	public function test_can_read_password_protected_without_password_and_sets_protected_flag(): void {
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

		// Password-protected published posts should be readable (content will be empty unless password provided).
		$this->assertTrue( $endpoint->can_read( $request ) );

		// Verify WordPress REST response marks content as protected.
		$wp_controller = new WP_REST_Posts_Controller( 'tec_fake_post' );
		$wp_request    = new WP_REST_Request( 'GET', '/wp/v2/tec_fake_post/' . $post_id );
		$wp_request->set_param( 'context', 'view' );
		$data       = $wp_controller->prepare_item_for_response( get_post( $post_id ), $wp_request );
		$prepared   = $wp_controller->prepare_response_for_collection( $data );
		$this->assertArrayHasKey( 'content', $prepared );
		$this->assertArrayHasKey( 'protected', $prepared['content'] );
		$this->assertTrue( (bool) $prepared['content']['protected'] );
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


