<?php

namespace Tribe\Tests\Testcases\Common\Abstracts;

use lucatume\WPBrowser\TestCase\WPTestCase;
use TEC\Common\Contracts\Model as Model_Interface;
use TEC\Common\StellarWP\Schema\Register;
use DateTime;
use Exception;
use TEC\Common\StellarWP\DB\DB;
use TEC\Common\Abstracts\Custom_Table_Abstract;
use TEC\Common\Abstracts\Custom_Table_Repository;

abstract class Abstract_Custom_Table_Repository_Testcase extends WPTestCase {
	/**
	 * The test model class.
	 *
	 * @var string
	 */
	protected string $test_model_class;

	/**
	 * The test repository class.
	 *
	 * @var string
	 */
	protected string $test_repository_class;

	/**
	 * The test table class.
	 *
	 * @var string
	 */
	protected string $test_table_class;

	/**
	 * @before
	 */
	public function prepare(): void {
		Register::table( $this->test_table_class );
	}

	/**
	 * @after
	 */
	public function reset(): void {
		Register::remove_table( tribe( $this->test_table_class ) );
	}

	/**
	 * Get a test repository instance.
	 *
	 * @return Custom_Table_Repository
	 */
	protected function get_repository() {
		return tribe( $this->test_repository_class );
	}

	/**
	 * Get the create data.
	 *
	 * @return array
	 */
	protected function get_create_data() {
		$columns = $this->test_table_class::get_columns();

		$create_data = [];

		foreach ( $columns as $column => $column_data ) {
			if ( $column === $this->test_table_class::uid_column() ) {
				continue;
			}

			if ( ! empty( $column_data['default'] ) ) {
				$create_data[ $column ] = $column_data['default'];
			}

			if ( ! empty( $column_data['nullable'] ) ) {
				continue;
			}

			switch ( $column_data['php_type'] ) {
				case Custom_Table_Abstract::PHP_TYPE_INT:
					$create_data[ $column ] = 7;
					break;
				case Custom_Table_Abstract::PHP_TYPE_STRING:
					$create_data[ $column ] = 'test String';
					break;
				case Custom_Table_Abstract::PHP_TYPE_FLOAT:
					$create_data[ $column ] = 7.0;
					break;
				case Custom_Table_Abstract::PHP_TYPE_BOOL:
					$create_data[ $column ] = true;
					break;
				case Custom_Table_Abstract::PHP_TYPE_DATETIME:
					$create_data[ $column ] = new DateTime( '2024-06-13 17:26:00' );
					break;
				default:
					throw new Exception( 'Invalid PHP type: ' . $column_data['php_type'] );
			}
		}

		return $create_data;
	}

	/**
	 * Get n create data.
	 *
	 * @param int $n The number of create data to get.
	 * @return array The create data.
	 */
	protected function get_n_create_data( int $n ) {
		$create_data = $this->get_create_data();

		$create_data_array = [];
		for ( $i = 0; $i < $n; $i++ ) {
			foreach ( $create_data as $key => $value ) {
				if ( is_numeric( $value ) ) {
					$create_data_array[ $i ][ $key ] = $value + $i;
				} elseif ( is_string( $value ) ) {
					$create_data_array[ $i ][ $key ] = $value . ' ' . $i;
				} elseif ( is_bool( $value ) ) {
					$create_data_array[ $i ][ $key ] = wp_rand( 0, 1 ) === 1;
				} elseif ( $value instanceof DateTime ) {
					$create_data_array[ $i ][ $key ] = $value->modify( '+' . $i . ' days' );
				} else {
					throw new Exception( 'Invalid value type: ' . gettype( $value ) );
				}
			}
		}

		return $create_data_array;
	}

	/**
	 * Insert test data into the table.
	 *
	 * @param array $data
	 * @return int Insert ID
	 */
	protected function insert_test_data( array $data ) {
		$this->test_table_class::insert( $data );
		return DB::last_insert_id();
	}

	public function test_repository_instantiation() {
		$repo = $this->get_repository();
		$this->assertInstanceOf( Custom_Table_Repository::class, $repo );
	}

	public function test_create_model() {
		$create_data = $this->get_create_data();

		$model = $this->get_repository()
			->set_args( $create_data )
			->create();

		$this->assertNotNull( $model );
		$this->assertInstanceOf( Model_Interface::class, $model );
		$this->assertInstanceOf( $this->test_model_class, $model );
		$this->assertNotNull( $model->get_id() );
		$this->assertIsInt( $model->get_id() );
		foreach ( $create_data as $key => $value ) {
			$method = 'get_' . $key;
			$this->assertEquals( $value, $model->$method() );
		}
	}

