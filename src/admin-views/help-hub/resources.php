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
	<div id="tec-settings-form">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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
					<a href="todo">
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

