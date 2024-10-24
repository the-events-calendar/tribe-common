<?php

/**
 * The template that displays the support hub sidebar.
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

$stars_icon_url = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );
$chat_icon_url  = tribe_resource_url( 'images/icons/chat-bubble.svg', false, null, $main );

?>
<div class="tec-settings__sidebar-inner">
	<div class="tec-settings__sidebar-icon-wrap">
		<div>
			<img class="tec-settings-infobox-logo"
				src="<?php echo esc_url( $chat_icon_url ); ?>"
				alt="Support chat logo"
			>
		</div>
		<div class="tec-settings__sidebar-icon-wrap-content">
			<h2>
				<?php
				echo esc_html_x(
					'Get priority live support',
					'Get support sidebar header',
					'tribe-common'
				);
				?>
			</h2>
			<p>
				<?php
				echo esc_html_x(
					'You can get live support from The Events Calendar team if you have an active license for one of our products.',
					'Live support sidebar paragraph',
					'tribe-common'
				);
				?>
			</p>
			<p>
				<a href="todo">
					<?php
					echo esc_html_x(
						'Learn how to get live support',
						'Live support sidebar link to article',
						'tribe-common'
					);
					?>
				</a>
			</p>
		</div>
	</div>
</div>
