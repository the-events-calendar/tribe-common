<?php
/**
 * Abstract for Custom Tables.
 *
 * @since TDB
 *
 * @package TEC\Common\Integrations
 */

namespace TEC\Common\Integrations;

use TEC\Common\StellarWP\Schema\Tables\Contracts\Table;

/**
 * Class Integration_Abstract
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations
 */
abstract class Custom_Table_Abstract extends Table {
	use Traits\Custom_Table_Query_Methods;

	/**
	 * @inheritDoc
	 */
	abstract protected function get_definition();
}
