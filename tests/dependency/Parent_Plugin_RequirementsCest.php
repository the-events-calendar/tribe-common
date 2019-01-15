<?php

use DependencyTester as Tester;

class Parent_Plugin_RequirementsCest {

	/**
	 * It should not show any notice if addon requirement is same as required by parent
	 *
	 * @test
	 */
	public function should_not_show_any_notice_if_addon_requirement_is_same_as_required_by_parent( Tester $I ) {
		$parent_plugin = 'the-events-calendar/the-events-calendar.php';
		$addon_plugin  = 'events-pro/events-calendar-pro.php';

		$test_plugin = $I->have_plugin_with_template_and_data( 'main_and_addon_filter', [
			'parent_class'    => 'Tribe__Events__Main',
			'addon_class'     => 'Tribe__Events__Pro__Main',
			'parent_requires' => '4.8',
			'addon_version'   => '4.8',
		] );

		$I->set_active_plugins( [ $test_plugin, $parent_plugin, $addon_plugin ] );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		$I->dontSeeElement( '.error .tribe-inactive-plugin' );
	}

	/**
	 * It should show a notice if addon version is lower than required by parent
	 *
	 * @test
	 */
	public function should_show_a_notice_if_addon_version_is_lower_than_required_by_parent( Tester $I ) {
		$parent_plugin = 'the-events-calendar/the-events-calendar.php';
		$addon_plugin  = 'events-pro/events-calendar-pro.php';

		$test_plugin = $I->have_plugin_with_template_and_data( 'main_and_addon_filter', [
			'parent_class'    => 'Tribe__Events__Main',
			'addon_class'     => 'Tribe__Events__Pro__Main',
			'parent_requires' => '4.8',
			'addon_version'   => '4.7',
		] );

		$I->set_active_plugins( [ $test_plugin, $parent_plugin, $addon_plugin ] );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		$I->seeElement( '.error .tribe-inactive-plugin' );
	}
}
