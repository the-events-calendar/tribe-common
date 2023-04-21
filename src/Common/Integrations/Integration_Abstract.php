<?php
/**
 * Abstract for Integrations.
 *
 * @since   TBD
 *
 * @package TEC\Common\Integrations
 */
namespace TEC\Common\Integrations;

/**
 * Class Integration_Abstract
 *
 * @since   TBD
 *
 * @link  https://docs.theeventscalendar.com/apis/integrations/including-new-integrations/
 *
 * @package TEC\Common\Integrations
 */
abstract class Integration_Abstract extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		// Registers this provider as a singleton for ease of use.
		$this->container->singleton( self::class, self::class );

		// Prevents any loading in case we shouldn't load.
		if ( ! $this->should_load() ) {
			return;
		}

		$this->load();
	}

	/**
	 * Gets the slug for this integration.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	abstract public static function get_slug(): string;

	/**
	 * Determines whether this integration should load.
	 *
	 * @since  TBD
	 *
	 * @return bool
	 */
	public function should_load(): bool {
		return $this->filter_should_load( $this->load_conditionals() );
	}

	/**
	 * Filters whether the integration should load.
	 *
	 * @since TBD
	 *
	 * @param bool $value Whether the integration should load.
	 *
	 * @return bool
	 */
	protected function filter_should_load( bool $value ): bool {
		$slug = static::get_slug();
		$type = static::get_type();

		/**
		 * Filters if integrations should be loaded.
		 *
		 * @since TBD
		 *
		 * @param bool   $value Whether the integration should load.
		 * @param string $type  Type of integration we are loading.
		 * @param string $slug  Slug of the integration we are loading.
		 */
		$value = apply_filters( 'tec_integrations_should_load', $value, $type, $slug );

		/**
		 * Filters if integrations of the current type should be loaded.
		 *
		 * @since TBD
		 *
		 * @param bool   $value Whether the integration should load.
		 * @param string $slug  Slug of the integration we are loading.
		 */
		$value = apply_filters( "tec_integrations_{$type}_should_load", $value, $slug );

		/**
		 * Filters if a specific integration (by type and slug) should be loaded.
		 *
		 * @since TBD
		 *
		 * @param bool $value Whether the integration should load.
		 */
		return (bool) apply_filters( "tec_integrations_{$type}_{$slug}_should_load", $value );
	}

	/**
	 * Determines if the integration in question should be loaded.
	 *
	 * @since  TBD
	 *
	 * @return bool
	 */
	abstract public function load_conditionals(): bool;

	/**
	 * Loads the integration itself.
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	abstract protected function load(): void;

	/**
	 * Determines the integration type.
	 *
	 * @since  TBD
	 *
	 * @return string
	 */
	abstract public static function get_type(): string;
}
