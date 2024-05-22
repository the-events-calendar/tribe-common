<?php

namespace TEC\Event_Automator\Zapier;

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
				'the-events-calendar-event-automator',
			]
		);
	}

	/**
	 * @test
	 */
	public function should_activate_plugin( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->seePluginActivated( 'the-events-calendar' );
		$I->seePluginActivated( 'the-events-calendar-event-automator' );
		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->seeElement( '.tec-settings-zapier-application__title' );
		$I->seeElement( '#tec-field-zapier_token' );
		$I->seeElement( '.tec-automator-settings__add-api-key-button' );
	}

	/**
	 * @test
	 */
	public function should_not_see_settings_when_tec_deactivated( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->deactivatePlugin( 'the-events-calendar' );

		$I->amOnAdminPage('/edit.php?page=tec-events-settings&tab=addons&post_type=tribe_events');
		$I->dontSeeElement( '.tec-settings-zapier-application__title' );
		$I->dontSeeElement( '#tec-field-zapier_token' );
		$I->dontSeeElement( '.tec-automator-settings__add-api-key-button' );
	}

	/**
	 * @test
	 */
	public function should_see_settings_when_tec_deactivated_and_et_activated( End2endTester $I ) {
		$I->amOnPluginsPage();
		$I->deactivatePlugin( 'the-events-calendar' );
		$I->amOnPluginsPage();
		$I->activatePlugin( [ 'event-tickets' ] );

		$I->amOnAdminPage('/admin.php?page=tec-tickets-settings&tab=integrations');
		$I->seeElement( '.tec-settings-zapier-application__title' );
		$I->seeElement( '#tec-field-zapier_token' );
		$I->seeElement( '.tec-automator-settings__add-api-key-button' );
	}

}
