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
	<?php
	$this->template( 'help-hub/header' );
	$this->template( 'help-hub/navigation' );
	$this->template( 'help-hub/container' );
	?>
</div>

