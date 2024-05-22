<?php

namespace Tribe;

class DB_LockTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * It should correctly prune when there are no stale locks
	 *
	 * @test
	 */
	public function should_correctly_prune_when_there_are_no_stale_locks() {
		global $wpdb;

		$not_stale = DB_Lock::$db_lock_option_prefix . '_lock_1';
		$wpdb->insert(
			$wpdb->options,
			[ 'option_name' => $not_stale, 'option_value' => microtime( true ), 'autoload' => 'no' ],
			[ '%s', '%s', '%s' ]
		);

		$pruned = DB_Lock::prune_stale_db_locks();

		$this->assertEquals( 0, $pruned );
		$prefix = DB_Lock::$db_lock_option_prefix;
		$this->assertEquals( [ $not_stale ], $wpdb->get_col(
			"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'"
		) );
	}

	/**
	 * It should correctly prune stale db locks
	 *
	 * @test
	 */
	public function should_correctly_prune_stale_db_locks() {
		global $wpdb;

		$not_stale = DB_Lock::$db_lock_option_prefix . '_lock_1';
		$wpdb->insert(
			$wpdb->options,
			[ 'option_name' => $not_stale, 'option_value' => microtime( true ), 'autoload' => 'no' ],
			[ '%s', '%s', '%s' ]
		);

		$stale_1 = DB_Lock::$db_lock_option_prefix . '_lock_2';
		$wpdb->insert(
			$wpdb->options,
			[ 'option_name' => $stale_1, 'option_value' => strtotime( '-2 days' ), 'autoload' => 'no' ],
			[ '%s', '%s', '%s' ]
		);

		$stale_2 = DB_Lock::$db_lock_option_prefix . '_lock_3';
		$wpdb->insert(
			$wpdb->options,
			[ 'option_name' => $stale_2, 'option_value' => strtotime( '-1 day -1 second' ), 'autoload' => 'no' ],
			[ '%s', '%s', '%s' ]
		);

		$stale_3 = DB_Lock::$db_lock_option_prefix . '_lock_4';
		$wpdb->insert(
			$wpdb->options,
			[ 'option_name' => $stale_3, 'option_value' => 'foo bar', 'autoload' => 'no' ],
			[ '%s', '%s', '%s' ]
		);

		$pruned = DB_Lock::prune_stale_db_locks();

		$this->assertEquals( 3, $pruned );
		$prefix = DB_Lock::$db_lock_option_prefix;
		$this->assertEquals( [ $not_stale ], $wpdb->get_col(
			"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'"
		) );
	}
}
