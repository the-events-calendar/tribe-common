<?php
/**
 * The template that displays the iframe.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

// Define the query arguments to pass to the iframe URL.
$query_args = [
	'help_hub'         => Hub::IFRAME_PAGE_SLUG,
	'page'             => tec_get_request_var( 'page' ),
	'embedded_content' => 'true',
	'_cb'              => wp_create_nonce( 'tec_help_hub_iframe' ),
];

if ( empty( $query_args['page'] ) ) {
	return;
}
// Generate the iframe URL by appending query arguments to the admin URL.
$iframe_url = add_query_arg( $query_args, admin_url( 'admin.php' ) );
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
