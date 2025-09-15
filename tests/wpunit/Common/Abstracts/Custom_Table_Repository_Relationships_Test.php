<?php
/**
 * Tests for Custom_Table_Repository relationships.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */

namespace TEC\Common\Abstracts;

use Codeception\TestCase\WPTestCase;
use TEC\Common\Contracts\Model as Model_Interface;
use TEC\Common\StellarWP\Schema\Register;

/**
 * Class Custom_Table_Repository_Relationships_Test
 *
 * Tests the many-to-many relationship functionality of the Custom_Table_Repository abstract class.
 *
 * @since TBD
 *
 * @package TEC\Common\Abstracts
 */
class Custom_Table_Repository_Relationships_Test extends WPTestCase {

	/**
	 * The test tables.
	 *
	 * @var array
	 */
	protected $test_tables = [];

	/**
	 * The test classes.
	 *
	 * @var array
	 */
	protected $test_classes = [];

	/**
	 * The test table classes for StellarWP Schema.
	 *
	 * @var array
	 */
	protected $test_table_classes_for_schema = [];

	/**
	 * Set up before each test.
	 */
	public function setUp(): void {
		parent::setUp();

		global $wpdb;

		// Define table names
		$this->test_tables = [
			'items'         => $wpdb->prefix . 'test_items',
			'relationships' => $wpdb->prefix . 'test_items_posts',
		];

		// Create dynamic test classes
		$this->create_test_classes();

		// Register tables with StellarWP Schema
		Register::table( $this->test_table_classes_for_schema['items'] );
		Register::table( $this->test_table_classes_for_schema['relationships'] );

		// Create some test posts
		$this->create_test_posts();
	}

	/**
	 * Tear down after each test.
	 */
	public function tearDown(): void {
		global $wpdb;

		// Unregister tables from StellarWP Schema
		if ( ! empty( $this->test_table_classes_for_schema ) ) {
			Register::remove_table( $this->test_table_classes_for_schema['items'] );
			Register::remove_table( $this->test_table_classes_for_schema['relationships'] );
		}

		// Clean up test posts
		$posts = get_posts( [
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'meta_key'       => '_test_post',
			'meta_value'     => '1',
		] );

		foreach ( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		}

		parent::tearDown();
	}


