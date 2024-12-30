<?php
/**
 * The template that displays the resources and support hub sidebar.
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
			<a class="button-secondary" href="<?php echo esc_url( $help_hub::get_telemetry_opt_in_link() ); ?>">
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
