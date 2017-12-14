<?php


class autoloadTest extends PHPUnit_Framework_TestCase
{

	public function projectClasses()
	{
		$classes = glob(dirname(dirname(dirname(__FILE__))) . '/src/tad/DI52/*.php');
		$data = array();
		foreach ($classes as $class) {
			$name = 'tad_DI52_' . basename($class, '.php');
			if ($name === 'closuresSupport') {
				continue;
			}
			$data[] = array($name, $class);
		}

		return $data;
	}

	/**
	 * Autoload classes
	 *
	 * @dataProvider projectClasses
	 */
	public function test_autoload_classes($className, $classPath)
	{
		include_once dirname(dirname(dirname(__FILE__))) . '/autoload.php';
		$this->assertEquals($classPath, di52_findFile($className));
	}
}
