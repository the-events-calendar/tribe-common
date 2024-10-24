<?php
/**
 * This template file generates the iframe URL for the Help Hub page, adds a loading spinner, and embeds the iframe within a container.
 * The iframe content is loaded from the admin URL with specific query parameters.
 *
 * @package Tribe_Events
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
	<iframe src="<?php echo esc_url( $iframe_url ); ?>" frameborder="0" id="tec-settings__support-hub-iframe" class="hidden"></iframe>
</div>
