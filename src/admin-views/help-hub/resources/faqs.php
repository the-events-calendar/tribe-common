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

<div class="tec-settings-form__content-section">
	<div class="tec-settings-form__header-block">
		<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
			<?php
			echo esc_html_x(
				'FAQs',
				'FAQs section title',
				'tribe-common'
			);
			?>
		</h3>
		<p class="tec-settings-form__section-description">
			<?php
			echo esc_html_x(
				'Get quick answers to common questions',
				'FAQs section paragraph',
				'tribe-common'
			);
			?>
		</p>
	</div>
	<div class="tec-ui-accordion">
		<h4>
			<?php
			echo esc_html_x(
				'Can I have more than one calendar?',
				'FAQ more than one calendar question',
				'tribe-common'
			);
			?>
		</h4>
		<div>
			<p>
				<?php
				echo esc_html_x(
					'No, but you can use event categories or tags to display certain events.',
					'FAQ more than one calendar answer',
					'tribe-common'
				);
				?>
			</p>
			<p>
				<a href="https://evnt.is/1arh">
					<?php
					echo esc_html_x(
						'Learn More',
						'Link to more than one calendar article',
						'tribe-common'
					);
					?>
				</a>
			</p>
		</div>
		<h4>
			<?php
			echo esc_html_x(
				'What do I get with Events Calendar Pro?',
				'FAQ what is in Calendar Pro question',
				'tribe-common'
			);
			?>
		</h4>
		<div>
			<p>
				<?php
				echo esc_html_x(
					'Events Calendar Pro enhances The Events Calendar with additional views, powerful shortcodes, and a host of premium features.',
					'FAQ what is in Calendar Pro answer',
					'tribe-common'
				);
				?>
			</p>
			<p>
				<a href="https://evnt.is/1arj">
					<?php
					echo esc_html_x(
						'Learn More',
						'Link to what is in Calendar Pro article',
						'tribe-common'
					);
					?>
				</a>
			</p>
		</div>
		<h4>
			<?php
			echo esc_html_x(
				'How do I sell event tickets?',
				'FAQ how to sell event tickets question',
				'tribe-common'
			);
			?>
		</h4>
		<div>
			<p>
				<?php
				echo esc_html_x(
					'Get started with tickets and RSVPs using our free Event Tickets plugin.',
					'FAQ how to sell event tickets answer',
					'tribe-common'
				);
				?>
			</p>
			<p>
				<a href="https://evnt.is/1ark">
					<?php
					echo esc_html_x(
						'Learn More',
						'Link to what is in Event Tickets article',
						'tribe-common'
					);
					?>
				</a>
			</p>
		</div>
		<h4>
			<?php
			echo esc_html_x(
				'Where can i find a list of available shortcodes?',
				'FAQ where are the shortcodes question',
				'tribe-common'
			);
			?>
		</h4>
		<div>
			<p>
				<?php
				echo esc_html_x(
					'Our plugins offer a variety of shortcodes, allowing you to easily embed the calendar, display an event countdown clock, show attendee details, and much more.',
					'FAQ where are the shortcodes answer',
					'tribe-common'
				);
				?>
			</p>
			<p>
				<a href="https://evnt.is/1arl">
					<?php
					echo esc_html_x(
						'Learn More',
						'Link to the shortcodes article',
						'tribe-common'
					);
					?>
				</a>
			</p>
		</div>
	</div>
</div>
