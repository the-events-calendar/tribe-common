<?php
/**
 * RUD endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1\Contracts;

/**
 * RUD endpoint interface.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1\Contracts
 */
interface RUD_Endpoint extends
	Readable_Endpoint,
	Updatable_Endpoint,
	Deletable_Endpoint {
}
