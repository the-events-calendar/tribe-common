<?php
/**
 * The template that displays the support hub sidebar.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

// No direct access.
defined( 'ABSPATH' ) || exit;

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
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $help_hub->get_icon_url( 'chat_icon' ) ); ?>"
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
					<a href="https://theeventscalendar.com/knowledgebase/priority-support-through-the-tec-support-hub" target="_blank" rel="noopener noreferrer">
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
</div>
