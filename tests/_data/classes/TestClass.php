<?php
namespace Tribe\Common\Tests;


class TestClass {
	public static $__static_prop__;
	public static $public_set_value;
	public $__prop__;
	public $public_set_instance_value;

	public static function static_setter($value){
		static::$public_set_value = $value;
	}

	public function setter($value  ) {
		$this->public_set_instance_value = $value	;
	}

}