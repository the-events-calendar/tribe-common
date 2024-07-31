<?php
/**
 * Class to manage GDPR/CCPA privacy notice.
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 *
 * @package TEC\Event_Automator\Zapier
 */

namespace TEC\Event_Automator\Zapier;

_deprecated_file( __FILE__, '1.2.0' );

/**
 * Class Privacy_Notice
 *
 * @since 6.0.0 Migrated to Common from Event Automator
 * @deprecated 1.2.0
 *
 * @package TEC\Event_Automator\Zapier
 */
class Privacy_Notice {

	/**
	 * Renders the GDPR/CCPA privacy notice.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 1.2.0
	 */
	public function render() {
		_deprecated_function( __METHOD__, '1.2.0' );

		tribe_notice(
			'zapier-privacy-notice',
			[ $this, 'display_notice' ],
			[
				'type'    => 'warning',
				'dismiss' => 1,
				'wrap'    => 'p',
			],
			[ $this, 'should_display' ]
		);

	}

	/**
	 * This function determines whether to display the privacy notice.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 1.2.0
	 *
	 * @return boolean Whether the notice should display.
	 */
	public function should_display() {
		_deprecated_function( __METHOD__, '1.2.0' );

		// Bail if the user is not admin or cannot manage plugins
		return current_user_can( 'activate_plugins' );
	}

	/**
	 * HTML for privacy notice.
	 *
	 * @since 6.0.0 Migrated to Common from Event Automator
	 * @deprecated 1.2.0
	 *
	 * @return string The notice text.
	 */
	public function display_notice() {
		_deprecated_function( __METHOD__, '1.2.0' );

		$text = sprintf(
			/* Translators:KB article link. */
			_x(
				'Congratulations on installing Event Automator! Please read our %1$sPrivacy vs Data Automation: What You Need to Know%2$s knowledgebase article.',
				'The dismissible privacy message.',
				'tribe-common'
			),
			'<a href="https://evnt.is/1bcd" target="_blank">',
			'</a>',
		);

		return $text;
	}
}