	public function test_find_by_id() {
		$create_data = $this->get_create_data();
		// Insert test data
		$id = $this->insert_test_data( $create_data );

		$repo = $this->get_repository();
		$model = $repo->by( 'id', $id )->first();

		$this->assertNotNull( $model );
		$this->assertInstanceOf( Model_Interface::class, $model );
		$this->assertEquals( $id, $model->get_id() );
		foreach ( $create_data as $key => $value ) {
			$method = 'get_' . $key;
			$this->assertEquals( $value, $model->$method() );
		}
	}

	public function test_find_multiple() {
		$create_data_array = $this->get_n_create_data( 5 );
		$ids = [];
		foreach ( $create_data_array as $data ) {
			$ids[] = $this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$models = $repo->all();

		$this->assertIsArray( $models );
		$this->assertCount( 5, $models );

		$random_key = array_rand( $create_data_array['0'] );
		$value = $create_data_array['0'][ $random_key ];
		$count_of_same_value = count( array_filter( $create_data_array, function( $data ) use ( $random_key, $value ) {
			return $data[ $random_key ] === $value;
		} ) );

		// Test filtering by status
		$queried_models = $repo->by( $random_key, $value )->all();
		$this->assertCount( $count_of_same_value, $queried_models, 'Count of same value is not correct for column ' . $random_key );
		$this->assertInstanceOf( Model_Interface::class, $queried_models[0] );
		$this->assertInstanceOf( $this->test_model_class, $queried_models[0] );

		$not_queried_models = $repo->by( $random_key . '_neq', $value )->all();
		$this->assertCount( 5 - $count_of_same_value, $not_queried_models );
		if ( 5 - $count_of_same_value > 0 ) {
			$this->assertInstanceOf( Model_Interface::class, $not_queried_models[0] );
			$this->assertInstanceOf( $this->test_model_class, $not_queried_models[0] );
		}
	}

	public function test_update_model() {
		$create_data = $this->get_create_data();
		$id = $this->insert_test_data( $create_data );

		$repo = $this->get_repository();

		// Create update data dynamically based on column types
		$update_data = [];
		foreach ( $create_data as $key => $value ) {
			if ( is_numeric( $value ) ) {
				$update_data[ $key ] = $value + 100;
			} elseif ( is_string( $value ) ) {
				$update_data[ $key ] = 'Updated ' . $value;
			} elseif ( is_bool( $value ) ) {
				$update_data[ $key ] = ! $value;
			} elseif ( $value instanceof DateTime ) {
				$update_data[ $key ] = clone $value;
				$update_data[ $key ]->modify( '+1 year' );
			}
		}

		// Update the model
		$updated_models = $repo
			->by( 'id', $id )
			->set_args( $update_data )
			->save();

		$this->assertIsArray( $updated_models );
		$this->assertCount( 1, $updated_models );
		$this->assertArrayHasKey( $id, $updated_models );
		$this->assertNotEmpty( $updated_models[ $id ] );

		// Verify the update
		$model = $repo->by( 'id', $id )->first();
		foreach ( $update_data as $key => $expected_value ) {
			$method = 'get_' . $key;
			$this->assertEquals( $expected_value, $model->$method() );
		}
	}

	public function test_delete_models() {
		$create_data_array = $this->get_n_create_data( 3 );
		$ids = [];
		foreach ( $create_data_array as $data ) {
			$ids[] = $this->insert_test_data( $data );
		}

		$repo = $this->get_repository();

		// Delete specific model
		$deleted = $repo->by( 'id', $ids[0] )->delete();
		$this->assertArrayHasKey( $ids[0], $deleted );
		$this->assertTrue( $deleted[ $ids[0] ] );

		// Verify deletion
		$model = $repo->by( 'id', $ids[0] )->first();
		$this->assertNull( $model );

		// Verify others still exist
		$remaining = $repo->all();
		$this->assertCount( 2, $remaining );
		$remaining_ids = array_map( function( $m ) { return $m->get_id(); }, $remaining );
		$this->assertContains( $ids[1], $remaining_ids );
		$this->assertContains( $ids[2], $remaining_ids );
		$this->assertNotContains( $ids[0], $remaining_ids );
	}

	public function test_pagination() {
		$create_data_array = $this->get_n_create_data( 10 );
		$ids = [];
		foreach ( $create_data_array as $data ) {
			$ids[] = $this->insert_test_data( $data );
		}

		$repo = $this->get_repository();

		// Test per_page
		$page1 = $repo->page( 1 )->per_page( 3 )->all();
		$this->assertCount( 3, $page1 );
		foreach ( $page1 as $model ) {
			$this->assertInstanceOf( Model_Interface::class, $model );
			$this->assertInstanceOf( $this->test_model_class, $model );
		}

		// Test offset
		$page2 = $repo->page( 2 )->per_page( 3 )->all();
		$this->assertCount( 3, $page2 );

		// Verify different items
		$page1_ids = array_map( function( $m ) { return $m->get_id(); }, $page1 );
		$page2_ids = array_map( function( $m ) { return $m->get_id(); }, $page2 );
		$this->assertEmpty( array_intersect( $page1_ids, $page2_ids ) );

		// Test last page
		$page4 = $repo->page( 4 )->per_page( 3 )->all();
		$this->assertCount( 1, $page4 ); // Only 1 item left
	}

	public function test_pagination_with_offset() {
		$create_data_array = $this->get_n_create_data( 10 );
		$ids = [];
		foreach ( $create_data_array as $data ) {
			$ids[] = $this->insert_test_data( $data );
		}

		$repo = $this->get_repository();

		// Test per_page
		$page1 = $repo->per_page( 3 )->all();
		$this->assertCount( 3, $page1 );
		foreach ( $page1 as $model ) {
			$this->assertInstanceOf( Model_Interface::class, $model );
			$this->assertInstanceOf( $this->test_model_class, $model );
		}

		// Test offset
		$page2 = $repo->per_page( 3 )->offset( 3 )->all();
		$this->assertCount( 3, $page2 );

		// Verify different items
		$page1_ids = array_map( function( $m ) { return $m->get_id(); }, $page1 );
		$page2_ids = array_map( function( $m ) { return $m->get_id(); }, $page2 );
		$this->assertEmpty( array_intersect( $page1_ids, $page2_ids ) );

		// Test last page
		$page4 = $repo->per_page( 3 )->offset( 9 )->all();
		$this->assertCount( 1, $page4 ); // Only 1 item left
	}

	public function test_ordering_numeric() {
		// Get a numeric column to test ordering
		$columns = $this->test_table_class::get_columns();
		$numeric_column = null;
		foreach ( $columns as $column => $data ) {
			if ( $column !== $this->test_table_class::uid_column() && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_INT ) {
				$numeric_column = $column;
				break;
			}
		}

		if ( ! $numeric_column ) {
			$this->markTestSkipped( 'No numeric column available for ordering test' );
		}

		// Insert test data with different values
		$values = [ 30, 10, 20, 40 ];
		foreach ( $values as $value ) {
			$data = $this->get_create_data();
			$data[ $numeric_column ] = $value;
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$getter = 'get_' . $numeric_column;

		// Test ordering ascending
		$asc_models = $repo->order_by( $numeric_column, 'ASC' )->all();
		$this->assertCount( 4, $asc_models );
		$asc_values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $asc_models );
		$this->assertEquals( [ 10, 20, 30, 40 ], $asc_values );

		// Test ordering descending
		$desc_models = $repo->order_by( $numeric_column )->all();
		$this->assertCount( 4, $desc_models );
		$desc_values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $desc_models );
		$this->assertEquals( [ 40, 30, 20, 10 ], $desc_values );
	}

