<?php

namespace Tribe;

use Tribe\Common\Tests\Test_Class;
use Tribe__Promise as Promise;
use Tribe__Utils__Callback as Callback;

class PromiseTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 * it should be instantiatable without side effects.
	 */
	public function it_should_be_instantiatable_wo_side_effects() {
		$this->assertInstanceOf( Promise::class, new Promise() );
	}

	/**
	 * It should allow registering a task and items
	 *
	 * @test
	 */
	public function should_allow_registering_a_task_and_items() {
		// To test it we force the process to run async, that's tested elsewhere.
		tribe_update_option( 'tribe_queue_sync', true );
		$post_payloads = [
			[ 'post_title' => 'foo', 'post_status' => 'publish' ],
			[ 'post_title' => 'bar', 'post_status' => 'publish' ],
			[ 'post_title' => 'baz', 'post_status' => 'publish' ],
		];

		$promise = new Promise( 'wp_insert_post', $post_payloads );
		$promise->save()->dispatch();

		$this->assertInternalType( 'string', $promise->get_id() );
		$posts = get_posts();
		$this->assertCount( 3, $posts );
		$this->assertEqualSets( [ 'foo', 'baz', 'bar' ], wp_list_pluck( $posts, 'post_title' ) );
	}

	/**
	 * It should correctly spawn in secondary request.
	 *
	 * In this test we setup the promise, destroy it, build another and dispatch
	 * it using the first one id.
	 *
	 * @test
	 */
	public function should_correctly_spawn_in_secondary_request() {
		$post_payloads   = [
			[ 'post_title' => 'foo', 'post_status' => 'publish' ],
			[ 'post_title' => 'bar', 'post_status' => 'publish' ],
			[ 'post_title' => 'baz', 'post_status' => 'publish' ],
		];
		$promise_builder = new Promise( 'wp_insert_post', $post_payloads );
		// Saved but not dispatched.
		$promise_id = $promise_builder->save()->get_id();
		// Populate the request nonce to passe the `maybe_handle` check the next promise will do.
		$_REQUEST['nonce'] = wp_create_nonce( $promise_builder->get_identifier() );
		// Also: do not die after handling the request.
		add_filter( 'wp_die_handler', function () {
			return '__return_false';
		} );

		$promise = new Promise();
		$promise->set_id( $promise_id );
		$promise->maybe_handle();

		$posts = get_posts();
		$this->assertCount( 3, $posts );
		$this->assertEqualSets( [ 'foo', 'baz', 'bar' ], wp_list_pluck( $posts, 'post_title' ) );
	}

	/**
	 * It should support extra args
	 *
	 * @test
	 */
	public function should_support_extra_args() {
		include_once codecept_data_dir('test-functions.php');
		$post_payloads   = [
			[ 'post_title' => 'foo', 'post_status' => 'publish' ],
			[ 'post_title' => 'bar', 'post_status' => 'publish' ],
			[ 'post_title' => 'baz', 'post_status' => 'publish' ],
		];
		$promise_builder = new Promise( 'tribe_test_insert_post', $post_payloads, [ ' - postfix' ] );
		// Saved but not dispatched.
		$promise_id = $promise_builder->save()->get_id();
		// Populate the request nonce to passe the `maybe_handle` check the next promise will do.
		$_REQUEST['nonce'] = wp_create_nonce( $promise_builder->get_identifier() );
		// Also: do not die after handling the request.
		add_filter( 'wp_die_handler', function () {
			return '__return_false';
		} );

		$promise = new Promise();
		$promise->set_id( $promise_id );
		$promise->maybe_handle();

		$posts = get_posts(  );
		$this->assertCount( 3, $posts );
		$this->assertEqualSets( [ 'foo - postfix', 'baz - postfix', 'bar - postfix' ], wp_list_pluck( $posts, 'post_title' ) );
	}

	/**
	 * It should support Tribe Callback class usage to pass callbacks
	 *
	 * @test
	 */
	public function should_support_tribe_callback_class_usage_to_pass_callbacks() {
		include_once codecept_data_dir( 'classes/Test_Class.php' );
		tribe_register( 'test-test', Test_Class::class );
		$callback = new Callback( 'test-test', 'insert_post' );
		$post_payloads   = [
			[ 'post_title' => 'foo', 'post_status' => 'publish' ],
			[ 'post_title' => 'bar', 'post_status' => 'publish' ],
			[ 'post_title' => 'baz', 'post_status' => 'publish' ],
		];
		$promise_builder = new Promise( $callback, $post_payloads );
		// Saved but not dispatched.
		$promise_id = $promise_builder->save()->get_id();
		// Populate the request nonce to passe the `maybe_handle` check the next promise will do.
		$_REQUEST['nonce'] = wp_create_nonce( $promise_builder->get_identifier() );
		// Also: do not die after handling the request.
		add_filter( 'wp_die_handler', function () {
			return '__return_false';
		} );

		$promise = new Promise();
		$promise->set_id( $promise_id );
		$promise->maybe_handle();

		$posts = get_posts();
		$this->assertCount( 3, $posts );
		$this->assertEqualSets( [ 'foo', 'baz', 'bar' ], wp_list_pluck( $posts, 'post_title' ) );
	}
}