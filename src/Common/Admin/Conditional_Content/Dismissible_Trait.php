<?php
/**
 * This trait is used to dismiss conditional content in the admin.
 *
 * @since 6.3.0
 *
 * @deprecated 6.9.8 Use TEC\Common\Admin\Conditional_Content\Traits\Is_Dismissible instead.
 *
 * @package TEC\Common\Admin\Conditional_Content
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\Is_Dismissible;

_deprecated_file( __FILE__, '6.9.8', '\TEC\Common\Admin\Conditional_Content\Traits\Is_Dismissible', 'This file is deprecated in favor of new Namespaced Traits.' );

/**
 * Trait Dismissible_Trait
 *
 * @since 6.3.0
 *
 * @deprecated 6.9.8 Use TEC\Common\Admin\Conditional_Content\Traits\Is_Dismissible instead.
 *
 * @package TEC\Common\Admin\Conditional_Content
 */
trait Dismissible_Trait {
	use Is_Dismissible;
}
