<?php

namespace Tribe\Values;

trait ValueCalculation {

	public function sub_total( $multiplier ) {
		$this->set_value( $this->multiply( $multiplier ) );

		return $this;
	}

	public function total( $values ) {
		$num = array_map( function ( $obj ) {
			return $obj->get_float();
		}, $values );

		$this->set_value( $this->sum( $num ) );

		return $this;
	}

	public function sum( $values ) {
		$values[] = $this->get_float();

		return array_sum( $values );
	}

	public function multiply( $multiplier ) {
		return $this->get_float() * $multiplier;
	}

	public function to_integer( $value ) {
		return (int) ( round( $value, $this->precision ) * pow( 10, $this->precision ) );
	}
}