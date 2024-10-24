<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main              The main common object.
 * @var bool        $is_opted_in       Whether the user has opted in to telemetry.
 * @var bool        $is_license_valid  Whether the user has any valid licenses.
 * @var string      $zendesk_chat_key  The API key for the Zendesk chat integration.
 * @var string      $docblock_chat_key The API key for the DocsBot chat integration.
 * @var string      $opt_in_link       The link to the telemetry opt-in page.
 * @var string      $notice            The admin notice HTML for the chatbot callout.
 * @var string      $template_variant  The template variant, determining which template to display.
 * @var string      $stars_icon_url    The URL for the stars icon image.
 * @var string      $chat_icon_url     The URL for the chat bubble icon image.
 * @var string      $tec_icon_url      The URL for The Events Calendar logo image.
 * @var string      $ea_icon_url       The URL for the Event Aggregator logo image.
 * @var string      $fbar_icon_url     The URL for the Filter Bar logo image.
 * @var string      $article_icon_url  The URL for the article icon image.
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
			<img class="tec-settings-infobox-logo" src="<?php echo esc_url( $stars_icon_url ); ?>" alt="AI Chatboat logo">
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
