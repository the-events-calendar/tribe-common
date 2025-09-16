<?php

namespace TEC\Common\Admin;

use Codeception\TestCase\WPTestCase;
use RuntimeException;

class Custom_List_Table_Test extends WPTestCase {
	private static $back_up;

	/**
	 * @before
	 */
	public function prepare() {
		global $wp_actions;

		self::$back_up = $wp_actions;

		$wp_actions = [];
	}

	/**
	 * @after
	 */
	public function clean() {
		global $wp_actions;

		$wp_actions = self::$back_up;
	}

	/**
	 * @test
	 */
	public function it_should_throw_exception_if_prepared_later_than_headers() {
		// We fire the action that fires just before headers are sent.
		set_current_screen( 'edit' );

		$this->expectException( RuntimeException::class );

		$table = new class extends Abstract_Custom_List_Table {
			protected const SINGULAR = 'singular';
			protected const PLURAL   = 'plural';
			protected const TABLE_ID = 'table_id';

			protected function get_total_items(): int {
				return 0;
			}

			protected function get_items( int $per_page ): array {
				return [];
			}
		};

		$table->prepare_items();
	}

	/**
	 * @test
	 */
	public function it_should_not_throw_exception_if_prepared_earlier_than_headers() {
		$called = 0;

		add_action( 'current_screen', function () use ( &$called ) {
			$table = new class extends Abstract_Custom_List_Table {
				protected const SINGULAR = 'singular';
				protected const PLURAL   = 'plural';
				protected const TABLE_ID = 'table_id';

				protected function get_total_items(): int {
					return 0;
				}

				protected function get_items( int $per_page ): array {
					return [];
				}
			};

			$table->prepare_items();
			$called++;
		} );

		$this->assertEquals( 0, $called );
		set_current_screen( 'edit' );
		$this->assertEquals( 1, $called );
	}
}
