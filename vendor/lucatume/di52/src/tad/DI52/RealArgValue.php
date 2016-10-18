<?php


	class tad_DI52_RealArgValue {

		protected $value;

		public static function create( $value ) {
			$instance = new self;
			$instance->value = $value;

			return $instance;
		}

		public function getValue() {
			return $this->value;
		}
	}
