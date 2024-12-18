<?php
/**
 * Help Hub Data Interface.
 *
 * This interface defines methods that are required for classes supplying data to the Help Hub.
 * It specifies the methods needed to create resource sections, retrieve icon URLs, and obtain license and telemetry statuses.
 *
 * @since   6.3.2
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub\Resource_Data;

/**
 * Interface Help_Hub_Data_Interface
 *
 * Defines the methods required for Help Hub data handling, ensuring consistent structure
 * for data retrieval, icons, and license status information within the Help Hub.
 *
 * @since 6.3.2
 */
interface Help_Hub_Data_Interface {

	/**
	 * Registers hooks for the Help Hub Resource Data class.
	 *
	 * This method registers filters and actions required for the Help Hub.
	 *
	 * @since 6.3.2
	 *
	 * @return void
	 */
	public function add_hooks(): void;

	/**
	 * Creates and returns an array of resource sections for the Help Hub.
	 *
	 * Each section includes relevant links and content for users, helping organize
	 * resources based on topics such as "Getting Started" or "Customizations."
	 *
	 * @since 6.3.2
	 *
	 * @return array An array of associative arrays, each representing a resource section.
	 */
	public function create_resource_sections(): array;

	/**
	 * Retrieves the URL for a specified icon.
	 *
	 * @since 6.3.2
	 *
	 * @param string $icon_name The name of the icon to retrieve.
	 *
	 * @return string The URL of the specified icon, or an empty string if the icon does not exist.
	 */
	public function get_icon_url( string $icon_name ): string;

	/**
	 * Gets the license validity and telemetry opt-in status.
	 *
	 * Provides an associative array indicating whether a license is valid and
	 * if the user has opted into telemetry, used for determining Help Hub access level.
	 *
	 * @since 6.3.2
	 *
	 * @return array An associative array with 'has_valid_license' and 'is_opted_in' status.
	 */
	public function get_license_and_opt_in_status(): array;
}
