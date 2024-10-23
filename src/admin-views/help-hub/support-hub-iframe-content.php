<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Template $this              The template object.
 * @var Tribe__Main     $main              The main class.
 * @var bool            $is_opted_in       Whether the user has opted in to telemetry.
 * @var bool            $is_license_valid  Whether the user has any valid licenses.
 * @var string          $zendesk_chat_key  The zendesk chat ID.
 * @var string          $docblock_chat_key The Docblock AI Key.
 * @var string          $opt_in_link       The link to opt into telemetry.
 */

$opted_in       = tribe_is_truthy( $is_opted_in ) ? '1' : '0';
$stars_icon_url = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php esc_html_e( 'Iframe Content', 'tribe-common' ); ?></title>
	<?php
	wp_head();
	?>
</head>
<body <?php body_class(); ?> data-opted-in="<?php echo esc_attr( $opted_in ); ?>">

<!-- Docsbot section-->
<div id="docsbot-widget-embed hide"></div>


<div class="iframe-opt-out-message">
	<div class="iframe-opt-out-message__image">
		<img src="<?php echo esc_url( $stars_icon_url ); ?>" alt="Stars Icon">
	</div>
	<div class="iframe-opt-out-message__content">
		<h2>Our AI Chatbot can help you find solutions quickly.</h2>
		<p>
			To enhance your experience, we require your consent to collect and share some of your website’s data with our AI chatbot.
			If you do not wish to consent, you could chat with the bot on The Events Calendar’s Knowledgebase.
		</p>
		<a target="_parent" href="<?php echo esc_url( $opt_in_link ); ?>" class="button-secondary">Manage Consent</a>
	</div>
</div>

<?php
wp_footer();
?>
</body>
</html>
