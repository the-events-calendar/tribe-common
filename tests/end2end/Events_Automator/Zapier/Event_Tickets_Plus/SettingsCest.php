<?php

namespace TEC\Event_Automator\Zapier\Event_Tickets_Plus;

use End2endTester;

class SettingsCest {
	public function _before( End2endTester $I ) {
		// Log in as an admin.
		$I->loginAsAdmin();

		// Activate required plugins.
		$I->amOnPluginsPage();
		$I->activatePlugin(
			[
				'event-tickets',
				'event-tickets-plus',
			]
		);
	}

	/**
	 * @test
	 */
	public function should_activate_plugin( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->seePluginActivated( 'event-tickets-plus' );
		$I->amOnAdminPage('/admin.php?page=tec-tickets-settings&tab=integrations');
		$I->canSeeInPageSource( 'Zapier' );
		$I->canSeeInPageSource( 'Power Automate' );
		// 15 instances of dashboard rows with 6 for PA and 8 for Zapier, includes the header.
		$I->canSeeNumberOfElementsInDOM('//div[contains(@class, "tec-automator-grid-row")]', 15);
	}

	/**
	 * @test
	 */
	public function should_not_see_settings_when_et_plus_deactivated( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->deactivatePlugin( 'event-tickets-plus' );
		$I->amOnAdminPage('/admin.php?page=tec-tickets-settings&tab=integrations');
		$I->dontSeeInPageSource( 'Zapier' );
		$I->dontSeeInPageSource( 'Power Automate' );
		$I->dontSeeElementInDOM('//div[contains(@class, "tec-automator-grid-row")]' );
	}

	/**
	 * @test
	 */
	public function should_not_see_settings_when_et_deactivated_and_et_activated( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->deactivatePlugin( 'event-tickets' );
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'the-events-calendar' ] );
		$I->amOnAdminPage('/admin.php?page=tec-tickets-settings&tab=integrations');
		$I->dontSeeInPageSource( 'Zapier' );
		$I->dontSeeInPageSource( 'Power Automate' );
	}

	/**
	 * @test
	 */
	public function should_see_only_et_plus_settings_when_pro_active( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'the-events-calendar-pro' ] );
		$I->amOnAdminPage('/admin.php?page=tec-tickets-settings&tab=integrations');
		$I->canSeeInPageSource( 'Zapier' );
		$I->canSeeInPageSource( 'Power Automate' );
		// 15 instances of dashboard rows with 6 for PA and 8 for Zapier, includes the header.
		$I->canSeeNumberOfElementsInDOM('//div[contains(@class, "tec-automator-grid-row")]', 15);
	}

	/**
	 * @test
	 */
	public function should_see_all_settings_when_et_plus_and_pro_active( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'the-events-calendar' ] );
		$I->activatePlugin( [ 'the-events-calendar-pro' ] );
		$I->amOnAdminPage('/admin.php?page=tec-tickets-settings&tab=integrations');
		$I->canSeeInPageSource( 'Zapier' );
		$I->canSeeInPageSource( 'Power Automate' );
		// 25 instances of dashboard rows with 11 for PA and 14 for Zapier, includes the header.
		$I->canSeeNumberOfElementsInDOM('//div[contains(@class, "tec-automator-grid-row")]', 25);
	}
}
