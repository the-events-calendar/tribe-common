<?php

namespace Tribe\Values;

interface Value_Interface {

	public function get_integer();

	public function get_float();

	public function get_precision();

	public function sum( $amounts );

	public function multiply( $quantity );

	public function normalize( $amount );

	public function set_value( $amount );
}