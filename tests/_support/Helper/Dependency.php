<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\WPDb;
use Codeception\Module\WPFilesystem;

class Dependency extends \Codeception\Module {

	/**
	 * Creates a plugin, that will be removed at the end of the test, in the WordPress plugins folder.
	 *
	 * Please note that the plugin is just created, and not activated.
	 *
	 * @since 4.9
	 *
	 * @param string $template The file to use as template for the plugin code, just the name. E.g.
	 *                         "foo" for "dependency/foo.php" in the `data/dependency` folder.
	 * @param array $data The data to use to render the plugin template.
	 *
	 * @return string The name, in the `folder/file.php` format, of the created plugin.
	 * @throws \Codeception\Exception\ModuleException If the WPFileSystem module cannot be connected.
	 */
	public function have_plugin_with_template_and_data( $template, array $data ) {
		$plugin_slug          = 'test-' . md5( uniqid( 'test-', true ) );
		$test_plugin          = "{$plugin_slug}/{$plugin_slug}.php";
		$plugin_code_template = file_get_contents( codecept_data_dir( 'dependency/' . $template . '.php' ) );
		$plugin_code          = $this->render_template( $plugin_code_template, $data );

		$this->getModule('WPFilesystem')->havePlugin( $test_plugin, $plugin_code );

		return $test_plugin;
	}

	/**
	 * Renders an Handlebar-ish template using the `str_replace` function.
	 *
	 * @since 4.9
	 *
	 * @param string $template The template to render.
	 * @param array $data The data to use to render the template.
	 *
	 * @return string The rendered template.
	 */
	protected function render_template( $template, array $data ) {
		return str_replace( array_map( function ( $k ) {
			return '{{' . $k . '}}';
		}, array_keys( $data ) ), $data, $template );
	}

	/**
	 * Sets the `active_plugins` option in the database.
	 *
	 * @since 4.9
	 *
	 * @param array $active_plugins The list of active plugins.
	 *
	 * @throws \Codeception\Exception\ModuleException If the WPDb module cannot be connected.
	 */
	public function set_active_plugins( array $active_plugins ) {
		$this->getModule( 'WPDb' )->haveOptionInDatabase( 'active_plugins', $active_plugins );
	}
}
