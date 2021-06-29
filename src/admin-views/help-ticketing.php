<?php
// products that should be highlighted on this page
$ticketing_products = apply_filters('tec-help-ticketing-products', [
    'event-tickets-plus',
    'tribe-eventbrite',
    'promoter',
]);

// there should only be 4 in this list
$faqs = apply_filters('tec-help-ticketing-faqs', [
    [
        'question' => __('Is The Events Calendar required?', 'tribe-common'),
        'answer' => __('Yep! The Events Calendar provides the calendar, and Community Events adds...', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can people sell tickets for submitted events?', 'tribe-common'),
        'answer' => __('Absolutely, but this function requires a few extra plugins. More info.'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I review events before they publish?', 'tribe-common'),
        'answer' => __('Yes. There’s a setting that puts all incoming submissions in draft mode.', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can people edit events after submitting them?', 'tribe-common'),
        'answer' => __('They can, as long as they’re logged in. In fact, there’s a page they can use.', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
]);

// there should only be 4 in this list
$extensions = apply_filters('tec-help-ticketing-extensions', [
    [
        'title' => __('Display Google Maps Setting', 'tribe-common'),
        'description' => __('This extension adds options for people to display a Google map on their events.', 'tribe-common'),
        'link' => '',
        'product-slug' => 'event-tickets',
    ],
    [
        'title' => __('Hide Additional Fields', 'tribe-common'),
        'description' => __('This extension prevents your custom fields from displaying in the event submission form.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-block-patterns',
        'product-slug' => 'event-tickets',
    ],
    [
        'title' => __('Hide Venues & Organizers', 'tribe-common'),
        'description' => __('This extension hides venues and organizers created during event submissions.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-alt-photo-view',
        'product-slug' => 'event-tickets',
    ],
    [
        'title' => __('Submission Form Custom HTML', 'tribe-common'),
        'description' => __('This extension allows you to add custom HTML to the top of the submission form.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-graphql',
        'product-slug' => 'event-tickets',
    ],
]);

?>
<div id="tribe-ticketing">
	<img
		class="tribe-events-admin-header__right-image"
		src="<?php echo esc_url(tribe_resource_url('images/help/help-ticketing-header.png', false, null, $main)); ?>"
	/>
	<p class="tribe-events-admin-products-description">
		<?php esc_html_e('Get help for these products and learn more about products you don\'t have.', 'tribe-common'); ?>
	</p>

	<?php // list of products?>
	<div class="tribe-events-admin-products tribe-events-admin-2col-grid">
	<?php //requires valid links for all the products ?>
		<?php $i = 0; ?>
		<?php foreach ($ticketing_products as $slug) : ?>
			<?php $i++; ?>

			<div class="tribe-events-admin-products-card">
				<img
					class="tribe-events-admin-products-card__icon"
					src="<?php echo esc_url(tribe_resource_url($products[ $slug ]['logo'], false, null, $main)); ?>"
					alt="<?php esc_attr_e('logo icon', 'tribe-common'); ?>"
				/>
				<div class="tribe-events-admin-products-card__group">
					<div class="tribe-events-admin-products-card__group-title">
						<?php echo esc_html($products[ $slug ]['title']); ?>
					</div>
					<div class="tribe-events-admin-products-card__group-description">
						<?php echo esc_html($products[ $slug ]['description-help']); ?>
					</div>
				</div>
				<?php if ($products[ $slug ]['is_installed']) : ?>
				<button class="tribe-events-admin-products-card__button tribe-events-admin-products-card__button--active">
					<?php esc_html_e('Active', 'tribe-common'); ?>
				</button>
				<?php else : ?>
					<a href="<?php echo $products[ $slug ]['link'] ?>" target="_blank" rel="noreferrer" class="tribe-events-admin-products-card__button">
						<?php esc_html_e('Learn More', 'tribe-common'); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('Start Here', 'tribe-common'); ?>
		</h3>
		
		<a href="https://evnt.is/1aq9" target="_blank" rel="noreferrer">
			<?php esc_html_e('Visit Knowledgebase', 'tribe-common'); ?><?php //requires valid link ?>
		</a>
	</div>

	<div class="tribe-events-admin-kb tribe-events-admin-3col-grid">
		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/help-start-guide-tickets.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events ticketing logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Getting Started Guides', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apn" target="_blank" rel="noreferrer">
						<?php esc_html_e('Event Tickets', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apo" target="_blank" rel="noreferrer">
						<?php esc_html_e('Calendar & Ticket Shortcodes', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1app" target="_blank" rel="noreferrer">
						<?php esc_html_e('Promoter', 'tribe-common'); ?>
					</a>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/customizing.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with Event Tickets logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Creating Tickets & RSVPs', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apq" target="_blank" rel="noreferrer">
						<?php esc_html_e('Creating Tickets', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apr" target="_blank" rel="noreferrer">
						<?php esc_html_e('Creating RSVPs', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aps" target="_blank" rel="noreferrer">
						<?php esc_html_e('Configuring Tribe Commerce', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apt" target="_blank" rel="noreferrer">
						<?php esc_html_e('Shortcodes', 'tribe-common'); ?>
					</a>
					<?php esc_html_e('(Event Tickets Plus)', 'tribe-common'); ?>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/common-issues.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events ticketing logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Plugin Maintenance', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apu" target="_blank" rel="noreferrer">
						<?php esc_html_e('Troubleshooting', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apv" target="_blank" rel="noreferrer">
						<?php esc_html_e('Release notes', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apw" target="_blank" rel="noreferrer">
						<?php esc_html_e('Integrations', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apx" target="_blank" rel="noreferrer">
						<?php esc_html_e('Automatic Updates', 'tribe-common'); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<?php // faq section?>
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('FAQs', 'tribe-common'); ?>
		</h3>
		
		<a href="https://theeventscalendar.com/products/wordpress-event-tickets/#faqs" target="_blank" rel="noreferrer">
			<?php esc_html_e('All FAQs', 'tribe-common'); ?>
		</a>
	</div>

	<div class="tribe-events-admin-faq tribe-events-admin-4col-grid">
		<?php foreach ($faqs as $faq) : ?>
			<div class="tribe-events-admin-faq-card">
				<div class="tribe-events-admin-faq-card__icon">
					<img
						src="<?php echo esc_url(tribe_resource_url('images/icons/faq.png', false, null, $main)); ?>"
						alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
					/>
				</div>
				<div class="tribe-events-admin-faq-card__content">
					<div class="tribe-events-admin-faq__question">
						<?php echo esc_html($faq['question']); ?>
					</div>
					<div class="tribe-events-admin-faq__answer">
						<?php echo esc_html($faq['answer']); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php // extensions section?>
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('Free extensions', 'tribe-common'); ?>
		</h3>
		
		<a href="https://theeventscalendar.com/extensions/" target="_blank" rel="noreferrer">
			<?php esc_html_e('All Extensions', 'tribe-common'); ?>
		</a>
	</div>

	<p class="tribe-events-admin-extensions-title">
		<?php esc_html_e('Small, lightweight WordPress plugins that add new capabilities to our core plugins. Support is not offered for extensions, but they do enhance your ticketing with bonus features.', 'tribe-common'); ?>
	</p>

	<div class="tribe-events-admin-extensions tribe-events-admin-4col-grid">
		<?php foreach ($extensions as $extension) : ?>
			<div class="tribe-events-admin-extensions-card">
				<div class="tribe-events-admin-extensions-card__title">
					<a href="<?php echo esc_html($extension['link']); ?>" target="_blank" rel="noreferrer">
						<?php echo esc_html($extension['title']); ?>
					</a>
				</div>
				<div class="tribe-events-admin-extensions-card__description">
					<?php echo esc_html($extension['description']); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>