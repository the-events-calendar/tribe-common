<?php

namespace TEC\Common\Integrations;


use Codeception\TestCase\WPTestCase;
use TEC\Common\StellarWP\Schema\Register;
use TEC\Common\Stellar\DB\DB;

class Custom_Table_Test extends WPTestCase {
	/**
	 * @var Custom_Table_Abstract
	 */
	private $table;

	/**
	 * @before
	 */
	public function prepare() {
		$this->table = new class extends Custom_Table_Abstract {
			const SCHEMA_VERSION = '0.0.1-dev';

			protected static $base_table_name = 'tec_tests';
			protected static $group = 'tec_tests_group';

			protected static $schema_slug = 'tec-tests';

			protected static $uid_column = 'id';

			public static function get_columns(): array {
				return [
					static::$uid_column,
					'post_id',
					'bool',
					'string',
				];
			}
			protected function get_definition() {
				global $wpdb;
				$table_name      = self::table_name( true );
				$charset_collate = $wpdb->get_charset_collate();
				$uid_column      = self::uid_column();

				return "
					CREATE TABLE `{$table_name}` (
						`{$uid_column}` bigint(20) NOT NULL AUTO_INCREMENT,
						`post_id` bigint(20) NOT NULL,
						`bool` boolean NOT NULL DEFAULT 0,
						`string` varchar(255) NULL,
						PRIMARY KEY (`{$uid_column}`)
					) {$charset_collate};
				";
			}

			protected function after_update( array $results ) {
				return $this->check_and_add_index( $results, 'post_id', 'post_id' );
			}
		};

		Register::table( get_class( $this->table ) );

		$entries = [];

		for ( $i = 0; $i < 47; $i++ ) {
			$entries[] = [
				'post_id' => $i % 10,
				'bool'    => $i % 2 === 0,
				'string'  => 'string ' . ( $i % 4 ),
			];
		}

		$this->assertCount( 47, $entries );

		$this->table::insert_many( $entries );
	}

	/**
	 * @test
	 */
	public function it_should_return_expected_results() {
		$this->assertEquals( 47, $this->table::get_total_items() );

		$first_page_results = $this->table::paginate( [], 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results );
		$second_page_results = $this->table::paginate( [], 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results );
		$third_page_results = $this->table::paginate( [], 10, 3, ARRAY_A );
		$this->assertCount( 10, $third_page_results );
		$fourth_page_results = $this->table::paginate( [], 10, 4, ARRAY_A );
		$this->assertCount( 10, $fourth_page_results );
		$fifth_page_results = $this->table::paginate( [], 10, 5, ARRAY_A );
		$this->assertCount( 7, $fifth_page_results );

		$this->assertNotSame( $first_page_results, $second_page_results );
		$this->assertNotSame( $first_page_results, $third_page_results );
		$this->assertNotSame( $first_page_results, $fourth_page_results );
		$this->assertNotSame( $second_page_results, $third_page_results );
		$this->assertNotSame( $second_page_results, $fourth_page_results );
		$this->assertNotSame( $third_page_results, $fourth_page_results );
		$this->assertNotSame( $fourth_page_results, $fifth_page_results );

		// Failed to be ordered because of not known column.
		$first_page_results_unordered = $this->table::paginate( [ 'orderby' => 'unknown' ], 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results_unordered );
		$second_page_results_unordered = $this->table::paginate( [ 'orderby' => 'unknown' ], 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results_unordered );
		$third_page_results_unordered = $this->table::paginate( [ 'orderby' => 'unknown' ], 10, 3, ARRAY_A );
		$this->assertCount( 10, $third_page_results_unordered );
		$fourth_page_results_unordered = $this->table::paginate( [ 'orderby' => 'unknown' ], 10, 4, ARRAY_A );
		$this->assertCount( 10, $fourth_page_results_unordered );
		$fifth_page_results_unordered = $this->table::paginate( [ 'orderby' => 'unknown' ], 10, 5, ARRAY_A );
		$this->assertCount( 7, $fifth_page_results_unordered );

		$this->assertSame( $first_page_results, $first_page_results_unordered );
		$this->assertSame( $second_page_results, $second_page_results_unordered );
		$this->assertSame( $third_page_results, $third_page_results_unordered );
		$this->assertSame( $fourth_page_results, $fourth_page_results_unordered );
		$this->assertSame( $fifth_page_results, $fifth_page_results_unordered );

		// THose will be ordered since the string column is known.
		$first_page_results_ordered = $this->table::paginate( [ 'orderby' => 'string' ], 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results_ordered );
		$second_page_results_ordered = $this->table::paginate( [ 'orderby' => 'string' ], 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results_ordered );
		$third_page_results_ordered = $this->table::paginate( [ 'orderby' => 'string' ], 10, 3, ARRAY_A );
		$this->assertCount( 10, $third_page_results_ordered );
		$fourth_page_results_ordered = $this->table::paginate( [ 'orderby' => 'string' ], 10, 4, ARRAY_A );
		$this->assertCount( 10, $fourth_page_results_ordered );
		$fifth_page_results_ordered = $this->table::paginate( [ 'orderby' => 'string' ], 10, 5, ARRAY_A );
		$this->assertCount( 7, $fifth_page_results_ordered );

		$this->assertNotSame( $first_page_results, $first_page_results_ordered );
		$this->assertNotSame( $second_page_results, $second_page_results_ordered );
		$this->assertNotSame( $third_page_results, $third_page_results_ordered );
		$this->assertNotSame( $fourth_page_results, $fourth_page_results_ordered );
		$this->assertNotSame( $fifth_page_results, $fifth_page_results_ordered );

		// THose will be ordered since the string column is known.
		$first_page_results_ordered_desc = $this->table::paginate( [ 'orderby' => 'string', 'order' => 'DESC' ], 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results_ordered_desc );
		$second_page_results_ordered_desc = $this->table::paginate( [ 'orderby' => 'string', 'order' => 'DESC' ], 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results_ordered_desc );
		$third_page_results_ordered_desc = $this->table::paginate( [ 'orderby' => 'string', 'order' => 'DESC' ], 10, 3, ARRAY_A );
		$this->assertCount( 10, $third_page_results_ordered_desc );
		$fourth_page_results_ordered_desc = $this->table::paginate( [ 'orderby' => 'string', 'order' => 'DESC' ], 10, 4, ARRAY_A );
		$this->assertCount( 10, $fourth_page_results_ordered_desc );
		$fifth_page_results_ordered_desc = $this->table::paginate( [ 'orderby' => 'string', 'order' => 'DESC' ], 10, 5, ARRAY_A );
		$this->assertCount( 7, $fifth_page_results_ordered_desc );

		$this->assertNotSame( $first_page_results_ordered, $first_page_results_ordered_desc );
		$this->assertNotSame( $second_page_results_ordered, $second_page_results_ordered_desc );
		$this->assertNotSame( $third_page_results_ordered, $third_page_results_ordered_desc );
		$this->assertNotSame( $fourth_page_results_ordered, $fourth_page_results_ordered_desc );
		$this->assertNotSame( $fifth_page_results_ordered, $fifth_page_results_ordered_desc );
	}

