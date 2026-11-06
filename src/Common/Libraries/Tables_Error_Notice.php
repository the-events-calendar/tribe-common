<?php
/**
 * The admin notice for a library whose database tables could not be created or updated.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries
 */

namespace TEC\Common\Libraries;

use TEC\Common\StellarWP\AdminNotices\AdminNotice;
use TEC\Common\StellarWP\AdminNotices\AdminNotices;
use TEC\Common\StellarWP\DB\Database\Exceptions\DatabaseQueryException;

/**
 * Class Tables_Error_Notice.
 *
 * @since TBD
 *
 * @package TEC\Common\Libraries
 */
final class Tables_Error_Notice {
	/**
	 * Registers a non-dismissible error notice explaining which query failed and why.
	 *
	 * @since TBD
	 *
	 * @param string                 $id      The notice ID.
	 * @param string                 $message The plain-text explanation shown first; it is escaped on output.
	 * @param DatabaseQueryException $error   The exception thrown by the failed query.
	 *
	 * @return AdminNotice The registered notice, for the caller to add conditions to.
	 */
	public function show( string $id, string $message, DatabaseQueryException $error ): AdminNotice {
		return AdminNotices::show(
			$id,
			static function () use ( $message, $error ): string {
				// The errors are grouped by error code.
				$messages     = array_merge( ...array_values( $error->getQueryErrors() ) );
				$query_errors = implode( '<br>', array_map( 'esc_html', $messages ) );

				return sprintf(
					'<p>%1$s</p><p>%2$s</p><p><code>%3$s</code></p>',
					esc_html( $message ),
					// Translators: %s is the query error message.
					sprintf( esc_html__( 'The below query failed with the message(s): %s', 'tribe-common' ), '<code>' . $query_errors . '</code>' ),
					esc_html( $error->getQuery() )
				);
			}
		)
			->urgency( 'error' )
			->dismissible( false )
			->inline();
	}
}