	/**
	 * Creates dynamic test classes for testing.
	 */
	protected function create_test_classes() {
		// Create relationship table class
		$relationship_table_name = $this->test_tables['relationships'];
		$this->test_table_classes_for_schema['relationships'] = new class( $relationship_table_name ) extends Custom_Table_Abstract {
			protected static $table_name_static;

			public function __construct( $table_name ) {
				self::$table_name_static = $table_name;
			}

			public static function uid_column(): string {
				return 'id';
			}

			public static function base_table_name(): string {
				return 'test_items_posts';
			}

			public static function get_schema_slug(): string {
				return 'test-relationships';
			}

			public static function group_name(): string {
				return 'test_group';
			}

			public static function get_columns(): array {
				return [
					'id'         => [
						'type'           => 'BIGINT',
						'length'         => 20,
						'unsigned'       => true,
						'auto_increment' => true,
						'nullable'       => false,
						'php_type'       => self::PHP_TYPE_INT,
					],
					'item_id'    => [
						'type'     => 'BIGINT',
						'length'   => 20,
						'unsigned' => true,
						'nullable' => false,
						'php_type' => self::PHP_TYPE_INT,
						'index'    => true,
					],
					'post_id'    => [
						'type'     => 'BIGINT',
						'length'   => 20,
						'unsigned' => true,
						'nullable' => false,
						'php_type' => self::PHP_TYPE_INT,
						'index'    => true,
					],
					'created_at' => [
						'type'     => 'DATETIME',
						'default'  => 'CURRENT_TIMESTAMP',
						'nullable' => true,
						'php_type' => self::PHP_TYPE_DATETIME,
					],
				];
			}

			protected static function get_model_from_array( array $data ): Model_Interface {
				// Not needed for relationship table
				return new class() extends Model_Abstract {};
			}
		};

		$this->test_classes['relationship_table'] = $this->test_table_classes_for_schema['relationships'];

		// Store relationship table class globally
		$GLOBALS['test_relationship_table'] = $this->test_classes['relationship_table'];

		// Create items table class
		$items_table_name = $this->test_tables['items'];
		$relationship_table_class = get_class( $this->test_classes['relationship_table'] );

		$this->test_table_classes_for_schema['items'] = new class( $items_table_name, $relationship_table_class ) extends Custom_Table_Abstract {
			protected static $table_name_static;
			protected $relationship_table_class;

			public function __construct( $table_name, $relationship_table_class ) {
				self::$table_name_static = $table_name;
				$this->relationship_table_class = $relationship_table_class;
			}

			public static function uid_column(): string {
				return 'id';
			}

			public static function base_table_name(): string {
				return 'test_items';
			}

			public static function get_schema_slug(): string {
				return 'test-items';
			}

			public static function group_name(): string {
				return 'test_group';
			}

			public static function get_columns(): array {
				return [
					'id'          => [
						'type'           => 'BIGINT',
						'length'         => 20,
						'unsigned'       => true,
						'auto_increment' => true,
						'nullable'       => false,
						'php_type'       => self::PHP_TYPE_INT,
					],
					'name'        => [
						'type'     => 'VARCHAR',
						'length'   => 255,
						'nullable' => false,
						'php_type' => self::PHP_TYPE_STRING,
					],
					'description' => [
						'type'     => 'TEXT',
						'nullable' => true,
						'php_type' => self::PHP_TYPE_STRING,
					],
					'status'      => [
						'type'     => 'VARCHAR',
						'length'   => 50,
						'default'  => 'active',
						'nullable' => true,
						'php_type' => self::PHP_TYPE_STRING,
						'index'    => true,
					],
					'created_at'  => [
						'type'     => 'DATETIME',
						'default'  => 'CURRENT_TIMESTAMP',
						'nullable' => true,
						'php_type' => self::PHP_TYPE_DATETIME,
					],
				];
			}

			protected static function get_model_from_array( array $data ): Model_Interface {
				$model = new class() extends Model_Abstract {
					protected $id;
					protected $name;
					protected $description;
					protected $status;
					protected $created_at;

					public function __construct() {
						parent::__construct();

						// Set up the many-to-many relationship with posts
						$this->set_relationship(
							'posts',
							Model_Abstract::RELATIONSHIP_TYPE_MANY_TO_MANY,
							get_class( $GLOBALS['test_relationship_table'] ),
							'post'
						);

						// Set relationship columns
						$this->set_relationship_columns( 'posts', 'item_id', 'post_id' );
					}

					public function get_id() {
						return $this->id;
					}

					public function set_id( $id ) {
						$this->id = $id;
						return $this;
					}

					public function get_name() {
						return $this->name;
					}

					public function set_name( $name ) {
						$this->name = $name;
						return $this;
					}

					public function get_description() {
						return $this->description;
					}

					public function set_description( $description ) {
						$this->description = $description;
						return $this;
					}

					public function get_status() {
						return $this->status;
					}

					public function set_status( $status ) {
						$this->status = $status;
						return $this;
					}

					public function get_created_at() {
						return $this->created_at;
					}

					public function set_created_at( $created_at ) {
						$this->created_at = $created_at;
						return $this;
					}

					public function get_table_interface(): string {
						return get_class( $GLOBALS['test_items_table'] );
					}

					public function save() {
						$table_class = $this->get_table_interface();
						$data = [
							'name'        => $this->name,
							'description' => $this->description,
							'status'      => $this->status,
						];

						if ( $this->id ) {
							$table_class::update( $this->id, $data );
						} else {
							$this->id = $table_class::insert( $data );
						}

						// Save relationship data
						$this->save_relationship_data();

						return $this;
					}

					public function delete() {
						// Delete relationships first
						$this->delete_all_relationship_data();

						if ( $this->id ) {
							$table_class = $this->get_table_interface();
							$table_class::delete( $this->id );
							$this->id = null;
						}
						return true;
					}

					/**
					 * Get related post IDs.
					 */
					public function get_post_ids() {
						if ( ! $this->id ) {
							return [];
						}

						global $wpdb;
						$relationship_table = $GLOBALS['test_relationship_table']::table_name();

						return $wpdb->get_col( $wpdb->prepare(
							"SELECT post_id FROM {$relationship_table} WHERE item_id = %d",
							$this->id
						) );
					}

					/**
					 * Add posts to the item.
					 */
					public function add_posts( array $post_ids ) {
						foreach ( $post_ids as $post_id ) {
							$this->add_id_to_relationship( 'posts', $post_id );
						}
						return $this;
					}

					/**
					 * Remove posts from the item.
					 */
					public function remove_posts( array $post_ids ) {
						foreach ( $post_ids as $post_id ) {
							$this->remove_id_from_relationship( 'posts', $post_id );
						}
						return $this;
					}
				};

				// Populate model with data
				foreach ( $data as $key => $value ) {
					$method = 'set_' . $key;
					if ( method_exists( $model, $method ) ) {
						$model->$method( $value );
					}
				}

				return $model;
			}
		};

		$this->test_classes['items_table'] = $this->test_table_classes_for_schema['items'];

		// Store items table class globally
		$GLOBALS['test_items_table'] = $this->test_classes['items_table'];

		// Create test model class
		$model_instance = $this->test_classes['items_table']::get_model_from_array( [] );
		$this->test_classes['model'] = get_class( $model_instance );

		// Register model in tribe container for constructor access
		tribe()->singleton( $this->test_classes['model'], function() use ( $model_instance ) {
			return clone $model_instance;
		} );

		// Create test repository class
		$items_table = $this->test_classes['items_table'];
		$model_class = $this->test_classes['model'];

		$this->test_classes['repository'] = new class( $items_table, $model_class ) extends Custom_Table_Repository {
			protected $table_class;
			protected $model_class;

			public function __construct( $table_class, $model_class ) {
				$this->table_class = $table_class;
				$this->model_class = $model_class;
				parent::__construct();
			}

			public function get_table_interface(): string {
				return get_class( $this->table_class );
			}

			public function get_model_class(): string {
				return $this->model_class;
			}
		};
	}

