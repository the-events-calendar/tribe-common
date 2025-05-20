<?php
/**
 * The main controller responsible for the Classy editor feature.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy;
 */

namespace TEC\Common\Classy;

use TEC\Common\Classy\Back_Compatibility\Editor;
use TEC\Common\Classy\Back_Compatibility\Editor_Utils;
use TEC\Common\Contracts\Provider\Controller as Controller_Contract;
use TEC\Common\StellarWP\Assets\Asset;
use Tribe__Date_Utils as Date_Utils;
use Tribe__Events__Main as TEC;
use Tribe__Main as Common;
use WP_Block_Editor_Context;
use WP_Post;

/**
 * Class Controller.
 *
 * @since TBD
 *
 * @package TEC\Common\Classy;
 */
class Controller extends Controller_Contract {
	/**
	 * The name of the action that will be fired when this controller has registered.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static string $registration_action = 'tec_classy_registered';

	/**
	 * The name of the constant that will be used to disable the feature.
	 * Setting it to a truthy value will disable the feature.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public const DISABLED = 'TEC_CLASSY_EDITOR_DISABLED';

	/**
	 * Returns true.
	 *
	 * The purpose of this method is to provide a uniquely identifiable method to be used in filters.
	 * This will allow removing the method hooked by this provider from filters, in place of removing
	 * a generic `__return_true` that might have been added by some other code.
	 *
	 * @since TBD
	 *
	 * @return true The boolean value `true`.
	 */
	public static function return_true(): bool {
		return true;
	}

	/**
	 * Return false.
	 *
	 * The purpose of this method is to provide a uniquely identifiable method to be used in filters.
	 * This will allow removing the method hooked by this provider from filters, in place of removing
	 * a generic `__return_false` that might have been added by some other code.
	 *
	 * @since TBD
	 *
	 * @return false The boolean value `false`.
	 */
	public static function return_false(): bool {
		return false;
	}

	/**
	 * Determines if the feature is enabled or not.
	 *
	 * Since this class `early_register` method is already filtering the `tec_using_classy_editor` template
	 * tag, this method will call the template tag to know whether it should activate or not.
	 *
	 * @since TBD
	 *
	 * @return bool Whether the feature is enabled or not.
	 */
	public function is_active(): bool {
		// The constant to disable the feature is defined and it's truthy.
		if ( defined( self::DISABLED ) && constant( self::DISABLED ) ) {
			return false;
		}

		// The environment variable to disable the feature is truthy.
		if ( getenv( self::DISABLED ) ) {
			return false;
		}

		// Read an option value to determine if the feature should be active or not.
		$active = (bool) get_option( 'tec_common_classy_editor_enabled', true );

		/**
		 * Allows filtering whether the whole Classy feature should be activated or not.
		 *
		 * Note: this filter will only apply if the disable constant or env var
		 * are not set or are set to falsy values.
		 *
		 * @since TBD
		 *
		 * @param bool $active Defaults to `true`.
		 */
		return (bool) apply_filters( 'tec_common_classy_editor_enabled', $active );
	}

	/**
	 * Binds the implementations required by the feature and hooks the controller to the actions and filters required.
	 *
	 * @since TBD
	 *
	 * @return void Bindings are registered, the controller is hooked to actions and filters.
	 */
	protected function do_register(): void {
		// Register the `editor` binding replacement for back-compatibility purposes.
		$back_compatible_editor = new Editor();
		$this->container->singleton( 'editor', $back_compatible_editor );
		// @todo move this to TEC.
		$this->container->singleton( 'events.editor', $back_compatible_editor );
		$this->container->singleton( 'events.editor.compatibility', $back_compatible_editor );
		$this->container->singleton( 'editor.utils', new Editor_Utils() );

		// Tell Common, TEC, ET and so on NOT to load blocks.
		add_filter( 'tribe_editor_should_load_blocks', [ self::class, 'return_false' ] );

		// We're using Classy editor.
		add_filter( 'tec_using_classy_editor', [ self::class, 'return_true' ] );

		add_filter( 'block_editor_settings_all', [ $this, 'filter_block_editor_settings' ], 100, 2 );

		// Register the main assets entry point.
		if ( did_action( 'tec_common_assets_loaded' ) ) {
			$this->register_assets();
		} else {
			add_action( 'tec_common_assets_loaded', [ $this, 'register_assets' ] );
		}
	}

