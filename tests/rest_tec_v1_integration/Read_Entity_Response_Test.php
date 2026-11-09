<?php
/**
 * Integration tests for the password handling of the Read_Entity_Response trait.
 *
 * @since TBD
 */

namespace TEC\Common\Tests\REST\TEC\V1;

use Codeception\TestCase\WPRestApiTestCase;
use TEC\Common\Contracts\Repository_Interface;
use TEC\Common\REST\TEC\V1\Traits\Read_Entity_Response;
use WP_Post;
use WP_REST_Request;

class Read_Entity_Response_Test extends WPRestApiTestCase {
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

	private function insert_post( string $password ): int {
		return wp_insert_post(
			[
				'post_type'     => 'tec_fake_post',
				'post_status'   => 'publish',
				'post_title'    => 'Protected',
				'post_password' => $password,
			]
		);
	}

	/**
	 * Builds an endpoint whose formatted entity reports the password state of a linked post.
	 *
	 * The ORM is mocked because the repository contract needs a full model stack that is
	 * irrelevant here; the trait's `post_password_required` handling runs for real.
	 */
	private function make_endpoint( int $primary_id, int $linked_id, WP_REST_Request $request ): object {
		$orm = $this->createMock( Repository_Interface::class );
		$orm->method( 'by_args' )->willReturnSelf();
		$orm->method( 'first' )->willReturn( get_post( $primary_id ) );

		return new class( $orm, $linked_id, $request ) {
			use Read_Entity_Response;

			private $orm;
			private $linked_id;
			private $request;

			public function __construct( $orm, $linked_id, $request ) {
				$this->orm       = $orm;
				$this->linked_id = $linked_id;
				$this->request   = $request;
			}

			public function get_orm(): Repository_Interface {
				return $this->orm;
			}

			public function get_formatted_entity( WP_Post $post ): array {
				return [
					'id'              => $post->ID,
					'linked_required' => post_password_required( $this->linked_id ),
				];
			}

			public function get_post_type(): string {
				return 'tec_fake_post';
			}

			public function get_request(): WP_REST_Request {
				return $this->request;
			}
		};
	}

	private function make_request( int $id, string $password ): WP_REST_Request {
		$request = new WP_REST_Request( 'GET', '/tec/v1/fake-posts/' . $id );
		$request->set_param( 'id', $id );
		$request->set_param( 'password', $password );

		return $request;
	}

	/** @test */
	public function it_should_keep_a_linked_post_with_a_different_password_protected(): void {
		$primary_id = $this->insert_post( 'primary-secret' );
		$linked_id  = $this->insert_post( 'linked-secret' );
		$request    = $this->make_request( $primary_id, 'primary-secret' );

		$response = $this->make_endpoint( $primary_id, $linked_id, $request )->read( [ 'id' => $primary_id ] );

		$this->assertSame( 200, $response->get_status() );
		$this->assertTrue( $response->get_data()['linked_required'] );
	}

	/** @test */
	public function it_should_unlock_a_linked_post_sharing_the_supplied_password(): void {
		$primary_id = $this->insert_post( 'shared-secret' );
		$linked_id  = $this->insert_post( 'shared-secret' );
		$request    = $this->make_request( $primary_id, 'shared-secret' );

		$response = $this->make_endpoint( $primary_id, $linked_id, $request )->read( [ 'id' => $primary_id ] );

		$this->assertFalse( $response->get_data()['linked_required'] );
	}

	/** @test */
	public function it_should_remove_the_bypass_after_reading(): void {
		$primary_id = $this->insert_post( 'primary-secret' );
		$linked_id  = $this->insert_post( 'primary-secret' );
		$request    = $this->make_request( $primary_id, 'primary-secret' );

		$this->make_endpoint( $primary_id, $linked_id, $request )->read( [ 'id' => $primary_id ] );

		$this->assertTrue( post_password_required( $linked_id ) );
	}
}
