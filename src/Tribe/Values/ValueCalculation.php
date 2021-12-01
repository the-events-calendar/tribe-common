<?php

namespace Tribe\Values;

trait ValueCalculation {

	public function sub_total( $multiplier ) {
		$this->set_value( $this->get_integer() * $multiplier );
		return $this;
	}

	public function total( $values ) {
		$num = array_map( function( $obj ) {
			return $obj->get_float();
		}, $values );

		$num[] = $this->get_float();

		$this->set_value( array_sum( $num ) );

		return $this;
	}

	public function sum( $amounts ) {
	}

	public function multiply( $quantity ) {
	}

	public function to_integer( $value ) {
		return (int) ( round( $value, $this->precision ) * pow( 10, $this->precision ) );
	}
}