	public function test_ordering_string() {
		// Get a string column to test ordering
		$columns = $this->test_table_class::get_columns();
		$string_column = null;
		foreach ( $columns as $column => $data ) {
			if ( $column !== $this->test_table_class::uid_column() && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_STRING ) {
				$string_column = $column;
				break;
			}
		}

		if ( ! $string_column ) {
			$this->markTestSkipped( 'No string column available for ordering test' );
		}

		// Insert test data with different string values
		$values = [ 'Charlie', 'Alice', 'Bob', 'David' ];
		foreach ( $values as $value ) {
			$data = $this->get_create_data();
			$data[ $string_column ] = $value;
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$getter = 'get_' . $string_column;

		// Test ordering ascending (alphabetical)
		$asc_models = $repo->order_by( $string_column, 'ASC' )->all();
		$this->assertCount( 4, $asc_models );
		$asc_values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $asc_models );
		$this->assertEquals( [ 'Alice', 'Bob', 'Charlie', 'David' ], $asc_values );

		// Test ordering descending (reverse alphabetical)
		$desc_models = $repo->order_by( $string_column, 'DESC' )->all();
		$this->assertCount( 4, $desc_models );
		$desc_values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $desc_models );
		$this->assertEquals( [ 'David', 'Charlie', 'Bob', 'Alice' ], $desc_values );
	}

	public function test_ordering_datetime() {
		// Get a datetime column to test ordering
		$columns = $this->test_table_class::get_columns();
		$datetime_column = null;
		foreach ( $columns as $column => $data ) {
			if ( $column !== $this->test_table_class::uid_column() && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_DATETIME ) {
				$datetime_column = $column;
				break;
			}
		}

		if ( ! $datetime_column ) {
			$this->markTestSkipped( 'No datetime column available for ordering test' );
		}

		// Insert test data with different datetime values
		$base_date = new DateTime( '2024-01-01 00:00:00' );
		$dates = [];
		$offsets = [ '+3 days', '+1 day', '+2 days', '+4 days' ];
		foreach ( $offsets as $offset ) {
			$date = clone $base_date;
			$date->modify( $offset );
			$dates[] = $date;
			$data = $this->get_create_data();
			$data[ $datetime_column ] = $date;
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$getter = 'get_' . $datetime_column;

		// Test ordering ascending (oldest first)
		$asc_models = $repo->order_by( $datetime_column, 'ASC' )->all();
		$this->assertCount( 4, $asc_models );
		$asc_dates = array_map( function( $m ) use ( $getter ) {
			return $m->$getter()->format( 'Y-m-d' );
		}, $asc_models );
		$this->assertEquals(
			[ '2024-01-02', '2024-01-03', '2024-01-04', '2024-01-05' ],
			$asc_dates
		);

		// Test ordering descending (newest first)
		$desc_models = $repo->order_by( $datetime_column, 'DESC' )->all();
		$this->assertCount( 4, $desc_models );
		$desc_dates = array_map( function( $m ) use ( $getter ) {
			return $m->$getter()->format( 'Y-m-d' );
		}, $desc_models );
		$this->assertEquals(
			[ '2024-01-05', '2024-01-04', '2024-01-03', '2024-01-02' ],
			$desc_dates
		);
	}

	public function test_count() {
		$create_data_array = $this->get_n_create_data( 7 );
		foreach ( $create_data_array as $data ) {
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();

		// Test total count
		$total = $repo->count();
		$this->assertEquals( 7, $total );

		// Test filtered count - pick a column and value from first item
		$filter_column = array_key_first( $create_data_array[0] );
		$filter_value = $create_data_array[0][ $filter_column ];
		$expected_count = count( array_filter( $create_data_array, function( $data ) use ( $filter_column, $filter_value ) {
			return $data[ $filter_column ] === $filter_value;
		} ) );

		$filtered_count = $repo->by( $filter_column, $filter_value )->count();
		$this->assertEquals( $expected_count, $filtered_count );
	}

	public function test_found() {
		$create_data_array = $this->get_n_create_data( 15 );
		foreach ( $create_data_array as $data ) {
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();

		// Get paginated results but check total found
		$page1 = $repo->per_page( 5 )->all();
		$found = $repo->found();

		$this->assertCount( 5, $page1 );
		$this->assertEquals( 15, $found );

		// Test with filter
		$filter_column = array_key_first( $create_data_array[0] );
		$filter_value = $create_data_array[0][ $filter_column ];
		$expected_found = count( array_filter( $create_data_array, function( $data ) use ( $filter_column, $filter_value ) {
			return $data[ $filter_column ] === $filter_value;
		} ) );

		$repo->by( $filter_column, $filter_value )->per_page( 2 )->all();
		$filtered_found = $repo->found();
		$this->assertEquals( $expected_found, $filtered_found );
	}

	public function test_get_ids() {
		$create_data_array = $this->get_n_create_data( 5 );
		$inserted_ids = [];
		foreach ( $create_data_array as $data ) {
			$inserted_ids[] = $this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$ids = $repo->get_ids();

		$this->assertIsArray( $ids );
		$this->assertCount( 5, $ids );

		foreach ( $ids as $id ) {
			$this->assertIsInt( $id );
			$this->assertContains( $id, $inserted_ids );
		}

		// Test with filter
		$filter_column = array_key_first( $create_data_array[0] );
		$filter_value = $create_data_array[0][ $filter_column ];
		$expected_ids = [];
		foreach ( $create_data_array as $i => $data ) {
			if ( $data[ $filter_column ] === $filter_value ) {
				$expected_ids[] = $inserted_ids[ $i ];
			}
		}

		$filtered_ids = $repo->by( $filter_column, $filter_value )->get_ids();
		sort( $expected_ids );
		sort( $filtered_ids );
		$this->assertEquals( $expected_ids, $filtered_ids );
	}

	public function test_by_primary_key() {
		$create_data = $this->get_create_data();
		$id = $this->insert_test_data( $create_data );

		$repo = $this->get_repository();
		$model = $repo->by_primary_key( $id );

		$this->assertNotNull( $model );
		$this->assertInstanceOf( Model_Interface::class, $model );
		$this->assertInstanceOf( $this->test_model_class, $model );
		$this->assertEquals( $id, $model->get_id() );

		// Verify all data matches
		foreach ( $create_data as $key => $expected_value ) {
			$method = 'get_' . $key;
			$this->assertEquals( $expected_value, $model->$method() );
		}

		// Test with non-existent ID
		$non_existent_model = $repo->by_primary_key( 999999 );
		$this->assertNull( $non_existent_model );
	}

	public function test_first_returns_null_when_no_results() {
		$repo = $this->get_repository();

		// Test with non-existent value
		$columns = $this->test_table_class::get_columns();
		foreach ( $columns as $column => $data ) {
			if ( $column === $this->test_table_class::uid_column() ) {
				continue;
			}

			// Create a non-existent value based on type
			$non_existent_value = null;
			switch ( $data['php_type'] ) {
				case Custom_Table_Abstract::PHP_TYPE_STRING:
					$non_existent_value = 'NonExistent_' . wp_generate_uuid4();
					break;
				case Custom_Table_Abstract::PHP_TYPE_INT:
					$non_existent_value = 999999999;
					break;
			}

			if ( $non_existent_value !== null ) {
				$model = $repo->by( $column, $non_existent_value )->first();
				$this->assertNull( $model, "Expected null for non-existent {$column} value" );
				break;
			}
		}
	}

	public function test_all_returns_empty_array_when_no_results() {
		$repo = $this->get_repository();

		// Test with non-existent value
		$columns = $this->test_table_class::get_columns();
		foreach ( $columns as $column => $data ) {
			if ( $column === $this->test_table_class::uid_column() ) {
				continue;
			}

			// Create a non-existent value based on type
			$non_existent_value = null;
			switch ( $data['php_type'] ) {
				case Custom_Table_Abstract::PHP_TYPE_STRING:
					$non_existent_value = 'NonExistent_' . wp_generate_uuid4();
					break;
				case Custom_Table_Abstract::PHP_TYPE_INT:
					$non_existent_value = 999999999;
					break;
			}

			if ( $non_existent_value !== null ) {
				$models = $repo->by( $column, $non_existent_value )->all();
				$this->assertIsArray( $models );
				$this->assertEmpty( $models, "Expected empty array for non-existent {$column} value" );
				break;
			}
		}
	}

	public function test_complex_filtering() {
		$create_data_array = $this->get_n_create_data( 10 );

		// Modify some data to create patterns for testing
		$columns = $this->test_table_class::get_columns();
		$string_columns = [];
		foreach ( $columns as $column => $data ) {
			if ( $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_STRING && $column !== $this->test_table_class::uid_column() ) {
				$string_columns[] = $column;
			}
		}

		if ( count( $string_columns ) >= 2 ) {
			// Set up data with specific patterns
			for ( $i = 0; $i < count( $create_data_array ); $i++ ) {
				$create_data_array[ $i ][ $string_columns[0] ] = $i < 5 ? 'GroupA' : 'GroupB';
				$create_data_array[ $i ][ $string_columns[1] ] = $i % 2 === 0 ? 'Even' : 'Odd';
			}

			foreach ( $create_data_array as $data ) {
				$this->insert_test_data( $data );
			}

			$repo = $this->get_repository();

			// Filter by multiple conditions
			$models = $repo
				->by( $string_columns[0], 'GroupA' )
				->by( $string_columns[1], 'Even' )
				->all();

			// GroupA (0-4) with Even (0, 2, 4) = 3 items
			$this->assertCount( 3, $models );
			$getter0 = 'get_' . $string_columns[0];
			$getter1 = 'get_' . $string_columns[1];
			foreach ( $models as $model ) {
				$this->assertEquals( 'GroupA', $model->$getter0() );
				$this->assertEquals( 'Even', $model->$getter1() );
			}
		} else {
			$this->markTestSkipped( 'Not enough string columns for complex filtering test' );
		}
	}

	public function test_operator_schema_entries() {
		// Find a numeric column for testing
		$columns = $this->test_table_class::get_columns();
		$numeric_column = null;
		$string_column = null;
		foreach ( $columns as $column => $data ) {
			if ( $column === $this->test_table_class::uid_column() ) {
				continue;
			}
			if ( ! $numeric_column && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_INT ) {
				$numeric_column = $column;
			}
			if ( ! $string_column && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_STRING ) {
				$string_column = $column;
			}
		}

		if ( ! $numeric_column ) {
			$this->markTestSkipped( 'No numeric column available for operator tests' );
		}

		// Create test data with specific numeric values
		$values = [ 10, 20, 30, 40, 50 ];
		$statuses = [ 'active', 'inactive', 'active', 'pending', 'active' ];
		for ( $i = 0; $i < 5; $i++ ) {
			$data = $this->get_create_data();
			$data[ $numeric_column ] = $values[ $i ];
			if ( $string_column ) {
				$data[ $string_column ] = $statuses[ $i ];
			}
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$getter = 'get_' . $numeric_column;

		// Test 'eq' operator (default when using just column name)
		$models = $repo->by( $numeric_column, 30 )->all();
		$this->assertCount( 1, $models );
		$this->assertEquals( 30, $models[0]->$getter() );

		// Test explicit 'eq' operator
		$models = $repo->by( $numeric_column . '_eq', 30 )->all();
		$this->assertCount( 1, $models );
		$this->assertEquals( 30, $models[0]->$getter() );

		// Test 'neq' operator
		if ( $string_column ) {
			$models = $repo->by( $string_column . '_neq', 'active' )->all();
			$this->assertCount( 2, $models );
			$string_getter = 'get_' . $string_column;
			foreach ( $models as $model ) {
				$this->assertNotEquals( 'active', $model->$string_getter() );
			}
		}

		// Test 'gt' operator
		$models = $repo->by( $numeric_column . '_gt', 30 )->all();
		$this->assertCount( 2, $models );
		foreach ( $models as $model ) {
			$this->assertGreaterThan( 30, $model->$getter() );
		}

		// Test 'lt' operator
		$models = $repo->by( $numeric_column . '_lt', 30 )->all();
		$this->assertCount( 2, $models );
		foreach ( $models as $model ) {
			$this->assertLessThan( 30, $model->$getter() );
		}

		// Test 'gte' operator
		$models = $repo->by( $numeric_column . '_gte', 30 )->all();
		$this->assertCount( 3, $models );
		foreach ( $models as $model ) {
			$this->assertGreaterThanOrEqual( 30, $model->$getter() );
		}

		// Test 'lte' operator
		$models = $repo->by( $numeric_column . '_lte', 30 )->all();
		$this->assertCount( 3, $models );
		foreach ( $models as $model ) {
			$this->assertLessThanOrEqual( 30, $model->$getter() );
		}
	}

	public function test_in_not_in_operators() {
		// Find numeric and string columns for testing
		$columns = $this->test_table_class::get_columns();
		$numeric_column = null;
		$string_column = null;
		foreach ( $columns as $column => $data ) {
			if ( $column === $this->test_table_class::uid_column() ) {
				continue;
			}
			if ( ! $numeric_column && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_INT ) {
				$numeric_column = $column;
			}
			if ( ! $string_column && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_STRING ) {
				$string_column = $column;
			}
		}

		if ( ! $numeric_column && ! $string_column ) {
			$this->markTestSkipped( 'No suitable columns for in/not_in operator tests' );
		}

		// Create test data
		$numeric_values = [ 10, 20, 30, 40, 50 ];
		$string_values = [ 'active', 'inactive', 'pending', 'active', 'completed' ];
		$inserted_data = [];
		for ( $i = 0; $i < 5; $i++ ) {
			$data = $this->get_create_data();
			if ( $numeric_column ) {
				$data[ $numeric_column ] = $numeric_values[ $i ];
			}
			if ( $string_column ) {
				$data[ $string_column ] = $string_values[ $i ];
			}
			$inserted_data[] = $data;
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();

		if ( $numeric_column ) {
			$getter = 'get_' . $numeric_column;

			// Test 'in' operator with numeric values
			$models = $repo->by( $numeric_column . '_in', [ 10, 30, 50 ] )->all();
			$this->assertCount( 3, $models );
			$values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $models );
			$this->assertContains( 10, $values );
			$this->assertContains( 30, $values );
			$this->assertContains( 50, $values );

			// Test 'not_in' operator with numeric values
			$models = $repo->by( $numeric_column . '_not_in', [ 10, 30, 50 ] )->all();
			$this->assertCount( 2, $models );
			$values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $models );
			$this->assertContains( 20, $values );
			$this->assertContains( 40, $values );
		}

		if ( $string_column ) {
			$getter = 'get_' . $string_column;

			// Test 'in' operator with string values
			$models = $repo->by( $string_column . '_in', [ 'active', 'pending' ] )->all();
			$this->assertCount( 3, $models );
			$values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $models );
			$this->assertEquals( 2, array_count_values( $values )['active'] );
			$this->assertEquals( 1, array_count_values( $values )['pending'] );

			// Test 'not_in' operator with string values
			$models = $repo->by( $string_column . '_not_in', [ 'active', 'pending' ] )->all();
			$this->assertCount( 2, $models );
			$values = array_map( function( $m ) use ( $getter ) { return $m->$getter(); }, $models );
			$this->assertContains( 'inactive', $values );
			$this->assertContains( 'completed', $values );
		}
	}

	public function test_combining_operators() {
		// Find columns for testing
		$columns = $this->test_table_class::get_columns();
		$numeric_column = null;
		$string_column = null;
		foreach ( $columns as $column => $data ) {
			if ( $column === $this->test_table_class::uid_column() ) {
				continue;
			}
			if ( ! $numeric_column && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_INT ) {
				$numeric_column = $column;
			}
			if ( ! $string_column && $data['php_type'] === Custom_Table_Abstract::PHP_TYPE_STRING ) {
				$string_column = $column;
			}
		}

		if ( ! $numeric_column || ! $string_column ) {
			$this->markTestSkipped( 'Need both numeric and string columns for combining operators test' );
		}

		// Insert test data
		for ( $i = 1; $i <= 20; $i++ ) {
			$data = $this->get_create_data();
			$data[ $numeric_column ] = $i * 5;
			$data[ $string_column ] = $i <= 10 ? 'active' : 'inactive';
			$this->insert_test_data( $data );
		}

		$repo = $this->get_repository();
		$numeric_getter = 'get_' . $numeric_column;
		$string_getter = 'get_' . $string_column;

		// Combine multiple operators: active status AND value between 25 and 50
		$models = $repo
			->by( $string_column, 'active' )
			->by( $numeric_column . '_gte', 25 )
			->by( $numeric_column . '_lte', 50 )
			->all();

		$this->assertCount( 6, $models ); // Items 5-10 (values 25-50)
		foreach ( $models as $model ) {
			$this->assertEquals( 'active', $model->$string_getter() );
			$this->assertGreaterThanOrEqual( 25, $model->$numeric_getter() );
			$this->assertLessThanOrEqual( 50, $model->$numeric_getter() );
		}

		// Test NOT equal combined with greater than
		$models = $repo
			->by( $string_column . '_neq', 'active' )
			->by( $numeric_column . '_gt', 75 )
			->all();

		$this->assertCount( 5, $models ); // Items 16-20 (inactive with value > 75)
		foreach ( $models as $model ) {
			$this->assertEquals( 'inactive', $model->$string_getter() );
			$this->assertGreaterThan( 75, $model->$numeric_getter() );
		}

		// Test IN combined with range
		$models = $repo
			->by( $string_column . '_in', [ 'active', 'inactive' ] )
			->by( $numeric_column . '_gt', 40 )
			->by( $numeric_column . '_lt', 80 )
			->all();

		$this->assertCount( 7, $models ); // Items 9-15 (values 45-75)
		foreach ( $models as $model ) {
			$this->assertContains( $model->$string_getter(), [ 'active', 'inactive' ] );
			$this->assertGreaterThan( 40, $model->$numeric_getter() );
			$this->assertLessThan( 80, $model->$numeric_getter() );
		}
	}
}
