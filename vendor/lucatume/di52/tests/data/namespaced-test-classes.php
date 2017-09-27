<?php

namespace Acme;

interface One
{

}

interface Two
{

}

class ClassOne implements One
{

}

class ClassOneOne implements One
{
	public function __construct()
	{

	}
}

class ClassOneTwo implements One
{
	/**
	 * @var string
	 */
	private $foo;

	public function __construct($foo = 'bar')
	{

		$this->foo = $foo;
	}

	/**
	 * @return string
	 */
	public function getFoo()
	{
		return $this->foo;
	}
}

class ClassTwo implements Two
{
	/**
	 * @var One
	 */
	private $one;

	public function __construct(One $one)
	{

		$this->one = $one;
	}

	/**
	 * @return One
	 */
	public function getOne()
	{
		return $this->one;
	}
}

class ClassTen
{
	public static $builtTimes = 0;
	private $varOne;
	private $varTwo;
	private $varThree;

	public static function reset()
	{
		self::$builtTimes = 0;
	}

	public function __construct($varOne, $varTwo, $varThree)
	{
		self::$builtTimes++;
		$this->varOne = $varOne;
		$this->varTwo = $varTwo;
		$this->varThree = $varThree;
	}

	public function getVarOne()
	{
		return $this->varOne;
	}

	public function getVarTwo()
	{
		return $this->varTwo;
	}

	public function getVarThree()
	{
		return $this->varThree;
	}
}

class ClassEleven
{
	public static $builtTimes = 0;
	private $varOne;
	private $varTwo;
	private $varThree;

	public static function reset()
	{
		self::$builtTimes = 0;
	}

	public function __construct(One $varOne, ClassTwo $varTwo, $varThree)
	{
		self::$builtTimes++;
		$this->varOne = $varOne;
		$this->varTwo = $varTwo;
		$this->varThree = $varThree;
	}

	public function getVarOne()
	{
		return $this->varOne;
	}

	public function getVarTwo()
	{
		return $this->varTwo;
	}

	public function getVarThree()
	{
		return $this->varThree;
	}
}

class ClassTwelve
{
	public static $builtTimes = 0;
	private $varOne;

	public static function reset()
	{
		self::$builtTimes = 0;
	}

	public function __construct(One $varOne)
	{
		self::$builtTimes++;
		$this->varOne = $varOne;
	}

	public function getVarOne()
	{
		return $this->varOne;
	}
}

class Factory {
	public function build() {
		return new ClassOne();
	}
}
