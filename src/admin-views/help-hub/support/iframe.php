<?php
/**
 * The template that displays the iframe.
 *
 * @var Tribe__Main $main             The main common object.
 * @var array       $status_values           Contains the user's telemetry and license status.
 * @var array       $keys             Contains the chat keys for support services.
 * @var array       $icons            Contains URLs for various support hub icons.
 * @var array       $links            Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice           The admin notice HTML for the chatbot callout.
 * @var string      $template_variant The template variant, determining which template to display.
 */

// Define the query arguments to pass to the iframe URL.
$query_args = [
	'post_type'        => 'tribe_events',         // The post type for events.
	'page'             => 'tec-events-help-hub',  // The page identifier for Help Hub.
	'embedded_content' => 'true',                 // Flag to indicate this is embedded content.
];

// Generate the iframe URL by appending query arguments to the admin URL.
$iframe_url = add_query_arg( $query_args, admin_url( 'edit.php' ) );

?>
<div class="tec-settings__support-hub-iframe-container">
	<!-- Loading spinner displayed while iframe content is loading -->
	<div id="tec-settings__support-hub-iframe-loader" class="loader">
		<div class="spinner-container">
			<div class="spinner is-active"></div> <!-- WordPress spinner class -->
		</div>
	</div>

	<!-- Embed the iframe with the dynamically generated URL -->
	<iframe src="<?php echo esc_url( $iframe_url ); ?>" frameborder="0" tabindex="-1" id="tec-settings__support-hub-iframe" class="hidden"></iframe>
</div>
