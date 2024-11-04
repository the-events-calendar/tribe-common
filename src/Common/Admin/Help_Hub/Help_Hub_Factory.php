<?php
/**
 * Help Hub Factory.
 *
 * Responsible for creating and returning a configured instance of the Help Hub based on the specified type.
 * This class provides a standardized way to instantiate the Help Hub with the correct data configuration.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub;

use TEC\Common\Admin\Help_Hub\Resource_Data\Help_Hub_Data_Interface;
use TEC\Common\Configuration\Configuration;
use Tribe__Template;

/**
 * Class Help_Hub_Factory
 *
 * Factory class to instantiate Help Hub instances with specific data configurations.
 *
 * @since   TBD
 *
 * @package TEC\Common\Admin\Help_Hub
 */
class Help_Hub_Factory {

	/**
	 * The template class.
	 *
	 * @since TBD
	 *
	 * @var Tribe__Template
	 */
	protected Tribe__Template $template;

	/**
	 * The configuration object.
	 *
	 * @since TBD
	 *
	 * @var Configuration
	 */
	protected Configuration $config;

	/**
	 * Constructor.
	 *
	 * Sets the configuration and template dependencies.
	 *
	 * @since TBD
	 *
	 * @param Configuration   $config   The configuration object.
	 * @param Tribe__Template $template The template class.
	 */
	public function __construct( Configuration $config, Tribe__Template $template ) {
		$this->config   = $config;
		$this->template = $template;
	}

	/**
	 * Creates a new Help Hub instance configured with the provided resource data.
	 *
	 * This method initializes a new `Hub` instance using the provided resource data,
	 * configuration, and template.
	 *
	 * @since TBD
	 *
	 * @param Help_Hub_Data_Interface $resource_data An instance of the resource data class.
	 *
	 * @return Hub Configured instance of Help Hub or WP_Error if an invalid class is provided.
	 */
	public function create( Help_Hub_Data_Interface $resource_data ) {
		// Create and return the Hub instance with the provided resource data.
		return new Hub( $resource_data, $this->config, $this->template );
	}
}
