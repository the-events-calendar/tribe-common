<?php
/**
 * Handles the update functionality of the Event Automator plugin.
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator
 */

namespace TEC\Common\Event_Automator;

use Tribe__PUE__Checker;
use TEC\Common\Contracts\Service_Provider;

/**
 * Class PUE
 *
 * @since 1.0.0
 *
 * @package TEC\Common\Event_Automator
 */
class PUE extends Service_Provider {

	/**
	 * The slug used for PUE.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private static $pue_slug = 'event-automator';

	/**
	 * Plugin update URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $update_url = 'https://pue.theeventscalendar.com/';

	/**
	 * PUE Instance.
	 *
	 * @since 1.6.0
	 *
	 * @var Tribe__PUE__Checker
	 */
	private $pue_instance;

	/**
	 * Registers the filters required by the Plugin Update Engine.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'event-automator.pue', $this );

		add_action( 'tribe_helper_activation_complete', [ $this, 'load_plugin_update_engine' ] );

		register_uninstall_hook( Plugin::FILE, 'tec_automator_uninstall' );
	}

	/**
	 * If the PUE Checker class exists, go ahead and create a new instance to handle
	 * update checks for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_update_engine() {
		/**
		 * Filters whether Event Automator PUE component should manage the plugin updates or not.
		 *
		 * @since 1.0.0
		 *
		 * @param bool   $pue_enabled Whether Event Automator  PUE component should manage the plugin updates or not.
		 * @param string $pue_slug    The Event Automator plugin slug used to register it in the Plugin Update Engine.
		 */
		$pue_enabled = apply_filters( 'tribe_enable_pue', true, static::get_slug() );

		if ( ! ( $pue_enabled && class_exists( 'Tribe__PUE__Checker', false ) ) ) {
			return;
		}

		$this->pue_instance = new Tribe__PUE__Checker(
			$this->update_url,
			static::get_slug(),
			[],
			plugin_basename( Plugin::FILE )
		);
	}

	/**
	 * Get the PUE slug for this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string PUE slug.
	 */
	public static function get_slug() : string {
		return static::$pue_slug;
	}
}
