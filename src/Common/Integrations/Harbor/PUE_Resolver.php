<?php
/**
 * The PUE resolver.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations\Harbor
 */

namespace TEC\Common\Integrations\Harbor;

use Tribe__Events__Pro__Main as ECP_Main;
use Tribe__Events__Filterbar__View as Filterbar_View;
use Tribe__Events__Tickets__Eventbrite__Main as Eventbrite_Main;
use Tribe__Events__Community__Main as Community_Main;
use Tribe__Tickets_Plus__Main as ETP_Main;
use Tribe__Events__Pro__PUE as ECP_PUE;
use Tribe__Events__Filterbar__PUE as Filterbar_PUE;
use Tribe__Events__Tickets__Eventbrite__PUE as Eventbrite_PUE;
use Tribe__Events__Community__PUE as Community_PUE;
use Tribe__Tickets_Plus__PUE as ETP_PUE;
use Tribe__PUE__Checker as PUE_Checker;
use ReflectionClass;

/**
 * The PUE resolver.
 *
 * @since TBD
 *
 * @package TEC\Common\Integrations\Harbor
 */
class PUE_Resolver {
	/**
	 * The PUE class map.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private const PUE_CLASS_MAP = [
		ECP_Main::class        => ECP_PUE::class,
		Filterbar_View::class  => Filterbar_PUE::class,
		Eventbrite_Main::class => Eventbrite_PUE::class,
		Community_Main::class  => Community_PUE::class,
		ETP_Main::class        => ETP_PUE::class,
	];

	/**
	 * Get the PUE from the class.
	 *
	 * @since TBD
	 *
	 * @param string $class The class.
	 *
	 * @return PUE_Checker|null
	 */
	public function get_pue_from_class( string $class ): ?PUE_Checker {
		if ( ! isset( self::PUE_CLASS_MAP[ $class ] ) ) {
			return null;
		}

		$pue_reflection = new ReflectionClass( self::PUE_CLASS_MAP[ $class ] );
		$values = $pue_reflection->getStaticProperties();
		$values['plugin_file'] = $this->get_pue_plugin_file( $class );
		$values['update_url'] = $values['update_url'] ?? 'http://theeventscalendar.com/';

		return new PUE_Checker( $values['update_url'], $values['pue_slug'], [], plugin_basename( $values['plugin_file'] ) );
	}

	/**
	 * Get the PUE plugin file from the class.
	 *
	 * @since TBD
	 *
	 * @param string $class The class.
	 *
	 * @return string
	 */
	private function get_pue_plugin_file( string $class ): string {
		if ( ! isset( self::PUE_CLASS_MAP[ $class ] ) ) {
			return '';
		}

		switch ( $class ) {
			case ECP_Main::class:
				return EVENTS_CALENDAR_PRO_FILE;
			case Filterbar_View::class:
				return TRIBE_EVENTS_FILTERBAR_FILE;
			case Eventbrite_Main::class:
				return EVENTBRITE_PLUGIN_FILE;
			case Community_Main::class:
				return EVENTS_COMMUNITY_FILE;
			case ETP_Main::class:
				return EVENT_TICKETS_PLUS_FILE;
			default:
				return '';
		}
	}
}
