<?php
/**
 * Exception for when a service provider is already registered.
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts\Provider
 */

namespace TEC\Common\Contracts\Provider;

use Exception;

/**
 * Class AlreadyRegisteredException
 *
 * @since TBD
 *
 * @package TEC\Common\Contracts\Provider
 */
class AlreadyRegisteredException extends Exception { // phpcs:ignore StellarWP.Classes.ValidClassName.NotSnakeCase, Generic.Classes.OpeningBraceSameLine.ContentAfterBrace
	// Intentionally left empty.
}
