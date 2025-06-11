<?php
/**
 * Resource Sections Helper Class
 *
 * This class provides helper methods for managing resource sections
 * in the Help Hub.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

use TEC\Common\Admin\Help_Hub\Resource_Data\Help_Hub_Data_Interface;

/**
 * Class Resource_Sections_Helper
 *
 * Provides helper methods for managing and filtering resource sections.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */
class Section_Helper {
	/**
	 * The sections array.
	 *
	 * @since 6.8.0
	 * @var array
	 */
	private array $sections;

	/**
	 * The data instance used for generating sections.
	 *
	 * @since 6.8.0
	 * @var Help_Hub_Data_Interface
	 */
	private Help_Hub_Data_Interface $data;

	/**
	 * Constructor.
	 *
	 * @since 6.8.0
	 *
	 * @param array                   $sections The sections array.
	 * @param Help_Hub_Data_Interface $data     The data instance.
	 */
	private function __construct( array $sections, Help_Hub_Data_Interface $data ) {
		$this->sections = $sections;
		$this->data     = $data;

		$this->apply_filters();
	}

	/**
	 * Creates a new instance from an array of sections.
	 *
	 * @since 6.8.0
	 *
	 * @param array                   $sections The sections array.
	 * @param Help_Hub_Data_Interface $data     The data instance.
	 *
	 * @return self
	 */
	public static function from_array( array $sections, Help_Hub_Data_Interface $data ): self {
		return new self( $sections, $data );
	}

	/**
	 * Gets the sections as an array.
	 *
	 * @since 6.8.0
	 *
	 * @return array
	 */
	public function to_array(): array {
		return $this->sections;
	}

	/**
	 * Applies filters to the sections.
	 *
	 * @since 6.8.0
	 *
	 * @return void
	 */
	private function apply_filters(): void {
		$data_class_name = get_class( $this->data );

		/**
		 * Filter the Help Hub resource sections for a specific data class.
		 *
		 * This dynamic filter allows customization of the Help Hub resource sections specific
		 * to a given data class, enabling more granular control over section customization.
		 *
		 * @since 6.3.2
		 *
		 * @param array                   $sections The array of resource sections.
		 * @param Help_Hub_Data_Interface $data     The data instance used for generating sections.
		 */
		$this->sections = apply_filters( "tec_help_hub_resource_sections_{$data_class_name}", $this->sections, $this->data );

		/**
		 * Filter the Help Hub resource sections.
		 *
		 * Allows customization of the Help Hub resource sections by other components.
		 *
		 * @since 6.3.2
		 *
		 * @param array                   $sections        The array of resource sections.
		 * @param Help_Hub_Data_Interface $data            The data instance used for generating sections.
		 * @param string                  $data_class_name The name of the data class.
		 */
		$this->sections = apply_filters( 'tec_help_hub_resource_sections', $this->sections, $this->data, $data_class_name );
	}

	/**
	 * Filters sections using a callback.
	 *
	 * @since 6.8.0
	 *
	 * @param callable $filter The filter callback.
	 *
	 * @return self
	 */
	public function filter( callable $filter ): self {
		$this->sections = array_filter( $this->sections, $filter );

		return $this;
	}

	/**
	 * Gets the number of sections.
	 *
	 * @since 6.8.0
	 *
	 * @return int
	 */
	public function count(): int {
		return count( $this->sections );
	}

	/**
	 * Checks if there are any sections.
	 *
	 * @since 6.8.0
	 *
	 * @return bool
	 */
	public function has_sections(): bool {
		return $this->count() > 0;
	}
}
