<?php

namespace TEC\Common\Libraries;

use TEC\Common\Key_Value_Cache\Table\Schema as Key_Value_Cache_Schema;
use TEC\Common\StellarWP\AdminNotices\AdminNotice;
use TEC\Common\StellarWP\AdminNotices\AdminNotices;
use TEC\Common\StellarWP\Migrations\Admin\UI;
use TEC\Common\StellarWP\Migrations\Config as Migrations_Config;
use TEC\Common\StellarWP\Migrations\Tables\Migration_Executions;
use TEC\Common\StellarWP\Migrations\Tables\Migration_Logs;
use TEC\Common\StellarWP\Migrations\Tables\Provider as Tables_Provider;
use TEC\Common\StellarWP\Schema\Builder;
use TEC\Common\StellarWP\Schema\Config as Schema_Config;
use TEC\Common\StellarWP\Schema\Register;
use TEC\Common\StellarWP\Schema\Schema;
use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;
use TEC\Common\Tests\Migrations\Applicable_Migration;
use TEC\Common\Tests\Provider\Controller_Test_Case;

use function TEC\Common\StellarWP\Migrations\migrations;

class Migrations_Test extends Controller_Test_Case {
	protected $controller_class = Migrations::class;

	/**
	 * MySQL and MariaDB reject longer table names.
	 */
	private const MAX_TABLE_NAME_LENGTH = 64;

	private const NOTICE_ID = 'tec_common_migrations_tables_error';

	private string $original_prefix;

	/**
	 * @var array<string,Table>
	 */
	private array $original_migrations_tables = [];

	/**
	 * @var string[]
	 */
	private array $registered_migration_ids = [];

	/**
	 * @before
	 */
	public function store_the_schema_state(): void {
		global $wpdb;
		$this->original_prefix = $wpdb->prefix;

		foreach ( [ Migration_Logs::class, Migration_Executions::class ] as $table_class ) {
			$this->original_migrations_tables[ $table_class::base_table_name() ] = Schema::tables()->get( $table_class::base_table_name() );
		}
	}

	/**
	 * @after
	 */
	public function restore_the_schema_state(): void {
		global $wpdb;
		$wpdb->prefix = $this->original_prefix;

		foreach ( $this->original_migrations_tables as $name => $table ) {
			Schema::tables()->set( $name, $table );
		}

		// A completed Builder::up() points the $wpdb table properties at whatever prefix was active.
		Schema_Config::get_container()->get( Builder::class )->register_custom_tables_names();

		AdminNotices::removeNotice( self::NOTICE_ID );

		foreach ( $this->registered_migration_ids as $migration_id ) {
			migrations()->get_registry()->offsetUnset( $migration_id );
		}
	}

	public function test_registering_a_table_after_a_migrations_table_failed_does_not_throw(): void {
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();

		tribe( Tables_Provider::class )->register();
		// Register::table() runs Builder::up(), which retries every registered table that is not current.
		Register::table( Key_Value_Cache_Schema::class );

		$this->assertFalse( Schema::tables()->offsetExists( Migration_Executions::base_table_name() ) );
		$this->assertTrue( Schema::tables()->offsetExists( Key_Value_Cache_Schema::base_table_name() ) );
	}

	public function test_the_table_that_was_created_stays_registered(): void {
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();

		tribe( Tables_Provider::class )->register();

		$this->assertTrue( Schema::tables()->offsetExists( Migration_Logs::base_table_name() ) );
	}

	public function test_the_library_does_not_start_running_migrations_when_a_table_fails(): void {
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();
		$tables_registered_action = 'stellarwp_migrations_' . Migrations_Config::get_hook_prefix() . '_tables_registered';
		$expected_runs            = did_action( $tables_registered_action );

		tribe( Tables_Provider::class )->register();

		/* The library hooks its scheduler, CLI commands, REST routes and log cleanup on this action alone. */
		$this->assertSame( $expected_runs, did_action( $tables_registered_action ) );
	}

	public function test_the_migrations_screens_render_nothing_when_a_table_fails(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();
		$migration_id = $this->register_an_applicable_migration();

		tribe( Tables_Provider::class )->register();
		$ui = tribe( UI::class );
		ob_start();
		$ui->render_list();
		$ui->render_single( $migration_id );

		$this->assertSame( '', ob_get_clean() );
	}

	public function test_tells_the_administrator_to_shorten_a_too_long_table_prefix(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();
		$expected_table = Migration_Executions::table_name( true );

		tribe( Tables_Provider::class )->register();
		$html = AdminNotices::render( $this->get_notice(), false );

		$this->assertStringContainsString( $expected_table, $html );
		$this->assertStringContainsString( self::MAX_TABLE_NAME_LENGTH . ' characters', $html );
		$this->assertStringContainsString( 'wp-config.php', $html );
	}

