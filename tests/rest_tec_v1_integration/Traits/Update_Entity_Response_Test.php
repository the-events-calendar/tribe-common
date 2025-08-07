<?php

namespace TEC\Common\Tests\REST\TEC\V1\Traits;

use Codeception\TestCase\WPRestApiTestCase;
use TEC\Common\REST\TEC\V1\Traits\Update_Entity_Response;
use Tribe\Tests\Traits\With_Uopz;
use WP_REST_Response;

/**
 * Class Update_Entity_Response_Test
 *
 * Tests the Update_Entity_Response trait functionality.
 *
 * @since TBD
 */
class Update_Entity_Response_Test extends WPRestApiTestCase {

	use With_Uopz;

	/**
	 * Test that update method properly handles save() method failures.
	 *
	 * This test addresses the concern that we need a test case that mocks
	 * save to return an empty response to assure we return what expected
	 * when the update fails.
	 *
	 * @since TBD
	 */
	public function test_update_handles_save_failure() {
		// Create a test post to work with.
		$post_id = $this->factory()->post->create(
			[
				'post_title'  => 'Test Post for Save Failure',
				'post_status' => 'publish',
			]
		);

		// Create a simple ORM mock class that can be used with UOPZ.
		$test_orm_class = 'Test_ORM_' . uniqid();

		// Create the test ORM class dynamically.
		eval(
			"
			class {$test_orm_class} {
				public function by_args( \$args ) { return \$this; }
				public function set_args( \$args ) { return \$this; }
				public function save() { return true; }
				public function first() { return get_post( {$post_id} ); }
			}
		"
		);

		// Use UOPZ to mock the save method to return false.
		$this->set_class_fn_return( $test_orm_class, 'save', false );

		// Create a test class that uses the Update_Entity_Response trait.
		$test_endpoint = new class() {
			use Update_Entity_Response;

			private $orm_instance;

			public function set_orm_instance( $orm ) {
				$this->orm_instance = $orm;
			}

			public function get_orm() {
				return $this->orm_instance;
			}

			public function get_post_type() {
				return 'post';
			}

			public function get_formatted_entity( $entity ) {
				return [
					'id'    => $entity->ID,
					'title' => $entity->post_title,
				];
			}
		};

		// Set up the test endpoint with our mocked ORM.
		$orm_instance = new $test_orm_class();
		$test_endpoint->set_orm_instance( $orm_instance );

		// Test the update method with a failing save.
		$result = $test_endpoint->update(
			[
				'id'    => $post_id,
				'title' => 'Updated Title',
			]
		);

		// Verify that we get a 500 error response when save fails.
		$this->assertInstanceOf( WP_REST_Response::class, $result );
		$this->assertEquals( 500, $result->get_status() );
		$this->assertArrayHasKey( 'error', $result->get_data() );
		$this->assertEquals( 'Failed to update entity.', $result->get_data()['error'] );

		// Clean up.
		wp_delete_post( $post_id, true );
	}

	/**
	 * Test that update method properly handles successful save operations.
	 *
	 * @since TBD
	 */
	public function test_update_handles_successful_save() {
		// Create a test post to work with.
		$post_id = $this->factory()->post->create(
			[
				'post_title'  => 'Test Post for Successful Save',
				'post_status' => 'publish',
			]
		);

		// Create a simple ORM mock class.
		$test_orm_class = 'Test_ORM_Success_' . uniqid();

		// Create the test ORM class dynamically.
		eval(
			"
			class {$test_orm_class} {
				public function by_args( \$args ) { return \$this; }
				public function set_args( \$args ) { return \$this; }
				public function save() { return true; }
				public function first() { return get_post( {$post_id} ); }
			}
		"
		);

		// Create a test class that uses the Update_Entity_Response trait.
		$test_endpoint = new class() {
			use Update_Entity_Response;

			private $orm_instance;

			public function set_orm_instance( $orm ) {
				$this->orm_instance = $orm;
			}

			public function get_orm() {
				return $this->orm_instance;
			}

			public function get_post_type() {
				return 'post';
			}

			public function get_formatted_entity( $entity ) {
				return [
					'id'    => $entity->ID,
					'title' => $entity->post_title,
				];
			}
		};

		// Set up the test endpoint with our ORM.
		$orm_instance = new $test_orm_class();
		$test_endpoint->set_orm_instance( $orm_instance );

		// Test the update method with a successful save.
		$result = $test_endpoint->update(
			[
				'id'    => $post_id,
				'title' => 'Updated Title',
			]
		);

		// Verify that we get a 200 success response.
		$this->assertInstanceOf( WP_REST_Response::class, $result );
		$this->assertEquals( 200, $result->get_status() );
		$this->assertArrayHasKey( 'id', $result->get_data() );
		$this->assertEquals( $post_id, $result->get_data()['id'] );

		// Clean up.
		wp_delete_post( $post_id, true );
	}

	/**
	 * Test that update method handles entity not found after update.
	 *
	 * @since TBD
	 */
	public function test_update_handles_entity_not_found_after_save() {
		// Create a test post to work with.
		$post_id = $this->factory()->post->create(
			[
				'post_title'  => 'Test Post for Entity Not Found',
				'post_status' => 'publish',
			]
		);

		// Create a simple ORM mock class.
		$test_orm_class = 'Test_ORM_NotFound_' . uniqid();

		// Create the test ORM class dynamically.
		eval(
			"
			class {$test_orm_class} {
				public function by_args( \$args ) { return \$this; }
				public function set_args( \$args ) { return \$this; }
				public function save() { return true; }
				public function first() { return null; }
			}
		"
		);

		// Create a test class that uses the Update_Entity_Response trait.
		$test_endpoint = new class() {
			use Update_Entity_Response;

			private $orm_instance;

			public function set_orm_instance( $orm ) {
				$this->orm_instance = $orm;
			}

			public function get_orm() {
				return $this->orm_instance;
			}

			public function get_post_type() {
				return 'post';
			}

			public function get_formatted_entity( $entity ) {
				return [
					'id'    => $entity->ID,
					'title' => $entity->post_title,
				];
			}
		};

		// Set up the test endpoint with our ORM.
		$orm_instance = new $test_orm_class();
		$test_endpoint->set_orm_instance( $orm_instance );

		// Test the update method where entity is not found after save.
		$result = $test_endpoint->update(
			[
				'id'    => $post_id,
				'title' => 'Updated Title',
			]
		);

		// Verify that we get a 500 error response when entity not found after update.
		$this->assertInstanceOf( WP_REST_Response::class, $result );
		$this->assertEquals( 500, $result->get_status() );
		$this->assertArrayHasKey( 'error', $result->get_data() );
		$this->assertEquals( 'Entity not found after update.', $result->get_data()['error'] );

		// Clean up.
		wp_delete_post( $post_id, true );
	}
}
