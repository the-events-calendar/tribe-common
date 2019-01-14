<?php

use DependencyTester as Tester;

class Parent_Plugin_RequirementsCest {

	/**
	 * It should not show any notice if addon requirement is same as required by parent
	 *
	 * @test
	 */
	public function should_not_show_any_notice_if_addon_requirement_is_same_as_required_by_parent( Tester $I ) {
		$main_plugin  = 'the-events-calendar/the-events-calendar.php';
		$addon_plugin = 'events-pro/events-calendar-pro.php';

		$data                 = [
			'main_class'      => 'Tribe__Events__Main',
			'addon_class'     => 'Tribe__Events__Pro__Main',
			'parent_requires' => '4.8',
			'addon_version'   => '4.8',
		];
		$plugin               = 'test-' . md5( uniqid( 'test-', true ) ) . '.php';
		$plugin_code_template = file_get_contents( codecept_data_dir( 'dependency/main_and_addon_filter.php' ) );
		$plugin_code          = str_replace( array_map( function ( $k ) {
			return '{{' . $k . '}}';
		}, array_keys( $data ) ), $data, $plugin_code_template );

		$I->haveMuPlugin( $plugin, $plugin_code );

		$I->haveOptionInDatabase('active_plugins', [ $main_plugin, $addon_plugin ]);

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		$I->dontSeeElement('.error .tribe-inactive-plugin');
	}
}
