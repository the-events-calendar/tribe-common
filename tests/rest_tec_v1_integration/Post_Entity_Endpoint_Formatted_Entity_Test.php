<?php
/**
 * Integration tests for the context handling of Post_Entity_Endpoint::get_formatted_entity().
 *
 * @since TBD
 */

namespace TEC\Common\Tests\REST\TEC\V1;

use Codeception\TestCase\WPRestApiTestCase;
use TEC\Common\REST\TEC\V1\Abstracts\Post_Entity_Endpoint;
use TEC\Common\Tests\REST\V1\Fake_Post_Endpoint;
use WP_Post;
use WP_REST_Request;

class Post_Entity_Endpoint_Formatted_Entity_Test extends WPRestApiTestCase {
	protected function setUp(): void {
		parent::setUp();

		register_post_type(
			'tec_fake_post',
			[
				'public'       => true,
				'show_in_rest' => true,
			]
		);
	}

	/**
	 * Builds an endpoint that runs the real `Post_Entity_Endpoint::get_formatted_entity()`.
	 *
	 * `Fake_Post_Endpoint` stubs the formatter out, so this calls the abstract's implementation
	 * directly, with the request injected rather than bound in the shared container.
	 */
	private function make_endpoint( WP_REST_Request $request ): Fake_Post_Endpoint {
		return new class( $request ) extends Fake_Post_Endpoint {
			private $request;

			public function __construct( WP_REST_Request $request ) {
				$this->request = $request;
			}

			public function get_request(): WP_REST_Request {
				return $this->request;
			}

			public function get_formatted_entity( WP_Post $post ): array {
				return Post_Entity_Endpoint::get_formatted_entity( $post );
			}
		};
	}

	private function make_edit_request( int $id ): WP_REST_Request {
		$request = new WP_REST_Request( 'GET', '/tec/v1/fake-posts/' . $id );
		$request->set_param( 'id', $id );
		$request->set_param( 'context', 'edit' );

		return $request;
	}

	/** @test */
	public function it_should_serve_the_view_context_to_a_guest_asking_for_edit(): void {
		$post    = $this->factory()->post->create_and_get( [ 'post_type' => 'tec_fake_post' ] );
		$request = $this->make_edit_request( $post->ID );

		$entity = $this->make_endpoint( $request )->get_formatted_entity( $post );

		$this->assertSame( get_the_title( $post ), $entity['title']['rendered'] );
		$this->assertArrayNotHasKey( 'raw', $entity['title'] );
		$this->assertArrayNotHasKey( 'raw', $entity['content'] );
		$this->assertArrayNotHasKey( 'password', $entity );
		$this->assertSame( 'edit', $request['context'] );
	}

	/** @test */
	public function it_should_serve_the_edit_context_to_a_user_who_can_edit_the_post(): void {
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );
		$post = $this->factory()->post->create_and_get( [ 'post_type' => 'tec_fake_post' ] );

		$entity = $this->make_endpoint( $this->make_edit_request( $post->ID ) )->get_formatted_entity( $post );

		$this->assertSame( $post->post_title, $entity['title']['raw'] );
		$this->assertSame( $post->post_content, $entity['content']['raw'] );
		$this->assertArrayHasKey( 'password', $entity );
	}
}
