<?php
/**
 * The template that displays the resources sidebar.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

?>

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<div class="tec-settings-form__sidebar-section">
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
					src="<?php echo esc_url( $help_hub->get_icon_url( 'stars_icon' ) ); ?>"
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
					src="<?php echo esc_url( $help_hub->get_icon_url( 'chat_icon' ) ); ?>"
					alt="Support chat logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h3>
					<?php
					echo esc_html_x(
						'Talk to our support team',
						'Get support sidebar header',
						'tribe-common'
					);
					?>
				</h3>
				<p>
					<?php
					echo esc_html_x(
						'Our Support team is available to help you out 5 days a week:',
						'Live support sidebar paragraph',
						'tribe-common'
					);
					?>
				</p>
				<p>
					<strong>
						<?php
						echo esc_html_x(
							'Mon-Fri from 9:00 - 20:00 PST',
							'Live support hours',
							'tribe-common'
						);
						?>
					</strong>
				</p>
			</div>
		</div>
	</div>
</div>
