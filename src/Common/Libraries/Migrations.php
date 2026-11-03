<?php
/**
 * The Controller to set up the Migrations library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Libraries_Provider;
use TEC\Common\StellarWP\DB\Database\Exceptions\DatabaseQueryException;
use TEC\Common\StellarWP\Migrations\Provider as Migrations_Provider;
use TEC\Common\StellarWP\Migrations\Config;
use TEC\Common\StellarWP\Migrations\Tables\Migration_Executions;
use TEC\Common\StellarWP\Schema\Schema;
use Tribe__Main as Common;
use Tribe__Template as Template;

/**
 * Controller for setting up the Migrations library.
 *
 * @since 6.13.0
 *
 * @package TEC\Common\Libraries\Migrations
 */
class Migrations extends Controller_Contract {
	/**
	 * The longest table name MySQL and MariaDB accept.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	private const MAX_TABLE_NAME_LENGTH = 64;

	/**
	 * Register the controller.
	 *
	 * @since 6.13.0
	 * @since TBD Handles the migrations tables failing to be created.
	 */
	protected function do_register(): void {
		$hook_prefix = tribe( Libraries_Provider::class )->get_hook_prefix();

		$common   = Common::instance();
		$template = new Template();

		$template->set_template_origin( $common );
		$template->set_template_folder( 'vendor/vendor-prefixed/stellarwp/migrations/src/views' );
		$template->set_template_folder_lookup();
		$template->set_template_context_extract( true );

		Config::set_container( $this->container );
		Config::set_hook_prefix( $hook_prefix );
		Config::set_template_engine( $template );
		Config::set_assets_url( $common->plugin_url . 'vendor/vendor-prefixed/stellarwp/migrations/assets/' );

		add_action( "stellarwp_migrations_{$hook_prefix}_tables_error", [ $this, 'handle_tables_error' ] );

		$this->container->register( Migrations_Provider::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.13.0
	 * @since TBD Removes the tables error handler.
	 *
	 * @return void
	 */
	public function unregister(): void {
		$hook_prefix = tribe( Libraries_Provider::class )->get_hook_prefix();

		remove_action( "stellarwp_migrations_{$hook_prefix}_tables_error", [ $this, 'handle_tables_error' ] );
	}

	/**
	 * Stops the migrations tables that could not be created from being retried, and tells the administrator why.
	 *
	 * The library catches its own failure, but the table stays registered and not current, and every later
	 * `Builder::up()` (the next `Register::table()` call, the `plugins_loaded` schema run) creates it again outside
	 * any catch, which fatals the site.
	 *
	 * @since TBD
	 *
	 * @param DatabaseQueryException $error The exception thrown by the failed query.
	 *
	 * @return void
	 */
	public function handle_tables_error( DatabaseQueryException $error ): void {
		$tables        = Schema::tables();
		$failed_tables = iterator_to_array(
			$tables->get_tables_needing_updates( $tables->get_by_group( Migration_Executions::group_name() ) )
		);

		$too_long_names = [];

		foreach ( $failed_tables as $base_table_name => $table ) {
			$tables->remove( $base_table_name );

			$table_name = $table::table_name( true );

			if ( strlen( $table_name ) > self::MAX_TABLE_NAME_LENGTH ) {
				$too_long_names[] = $table_name;
			}
		}

		$message = $too_long_names
			? sprintf(
				// Translators: %1$s is a list of table names, %2$d the maximum table name length, %3$s the wp-config.php variable holding the prefix, %4$s the wp-config.php file name.
				__( 'The database table %1$s could not be created because its name is longer than the %2$d characters MySQL allows. Shorten the database table prefix (%3$s in %4$s), then rename the existing database tables to use the new prefix.', 'tribe-common' ),
				implode( ', ', $too_long_names ),
				self::MAX_TABLE_NAME_LENGTH,
				'$table_prefix',
				'wp-config.php'
			)
			: __( 'The migrations tables could not be created/updated. Migrations will not run until they are. Reach out to support if you need help.', 'tribe-common' );

		$this->container->get( Tables_Error_Notice::class )
			->show( 'tec_common_migrations_tables_error', $message, $error )
			->ifUserCan( 'manage_options' );
	}
}
