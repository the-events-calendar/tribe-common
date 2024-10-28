<?php
/**
 * The template that displays the help page.
 *
 * @var Tribe__Main $main              The main common object.
 * @var array       $status            Contains the user's telemetry and license status.
 * @var array       $keys              Contains the chat keys for support services.
 * @var array       $icons             Contains URLs for various support hub icons.
 * @var array       $links             Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice            The admin notice HTML for the chatbot callout.
 * @var string      $template_variant  The template variant, determining which template to display.
 * @var array       $resource_sections An array of data to display in the Resource section.
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
	<div id="tec-help-hub-tab-containers">
		<div id="tec-help-tab" class="tec-tab-container">
			<?php $this->template( 'help-hub/support/support-hub' ); ?>
		</div>

		<div id="tec-resources-tab" class="tec-tab-container">
			<?php $this->template( 'help-hub/resources/resources' ); ?>
		</div>
	</div>
</div>

