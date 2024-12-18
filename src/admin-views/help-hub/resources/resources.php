<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var Hub         $help_hub         The Help Hub class.
 * @var string      $template_variant The template variant, determining which template to display.
 */

use TEC\Common\Admin\Help_Hub\Hub;

?>
<div class="tribe-settings-form form">
	<div class="tec-settings-form">
		<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
			<h2 class="tec-settings-form__section-header">
				<?php
				echo esc_html_x( 'Resources', 'Resources tab title', 'tribe-common' );
				?>
			</h2>
			<p class="tec-settings-form__section-description">
				<?php
				printf(
				/* translators: %1$s is the link to the Knowledgebase. */
					esc_html__( 'Help on setting up, customizing, and troubleshooting your calendar. See our %1$s for in-depth content.', 'tribe-common' ),
					'<a href="https://evnt.is/1bbw" rel="noopener noreferrer" target="_blank">' . esc_html__( 'Knowledgebase', 'tribe-common' ) . '</a>'
				);
				?>
			</p>
			<?php
			$notice_content = sprintf(
			// translators: Placeholders are for the `a` tag that displays a link.
				_x(
					'To find the answer to all your questions use the %1$sTEC Chatbot%2$s',
					'The callout notice to try the chatbot with a link to the page',
					'tribe-common'
				),
				'<a data-tab-target="tec-help-tab" href="#">',
				'</a>'
			);

			echo wp_kses( $help_hub->generate_notice_html( $notice_content, 'tec-common-help-chatbot-notice' ), 'post' );

			?>
		</div>
		<?php $this->template( 'help-hub/resources/getting-started' ); ?>
		<?php $this->template( 'help-hub/resources/customization' ); ?>
		<?php $this->template( 'help-hub/resources/common-issues' ); ?>
		<?php $this->template( 'help-hub/resources/faqs' ); ?>

		<div class="tec-settings-infobox">
			<img class="tec-settings-infobox-logo" src="<?php echo esc_url( $help_hub->get_icon_url( 'stars_icon' ) ); ?>" alt="AI Chatboat logo">
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
