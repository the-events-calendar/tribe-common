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

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php
			echo esc_html_x(
				'Common issues',
				'Common issues section title',
				'tribe-common'
			);
			?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php
			echo esc_html_x(
				'Having trouble? Find solutions to common issues or ask our AI Chatbot.',
				'Common issues section paragraph',
				'tribe-common'
			);
			?>
		</p>
	</div>
	<ul class="tec-help-list__list-expanded">
		<li>
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>"/>
			<a href="https://evnt.is/1apj">
				<?php
				echo esc_html_x(
					'Known issues',
					'Known issues article',
					'tribe-common'
				);
				?>
			</a>
		</li>
		<li>
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>"/>
			<a href="https://evnt.is/1apk">
				<?php
				echo esc_html_x(
					'Release notes',
					'Release notes article',
					'tribe-common'
				);
				?>
			</a>
		</li>
		<li>
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>"/>
			<a href="https://evnt.is/1apl">
				<?php
				echo esc_html_x(
					'Integrations',
					'Integrations article',
					'tribe-common'
				);
				?>
			</a>
		</li>
		<li>
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>"/>
			<a href="https://evnt.is/1apm">
				<?php
				echo esc_html_x(
					'Shortcodes',
					'Shortcodes article',
					'tribe-common'
				);
				?>
			</a>
		</li>
	</ul>
</div>
