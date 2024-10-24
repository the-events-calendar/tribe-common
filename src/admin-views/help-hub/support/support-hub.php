<?php
/**
 * The template that displays the help hub tab on the help page.
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
 */

?>
<div class="tribe-settings-form form">
	<div class="tec-settings-form">
		<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
			<h2 class="tec-settings-form__section-header">
				<?php
				echo esc_html_x(
					'TEC Support Hub',
					'Help page Support Hub title',
					'tribe-common'
				);
				?>
			</h2>
			<p class="tec-settings-form__section-description">
				<?php
				echo esc_html_x(
					'Help on setting up, customizing and troubleshooting your calendar.',
					'Help page Support Hub header paragraph',
					'tribe-common'
				);
				?>
			</p>
		</div>
		<div class="tec-settings-form__content-section">
			<div>
				<?php
				$this->template( 'help-hub/support/iframe' );
				?>
			</div>

		</div>
	</div>
</div>
<?php
$this->template( "help-hub/support/sidebar/{$template_variant}" );
?>
