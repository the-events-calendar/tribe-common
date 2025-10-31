<?php
/**
 * Collection endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * Collection endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface Collection_Endpoint extends
	Readable_Endpoint,
	Creatable_Endpoint,
	Updatable_Endpoint,
	Deletable_Endpoint {
		// Intentionally left blank.
}