	/**
	 * Create test posts.
	 */
	protected function create_test_posts() {
		$this->test_posts = [];

		for ( $i = 1; $i <= 5; $i++ ) {
			$post_id = wp_insert_post( [
				'post_title'   => "Test Post $i",
				'post_content' => "Test content $i",
				'post_status'  => 'publish',
				'post_type'    => 'post',
			] );

			update_post_meta( $post_id, '_test_post', '1' );
			$this->test_posts[] = $post_id;
		}
	}

	/**
	 * Get a test repository instance.
	 *
	 * @return Custom_Table_Repository
	 */
	protected function get_repository() {
		return clone $this->test_classes['repository'];
	}

	/**
	 * Insert test item data.
	 *
	 * @param array $data
	 * @return int Insert ID
	 */
	protected function insert_test_item( array $data ) {
		global $wpdb;
		$wpdb->insert( $this->test_tables['items'], $data );
		return $wpdb->insert_id;
	}

	/**
	 * Insert relationship data.
	 *
	 * @param int $item_id
	 * @param int $post_id
	 */
	protected function insert_relationship( $item_id, $post_id ) {
		global $wpdb;
		$wpdb->insert( $this->test_tables['relationships'], [
			'item_id' => $item_id,
			'post_id' => $post_id,
		] );
	}

