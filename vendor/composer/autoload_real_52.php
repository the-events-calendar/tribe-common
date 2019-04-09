<?php

// autoload_real_52.php generated by xrstf/composer-php52

class ComposerAutoloaderInit0834d39d57e63db09a7880357a92a4be {
	private static $loader;

	public static function loadClassLoader($class) {
		if ('xrstf_Composer52_ClassLoader' === $class) {
			require dirname(__FILE__).'/ClassLoader52.php';
		}
	}

	/**
	 * @return xrstf_Composer52_ClassLoader
	 */
	public static function getLoader() {
		if (null !== self::$loader) {
			return self::$loader;
		}

		spl_autoload_register(array('ComposerAutoloaderInit0834d39d57e63db09a7880357a92a4be', 'loadClassLoader'), true /*, true */);
		self::$loader = $loader = new xrstf_Composer52_ClassLoader();
		spl_autoload_unregister(array('ComposerAutoloaderInit0834d39d57e63db09a7880357a92a4be', 'loadClassLoader'));

		$vendorDir = dirname(dirname(__FILE__));
		$baseDir   = dirname($vendorDir);
		$dir       = dirname(__FILE__);

		$map = require $dir.'/autoload_namespaces.php';
		foreach ($map as $namespace => $path) {
			$loader->add($namespace, $path);
		}

		$classMap = require $dir.'/autoload_classmap.php';
		if ($classMap) {
			$loader->addClassMap($classMap);
		}

		$loader->register(true);

//		require $vendorDir . '/symfony/polyfill-ctype/bootstrap.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/symfony/polyfill-mbstring/bootstrap.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/ramsey/array_column/src/array_column.php';
		require $vendorDir . '/wp-cli/mustangostang-spyc/includes/functions.php';
		require $vendorDir . '/wp-cli/package-command/package-command.php';
//		require $vendorDir . '/wp-cli/php-cli-tools/lib/cli/cli.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/ralouphie/getallheaders/src/getallheaders.php';
//		require $vendorDir . '/guzzlehttp/psr7/src/functions_include.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/guzzlehttp/promises/src/functions_include.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/guzzlehttp/guzzle/src/functions_include.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/illuminate/support/helpers.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/wp-cli/wp-config-transformer/src/WPConfigTransformer.php';
//		require $vendorDir . '/myclabs/deep-copy/src/DeepCopy/deep_copy.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/wp-cli/cache-command/cache-command.php';
		require $vendorDir . '/wp-cli/checksum-command/checksum-command.php';
		require $vendorDir . '/wp-cli/config-command/config-command.php';
		require $vendorDir . '/wp-cli/core-command/core-command.php';
		require $vendorDir . '/wp-cli/cron-command/cron-command.php';
		require $vendorDir . '/wp-cli/db-command/db-command.php';
//		require $vendorDir . '/wp-cli/embed-command/embed-command.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/wp-cli/entity-command/entity-command.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/wp-cli/eval-command/eval-command.php';
		require $vendorDir . '/wp-cli/export-command/export-command.php';
		require $vendorDir . '/wp-cli/extension-command/extension-command.php';
		require $vendorDir . '/wp-cli/import-command/import-command.php';
//		require $vendorDir . '/wp-cli/language-command/language-command.php'; // disabled because of PHP 5.3 syntax
		require $vendorDir . '/wp-cli/media-command/media-command.php';
		require $vendorDir . '/wp-cli/rewrite-command/rewrite-command.php';
		require $vendorDir . '/wp-cli/role-command/role-command.php';
		require $vendorDir . '/wp-cli/scaffold-command/scaffold-command.php';
		require $vendorDir . '/wp-cli/search-replace-command/search-replace-command.php';
		require $vendorDir . '/wp-cli/server-command/server-command.php';
		require $vendorDir . '/wp-cli/shell-command/shell-command.php';
		require $vendorDir . '/wp-cli/super-admin-command/super-admin-command.php';
		require $vendorDir . '/wp-cli/widget-command/widget-command.php';
//		require $vendorDir . '/lucatume/function-mocker/src/shims.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/lucatume/function-mocker/src/functions.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/lucatume/function-mocker-le/src/function-mocker-le.php'; // disabled because of PHP 5.3 syntax
//		require $vendorDir . '/lucatume/wp-browser/src/tad/WPBrowser/functions.php'; // disabled because of PHP 5.3 syntax

		return $loader;
	}
}
