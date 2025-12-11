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
	<?php
	/**
	 * Trigger the conditional content sidebar notice.
	 *
	 * @since 6.15.9
	 */
	do_action( 'tec_conditional_content_sidebar_notice__help_hub_support' );
	?>
	<div class="tec-settings-form__sidebar-section">
		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $help_hub->get_icon_url( 'stars_icon' ) ); ?>"
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
