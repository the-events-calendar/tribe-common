<?php

namespace TEC\Common\Key_Value_Cache;

use TEC\Common\Key_Value_Cache\Table\Schema;
use TEC\Common\Tests\Provider\Controller_Test_Case;
use Tribe\Tests\Traits\With_Uopz;

class Controller_Test extends Controller_Test_Case {
	use With_Uopz;

	protected $controller_class = Controller::class;

	/**
	 * @before
	 */
	public function unschedule_action(): void {
		wp_unschedule_event( time(), Controller::CLEAR_EXPIRED_ACTION );
	}

	/**
	 * @covers \TEC\Common\Key_Value_Cache\Controller::register
	 */
	public function test_uses_object_cache_if_available(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', true );

		$controller = $this->make_controller();
		$controller->register();

		$this->assertInstanceOf( Object_Cache::class, tec_kv_cache() );
	}

	/**
	 * @covers \TEC\Common\Key_Value_Cache\Controller::register
	 */
	public function test_uses_table_cache_if_not_available(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', false );

		$controller = $this->make_controller();
		$controller->register();

		$this->assertInstanceOf( Key_Value_Cache_Table::class, tec_kv_cache() );
		$this->assertEquals(
			10,
			has_action( Controller::CLEAR_EXPIRED_ACTION, [ $controller, 'clear_expired' ] )
		);
	}

	public function test_uses_table_cache_if_forced_to_by_filter(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', true );
		add_filter( 'tec_key_value_cache_force_use_of_table_cache', '__return_true' );

		$controller = $this->make_controller();
		$controller->register();

		$this->assertInstanceOf( Key_Value_Cache_Table::class, tec_kv_cache() );
		$this->assertEquals(
			10,
			has_action( Controller::CLEAR_EXPIRED_ACTION, [ $controller, 'clear_expired' ] )
		);
	}

	/**
	 * @covers \TEC\Common\Key_Value_Cache\Controller::clear_expired
	 */
	public function test_clear_expired(): void {
		global $wpdb;
		$this->set_fn_return( 'wp_using_ext_object_cache', false );

		$controller = $this->make_controller();
		$controller->register();

		// Set up an expired key.
		$wpdb->insert(
			Schema::table_name(),
			[
				'cache_key'  => 'expired',
				'value'      => 'expired_value',
				'expiration' => time() - 1000,
			]
		);
		// Set up a non-expired key.
		$wpdb->insert(
			Schema::table_name(),
			[
				'cache_key'  => 'not_expired',
				'value'      => 'not_expired_value',
				'expiration' => time() + 1000,
			]
		);

		// Pre-clear checks.
		$this->assertEquals( '', tec_kv_cache()->get( 'expired' ) );
		$this->assertEquals( 'not_expired_value', tec_kv_cache()->get( 'not_expired' ) );
		$this->assertEquals(
			[
				'expired',
				'not_expired',
			],
			$wpdb->get_col( "SELECT cache_key FROM {$wpdb->prefix}tec_kv_cache ORDER BY cache_key" )
		);

		$controller->clear_expired();

		$this->assertEquals( '', tec_kv_cache()->get( 'expired' ) );
		$this->assertEquals( 'not_expired_value', tec_kv_cache()->get( 'not_expired' ) );
		$this->assertEquals(
			[ 'not_expired' ],
			$wpdb->get_col( "SELECT cache_key FROM {$wpdb->prefix}tec_kv_cache ORDER BY cache_key" )
		);
	}

	/**
	 * @covers \TEC\Common\Key_Value_Cache\Controller::do_register
	 * @covers \TEC\Common\Key_Value_Cache\Controller::unregister
	 */
	public function test_schedules_unschedules_correctly_with_action_scheduler(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$as_has_scheduled_action = false;
		if ( ! function_exists( 'as_has_scheduled_action' ) ) {
			$this->set_fn_return(
				'as_has_scheduled_action',
				function () use ( &$as_has_scheduled_action ) {
					return $as_has_scheduled_action;
				},
				true
			);
		}
		if ( ! function_exists( 'as_schedule_single_action' ) ) {
			$this->set_fn_return(
				'as_schedule_single_action',
				function () use ( &$as_has_scheduled_action ) {
					$as_has_scheduled_action = true;
				},
				true
			);
		}
		if ( ! function_exists( 'as_unschedule_action' ) ) {
			$this->set_fn_return(
				'as_unschedule_action',
				function () use ( &$as_has_scheduled_action ) {
					$as_has_scheduled_action = false;
				},
				true
			);
		}
		// Remove anything hooked to init to avoid side-effects.
		remove_all_filters( 'init' );

		$controller = $this->make_controller();
		$controller->register();

		do_action( 'init' );

		// Sanity check.
		$this->assertTrue( as_has_scheduled_action( Controller::CLEAR_EXPIRED_ACTION ) );
		$this->assertFalse( wp_next_scheduled( Controller::CLEAR_EXPIRED_ACTION ) );

		$controller->unregister();

		$this->assertFalse( as_has_scheduled_action( Controller::CLEAR_EXPIRED_ACTION ) );
		$this->assertFalse( wp_next_scheduled( Controller::CLEAR_EXPIRED_ACTION ) );
	}

	/**
	 * @covers \TEC\Common\Key_Value_Cache\Controller::do_register
	 * @covers \TEC\Common\Key_Value_Cache\Controller::unregister
	 */
	public function test_schedules_unschedules_correctly_with_wp_cron(): void {
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$this->set_fn_return(
			'function_exists',
			function ( string $fn ): bool {
				if ( str_starts_with( $fn, 'as_' ) ) {
					return false;
				}

				return function_exists( $fn );
			},
			true
		);
		// Remove anything hooked to init to avoid side-effects.
		remove_all_filters( 'init' );

		$controller = $this->make_controller();
		$controller->register();

		do_action( 'init' );

		// Sanity check.
		$this->assertNotFalse( wp_next_scheduled( Controller::CLEAR_EXPIRED_ACTION ) );

		$controller->unregister();

		$this->assertFalse( wp_next_scheduled( Controller::CLEAR_EXPIRED_ACTION ) );
	}
}
