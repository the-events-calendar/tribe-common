<?php
/**
 * The template that displays the help page.
 *
 *
 * @var Tribe__Main $main              The main common object.
 * @var bool        $is_opted_in       Whether the user has opted in to telemetry.
 * @var bool        $is_license_valid  Whether the user has any valid licenses.
 * @var string      $zendesk_chat_key  The API key for the Zendesk chat integration.
 * @var string      $docblock_chat_key The API key for the DocsBot chat integration.
 * @var string      $opt_in_link       The link to the telemetry opt-in page.
 * @var string      $notice            The admin notice HTML for the chatbot callout.
 * @var string      $template_variant  The template variant, determining which template to display.
 * @var string      $stars_icon_url    The URL for the stars icon image.
 * @var string      $chat_icon_url     The URL for the chat bubble icon image.
 */

?>
<div class="tribe_settings wrap tec-events-admin-settings">
	<h1>
		<img
			class="tribe-events-admin-title__logo"
			src="<?php echo esc_url( tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main ) ); ?>"
			alt="<?php esc_attr_e( 'The Events Calendar logo', 'the-events-calendar' ); ?>"
			role="presentation"
			id="tec-settings-logo"
		/>
		Help
	</h1>
	<nav class="tec-settings__nav-wrapper">
		<ul class="tec-nav">
			<li data-tab-target="tec-help-tab" class="tec-nav__tab tec-nav__tab--subnav-active">
				<a class="tec-nav__link">Support Hub</a>
			</li>
			<li data-tab-target="tec-resources-tab" class="tec-nav__tab">
				<a class="tec-nav__link">Resources</a>
			</li>
		</ul>
	</nav>

	<div id="tec-help-tab" class="tec-tab-container">
		<?php $this->template( 'help-hub/support/support-hub' ); ?>
	</div>

	<div id="tec-resources-tab" class="tec-tab-container">
		<?php $this->template( 'help-hub/resources/resources' ); ?>
	</div>
</div>

