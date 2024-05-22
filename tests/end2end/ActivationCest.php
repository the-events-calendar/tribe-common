<?php

class ActivationCest {

	/**
	 * The plugin slug for Event Automator.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $plugin_slug = 'the-events-calendar-event-automator';

	/**
	 * The plugin slug for Event Tickets.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $et_plugin_slug = 'event-tickets';

	/**
	 * The plugin slug for The Events Calendar.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected static $tec_plugin_slug = 'the-events-calendar';

	/**
	 * @test
	 */
	public function should_activate_plugin( End2endTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
		$I->activatePlugin( [ static::$tec_plugin_slug, static::$plugin_slug ] );

		$I->amOnPluginsPage();
		$I->seePluginActivated( static::$tec_plugin_slug );
		$I->seePluginActivated( static::$plugin_slug );
	}

	/**
	 * @test
	 */
	public function should_activate_and_deactivate_plugin( End2endTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
		$I->activatePlugin( [ static::$tec_plugin_slug, static::$plugin_slug ] );

		$I->amOnPluginsPage();
		$I->seePluginActivated( static::$plugin_slug );
		$I->deactivatePlugin( static::$plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
	}

	/**
	 * @test
	 */
	public function should_activate_plugin_without_tec( End2endTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->deactivatePlugin( static::$tec_plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
		$I->activatePlugin( [ static::$plugin_slug ] );

		$I->amOnPluginsPage();
		$I->seePluginActivated( static::$plugin_slug );
	}

	/**
	 * @test
	 */
	public function should_activate_and_deactivate_plugin_without_tec( End2endTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->deactivatePlugin( static::$tec_plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
		$I->activatePlugin( static::$plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginActivated( static::$plugin_slug );
		$I->deactivatePlugin( static::$plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
	}

	/**
	 * @test
	 */
	public function should_activate_plugin_without_et( End2endTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->deactivatePlugin( static::$et_plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
		$I->activatePlugin( static::$plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginActivated( static::$plugin_slug );
	}

	/**
	 * @test
	 */
	public function should_activate_and_deactivate_plugin_without_et( End2endTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->deactivatePlugin( static::$et_plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
		$I->activatePlugin( static::$plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginActivated( static::$plugin_slug );
		$I->deactivatePlugin( static::$plugin_slug );

		$I->amOnPluginsPage();
		$I->seePluginDeactivated( static::$plugin_slug );
	}
}
