<?php
/**
* The template that displays the resources tab on the help page.
*
* @var Tribe__Main $main The main common object.
*/

$tec_icon_url     = tribe_resource_url( 'images/logo/the-events-calendar.svg', false, null, $main );
$ea_icon_url      = tribe_resource_url( 'images/logo/event-aggregator.svg', false, null, $main );
$fbar_icon_url    = tribe_resource_url( 'images/logo/filterbar.svg', false, null, $main );
$article_icon_url = tribe_resource_url( 'images/icons/file-text1.svg', false, null, $main );
$stars_icon_url   = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );

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
			<p>todo admin notice</p>
			</div>
		</div>
		<div class="tec-settings-form__content-section">
			<div class="tec-settings-form__header-block">
				<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
					<?php
					echo esc_html_x(
						'Getting started guides',
						'Getting started guide section title',
						'tribe-common'
					);
					?>
				</h3>
				<p class="tec-settings-form__section-description">
					<?php
					echo esc_html_x(
						'Easy to follow step-by-step instructions to make the most out of your calendar.',
						'Getting started guide section paragraph',
						'tribe-common'
					);
					?>
				</p>
			</div>
			<ul class="tec-help-list__list-expanded">
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $tec_icon_url ); ?>" />
					<a href="https://evnt.is/1ap9">
						<?php
						echo esc_html_x(
							'The Events Calendar',
							'The Events Calendar title',
							'tribe-common'
						);
						?>
					</a>
				</li>
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $ea_icon_url ); ?>" />
					<a href="https://evnt.is/1apc">
						<?php
						echo esc_html_x(
							'Event Aggregator',
							'Event Aggregator title',
							'tribe-common'
						);
						?>
					</a>
				</li>
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $fbar_icon_url ); ?>" />
					<a href="https://evnt.is/1apd">
						<?php
						echo esc_html_x(
							'Filter Bar',
							'Filter Bar title',
							'tribe-common'
						);
						?>
					</a>
				</li>
			</ul>
		</div>

		<div class="tec-settings-form__content-section">
			<div class="tec-settings-form__header-block">
				<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">
					<?php
					echo esc_html_x(
						'Customization guides',
						'Customization guides section title',
						'tribe-common'
					);
					?>
				</h3>
				<p class="tec-settings-form__section-description">
					<?php
					echo esc_html_x(
						'Tips and tricks on making your calendar just the way you want it.',
						'Customization guides section paragraph',
						'tribe-common'
					);
					?>
				</p>
			</div>
			<ul class="tec-help-list__list-expanded">
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
					<a href="https://evnt.is/1apf">
						<?php
						echo esc_html_x(
							'Getting started with customization',
							'Customization article',
							'tribe-common'
						);
						?>
					</a>
				</li>
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
					<a href="https://evnt.is/1apg">
						<?php
						echo esc_html_x(
							'Highlighting events',
							'Highlighting events article',
							'tribe-common'
						);
						?>
					</a>
				</li>
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
					<a href="https://evnt.is/1aph">
						<?php
						echo esc_html_x(
							'Customizing template files',
							'Customizing templates article',
							'tribe-common'
						);
						?>
					</a>
				</li>
				<li>
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
					<a href="https://evnt.is/1api">
						<?php
						echo esc_html_x(
							'Customizing CSS',
							'Customizing CSS article',
							'tribe-common'
						);
						?>
					</a>
				</li>
			</ul>
		</div>
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
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
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
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
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
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
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
					<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $article_icon_url ); ?>" />
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
							'No, but you can use event categories or tags to display certain events like having...',
							'FAQ more than one calendar answer',
							'tribe-common'
						);
						?>
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
							'Events Calendar Pro runs alongside The Events Calendar and enhances...',
							'FAQ what is in Calendar Pro answer',
							'tribe-common'
						);
						?>
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
							'Use our free Event Tickets plugin to get started with tickets and RSVPs.',
							'FAQ how to sell event tickets answer',
							'tribe-common'
						);
						?>
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
							'Our plugins include many shortcodes that do everything from embedding the calendar...',
							'FAQ where are the shortcodes answer',
							'tribe-common'
						);
						?>
					</p>
				</div>
				<h4>
					<?php
					echo esc_html_x(
						'Can I have more than one calendar?',
						'FAQ can I have multiple calendars question',
						'tribe-common'
					);
					?>
				</h4>
				<div>
					<p>
						<?php
						echo esc_html_x(
							'Can I have more than one calendar?',
							'FAQ can I have multiple calendars answer',
							'tribe-common'
						);
						?>
					</p>
				</div>
			</div>
		</div>
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
					_ex(
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

<?php $this->template('help-hub/resources-sidebar') ?>

