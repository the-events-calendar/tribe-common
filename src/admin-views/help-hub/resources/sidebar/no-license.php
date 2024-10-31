<?php
/**
 * The template that displays the resources sidebar.
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
					alt="AI Chatbot logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
					<h2>
						<?php echo esc_html_x( 'Our AI Chatbot is here to help you', 'Help page resources sidebar header', 'tribe-common' ); ?>
					</h2>
					<p>
						<?php
						echo esc_html_x(
							'You have questions? The TEC Chatbot has the answers.',
							'Call to action to use The Events Calendar Help Chatbot.',
							'tribe-common'
						);
						?>
					</p>
					<p>
						<a data-tab-target="tec-help-tab" href="javascript:void(0);">
							<?php
							echo esc_html_x(
								'Talk to TEC Chatbot',
								'Link to the Help Chatbot',
								'tribe-common'
							);
							?>
						</a>
					</p>

			</div>
		</div>
	</div>

	<?php $this->template( 'help-hub/resources/shared-live-support' ); ?>
</div>
