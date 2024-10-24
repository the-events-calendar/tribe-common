<?php
/**
 * The template that displays the iframe.
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
