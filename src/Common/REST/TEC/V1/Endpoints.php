<?php
/**
 * Endpoints Controller class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1
 */

declare( strict_types=1 );

namespace TEC\Common\REST\TEC\V1;

use TEC\Common\REST\TEC\V1\Endpoints\OpenApiDocs;
use TEC\Common\REST\TEC\V1\Contracts\Endpoint_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Definition_Interface;
use TEC\Common\REST\TEC\V1\Contracts\Tag_Interface;
use TEC\Common\REST\TEC\V1\Abstracts\Endpoints_Controller;
use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Definition;
use TEC\Common\REST\TEC\V1\Documentation\OpenApi_Path_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Date_Details_Definition;
use TEC\Common\REST\TEC\V1\Documentation\TEC_Post_Entity_Definition;
use TEC\Common\REST\TEC\V1\Documentation\TEC_Post_Entity_Request_Body_Definition;
use TEC\Common\REST\TEC\V1\Documentation\Date_Definition;
use TEC\Common\REST\TEC\V1\Tags\Common_Tag;

/**
 * Endpoints Controller class.
 *
 * @since 6.9.0
 *
 * @package TEC\Common\REST\TEC\V1
 */
class Endpoints extends Endpoints_Controller {
	/**
	 * Returns the endpoints to register.
	 *
	 * @since 6.9.0
	 *
	 * @return Endpoint_Interface[]
	 */
	public function get_endpoints(): array {
		return [
			OpenApiDocs::class,
		];
	}

	/**
	 * Returns the tags to register.
	 *
	 * @since 6.9.0
	 *
	 * @return Tag_Interface[]
	 */
	public function get_tags(): array {
		return [
			Common_Tag::class,
		];
	}

	/**
	 * Returns the definitions to register.
	 *
	 * @since 6.9.0
	 *
	 * @return Definition_Interface[]
	 */
	public function get_definitions(): array {
		return [
			OpenApi_Definition::class,
			OpenApi_Path_Definition::class,
			Date_Details_Definition::class,
			Date_Definition::class,
			TEC_Post_Entity_Definition::class,
			TEC_Post_Entity_Request_Body_Definition::class,
		];
	}
}
