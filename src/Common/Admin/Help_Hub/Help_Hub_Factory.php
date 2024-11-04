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

use WP_Error;
use TEC\Common\Configuration\Configuration;
use TEC\Events\Admin\Notice\Help_Hub\TEC_Hub_Resource_Data;
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
	 * Creates a new Help Hub instance configured with the appropriate data.
	 *
	 * This method initializes a new `Hub` instance and applies the relevant data configuration
	 * based on the provided `$type`. Returns a WP_Error for unrecognized types.
	 *
	 * @since TBD
	 *
	 * @param string $type The type of data configuration needed for the Help Hub.
	 *                     Accepts 'tec_events' or 'event_tickets'.
	 *
	 * @return Hub|WP_Error Configured instance of Help Hub or WP_Error if an unknown type is provided.
	 */
	public function create( string $type ) {
		switch ( $type ) {
			case 'tec_events':
				$help_hub = new Hub( new TEC_Hub_Resource_Data(), $this->config, $this->template );
				break;
			default:
				return new WP_Error(
					'invalid_help_hub_type',
					// translators: %s is the help hub type passed.
					sprintf( __( 'Unknown Help Hub type: %s', 'tribe-common' ), $type )
				);
		}

		return $help_hub;
	}
}
