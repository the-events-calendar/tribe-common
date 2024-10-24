<?php
/**
 * The template that displays the support hub iframe content.
 *
 * @var Tribe__Main $main             The main common object.
 * @var array       $status           Contains the user's telemetry and license status.
 * @var array       $keys             Contains the chat keys for support services.
 * @var array       $icons            Contains URLs for various support hub icons.
 * @var array       $links            Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice           The admin notice HTML for the chatbot callout.
 * @var string      $template_variant The template variant, determining which template to display.
 */

$opted_in       = tribe_is_truthy( $status['is_opted_in'] ) ? '1' : '0';
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
<div id="docsbot-widget-embed"></div>


<div class="iframe-opt-out-message hide">
	<div class="iframe-opt-out-message__image">
		<img src="<?php echo esc_url( $stars_icon_url ); ?>" alt="<?php esc_attr_e( 'Star Icon', 'tribe-common' ); ?>">
	</div>
	<div class="iframe-opt-out-message__content">
		<h2><?php esc_html_e( 'Our AI Chatbot can help you find solutions quickly.', 'tribe-common' ); ?></h2>
		<p>
			<?php esc_html_e( 'To enhance your experience, we require your consent to collect and share some of your website’s data with our AI chatbot.', 'tribe-common' ); ?>
		</p>
		<p>
			<?php
			printf(
			// translators: 1: the opening tag to the chatbot link, 2: the closing tag.
				esc_html_x(
					'If you do not wish to consent, you could chat with the bot on %1$sThe Events Calendar’s Knowledgebase%2$s.',
					'Text for opting out of chatbot and linking to The Events Calendar’s Knowledgebase',
					'tribe-common'
				),
				'<a target="_parent" href="https://theeventscalendar.com/knowledgebase/">',
				'</a>'
			);
			?>
		</p>
		<a target="_parent" href="<?php echo esc_url( $links['opt_in_link'] ); ?>" class="button-secondary"><?php esc_html_e( 'Manage Consent', 'tribe-common' ); ?></a>
	</div>
</div>


<?php
wp_footer();
?>
</body>
</html>
