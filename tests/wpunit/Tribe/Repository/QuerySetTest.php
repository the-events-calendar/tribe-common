<?php

namespace Tribe\Repository;

use PHPUnit\Framework\AssertionFailedError;
use Tribe__Repository as Repository;
use Tribe__Repository__Usage_Error as Usage_Error;

class QuerySetTest extends \Codeception\TestCase\WPTestCase {

	protected $class;

	public function setUp() {
		parent::setUp();
		register_post_type( 'book' );
		register_taxonomy( 'genre', 'book' );
		$this->class = new class extends \Tribe__Repository {
			protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];
			protected $filter_name = 'books';
		};
	}

	/**
	 * @return Repository
	 */
	protected function repository() {
		return new $this->class();
	}

	/**
	 * It should allow setting the query on the repository
	 *
	 * @test
	 */
	public function should_allow_setting_the_query_on_the_repository() {
		$query = new \WP_Query();

		$repository = $this->repository();
		$repository->set_query( $query );

		$this->assertSame( $query, $repository->get_query() );
		$this->assertSame( $query, $repository->build_query() );
	}

	/**
	 * It should not run the query when setting it
	 *
	 * @test
	 */
	public function should_not_run_the_query_when_setting_it() {
		$query = new \WP_Query();
		add_action( 'pre_get_posts', function ( \WP_Query $wp_query ) use ( $query ) {
			if ( $wp_query === $query ) {
				throw new AssertionFailedError( 'Query should not run when set with "Tribe__Repository::set_query()"!' );
			}
		} );

		$repository = $this->repository();
		$repository->set_query( $query );
	}

	/**
	 * It should run the query when running the first fetch method
	 *
	 * @test
	 */
	public function should_run_the_query_when_running_the_first_fetch_method() {
		$query = new \WP_Query();
		$calls = 0;
		add_action( 'pre_get_posts', function ( \WP_Query $wp_query ) use ( $query, &$calls ) {
			if ( $wp_query !== $query ) {
				return;
			}
			if ( ++ $calls > 1 ) {
				throw new AssertionFailedError( 'Query should only run once!' );
			}
		} );

		$repository = $this->repository();
		$repository->set_query( $query );

		$repository->found();
		$this->assertEquals( 1, $calls, 'Query should run once.');
	}

	/**
	 * It should run the query once only when setting query and running fetch method more than once
	 *
	 * @test
	 */
	public function should_run_the_query_once_only_when_setting_query_and_running_fetch_method_more_than_once() {
		$query = new \WP_Query();
		$calls = 0;
		add_action( 'pre_get_posts', function ( \WP_Query $wp_query ) use ( $query, &$calls ) {
			if ( $wp_query !== $query ) {
				return;
			}
			if ( ++ $calls > 1 ) {
				throw new AssertionFailedError( 'Query should only run once!' );
			}
		} );

		$repository = $this->repository();
		$repository->set_query( $query );

		$repository->count();
		$repository->first();
		$repository->found();
		$this->assertEquals( 1, $calls, 'Query should run once.');
	}

	/**
	 * It should throw a usage error if setting the query after a first fetch
	 *
	 * @test
	 */
	public function should_throw_a_usage_error_if_setting_the_query_after_a_first_fetch() {
		$repository = $this->repository();

		$repository->count();

		$this->expectException( Usage_Error::class );
		$repository->set_query( new \WP_Query() );
	}
}
