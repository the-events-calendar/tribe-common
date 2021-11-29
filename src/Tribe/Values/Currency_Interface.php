<?php

namespace Tribe\Values;

interface Currency_Interface {

	public function get_formatted();

	public function get_decimal( Abstract_Currency $amount );

	public function get_currency_code();

	public function get_currency_code_fallback();

	public function get_currency_symbol();

	public function get_currency_symbol_position();

}