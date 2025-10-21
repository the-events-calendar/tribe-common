<?php
/**
 * Abstract class for conditional content.
 *
 * @since 6.3.0
 * @deprecated 6.9.8 Use TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions instead.
 *
 * @package TEC\Common\Admin\Conditional_Content
 */

namespace TEC\Common\Admin\Conditional_Content;

use TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions;

_deprecated_file( __FILE__, '6.9.8', '\TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions', 'This file is deprecated in favor of a new trait.' );

/**
 * Abstract class for conditional content.
 *
 * @since 6.3.0
 * @deprecated 6.9.8 Use TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions instead.
 */
abstract class Datetime_Conditional_Abstract {
	use Has_Datetime_Conditions {
		should_display as private should_display_from_trait;
	}

	/**
	 * Whether the content should display.
	 *
	 * @since 6.3.0
	 * @deprecated 6.9.8 Use TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions instead.
	 *
	 * @return boolean Whether the content should display.
	 */
	protected function should_display(): bool {
		_deprecated_function(
			__METHOD__,
			'6.9.8',
			'TEC\Common\Admin\Conditional_Content\Traits\Has_Datetime_Conditions::should_display()'
		);

		return $this->should_display_from_trait();
	}
}
