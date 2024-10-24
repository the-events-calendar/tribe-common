<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main             The main common object.
 * @var array       $status_values           Contains the user's telemetry and license status.
 * @var array       $keys             Contains the chat keys for support services.
 * @var array       $icons            Contains URLs for various support hub icons.
 * @var array       $links            Contains URLs for important links, like the telemetry opt-in link.
 * @var string      $notice           The admin notice HTML for the chatbot callout.
 * @var string      $template_variant The template variant, determining which template to display.
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
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $icons['article_icon_url'] ); ?>"/>
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
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $icons['article_icon_url'] ); ?>"/>
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
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $icons['article_icon_url'] ); ?>"/>
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
			<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $icons['article_icon_url'] ); ?>"/>
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
