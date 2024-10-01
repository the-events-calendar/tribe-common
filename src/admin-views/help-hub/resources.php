<?php
/**
 * The template that displays the resources tab on the help page.
 *
 * @var Tribe__Main $main The main common object.
 */

use TEC\Common\Telemetry\Telemetry;

?>
<div class="tec-row">
	<div class="tec-col">
		<div class="tribe-settings-form form">
			<div id="tec-settings-form" method="post">
				<div class="tec-settings-form__header-block tec-settings-form__header-block--horizontal">
					<h2 class="tec-settings-form__section-header">Resources</h2>
					<p class="tec-settings-form__section-description">
						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
					</p>
					<div class="notice notice-info inline is-dismissible">
						<p>
							<?php
							printf(
							// translators: Leave always a hint for translators to understand the placeholders.
								esc_attr__( 'class %1$s with paragraph and %2$s class', 'WpAdminStyle' ),
								'<code>.notice-error</code>',
								'<code>.inline</code>'
							);
							?>
						</p>
					</div>
				</div>
				<div class="tec-settings-form__content-section">
					<div class="tec-row">
						<div class="tec-col">
							<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">Getting started guides</h3>
							<p>
								Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
							</p>
						</div>
						<div class="tec-col">
							<p>img <a href="todo">The Events Calendar</a></p>
							<p>img <a href="todo">Event Aggregator</a></p>
							<p>img <a href="todo">Filter Bar</a></p>

						</div>
					</div>

				</div>
				<div class="tec-settings-form__content-section">
					<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">Customization guides</h3>
					<p class="tec-settings-form__section-description">
						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
					</p>
				</div>
				<div class="tec-settings-form__content-section">
					<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">Common issues</h3>
					<p class="tec-settings-form__section-description">
						Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
					</p>
				</div>
				<div class="tec-settings-form__content-section">
					<h3 class="tec-settings-form__section-header tec-settings-form__section-header--sub">FAQs</h3>
					<p class="tec-settings-form__section-description">
Accordion					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="tec-col">
		<div class="tec-settings-form__sidebar">
			<div class="tec-settings-form__sidebar-section tec-settings-form__sidebar-header">
				<img src="http://localhost/wp-content/plugins/the-events-calendar/src/resources/images/settings_illustration.jpg" role="presentation">		<h2 class="tec-settings-form__sidebar-header">
					Finding and extending your calendar		</h2>
			</div>

			<div class="tec-settings-form__sidebar-section">
				<div class="tribe-settings-section">
					<p class="">
						Looking for additional functionality including recurring events, custom meta, community events, ticket sales, and more?		</p>
					<a href="http://localhost/wp-admin/edit.php?post_type=tribe_events&amp;page=tribe-app-shop" class="">Check out the available add-ons.</a>		</div>
			</div>
			<div class="tec-settings-form__sidebar-section">
				<div class="tribe-settings-section">
					<h3>
						Documentation		</h3>
					<a href="http://localhost/wp-admin/edit.php?page=tec-events-settings&amp;post_type=tribe_events&amp;welcome-message-the-events-calendar=1" class="">View Welcome Page</a><br><a href="https://evnt.is/1bbv" class="" target="_blank" rel="noopener">Getting started guide</a><br><a href="https://evnt.is/1bbw" class="" target="_blank" rel="noopener">Knowledgebase</a>		</div>
			</div>
			<div class="tec-settings-form__sidebar-section">
				<div class="tribe-settings-section">
					<p class="">
						Where is my calendar?		</p>
					<a href="http://localhost/events/" class="">Right here</a>		</div>
			</div>
			<div class="tec-settings-form__sidebar-section">
				<div class="tribe-settings-section">
					<p class="">
						Having trouble?		</p>
					<a href="http://localhost/wp-admin/edit.php?post_type=tribe_events&amp;page=tec-events-help" class="">Help</a><br><a href="http://localhost/wp-admin/edit.php?post_type=tribe_events&amp;page=tec-troubleshooting" class="">Troubleshoot</a>		</div>
			</div>
		</div>
	</div>
</div>
