<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var array       $status           Contains the user's telemetry and license status.
 * @var array       $keys             Contains the chat keys for support services.
 * @var array       $icons            Contains URLs for various support hub icons.
 * @var array       $links            Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice           The admin notice HTML for the chatbot callout.
 * @var string      $template_variant The template variant, determining which template to display.
 */

?>
<div class="tribe-settings-form form">
	<div class="tec-settings-form">
		<div class="tec-settings-form__content-section">
			<div class="tec-settings-form__header-block">
				<h2 class="tec-settings-form__section-header">
					<?php
					echo esc_html_x( 'Resources', 'Resources tab title', 'tribe-common' );
					?>
				</h2>
				<p class="tec-settings-form__section-description">
					<?php
					echo esc_html_x(
						'Help on setting up, customizing and troubleshooting your calendar. See our Knowledgebase for in-depth content.',
						'Overview paragraph for Resources tab',
						'tribe-common'
					);
					?>
				</p>
				<?php echo wp_kses( $notice, 'post' ); ?>
			</div>
		</div>

		<?php $this->template( 'help-hub/resources/getting-started' ); ?>

		<?php $this->template( 'help-hub/resources/customization' ); ?>

		<?php $this->template( 'help-hub/resources/common-issues' ); ?>

		<?php $this->template( 'help-hub/resources/faqs' ); ?>


		<div class="tec-settings-infobox">
			<img class="tec-settings-infobox-logo" src="<?php echo esc_url( $icons['stars_icon_url'] ); ?>" alt="AI Chatboat logo">
			<h3 class="tec-settings-infobox-title">
				<?php
				echo esc_html_x(
					'Our AI Chatbot is here to help you',
					'AI Chatbot notice title',
					'tribe-common'
				);
				?>
			</h3>
			<p>
				<?php
				echo esc_html_x(
					'You have questions? The TEC Chatbot has the answers.',
					'AI Chatbot section paragraph',
					'tribe-common'
				);
				?>
			</p>
			<p>
				<a data-tab-target="tec-help-tab" href="javascript:void(0)">
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
<?php $this->template( "help-hub/resources/sidebar/{$template_variant}" ); ?>
