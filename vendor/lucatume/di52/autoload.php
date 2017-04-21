<?php
if (!function_exists('di52_findFile')) {
	function di52_findFile($class)
	{
		if (0 !== strpos($class, 'tad_DI52_')) {
			return false;
		}

		return dirname(__FILE__) . '/src/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
	}
}

if (!function_exists('di52_autoload')) {
	function di52_autoload($class)
	{
		include di52_findFile($class);
	}
}

spl_autoload_register('di52_autoload');
