<?php

namespace TEC\Event_Automator\Zapier\Pro;

use End2endTester;

class SettingsCest {
	public function _before( End2endTester $I ) {
		// Log in as an admin.
		$I->loginAsAdmin();

		// Activate required plugins.
		$I->amOnPluginsPage();
		$I->activatePlugin(
			[
				'the-events-calendar',
				'the-events-calendar-pro',
			]
		);
	}

	/**
	 * @test
	 */
	public function should_activate_plugin( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->seePluginActivated( 'the-events-calendar' );
		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->canSeeInPageSource( 'Zapier' );
		$I->canSeeInPageSource( 'Power Automate' );
		// 13 instances of dashboard rows with 5 for PA and 8 for Zapier, includes the header.
		$I->canSeeNumberOfElementsInDOM('//div[contains(@class, "tec-automator-grid-row")]', 13);
	}

	/**
	 * @test
	 */
	public function should_not_see_settings_when_pro_deactivated( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->deactivatePlugin( 'the-events-calendar-pro' );
		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->dontSeeInPageSource( 'Zapier' );
		$I->dontSeeInPageSource( 'Power Automate' );
		$I->dontSeeElementInDOM('//div[contains(@class, "tec-automator-grid-row")]' );
	}

	/**
	 * @test
	 */
	public function should_not_see_settings_when_tec_deactivated_and_et_activated( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->deactivatePlugin( 'the-events-calendar' );
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'event-tickets' ] );
		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->dontSeeInPageSource( 'Zapier' );
		$I->dontSeeInPageSource( 'Power Automate' );
	}

	/**
	 * @test
	 */
	public function should_see_only_pro_settings_when_et_plus( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'event-tickets-plus' ] );
		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->canSeeInPageSource( 'Zapier' );
		$I->canSeeInPageSource( 'Power Automate' );
		// 13 instances of dashboard rows with 5 for PA and 8 for Zapier, includes the header.
		$I->canSeeNumberOfElementsInDOM('//div[contains(@class, "tec-automator-grid-row")]', 13);
	}

	/**
	 * @test
	 */
	public function should_see_all_settings_when_et_plus_and_pro_active( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'event-tickets' ] );
		$I->activatePlugin( [ 'event-tickets-plus' ] );
		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->canSeeInPageSource( 'Zapier' );
		$I->canSeeInPageSource( 'Power Automate' );
		// 25 instances of dashboard rows with 11 for PA and 14 for Zapier, includes the header.
		$I->canSeeNumberOfElementsInDOM('//div[contains(@class, "tec-automator-grid-row")]', 25);
	}
}