	public function test_names_the_prefixed_keys_that_keep_administrators_able_to_log_in(): void {
		global $wpdb;
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();
		$expected_option_key    = $wpdb->prefix . 'user_roles';
		$expected_user_meta_key = $wpdb->prefix . 'capabilities';

		tribe( Tables_Provider::class )->register();
		$html = AdminNotices::render( $this->get_notice(), false );

		$this->assertStringContainsString( $expected_option_key, $html );
		$this->assertStringContainsString( $expected_user_meta_key, $html );
	}

	public function test_shows_the_failed_query_when_the_table_name_is_within_the_limit(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'administrator' ] ) );
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$missing_table = $this->make_the_executions_table_creation_fail();

		tribe( Tables_Provider::class )->register();
		$html = AdminNotices::render( $this->get_notice(), false );

		$this->assertStringContainsString( $missing_table, $html );
		$this->assertStringNotContainsString( 'wp-config.php', $html );
	}

	public function test_the_notice_is_only_shown_to_users_who_can_manage_options(): void {
		wp_set_current_user( static::factory()->user->create( [ 'role' => 'editor' ] ) );
		$this->make_controller()->register();
		$this->make_the_executions_table_need_an_update();
		$this->use_a_prefix_one_character_too_long_for_the_executions_table();

		tribe( Tables_Provider::class )->register();

		$this->assertSame( '', AdminNotices::render( $this->get_notice(), false ) );
	}

	public function test_does_nothing_when_the_migrations_tables_are_current(): void {
		$this->make_controller()->register();

		tribe( Tables_Provider::class )->register();

		$this->assertTrue( Schema::tables()->offsetExists( Migration_Executions::base_table_name() ) );
		$this->assertNull( $this->find_notice() );
	}

	private function make_the_executions_table_need_an_update(): void {
		$option = $this->original_migrations_tables[ Migration_Executions::base_table_name() ]->get_schema_version_option();
		add_filter( "pre_option_{$option}", '__return_empty_string' );
	}

	/**
	 * One character over the limit is enough for the database to reject the real CREATE TABLE.
	 *
	 * Only `$wpdb->prefix` changes: the core table properties keep the real prefix, so options and users still load.
	 */
	private function use_a_prefix_one_character_too_long_for_the_executions_table(): void {
		global $wpdb;
		$wpdb->prefix = str_repeat( 'p', self::MAX_TABLE_NAME_LENGTH + 1 - strlen( Migration_Executions::base_table_name() ) );

		$this->assertSame( self::MAX_TABLE_NAME_LENGTH + 1, strlen( Migration_Executions::table_name( true ) ) );
	}

	/**
	 * Makes the executions table look missing to dbDelta, then turns its CREATE TABLE into a read of a missing table.
	 *
	 * The test suite already rewrites CREATE TABLE into CREATE TEMPORARY TABLE, so both forms are matched.
	 *
	 * @return string The name of the missing table the failed query reads.
	 */
	private function make_the_executions_table_creation_fail(): string {
		$table         = Migration_Executions::table_name( true );
		$missing_table = $table . '_missing';

		add_filter(
			'query',
			static function ( $query ) use ( $table, $missing_table ) {
				if ( ! is_string( $query ) ) {
					return $query;
				}

				if ( 0 === strpos( $query, "DESCRIBE {$table};" ) ) {
					return "DESCRIBE {$missing_table};";
				}

				if ( preg_match( '/^\s*CREATE\s+(TEMPORARY\s+)?TABLE\b/i', $query ) && false !== strpos( $query, $table ) ) {
					return "SELECT * FROM {$missing_table}";
				}

				return $query;
			}
		);

		return $missing_table;
	}

	/**
	 * @return string The ID the migration is registered with.
	 */
	private function register_an_applicable_migration(): string {
		$migration_id = 'tec-common-tests-applicable-migration';
		migrations()->get_registry()->register( $migration_id, Applicable_Migration::class );
		$this->registered_migration_ids[] = $migration_id;

		return $migration_id;
	}

	/**
	 * @return AdminNotice|null The migrations tables error notice, or null when none was registered.
	 */
	private function find_notice(): ?AdminNotice {
		foreach ( AdminNotices::getNotices() as $notice ) {
			if ( self::NOTICE_ID === $notice->getId() ) {
				return $notice;
			}
		}

		return null;
	}

	/**
	 * @return AdminNotice The migrations tables error notice; the test fails when none was registered.
	 */
	private function get_notice(): AdminNotice {
		$notice = $this->find_notice();
		$this->assertNotNull( $notice, 'The migrations tables error notice was not registered.' );

		return $notice;
	}
}
