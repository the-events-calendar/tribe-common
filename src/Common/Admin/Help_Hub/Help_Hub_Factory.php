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

use TEC\Common\Admin\Help_Hub\Resource_Data\TEC_Hub_Resource_Data;
use InvalidArgumentException;
use Tribe__Template;
use TEC\Common\Configuration\Configuration;

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
	 * @since TBD
	 *
	 * @param Configuration   $config   The Zendesk support key.
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
	 * based on the provided `$type`. Throws an exception for unrecognized types.
	 *
	 * @since TBD
	 *
	 * @param string $type The type of data configuration needed for the Help Hub.
	 *                     Accepts 'tec_events' or 'event_tickets'.
	 *
	 * @return Hub Configured instance of Help Hub.
	 * @throws InvalidArgumentException If an unknown type is provided.
	 */
	public function create( string $type ): Hub {
		switch ( $type ) {
			case 'tec_events':
				$help_hub = new Hub( new TEC_Hub_Resource_Data(), $this->config, $this->template );
				break;

			case 'event_tickets':
				// Todo - Introduce an ET Hub_Resource_Data class in the future.
				break;

			default:
				throw new InvalidArgumentException( "Unknown HelpHub type: {$type}" );
		}

		return $help_hub;
	}
}
