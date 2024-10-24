<?php

/**
 * The template that displays the resources and support hub sidebar.
 *
 * @var Tribe__Main $main             The main common object.
 * @var array       $status           Contains the user's telemetry and license status.
 * @var array       $keys             Contains the chat keys for support services.
 * @var array       $icons            Contains URLs for various support hub icons.
 * @var array       $links            Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice           The admin notice HTML for the chatbot callout.
 * @var string      $template_variant The template variant, determining which template to display.
 * @var string      $stars_icon_url   The URL for the stars icon image.
 * @var string      $chat_icon_url    The URL for the chat bubble icon image.
 */

$stars_icon_url = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );
$chat_icon_url  = tribe_resource_url( 'images/icons/chat-bubble.svg', false, null, $main );

?>

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<div class="tec-settings__sidebar-inner">
		<h2>
			<?php
			echo esc_html_x(
				'Our TEC support hub now offers an improved help experience',
				'Help page resources sidebar header',
				'tribe-common'
			);
			?>
		</h2>
		<p>
			<?php
			echo esc_html_x(
				'Our Help page is better than ever with the addition of:',
				'Describes why consent is beneficial',
				'tribe-common'
			);
			?>
		</p>

		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $stars_icon_url ); ?>"
					alt="AI chat bot logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h3>
					<?php
					echo esc_html_x(
						'AI Chatbot',
						'AI Chatbot sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					echo esc_html_x(
						'Here to provide quick answers to your questions. It’s never been easier to find the right resource.',
						'AI Chatbot support sidebar paragraph',
						'tribe-common'
					);
					?>
				</p>
			</div>
		</div>
		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $chat_icon_url ); ?>"
					alt="Support chat logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h3>
					<?php
					echo esc_html_x(
						'In-app priority live support',
						'Get support sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					echo esc_html_x(
						'Get access to our agents or generate a support ticket from right here.',
						'Live support sidebar paragraph',
						'tribe-common'
					);
					?>
				</p>
			</div>
		</div>
		<p>
			<?php
			echo esc_html_x(
				'To enhance your experience, we require your consent to collect and share some of your website’s data with our AI chatbot. ',
				'Opt in sidebar paragraph',
				'tribe-common'
			);
			?>
		</p>
		<p>
			<a class="button-secondary" href="<?php echo esc_url( $opt_in_link ); ?>">
				<?php
				echo esc_html_x(
					'Manage my data sharing consent',
					'Button to manage opt in status',
					'tribe-common'
				);
				?>
			</a>
		</p>
	</div>
</div>
