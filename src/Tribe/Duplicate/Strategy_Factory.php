<?php

/**
 * Class Tribe__Duplicate__Strategy_Factory
 *
 * Provides built and ready to use strategies to find duplicates.
 *
 * @since TBD
 */
class Tribe__Duplicate__Strategy_Factory {
	protected $strategy_map = array(
		'default' => 'Tribe__Duplicate__Strategy__Same',
		'same'    => 'Tribe__Duplicate__Strategy__Same',
		'like'    => 'Tribe__Duplicate__Strategy__Like',
	);

	/**
	 * Builds a strategy provided a strategy slug.
	 *
	 * @param string $strategy The slug for the strategy that should be built.
	 *
	 * @return Tribe__Duplicate__Strategy__Interface
	 *
	 * @since TBD
	 */
	public function make( $strategy ) {
		/**
		 * Filters the strategy managed by the strategy factory.
		 *
		 * If a 'default' slug is not provided the first strategy class in the map will be used as default.
		 *
		 * @param array                              $strategy_map An array that maps strategy slugs to strategy classes.
		 * @param string                             $strategy     The requested strategy slug.
		 * @param Tribe__Duplicate__Strategy_Factory $this         This factory object.
		 *
		 * @since TBD
		 */
		$strategy_map = apply_filters( 'tribe_duplicate_post_strategies', $this->strategy_map, $strategy, $this );

		if ( isset( $strategy_map[ $strategy ] ) ) {
			$strategy_class = $strategy_map[ $strategy ];
		} else {
			$strategy_class = ! empty( $strategy_map['default'] )
				? $strategy_map['default']
				: reset( $strategy_map );
		}

		return class_exists( $strategy_class )
			? new $strategy_class
			: false;
	}

	/**
	 * Gets the unfiltered slug to strategy class map used by the factory.
	 *
	 * @return array
	 *
	 * @since TBD
	 */
	public function get_strategy_map(): array {
		return $this->strategy_map;
	}

	/**
	 * Sets the unfiltered slug to strategy class map used by the factory.
	 *
	 * @param array $strategy_map
	 *
	 * @since TBD
	 */
	public function set_strategy_map( array $strategy_map ) {
		$this->strategy_map = $strategy_map;
	}
}