add_filter('tribe_register_{{parent_class}}_plugin_dependencies', 'test_filter_main_version');
function test_filter_main_version( array $dependencies ){
	$dependencies['addon-dependencies']['{{addon_class}}'] = '{{parent_requires}}';

	return $dependencies;
}

add_filter('tribe_register_{{addon_class}}_plugin_version', 'test_filter_addon_version');
function test_filter_addon_version( $version ){
	return '{{addon_version}}';
}
