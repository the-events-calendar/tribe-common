<?php
/**
 * The migrations admin screens of a site whose migrations tables could not be created.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries
 */

namespace TEC\Common\Libraries;

use TEC\Common\StellarWP\Migrations\Admin\UI;

/**
 * Class Unavailable_Migrations_UI.
 *
 * The library's screens read the migration executions table, and stellarwp/db throws on a read of a missing table.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries
 */
final class Unavailable_Migrations_UI extends UI {
	/**
	 * Renders nothing: the tables error notice already tells the administrator why no migration can run.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render_list(): void {
	}

	/**
	 * Renders nothing: the tables error notice already tells the administrator why no migration can run.
	 *
	 * @since TBD
	 *
	 * @param string $migration_id The ID of the migration to display.
	 *
	 * @return void
	 */
	public function render_single( string $migration_id ): void {
	}
}