	/**
	 * Unhooks the controller from the actions and filters required by the feature.
	 *
	 * @since TBD
	 *
	 * @return void The hooked actions and filters are removed.
	 */
	public function unregister(): void {
		// Unregister the back-compat editor and utils.
		if ( $this->container->has( 'editor' ) && $this->container->get( 'editor' ) instanceof Editor ) {
			unset( $this->container['editor'] );
			// @todo move this to TEC.
			unset( $this->container['events.editor'] );
			unset( $this->container['events.editor.compatibility'] );
		}

		if ( $this->container->has( 'editor.utils' ) && $this->container->get( 'editor.utils' ) instanceof Editor_Utils ) {
			unset( $this->container['editor.utils'] );
		}

		// Remove filters and actions.
		remove_filter( 'tribe_editor_should_load_blocks', [ self::class, 'return_false' ] );
		remove_filter( 'tec_using_classy_editor', [ self::class, 'return_true' ] );
		remove_filter( 'block_editor_settings_all', [ $this, 'filter_block_editor_settings' ], 100 );
		remove_filter( 'tec_using_classy_editor', [ self::class, 'return_true' ] );
		remove_filter( 'tribe_editor_should_load_blocks', [ self::class, 'return_false' ] );
		remove_action( 'tec_common_assets_loaded', [ $this, 'register_assets' ] );
	}

	/**
	 * Registers the assets required for the Classy app.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register_assets() {
		$post_uses_classy = fn() => $this->post_uses_classy( get_post_type() );

		Asset::add(
			'tec-classy',
			'classy.js'
		)->add_to_group_path( Common::class . '-packages' )
			->add_to_group( 'tec-classy' )
			->add_dependency( 'wp-tinymce' )
			// @todo this should be dynamic depending on the loading context.
			->enqueue_on( 'enqueue_block_editor_assets' )
			->set_condition( $post_uses_classy )
			->add_localize_script( 'tec.common.classy.data', [ $this, 'get_data' ] )
			->register();

		Asset::add(
			'tec-classy-style',
			'style-classy.css'
		)->add_to_group_path( Common::class . '-packages' )
			->add_to_group( 'tec-classy' )
			// @todo this should be dynamic depending on the loading context.
			->enqueue_on( 'enqueue_block_editor_assets' )
			->set_condition( $post_uses_classy )
			->register();
	}

	/**
	 * Returns whether the given Post uses the Classy editor.
	 *
	 * @since TBD
	 *
	 * @param string $post_type The post type to check.
	 *
	 * @return bool Whether the given Post uses the Classy editor.
	 */
	public function post_uses_classy( string $post_type ): bool {
		$supported_post_types = $this->get_supported_post_types();

		return in_array( $post_type, $supported_post_types, true );
	}

	/**
	 * Filters the Block Editor Settings for a given Post Type to lock the template.
	 *
	 * @since TBD
	 *
	 * @param array<string,string>    $settings The Block Editor settings.
	 * @param WP_Block_Editor_Context $context
	 *
	 * @return array<string,string> The updated Block Editor settings.
	 */
	public function filter_block_editor_settings( array $settings, WP_Block_Editor_Context $context ) {
		if ( ! (
			$context->post instanceof WP_Post
			&& $this->post_uses_classy( $context->post->post_type )
		) ) {
			return $settings;
		}

		// Lock the template.
		$settings['templateLock'] = true;

		return $settings;
	}

