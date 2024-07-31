<?php
/**
 * Handles the Event Automator plugin dependency manifest registration.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator
 */

namespace TEC\Event_Automator;

use Tribe__Abstract_Plugin_Register as Abstract_Plugin_Register;

/**
 * Class Plugin_Register.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator
 *
 * @see     Tribe__Abstract_Plugin_Register For the plugin dependency manifest registration.
 */
class Plugin_Register extends Abstract_Plugin_Register {
	/**
	 * The version of the plugin.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	public const VERSION = '1.7.0';

	/**
	 * Configures the base_dir property which is the path to the plugin bootstrap file.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param string $file Which is the path to the plugin bootstrap file.
	 */
	public function set_base_dir( string $file ): void {
		$this->base_dir = $file;
	}

	/**
	 * Gets the previously configured base_dir property.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string
	 */
	public function get_base_dir(): string {
		return $this->base_dir;
	}

	/**
	 * Gets the main class of the Plugin, stored on the main_class property.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @return string
	 */
	public function get_plugin_class(): string {
		return $this->main_class;
	}

	/**
	 * File path to the main class of the plugin.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string The path to the main class of the plugin.
	 */
	protected $base_dir;

	/**
	 * Alias to the VERSION constant.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string The version of the plugin.
	 */
	protected $version = self::VERSION;

	/**
	 * Fully qualified name of the main class of the plugin.
	 * Do not use the Plugin::class constant here, we need this value without loading the Plugin class.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string The main class of the plugin.
	 */
	protected $main_class = 'TEC\Event_Automator\Plugin';

	/**
	 * An array of dependencies for the plugin.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var array<string,mixed>
	 */
	protected $dependencies = [
		/*
		 * READ THIS: Because Event Automator requires EITHER ET or TEC, we have to handle them
		 *            in a weird way. So, ET and TEC version numbers are defined as separate class properties.
		 */
		'parent-dependencies' => [],
	];

	/**
	 * Required version of ET.
	 *
	 * This is separated out from $dependencies because Event Automator is an either/or dependency on TEC and ET.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $required_tec_tickets = '5.10.0';

	/**
	 * Required version of TEC.
	 *
	 * This is separated out from $dependencies because Event Automator is an either/or dependency on TEC and ET.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @var string
	 */
	protected $required_tec_events = '6.5.0';

	/**
	 * Constructor method.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	public function __construct() {
		add_filter( 'tribe_register_' . $this->main_class . '_plugin_dependencies', [ $this, 'add_et_and_tec_as_loose_dependency' ] );

		$this->load_deprecated();
	}

	/**
	 * Add ET and/or TEC as loose parent-dependency via filter instead of class property to avoid grammar errors in the notice.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 *
	 * @param array $dependencies An array of dependencies for the plugins. These can include parent, add-on and other dependencies.
	 *
	 * @return array
	 */
	public function add_et_and_tec_as_loose_dependency( $dependencies ) {
		$low_tec_tickets = null;
		$low_tec_events  = null;

		if ( ! defined( 'EVENT_TICKETS_DIR' ) && ! defined( 'TRIBE_EVENTS_FILE' ) ) {
			$low_tec_tickets = true;
			$low_tec_events  = true;
		}

		if ( defined( 'EVENT_TICKETS_DIR' ) ) {
			$low_tec_tickets = ( -1 === version_compare( \Tribe__Tickets__Main::VERSION, $this->required_tec_tickets ) );
		}

		if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
			$low_tec_events = ( -1 === version_compare( \Tribe__Events__Main::VERSION, $this->required_tec_events ) );
		}

		if ( $low_tec_tickets ) {
			$dependencies['parent-dependencies']['Tribe__Tickets__Main'] = $this->required_tec_tickets;
		}

		if ( $low_tec_events ) {
			$dependencies['parent-dependencies']['Tribe__Events__Main'] = $this->required_tec_events;
		}

		return $dependencies;
	}

	/**
	 * Load the deprecated constants.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 */
	protected function load_deprecated() {
		if ( ! defined( 'EVENT_AUTOMATOR_FILE' ) ) {
			define( 'EVENT_AUTOMATOR_FILE', __FILE__ );
		}
	}
}
