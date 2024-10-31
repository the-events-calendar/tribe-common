<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Main $main             The main common object.
 * @var array       $status_values    Contains the user's telemetry and license status.
 * @var array       $keys             Contains the chat keys for support services.
 * @var array       $icons            Contains URLs for various support hub icons.
 * @var array       $links            Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice           The admin notice HTML for the chatbot callout.
 * @var string      $template_variant The template variant, determining which template to display.
 */

?>

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<?php $this->template( 'help-hub/shared-live-support' ); ?>
</div>