	/**
	 * Returns the filtered list of Post Types that should be using the Classy editor.
	 *
	 * @since TBD
	 *
	 * @return list<string> The filtered list of Post Types that should be using the Classy editor.
	 */
	private function get_supported_post_types(): array {
		/**
		 * Filters the list of post types that use the Classy editor.
		 *
		 * @since TBD
		 *
		 * @param array<string> $supported_post_types The list of post types that use the Classy editor.
		 */
		$supported_post_types = apply_filters(
			'tec_classy_post_types',
			[ TEC::POSTTYPE ]
		);

		return (array) $supported_post_types;
	}

	/**
	 * Returns the data that is localized on the page for the Classy app.
	 *
	 * @since TBD
	 *
	 * @return array{
	 *     settings: array{
	 *          compactDateFormat: string,
	 *          dateTimeSeparator: string,
	 *          dateWithYearFormat: string,
	 *          dateWithoutYearFormat: string,
	 *          endOfDayCutoff: array {
	 *              hours: int,
	 *              minutes: int,
	 *          },
	 *          monthAndYearFormat: string,
	 *          startOfWeek: int,
	 *          timeFormat: string,
	 *          timeInterval: int,
	 *          timeRangeSeparator: string,
	 *          timezoneChoice: string,
	 *          timezoneString: string
	 *      }
	 * } The data that is localized on the page for the Classy app.
	 */
	public function get_data(): array {
		$timezone_string  = get_option( 'timezone_string' );
		$start_of_week    = get_option( 'start_of_week' );
		$multi_day_cutoff = tribe_get_option( 'multiDayCutoff', '00:00' );
		[ $multi_day_cutoff_hours, $multi_day_cutoff_minutes ] = array_replace(
			[ 0, 0 ],
			explode( ':', $multi_day_cutoff, 2 )
		);
		$date_with_year_format                                 = tribe_get_option( 'dateWithYearFormat', 'F j, Y' );
		$date_without_year_format                              = tribe_get_option( 'dateWithoutYearFormat', 'F j' );
		$month_and_year_format                                 = tribe_get_option( 'monthAndYearFormat', 'F Y' );
		$compact_date_format                                   = Date_Utils::datepicker_formats( tribe_get_option( 'datepickerFormat', 1 ) );
		$data_time_separator                                   = tribe_get_option( 'dateTimeSeparator', ' @ ' );
		$time_range_separator                                  = tribe_get_option( 'timeRangeSeparator', ' - ' );
		$time_format     = tribe_get_option( 'time_format', 'g:i a' );
		$timezone_choice = wp_timezone_choice( $timezone_string );

		/**
		 * The time interval in minutes to use when populating the time picker options.
		 *
		 * @since TBD
		 *
		 * @param int $time_interval The time interval in minutes; defaults to 15 minutes.
		 */
		$time_interval = apply_filters( 'tec_classy_time_picker_interval', 15 );

		$default_data = [
			'settings' => [
				'compactDateFormat'     => $compact_date_format,
				'dataTimeSeparator'     => $data_time_separator,
				'dateWithYearFormat'    => $date_with_year_format,
				'dateWithoutYearFormat' => $date_without_year_format,
				'endOfDayCutoff'        => [
					'hours'   => min( 23, (int) $multi_day_cutoff_hours ),
					'minutes' => min( 59, (int) $multi_day_cutoff_minutes ),
				],
				'monthAndYearFormat'    => $month_and_year_format,
				'startOfWeek'           => $start_of_week,
				'timeFormat'            => $time_format,
				'timeInterval'          => $time_interval,
				'timeRangeSeparator'    => $time_range_separator,
				'timezoneChoice'        => $timezone_choice,
				'timezoneString'        => $timezone_string,
			],
		];

		/**
		 * Filter the data that will be localized on the page for the Classy application.
		 *
		 * @since TBD
		 *
		 * @param array<string,mixed> $data The localized data for Classy.
		 */
		$filtered_data = apply_filters( 'tec_classy_localized_data', $default_data );

		if ( ! is_array( $filtered_data ) ) {
			return $default_data;
		}

		return $filtered_data;
	}
}