	/**
	 * Test creating an item with post relationships.
	 */
	public function test_create_item_with_posts() {
		$repo = $this->get_repository();

		$model = $repo
			->set_args( [
				'name'        => 'Item with Posts',
				'description' => 'Item that has posts',
				'status'      => 'active',
				'posts'       => [ $this->test_posts[0], $this->test_posts[1] ],
			] )
			->create();

		$this->assertNotNull( $model );
		$this->assertEquals( 'Item with Posts', $model->get_name() );

		// Check that relationships were created
		$post_ids = $model->get_post_ids();
		$this->assertCount( 2, $post_ids );
		$this->assertContains( (string) $this->test_posts[0], $post_ids );
		$this->assertContains( (string) $this->test_posts[1], $post_ids );
	}

	/**
	 * Test finding items by related posts.
	 */
	public function test_find_items_by_posts() {
		// Create items with different post relationships
		$item1_id = $this->insert_test_item( [
			'name'        => 'Item 1',
			'description' => 'First item',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item1_id, $this->test_posts[0] );
		$this->insert_relationship( $item1_id, $this->test_posts[1] );

		$item2_id = $this->insert_test_item( [
			'name'        => 'Item 2',
			'description' => 'Second item',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item2_id, $this->test_posts[1] );
		$this->insert_relationship( $item2_id, $this->test_posts[2] );

		$item3_id = $this->insert_test_item( [
			'name'        => 'Item 3',
			'description' => 'Third item',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item3_id, $this->test_posts[2] );

		$repo = $this->get_repository();

		// Find items related to post 1
		$items = $repo->by( 'posts', $this->test_posts[0] )->all();
		$this->assertCount( 1, $items );
		$this->assertEquals( 'Item 1', $items[0]->get_name() );

		// Find items related to post 2
		$items = $repo->by( 'posts', $this->test_posts[1] )->all();
		$this->assertCount( 2, $items );

		$names = array_map( function( $item ) {
			return $item->get_name();
		}, $items );
		$this->assertContains( 'Item 1', $names );
		$this->assertContains( 'Item 2', $names );

		// Find items related to multiple posts (OR condition)
		$items = $repo->by( 'posts_in', [ $this->test_posts[0], $this->test_posts[2] ] )->all();
		$this->assertCount( 3, $items ); // All three items
	}

	/**
	 * Test finding items NOT related to specific posts.
	 */
	public function test_find_items_not_related_to_posts() {
		// Create items with different post relationships
		$item1_id = $this->insert_test_item( [
			'name'        => 'Related Item',
			'description' => 'Has relationships',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item1_id, $this->test_posts[0] );

		$item2_id = $this->insert_test_item( [
			'name'        => 'Unrelated Item',
			'description' => 'No relationships',
			'status'      => 'active',
		] );

		$item3_id = $this->insert_test_item( [
			'name'        => 'Different Related Item',
			'description' => 'Different relationships',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item3_id, $this->test_posts[1] );

		$repo = $this->get_repository();

		// Find items NOT related to post 0
		$items = $repo->by( 'posts_not_in', [ $this->test_posts[0] ] )->all();
		$this->assertCount( 2, $items );

		$names = array_map( function( $item ) {
			return $item->get_name();
		}, $items );
		$this->assertContains( 'Unrelated Item', $names );
		$this->assertContains( 'Different Related Item', $names );
		$this->assertNotContains( 'Related Item', $names );
	}

	/**
	 * Test updating an item's post relationships.
	 */
	public function test_update_item_posts() {
		// Create an item with initial relationships
		$item_id = $this->insert_test_item( [
			'name'        => 'Update Test Item',
			'description' => 'Item to update',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item_id, $this->test_posts[0] );
		$this->insert_relationship( $item_id, $this->test_posts[1] );

		$repo = $this->get_repository();
		$model = $repo->by( 'id', $item_id )->first();

		// Add more posts
		$model->add_posts( [ $this->test_posts[2], $this->test_posts[3] ] );
		$model->save();

		// Verify posts were added
		$post_ids = $model->get_post_ids();
		$this->assertCount( 4, $post_ids );
		$this->assertContains( (string) $this->test_posts[2], $post_ids );
		$this->assertContains( (string) $this->test_posts[3], $post_ids );

		// Remove some posts
		$model->remove_posts( [ $this->test_posts[0], $this->test_posts[2] ] );
		$model->save();

		// Verify posts were removed
		$post_ids = $model->get_post_ids();
		$this->assertCount( 2, $post_ids );
		$this->assertNotContains( (string) $this->test_posts[0], $post_ids );
		$this->assertNotContains( (string) $this->test_posts[2], $post_ids );
		$this->assertContains( (string) $this->test_posts[1], $post_ids );
		$this->assertContains( (string) $this->test_posts[3], $post_ids );
	}

	/**
	 * Test deleting an item removes its relationships.
	 */
	public function test_delete_item_removes_relationships() {
		// Create an item with relationships
		$item_id = $this->insert_test_item( [
			'name'        => 'Delete Test Item',
			'description' => 'Item to delete',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item_id, $this->test_posts[0] );
		$this->insert_relationship( $item_id, $this->test_posts[1] );

		// Verify relationships exist
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->test_tables['relationships']} WHERE item_id = %d",
			$item_id
		) );
		$this->assertEquals( 2, $count );

		// Delete the item
		$repo = $this->get_repository();
		$deleted = $repo->by( 'id', $item_id )->delete();
		$this->assertTrue( $deleted );

		// Verify item is deleted
		$model = $repo->by( 'id', $item_id )->first();
		$this->assertNull( $model );

		// Verify relationships are also deleted
		$count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$this->test_tables['relationships']} WHERE item_id = %d",
			$item_id
		) );
		$this->assertEquals( 0, $count );
	}

	/**
	 * Test complex filtering with relationships and other conditions.
	 */
	public function test_complex_filtering_with_relationships() {
		// Create items with various attributes and relationships
		$item1_id = $this->insert_test_item( [
			'name'        => 'Active Item 1',
			'description' => 'First active',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item1_id, $this->test_posts[0] );
		$this->insert_relationship( $item1_id, $this->test_posts[1] );

		$item2_id = $this->insert_test_item( [
			'name'        => 'Active Item 2',
			'description' => 'Second active',
			'status'      => 'active',
		] );
		$this->insert_relationship( $item2_id, $this->test_posts[1] );

		$item3_id = $this->insert_test_item( [
			'name'        => 'Inactive Item',
			'description' => 'Inactive item',
			'status'      => 'inactive',
		] );
		$this->insert_relationship( $item3_id, $this->test_posts[1] );

		$item4_id = $this->insert_test_item( [
			'name'        => 'Active Item 3',
			'description' => 'Third active',
			'status'      => 'active',
		] );
		// No relationships for this item

		$repo = $this->get_repository();

		// Find active items related to post 1
		$items = $repo
			->by( 'status', 'active' )
			->by( 'posts', $this->test_posts[1] )
			->all();

		$this->assertCount( 2, $items );
		$names = array_map( function( $item ) {
			return $item->get_name();
		}, $items );
		$this->assertContains( 'Active Item 1', $names );
		$this->assertContains( 'Active Item 2', $names );
		$this->assertNotContains( 'Inactive Item', $names );
		$this->assertNotContains( 'Active Item 3', $names );
	}

	/**
	 * Test saving multiple items with relationships.
	 */
	public function test_save_multiple_items_with_relationships() {
		// Create multiple items
		$item1_id = $this->insert_test_item( [
			'name'        => 'Batch Item 1',
			'description' => 'First batch item',
			'status'      => 'active',
		] );

		$item2_id = $this->insert_test_item( [
			'name'        => 'Batch Item 2',
			'description' => 'Second batch item',
			'status'      => 'active',
		] );

		$repo = $this->get_repository();

		// Update multiple items with relationships
		$saved = $repo
			->by( 'status', 'active' )
			->set_args( [
				'posts' => [ $this->test_posts[0], $this->test_posts[1] ],
			] )
			->save();

		$this->assertIsArray( $saved );
		$this->assertCount( 2, $saved );

		// Verify both items have the relationships
		foreach ( [ $item1_id, $item2_id ] as $item_id ) {
			$model = $repo->by( 'id', $item_id )->first();
			$post_ids = $model->get_post_ids();
			$this->assertCount( 2, $post_ids );
			$this->assertContains( (string) $this->test_posts[0], $post_ids );
			$this->assertContains( (string) $this->test_posts[1], $post_ids );
		}
	}

	/**
	 * Test counting items with relationship filters.
	 */
	public function test_count_with_relationships() {
		// Create items with various relationships
		for ( $i = 1; $i <= 3; $i++ ) {
			$item_id = $this->insert_test_item( [
				'name'        => "Count Item $i",
				'description' => "Count description $i",
				'status'      => 'active',
			] );

			// First two items related to post 0
			if ( $i <= 2 ) {
				$this->insert_relationship( $item_id, $this->test_posts[0] );
			}

			// All items related to post 1
			$this->insert_relationship( $item_id, $this->test_posts[1] );
		}

		$repo = $this->get_repository();

		// Count all items
		$total = $repo->count();
		$this->assertEquals( 3, $total );

		// Count items related to post 0
		$count = $repo->by( 'posts', $this->test_posts[0] )->count();
		$this->assertEquals( 2, $count );

		// Count items related to post 1
		$count = $repo->by( 'posts', $this->test_posts[1] )->count();
		$this->assertEquals( 3, $count );
	}

	/**
	 * Test operators with relationships.
	 */
	public function test_operators_with_relationships() {
		// Create items with different statuses and relationships
		$items = [
			[ 'name' => 'Priority High', 'status' => 'active', 'posts' => [ 0, 1 ] ],
			[ 'name' => 'Priority Medium', 'status' => 'pending', 'posts' => [ 1 ] ],
			[ 'name' => 'Priority Low', 'status' => 'inactive', 'posts' => [ 2 ] ],
			[ 'name' => 'Priority None', 'status' => 'active', 'posts' => [] ],
			[ 'name' => 'Priority Extra', 'status' => 'pending', 'posts' => [ 0, 2 ] ],
		];

		foreach ( $items as $item_data ) {
			$item_id = $this->insert_test_item( [
				'name'   => $item_data['name'],
				'status' => $item_data['status'],
			] );

			foreach ( $item_data['posts'] as $post_index ) {
				$this->insert_relationship( $item_id, $this->test_posts[ $post_index ] );
			}
		}

		$repo = $this->get_repository();

		// Test status_neq with posts filter
		$models = $repo
			->by( 'status_neq', 'inactive' )
			->by( 'posts', $this->test_posts[1] )
			->all();

		$this->assertCount( 2, $models );
		$names = array_map( function( $m ) { return $m->get_name(); }, $models );
		$this->assertContains( 'Priority High', $names );
		$this->assertContains( 'Priority Medium', $names );

		// Test status_in with posts_in
		$models = $repo
			->by( 'status_in', [ 'active', 'pending' ] )
			->by( 'posts_in', [ $this->test_posts[0], $this->test_posts[2] ] )
			->all();

		$this->assertCount( 3, $models );
		$names = array_map( function( $m ) { return $m->get_name(); }, $models );
		$this->assertContains( 'Priority High', $names );
		$this->assertContains( 'Priority Low', $names );
		$this->assertContains( 'Priority Extra', $names );

		// Test status_not_in with posts_not_in
		$models = $repo
			->by( 'status_not_in', [ 'inactive' ] )
			->by( 'posts_not_in', [ $this->test_posts[2] ] )
			->all();

		$this->assertCount( 3, $models );
		$names = array_map( function( $m ) { return $m->get_name(); }, $models );
		$this->assertContains( 'Priority High', $names );
		$this->assertContains( 'Priority Medium', $names );
		$this->assertContains( 'Priority None', $names );
	}
}
