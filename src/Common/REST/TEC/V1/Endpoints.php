<?php
/**
 * Endpoints Controller class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Endpoints\OpenApiDocs;
use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;
use TEC\Common\REST\TEC\V1\Abstracts\Endpoints_Controller;

/**
 * Endpoints Controller class.
 *
 * @since TBD
 *
 * @package TEC\Common\REST\TEC\V1
 */
class Endpoints extends Endpoints_Controller {
	/**
	 * Returns the endpoints to register.
	 *
	 * @since TBD
	 *
	 * @return Endpoint_Interface[]
	 */
	public function get_endpoints(): array {
		return [
			OpenApiDocs::class,
		];
	}

	/**
	 * Returns the definitions to register.
	 *
	 * @since TBD
	 *
	 * @return Definition_Interface[]
	 */
	public function get_definitions(): array {
		return [];
	}
}
