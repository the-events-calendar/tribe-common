<?php

namespace Tribe\Values;

trait ValueCalculation {

	public function sub_total( $multiplier ) {
		$this->set_value( $this->get_integer() * $multiplier );
		return $this;
	}

	public function sum( $amounts ) {
	}

	public function multiply( $quantity ) {
	}

	public function to_integer( $value ) {
		return round( $value, $this->precision ) * pow( 10, $this->precision );
	}
}