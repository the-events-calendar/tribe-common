<?php

namespace Tribe\Values;

interface Currency_Interface {

	public function get_currency();

	public function get_decimal();

	public function get_currency_code();

	public function get_currency_symbol();

	public function get_currency_symbol_position();

	public function set_up_currency_details();

}