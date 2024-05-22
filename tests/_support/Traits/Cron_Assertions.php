<?php
/**
 * Provides assertion methods related to the Cron.
 *
 * @package Tribe\Tests\Traits
 */

namespace
Tribe\Tests\Traits;

/**
 * Trait Cron_Assertions
 *
 * This trait should be used by a PHPUnit test case.
 *
 * @package Tribe\Tests\Traits
 */
trait Cron_Assertions {

	/**
	 * Asserts that an hook is scheduled in the cron.
	 *
	 * @param string $hook The hook name.
	 */
	protected function assert_cron_event_exists( $hook ) {
		$this->assertContains( $hook, $this->get_cron_hooks( ) );
	}

	/**
	 * Asserts that an hook is not scheduled in the cron.
	 *
	 * @since 4.7.23
	 *
	 * @param $hook
	 */
	protected function assert_cron_event_not_exists( $hook ) {
		$this->assertNotContains( $hook, $this->get_cron_hooks() );
	}

	/**
	 * Returns the hook names of all hooks currently scheduled in the cron.
	 *
	 * @return array An array of currently scheduled cron hooks.
	 */
	protected function get_cron_hooks( ) {
		$cron  = get_option( 'cron', [] );
		$hooks = array_unique(
			array_merge(
				...array_filter(
					array_map(
						'array_keys',
						array_filter(
							array_values( $cron ), '\is_array' )
					)
				)
			)
		);

		return $hooks;
	}
}