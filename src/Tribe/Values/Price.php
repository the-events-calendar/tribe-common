<?php

namespace Tribe\Values;

/**
 * This is what a Price class should look like when implemented in a plugin. A base implementation will be available
 * with an empty `set_up_currency_details()` method.
 */
class Price extends Abstract_Currency {

	public function set_up_currency_details() {
		$this->use_wp_currency_locale   = tribe_get_option( 'tickets-commerce-use-currency-locale', false );
		$this->currency_code            = tribe_get_option( 'tickets-commerce-currency-code', $this->get_currency_code() );
		$this->currency_symbol          = tribe_get_option( 'tickets-commerce-currency-symbol', $this->get_currency_symbol() );
		$this->currency_symbol_position = tribe_get_option( 'tickets-commerce-currency-symbol-position', $this->get_currency_symbol_position() );
	}

}