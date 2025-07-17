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
use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Definition;
use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Path_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Cost_Details_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Date_Details_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Image_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Image_Size_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Term_Definition;
use TEC\Common\REST\TEC\V1\Documentation\TEC_Post_Entity_Definition;

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
		return [
			OpenApi_Definition::class,
			OpenApi_Path_Definition::class,
			Cost_Details_Definition::class,
			Date_Details_Definition::class,
			Image_Definition::class,
			Image_Size_Definition::class,
			Term_Definition::class,
			TEC_Post_Entity_Definition::class,
		];
	}
}
