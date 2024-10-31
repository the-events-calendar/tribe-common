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
<div class="tec-settings__sidebar-inner">
	<div class="tec-settings__sidebar-icon-wrap">
		<div>
			<img class="tec-settings-infobox-logo"
				src="<?php echo esc_url( $icons['chat_icon'] ); ?>"
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
