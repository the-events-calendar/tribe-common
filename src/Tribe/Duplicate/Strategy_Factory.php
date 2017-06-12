<?php


class Tribe__Duplicate__Strategy_Factory {
	/**
	 * @param string $strategy The slug for the strategy that should be built.
	 *
	 * @return Tribe__Duplicate__Strategy__Interface
	 */
	public function make( $strategy ) {
		$map = array(
			'default' => 'Tribe__Duplicate__Strategy__Same',
			'same'    => 'Tribe__Duplicate__Strategy__Same',
			'like'    => 'Tribe__Duplicate__Strategy__Like',
		);

		if ( isset( $map[ $strategy ] ) ) {
			$strategy_class = $map[ $strategy ];
		} else {
			$strategy_class = $map['default'];
		}

		return new $strategy_class;
	}
}