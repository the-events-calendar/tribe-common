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

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<div class="tec-settings__sidebar-inner">


		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $icons['stars_icon'] ); ?>"
					alt="AI chat bot logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
				<h2>
					<?php
					echo esc_html_x(
						'Talk to our support team',
						'Talk to support sidebar header',
						'tribe-common'
					);
					?>
				</h2>
				<p>
					<?php
					echo esc_html_x(
						'If you still need help contact us. Our Support team is available to help you out 5 days a week:',
						'Contact support paragraph',
						'tribe-common'
					);
					?>
				</p>
				<p>
					<?php
					echo esc_html_x(
						'Mon-Fri from 9:00 - 20:00 PST',
						'Support hours',
						'tribe-common'
					);
					?>
				</p>
				<p>
					<a data-open-support-chat="" href="javascript:void(0)">
						<?php
						echo esc_html_x(
							'Contact support',
							'Contact support link',
							'tribe-common'
						);
						?>
					</a>
				</p>
			</div>
		</div>
	</div>
</div>
