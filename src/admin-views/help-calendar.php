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
        'question' => __('Can I have more than one calendar?', 'tribe-common'),
        'answer' => __('No, but you can use event categories or tags to display certain events like having...', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('What do I get with The Events Calendar Pro?', 'tribe-common'),
        'answer' => __('Events Calendar Pro runs alongside The Events Calendar and enhances...'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('How do I sell tickets to events?', 'tribe-common'),
        'answer' => __('Use our free Event Tickets plugin to get started with tickets and RSVPs.', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('What happens if I disable the plugin?', 'tribe-common'),
        'answer' => __('Nothing. Whether you disable the plugin or uninstall it, your events...', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
]);

// there should only be 4 in this list
$extensions = apply_filters('tec-help-calendar-extensions', [
    [
        'title' => __('Calendar widget areas', 'tribe-common'),
        'description' => __('This extension creates a useful variety of WordPress widget areas (a.k.a. sidebars).', 'tribe-common'),
        'link' => 'https://evnt.is/ext-cal-widget-areas',
        'product-slug' => 'the-events-calendar',
    ],
    [
        'title' => __('Event block patterns', 'tribe-common'),
        'description' => __('This extension adds a set of block patterns for events to the WordPress block editor.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-block-patterns',
        'product-slug' => 'the-events-calendar',
    ],
    [
        'title' => __('Alternative photo view', 'tribe-common'),
        'description' => __('This extension replaces photo view with a tiled grid of cards featuring event images.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-alt-photo-view',
        'product-slug' => 'events-calendar-pro',
    ],
    [
        'title' => __('Test data generator', 'tribe-common'),
        'description' => __('This extension adds a tool to generate realistic dummy content for events.', 'tribe-common'),
        'link' => 'https://evnt.is/ext-graphql',
        'product-slug' => 'the-events-calendar',
    ],
]);

?>
<div id="tribe-calendar">
	<img
		class="tribe-events-admin-header__right-image"
		src="<?php echo esc_url(tribe_resource_url('images/help/help-calendar-header.png', false, null, $main)); ?>"
	/>
	<p class="tribe-events-admin-products-description">
		<?php esc_html_e('Get help for these products and learn more about products you don\'t have.', 'tribe-common'); ?>
	</p>

	<?php // list of products?>
	<div class="tribe-events-admin-products tribe-events-admin-2col-grid">

		<?php $i = 0; ?>
		<?php foreach ($calendar_products as $slug) : ?>
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
					<button class="tribe-events-admin-products-card__button">
						<?php esc_html_e('Learn More', 'tribe-common'); ?>
					</button>
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

	<div class="tribe-events-admin-kb tribe-events-admin-3col-grid">
		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/getting-started.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events Calendar logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Getting Started Guides', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1ap9" target="_blank">
						<?php esc_html_e('The Events Calendar', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apc" target="_blank">
						<?php esc_html_e('Event Aggregator', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apd" target="_blank">
						<?php esc_html_e('Filter Bar', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1ape" target="_blank">
						<?php esc_html_e('Virtual Events', 'tribe-common'); ?>
					</a>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/customizing.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events Calendar logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Customizing', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/guide-tec" target="_blank">
						<?php esc_html_e('Getting started with customizations', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/guide-ea" target="_blank">
						<?php esc_html_e('Highlighting events', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/guide-fb" target="_blank">
						<?php esc_html_e('Another link', 'tribe-common'); ?>
					</a>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/common-issues.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events Calendar logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Common Issues', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/guide-tec" target="_blank">
						<?php esc_html_e('Known Issues', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/guide-ea" target="_blank">
						<?php esc_html_e('Release notes', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/guide-fb" target="_blank">
						<?php esc_html_e('Integrations', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/guide-ve" target="_blank">
						<?php esc_html_e('Shortcodes', 'tribe-common'); ?>
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
		
		<a href="https://event.is/faqs-help">
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
						<a href="<?php echo esc_html($faq['link']); ?>" target="_blank">
							<?php echo esc_html($faq['question']); ?>						
						</a>
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
		
		<a href="https://event.is/exts-help">
			<?php esc_html_e('All Extensions', 'tribe-common'); ?>
		</a>
	</div>

	<p class="tribe-events-admin-extensions-title">
		<?php esc_html_e('Small, lightweight WordPress plugins that add new capabilities to our core plugins. Support is not offered for extensions, but they do enhance your calendar with bonus features.', 'tribe-common'); ?>
	</p>

	<div class="tribe-events-admin-extensions tribe-events-admin-4col-grid">
		<?php foreach ($extensions as $extension) : ?>
			<div class="tribe-events-admin-extensions-card">
				<div class="tribe-events-admin-extensions-card__title">
					<a href="<?php echo esc_html($extension['link']); ?>" target="_blank">
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