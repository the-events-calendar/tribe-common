<?php
/**
 * The template that displays the support hub sidebar.
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
	 * @since 6.8.2
	 */
	do_action( 'tec_conditional_content_sidebar_notice__help_hub_support' );
	?>
	<div class="tec-settings-form__sidebar-section">
		<div class="tec-settings__sidebar-icon-wrap">
			<img class="tec-settings-infobox-logo"
				src="<?php echo esc_url( $help_hub->get_icon_url( 'stars_icon' ) ); ?>"
				alt="AI chat bot logo"
			>
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
