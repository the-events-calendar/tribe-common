<?php


interface Tribe__Events__Aggregator_Mocker__Binding_Provider_Interface {

	/**
	 * Returns an array of options that should trigger the mocker as enabled.
	 *
	 * The options will be evaluated in a logic OR condition. Returning `true` in this method will always activate
	 * the provider.
	 *
	 * @return array|bool
	 */
	public static function enable_on(  );

	/**
	 * Binds mock implementations overriding the existing ones.
	 */
	public static function bind(  );
}