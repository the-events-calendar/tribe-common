<?php
/**
 * The Controller to set up the Uplink library.
 */

namespace TEC\Common\Libraries;

use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\Libraries\Provider as Libraries_Provider;
use TEC\Common\StellarWP\Shepherd\Provider as Shepherd_Provider;
use TEC\Common\StellarWP\DB\Database\Exceptions\DatabaseQueryException;
use TEC\Common\StellarWP\Shepherd\Config;
use TEC\Common\StellarWP\AdminNotices\AdminNotices;

/**
 * Controller for setting up the Shepherd library.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\Libraries\Shepherd
 */
class Shepherd extends Controller_Contract {
	/**
	 * Register the controller.
	 *
	 * @since 6.9.0
	 */
	public function do_register(): void {
		$hook_prefix = tribe( Libraries_Provider::class )->get_hook_prefix();
		Config::set_container( $this->container );
		Config::set_hook_prefix( $hook_prefix );

		add_action( "shepherd_{$hook_prefix}_tables_error", [ $this, 'handle_tables_error' ] );

		$this->container->register( Shepherd_Provider::class );
	}

	/**
	 * Unregister the controller.
	 *
	 * @since 6.9.0
	 *
	 * @return void
	 */
	public function unregister(): void {
		$hook_prefix = tribe( Libraries_Provider::class )->get_hook_prefix();

		remove_action( "shepherd_{$hook_prefix}_tables_error", [ $this, 'handle_tables_error' ] );
	}

	/**
	 * Handle tables error.
	 *
	 * @since TBD
	 *
	 * @param DatabaseQueryException $error The error.
	 */
	public function handle_tables_error( DatabaseQueryException $error ): void {
		AdminNotices::show(
			'tec_common_shepherd_tables_error',
			function () use ( $error ) {
				?>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'Shepherd tables could not be created/updated. This is a critical issue since important functionality might be affected. Reach out to support if you need help.', 'tribe-common' ); ?></p>
					<?php // Translators: %s is the query error message. ?>
					<p><?php printf( esc_html__( 'The below query failed with the message(s): %s', 'tribe-common' ), '<code>' . esc_html( implode( '<br>', $error->getQueryErrors() ) ) . '</code>' ); ?></p>
					<p><code><?php echo esc_html( $error->getQuery() ); ?></code></p>
				</div>
				<?php
			}
		)
			->urgency( 'error' )
			->dismissible( false )
			->inline( true );
	}
}
