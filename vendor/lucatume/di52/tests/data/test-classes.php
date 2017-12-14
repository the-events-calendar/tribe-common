<?php

interface One {

}

interface Two {

}

interface Four {

}

interface Five {

}

class ClassOne implements One {

}

class ExtendingClassOneOne extends ClassOne {

}

class ExtendingClassOneTwo extends ClassOne {

}

class ClassOneOne implements One {
	public function __construct() {

	}
}

class ClassOneTwo implements One {
	/**
	 * @var string
	 */
	private $foo;

	public function __construct($foo = 'bar') {

		$this->foo = $foo;
	}

	/**
	 * @return string
	 */
	public function getFoo() {
		return $this->foo;
	}
}

class ClassOneThree {
	public $oneCalled;
	public $twoCalled;

	public function methodOne() {
		$this->oneCalled = true;
	}

	public function methodTwo() {
		$this->twoCalled = true;
	}
}

class ClassTwo implements Two {
	/**
	 * @var One
	 */
	private $one;

	public function __construct(One $one) {

		$this->one = $one;
	}

	/**
	 * @return One
	 */
	public function getOne() {
		return $this->one;
	}
}

class ClassTwoOne implements Two {
	private $one;

	public function __construct(ClassOne $one) {

		$this->one = $one;
	}

	public function getOne() {
		return $this->one;
	}
}

class ClassTwoTwo implements Two {
	public function __construct(One $one) {

	}
}

class ClassThree {
	public function __construct(One $one, Two $two, $three = 3) {

	}
}

class ClassThreeOne {
	public function __construct(One $one, ClassTwo $two, $three = 3) {

	}
}

class ClassThreeTwo {
	public function __construct(ClassOne $one, ClassOneOne $two, $three = 3) {

	}
}

class ClassFour {
	public function __construct($some) {

	}

	public function methodOne($n) {
		return $n + 23;
	}

	public function methodTwo() {
		return 23;
	}
}

class FourBase implements Four {
	public function __construct() {

	}

	public function methodOne() {
		global $one;
		$one = __CLASS__;
	}

	public function methodTwo() {
		global $two;
		$two = __CLASS__;
	}

	public function methodThree($n) {
		return $n + 23;
	}
}

class FourTwo implements Four {

}

class FourDecoratorOne implements Four {
	public function __construct(Four $decorated) {

	}

	public function methodOne($n) {
		return $n + 23;
	}
}

class FourDecoratorTwo implements Four {
	public function __construct(Four $decorated) {

	}
}

class FourDecoratorThree implements Four {
	public function __construct(Four $decorated) {

	}
}

class FiveBase implements Five {
	public function __construct($foo = 10) {
	}
}

class FiveDecoratorOne implements Five {
	public function __construct(Five $five, Four $four) {

	}
}

class FiveDecoratorTwo implements Five {
	public function __construct(Five $five, One $one) {

	}
}

class FiveDecoratorThree implements Five {
	public function __construct(Five $five, Two $two) {

	}
}

class FiveTwo implements Five {

}


class ClassSix {
	private $one;

	public function __construct(One $one) {
		$this->one = $one;
	}

	public function getOne() {
		return $this->one;
	}
}

class ClassSeven {
	private $one;

	public function __construct(One $one) {

		$this->one = $one;
	}

	public function getOne() {
		return $this->one;
	}
}

class ClassSixOne {
	private $one;

	public function __construct(ClassOne $one) {
		$this->one = $one;
	}

	public function getOne() {
		return $this->one;
	}
}

class ClassSevenOne {
	private $one;

	public function __construct(ClassOne $one) {

		$this->one = $one;
	}

	public function getOne() {
		return $this->one;
	}
}

interface Eight {
	public function methodOne();

	public function methodTwo();

	public function methodThree();
}

class ClassEight implements Eight {
	public static $called = array();
	public static $calledWith = array();

	public static function reset() {
		self::$called = array();
		self::$calledWith = array();
	}

	public function methodOne() {
		self::$called[] = 'methodOne';
	}

	public function methodTwo() {
		self::$called[] = 'methodTwo';
	}

	public function methodThree() {
		self::$called[] = 'methodThree';
	}

	public function methodFour() {
		self::$calledWith = func_get_args();
	}
}

class ClassEightExtension extends ClassEight {
}

class ClassNine {
	public function __construct() {

	}

	public static function reset() {
		unset($GLOBALS['nine']);
	}

	public function methodOne() {
		$GLOBALS['nine'] = 'called';
	}
}

class ClassTen {
	public static $builtTimes = 0;
	private $varOne;
	private $varTwo;
	private $varThree;

	public static function reset() {
		self::$builtTimes = 0;
	}

	public function __construct($varOne, $varTwo, $varThree) {
		self::$builtTimes++;
		$this->varOne = $varOne;
		$this->varTwo = $varTwo;
		$this->varThree = $varThree;
	}

	public function getVarOne() {
		return $this->varOne;
	}

	public function getVarTwo() {
		return $this->varTwo;
	}

	public function getVarThree() {
		return $this->varThree;
	}
}

class ClassEleven {
	public static $builtTimes = 0;
	private $varOne;
	private $varTwo;
	private $varThree;

	public static function reset() {
		self::$builtTimes = 0;
	}

	public function __construct(One $varOne, ClassTwo $varTwo, $varThree) {
		self::$builtTimes++;
		$this->varOne = $varOne;
		$this->varTwo = $varTwo;
		$this->varThree = $varThree;
	}

	public function getVarOne() {
		return $this->varOne;
	}

	public function getVarTwo() {
		return $this->varTwo;
	}

	public function getVarThree() {
		return $this->varThree;
	}
}

class ClassTwelve {
	public static $builtTimes = 0;
	private $varOne;

	public static function reset() {
		self::$builtTimes = 0;
	}

	public function __construct(One $varOne) {
		self::$builtTimes++;
		$this->varOne = $varOne;
	}

	public function getVarOne() {
		return $this->varOne;
	}
}

class Factory {
	public function build() {
		return new ClassOne();
	}
}

class ClassThirteen {
	public function doSomething() {
		return 'IDidSomething';
	}
}

class ClassFourteen {
}

class ClassFifteen {
	public function __construct(One $one, ClassFourteen $fourteen) {

	}

	public function doSomething() {
		return 'IDidSomething';
	}
}

class Dependency {}

class Depending {
	private $dependency;

	public function __construct( Dependency $dependency ) {
		$this->dependency = $dependency;
	}

	public function getDependency() {
		return $this->dependency;
	}
}
