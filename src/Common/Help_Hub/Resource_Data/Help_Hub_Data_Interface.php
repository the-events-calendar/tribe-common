<?php
/**
 * Help Hub Data Interface.
 *
 * This interface defines methods that are required for classes supplying data to the Help Hub.
 * It specifies the methods needed to create resource sections, retrieve icon URLs, and obtain license and telemetry statuses.
 *
 * @since   TBD
 * @package TEC\Common\Help_Hub
 */

namespace TEC\Common\Help_Hub\Resource_Data;

use Tribe__Main;

/**
 * Interface Help_Hub_Data_Interface
 *
 * Defines the methods required for Help Hub data handling, ensuring consistent structure
 * for data retrieval, icons, and license status information within the Help Hub.
 *
 * @since TBD
 */
interface Help_Hub_Data_Interface {

	/**
	 * Creates and returns an array of resource sections for the Help Hub.
	 *
	 * Each section includes relevant links and content for users, helping organize
	 * resources based on topics such as "Getting Started" or "Customizations."
	 *
	 * @since TBD
	 *
	 * @return array An array of associative arrays, each representing a resource section.
	 */
	public function create_resource_sections(): array;

	/**
	 * Retrieves the URLs for required icons in the Help Hub.
	 *
	 * Accepts a `Tribe__Main` instance to generate URLs specific to The Events Calendar plugin.
	 *
	 * @since TBD
	 *
	 * @param Tribe__Main $main The main instance used to generate resource URLs.
	 *
	 * @return array An associative array of icon URLs keyed by icon type.
	 */
	public function get_icon_urls( Tribe__Main $main ): array;

	/**
	 * Gets the license validity and telemetry opt-in status.
	 *
	 * Provides an associative array indicating whether a license is valid and
	 * if the user has opted into telemetry, used for determining Help Hub access level.
	 *
	 * @since TBD
	 *
	 * @return array An associative array with 'is_license_valid' and 'is_opted_in' status.
	 */
	public function get_license_and_opt_in_status(): array;
}
