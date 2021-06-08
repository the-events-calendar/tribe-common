<?php
// products that should be highlighted on this page
$calendar_products = apply_filters('tec-help-calendar-products', [
    'events-calendar-pro',
    'tribe-filterbar',
    'event-aggregator',
    'events-virtual',
]);

// there should only be 4 in this list
$faqs = apply_filters('tec-help-calendar-faqs', [
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('You can, but…', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('No. The answer is no.', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('Stop asking.', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('Seriously, I am dying.', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
]);

// there should only be 4 in this list
$extensions = apply_filters('tec-help-calendar-extensions', [
    [
        'title' => __('Calendar widget areas', 'tribe-common'),
        'description' => __('This extension creates a variety of WordPress widget areas (a.k.a. sidebars)', 'tribe-common'),
        'link' => 'https://evnt.is/ext-cal-widget-areas',
        'product-slug' => 'the-events-calendar',
    ],
    [
        'title' => __('Event block patterns', 'tribe-common'),
        'description' => __('Add a set of block patterns for events to the WordPress block editor.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-block-patterns',
        'product-slug' => 'the-events-calendar',
    ],
    [
        'title' => __('Alternative photo view', 'tribe-common'),
        'description' => __('Replace photo view with a beautiful alternative.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-alt-photo-view',
        'product-slug' => 'events-calendar-pro',
    ],
    [
        'title' => __('GraphQL', 'tribe-common'),
        'description' => __('Add a GraphQL API for your event data.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-graphql',
        'product-slug' => 'the-events-calendar',
    ],
]);

?>
<div id="tec-help-calendar">
	<p class="tribe-events-admin-description"><?php esc_html_e('Get help for these products and learn more about products you don\'t have.', 'tribe-common'); ?></p>

	<?php // list of products?>
	<div class="tribe-events-admin-products-grid">

		<?php $i = 0; ?>
		<?php foreach ($calendar_products as $slug) : ?>
			<?php $i++; ?>

			<div class="tribe-events-admin-card">
				<img
					class="tribe-events-admin-card__icon"
					src="<?php echo esc_url(tribe_resource_url($products[ $slug ]['logo'], false, null, $main)); ?>"
					alt="<?php esc_attr_e('logo icon', 'tribe-common'); ?>"
				/>
				<div class="tribe-events-admin-card__group">
					<div class="tribe-events-admin-card__group-title"><?php echo esc_html($products[ $slug ]['title']); ?></div>
					<div class="tribe-events-admin-card__group-description"><?php echo esc_html($products[ $slug ]['description-help']); ?></div>
				</div>
				<?php if ($products[ $slug ]['is_installed']) : ?>
				<button class="tribe-events-admin-card__button tribe-events-admin-card__button--active"><?php esc_html_e('Active', 'tribe-common'); ?></button>
				<?php else : ?>
					<button class="tribe-events-admin-card__button"><?php esc_html_e('Learn More', 'tribe-common'); ?></button>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('Start Here', 'tribe-common'); ?>
		</h3>
		
		<a href="https://event.is/kb-help">
			<?php esc_html_e('Visit Knowledgebase', 'tribe-common'); ?>
		</a>
	</div>

	<div class="tribe-events-admin-kb-grid">
		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/getting-started.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events Calendar logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title"><?php esc_html_e('Getting Started Guides', 'tribe-common'); ?></div>
			<ul class="tribe-events-admin-kb-card__links">
				<li><a href="https://evnt.is/guide-tec" target="_blank"><?php esc_html_e('The Events Calendar', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-ea" target="_blank"><?php esc_html_e('Event Aggregator', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-fb" target="_blank"><?php esc_html_e('Filter Bar', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-ve" target="_blank"><?php esc_html_e('Virtual Events', 'tribe-common'); ?></a></li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/customizing.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events Calendar logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title"><?php esc_html_e('Customizing', 'tribe-common'); ?></div>
			<ul class="tribe-events-admin-kb-card__links">
				<li><a href="https://evnt.is/guide-tec" target="_blank"><?php esc_html_e('Getting started with customizations', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-ea" target="_blank"><?php esc_html_e('Highlighting events', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-fb" target="_blank"><?php esc_html_e('Another link', 'tribe-common'); ?></a></li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/common-issues.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events Calendar logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title"><?php esc_html_e('Common Issues', 'tribe-common'); ?></div>
			<ul class="tribe-events-admin-kb-card__links">
				<li><a href="https://evnt.is/guide-tec" target="_blank"><?php esc_html_e('Known Issues', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-ea" target="_blank"><?php esc_html_e('Release notes', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-fb" target="_blank"><?php esc_html_e('Integrations', 'tribe-common'); ?></a></li>
				<li><a href="https://evnt.is/guide-ve" target="_blank"><?php esc_html_e('Shortcodes', 'tribe-common'); ?></a></li>
			</ul>
		</div>
	</div>

	<!-- FAQ Section -->
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('FAQs', 'tribe-common'); ?>
		</h3>
		
		<a href="https://event.is/faqs-help">
			<?php esc_html_e('All FAQs', 'tribe-common'); ?>
		</a>
	</div>

	<div class="tribe-events-admin-card-grid">
		<?php foreach ($faqs as $faq) : ?>
			<div class="tribe-events-admin-card tribe-events-admin-card--faq">
				<img
					class="tribe-events-admin-card__image"
					src="<?php echo esc_url(tribe_resource_url('images/icons/faq.png', false, null, $main)); ?>"
					alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
				/>
				<div class="tribe-events-admin-faq__question"><?php echo esc_html($faq['question']); ?></div>
				<div class="tribe-events-admin-faq__answer"><?php echo esc_html($faq['answer']); ?></div>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Extensions Section -->
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('Extensions', 'tribe-common'); ?>
		</h3>
		
		<a href="https://event.is/exts-help">
			<?php esc_html_e('All Extensions', 'tribe-common'); ?>
		</a>
	</div>

	<p><?php esc_html_e('Small, lightweight WordPress plugins that add new capabilities to our core plugins. Sorry, support is not offered for extensions.', 'tribe-common'); ?></p>

	<div class="tribe-events-admin-card-grid">
		<?php foreach ($extensions as $extension) : ?>
			<div class="tribe-events-admin-card tribe-events-admin-card--top-stripe">
				<div class="tribe-events-admin-simple-card__title"><?php echo esc_html($extension['title']); ?></div>
				<div class="tribe-events-admin-simple-card__description"><?php echo esc_html($extension['description']); ?></div>
				<div class="tribe-events-admin-simple-card__description"><?php echo esc_html($products[ $extension['product'] ]['title']); ?></div>
			</div>
		<?php endforeach; ?>
	</div>

</div>