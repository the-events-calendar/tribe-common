<?php

namespace Tribe\Traits;

class With_DB_LockTest extends \Codeception\TestCase\WPTestCase {
	use With_DB_Lock;

	/**
	 * @var \PDO
	 */
	protected $pdo;

	/**
	 * It should allow acquiring a db lock
	 *
	 * @test
	 */
	public function should_allow_acquiring_a_db_lock() {
		$test_lock = uniqid( 'lock', true );
		$acquired  = $this->acquire_db_lock( $test_lock );

		$this->assertTrue( $acquired );
	}

	/**
	 * It should allow releasing a db lock
	 *
	 * @test
	 */
	public function should_allow_releasing_a_db_lock() {
		$test_lock = uniqid( 'lock', true );
		// Acquire the lock with the same db connection.
		global $wpdb;
		$wpdb->query( "SELECT GET_LOCK('{$test_lock}',10)" );

		$released = $this->release_db_lock( $test_lock );

		$this->assertTrue( $released );
	}

	/**
	 * It should not allow acquiring a held lock by other session
	 *
	 * @test
	 */
	public function should_not_allow_acquiring_a_held_lock_by_other_session() {
		$test_lock = uniqid( 'lock', true );
		$this->acquire_lock_w_other_connection( $test_lock );

		add_filter( 'tribe_db_lock_timeout', static function () {
			return .25;
		} );

		$acquired = $this->acquire_db_lock( $test_lock );

		$this->assertFalse( $acquired );
	}

	protected function acquire_lock_w_other_connection( $test_lock ) {
		$dsn = sprintf( "mysql:host=%s;dbname=%s", DB_HOST, DB_NAME );
		$pdo = new \PDO( $dsn, DB_USER, DB_PASSWORD );
		$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		$this->pdo = $pdo;
		$is_free   = $pdo->query( "SELECT IS_FREE_LOCK(SHA1('{$test_lock}'))" );

		$this->assertEquals( '1', $is_free->fetchColumn() );

		$pdo->query( "SELECT GET_LOCK(SHA1('{$test_lock}'),10)" );

		$is_free = $pdo->query( "SELECT IS_FREE_LOCK(SHA1('{$test_lock}'))" );

		$this->assertEquals( '0', $is_free->fetchColumn() );
	}

	/**
	 * It should allow acquiring an held lock after its release by other session
	 *
	 * @test
	 */
	public function should_allow_acquiring_an_held_lock_after_its_release_by_other_session() {
		$test_lock = uniqid( 'lock', true );
		$this->acquire_lock_w_other_connection( $test_lock );

		add_filter( 'tribe_db_lock_timeout', static function () {
			return .25;
		} );

		$acquired = $this->acquire_db_lock( $test_lock );

		$this->assertFalse( $acquired );

		$this->release_lock_w_other_connection( $test_lock );

		$acquired = $this->acquire_db_lock( $test_lock );

		$this->assertTrue( $acquired );
	}

	protected function release_lock_w_other_connection( $test_lock ) {
		$this->pdo->query( "SELECT RELEASE_LOCK(SHA1('{$test_lock}'))" );
		$is_free = $this->pdo->query( "SELECT IS_FREE_LOCK(SHA1('{$test_lock}'))" );

		$this->assertEquals( '1', $is_free->fetchColumn() );
	}

	/**
	 * It should not allow acquiring lock two times in same session
	 *
	 * @test
	 */
	public function should_not_allow_acquiring_lock_two_times_in_same_session() {
		$test_lock = uniqid( 'lock', true );
		$acquired  = $this->acquire_db_lock( $test_lock );

		$this->assertTrue( $acquired );
		$this->assertFalse( $this->acquire_db_lock( $test_lock ) );
		$this->assertFalse( $this->acquire_db_lock( $test_lock ) );
		$this->assertFalse( $this->acquire_db_lock( $test_lock ) );
	}
}
