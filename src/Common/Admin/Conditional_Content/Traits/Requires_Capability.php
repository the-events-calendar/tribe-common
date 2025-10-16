<?php
/**
 * Trait for restricting content display based on user capabilities.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */

namespace TEC\Common\Admin\Conditional_Content\Traits;

/**
 * Trait Requires_Capability
 *
 * Restricts content display to users with specific capabilities.
 * The required capability can be customized by overriding the
 * get_required_capability() method in the class using this trait.
 *
 * @since TBD
 *
 * @package TEC\Common\Admin\Conditional_Content\Traits
 */
trait Requires_Capability {
	/**
	 * Whether the promotional content requires a capability.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function requires_capability(): bool {
		return true;
	}

	/**
	 * Get the capability required to view this content.
	 *
	 * Override this method in your class to require a different capability.
	 *
	 * @since TBD
	 *
	 * @return string The capability required to view content.
	 */
	protected function get_required_capability(): string {
		return 'manage_options';
	}

	/**
	 * Check if the current user has the required capability.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the current user has the required capability.
	 */
	protected function check_capability(): bool {
		/**
		 * Filters the required capability for viewing conditional content.
		 *
		 * @since TBD
		 *
		 * @param string $capability The capability required to view content.
		 * @param object $instance   The conditional content object.
		 */
		$capability = apply_filters(
			'tec_admin_conditional_content_required_capability',
			$this->get_required_capability(),
			$this
		);

		/**
		 * Filters the result of the capability check.
		 *
		 * @since TBD
		 *
		 * @param bool   $result     The result of the capability check.
		 * @param object $instance   The conditional content object.
		 */
		return (bool) apply_filters( "tec_admin_conditional_content_{$this->slug}_check_capability", current_user_can( $capability ), $this );
	}
}