	/**
	 * @test
	 */
	public function it_should_return_expected_results_using_search_criteria() {
		$search = [
			[
				'column' => 'bool',
				'value'  => 1,
			],
			[
				'column'   => 'post_id',
				'value'    => 7,
				'operator' => '<=',
			],
		];

		// What are we asking ?
		// 1) bool to be 1. Thats 24 items.
		// 2) post_id to be less than or equal to 7. For every 10 entries only 2 are not less than or equal to 7. So that means that 8 are less than or equal to 7. 4 * 8 = 32 + 7 = 39.
		// 3) How many of them are both ? 20
		// 4) How many are one or the other ? 43
		// Math done! Lets roll :D

		$this->assertEquals( 20, $this->table::get_total_items( $search ) );

		$first_page_results = $this->table::paginate( $search, 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results );
		$second_page_results = $this->table::paginate( $search, 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results );
		$third_page_results = $this->table::paginate( $search, 10, 3, ARRAY_A );
		$this->assertCount( 0, $third_page_results );

		$this->assertNotSame( $first_page_results, $second_page_results );
		$this->assertNotSame( $first_page_results, $third_page_results );
		$this->assertNotSame( $second_page_results, $third_page_results );

		$search['query_operator'] = 'OR';
		$this->assertEquals( 43, $this->table::get_total_items( $search ) );

		$first_page_results = $this->table::paginate( $search, 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results );
		$second_page_results = $this->table::paginate( $search, 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results );
		$third_page_results = $this->table::paginate( $search, 10, 3, ARRAY_A );
		$this->assertCount( 10, $third_page_results );
		$fourth_page_results = $this->table::paginate( $search, 10, 4, ARRAY_A );
		$this->assertCount( 10, $fourth_page_results );
		$fifth_page_results = $this->table::paginate( $search, 10, 5, ARRAY_A );
		$this->assertCount( 3, $fifth_page_results );

		$this->assertNotSame( $first_page_results, $second_page_results );
		$this->assertNotSame( $first_page_results, $third_page_results );
		$this->assertNotSame( $first_page_results, $fourth_page_results );
		$this->assertNotSame( $second_page_results, $third_page_results );
		$this->assertNotSame( $third_page_results, $fourth_page_results );
		$this->assertNotSame( $fourth_page_results, $fifth_page_results );

		unset( $search['query_operator'] );

		// If we dont set a value we expect to do a where column != ''. thats true for all the string column. so we should get the same results as the first time.
		$search[] = [
			'column' => 'string',
		];

		$this->assertEquals( 20, $this->table::get_total_items( $search ) );

		$first_page_results = $this->table::paginate( $search, 10, 1, ARRAY_A );
		$this->assertCount( 10, $first_page_results );
		$second_page_results = $this->table::paginate( $search, 10, 2, ARRAY_A );
		$this->assertCount( 10, $second_page_results );
		$third_page_results = $this->table::paginate( $search, 10, 3, ARRAY_A );
		$this->assertCount( 0, $third_page_results );

		$this->assertNotSame( $first_page_results, $second_page_results );
		$this->assertNotSame( $first_page_results, $third_page_results );
		$this->assertNotSame( $second_page_results, $third_page_results );
	}

	/**
	 * @after
	 */
	public function clean_up () {
		$this->table->drop();
	}
}
