<?php
// products that should be highlighted on this page
$community_products = apply_filters('tec-help-community-products', [
    'events-community',
    'events-community-tickets',
]);

// there should only be 4 in this list
$faqs = apply_filters('tec-help-community-faqs', [
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('You can, but if you do, we might have to blah blah...', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('No. The answer is no.'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('If you try to install more than one calendar, you might...', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
    [
        'question' => __('Can I have more than one calendar on my site?', 'tribe-common'),
        'answer' => __('More than one calendar may be problematic, but then...', 'tribe-common'),
        'link' => 'https://evnt.is/somewhere',
    ],
]);

// there should only be 4 in this list
$extensions = apply_filters('tec-help-community-extensions', [
    [
        'title' => __('Calendar widget areas', 'tribe-common'),
        'description' => __('This extension creates a useful variety of WordPress widget areas (a.k.a. sidebars)', 'tribe-common'),
        'link' => '',
        'product-slug' => 'community-events',
    ],
    [
        'title' => __('Event block patterns', 'tribe-common'),
        'description' => __('This extension creates a useful variety of WordPress widget areas (a.k.a. sidebars)', 'tribe-common'),
        'link' => 'https://evnt.is/ext-block-patterns',
        'product-slug' => 'community-events',
    ],
    [
        'title' => __('Alternative photo view', 'tribe-common'),
        'description' => __('This extension creates a useful variety of WordPress widget areas (a.k.a. sidebars)', 'tribe-common'),
        'link' => 'https://evnt.is/ext-alt-photo-view',
        'product-slug' => 'community-events',
    ],
    [
        'title' => __('Test data generator', 'tribe-common'),
        'description' => __('This extension creates a useful variety of WordPress widget areas (a.k.a. sidebars)', 'tribe-common'),
        'link' => 'https://evnt.is/ext-graphql',
        'product-slug' => 'community-events',
    ],
]);

?>
<div id="tribe-community">
	<img
		class="tribe-events-admin-header__right-image"
		src="<?php echo esc_url(tribe_resource_url('images/help/help-community-header.png', false, null, $main)); ?>"
	/>
	<p class="tribe-events-admin-products-description">
		<?php esc_html_e('Get help for these products and learn more about products you don\'t have.', 'tribe-common'); ?>
	</p>

	<?php // list of products?>
	<div class="tribe-events-admin-products tribe-events-admin-2col-grid">
	<?php //requires valid links for all the products ?>
		<?php $i = 0; ?>
		<?php foreach ($community_products as $slug) : ?>
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
		
		<a href="https://event.is/kb-help">
			<?php esc_html_e('Visit Knowledgebase', 'tribe-common'); ?><?php //requires valid link ?>
		</a>
	</div>

	<div class="tribe-events-admin-kb tribe-events-admin-3col-grid">
		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/help-start-guide-tickets.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events community logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Getting Started Guides', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apy" target="_blank">
						<?php esc_html_e('Community Events', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apz" target="_blank">
						<?php esc_html_e('Community Tickets', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1ap-" target="_blank">
						<?php esc_html_e('Calendar & Ticket Shortcodes', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aq0" target="_blank">
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
				<?php esc_html_e('Managing Submissions', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1aq1" target="_blank">
						<?php esc_html_e('Managing Submissions Overview', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aq2" target="_blank">
						<?php esc_html_e('Setting Notifications', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aq3" target="_blank">
						<?php esc_html_e('Community Events Shortcodes', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aq4" target="_blank">
						<?php esc_html_e('Preventing Spam Submissions', 'tribe-common'); ?>
					</a>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url('images/help/common-issues.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e('book with The Events community logo', 'tribe-common'); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e('Plugin Maintenance', 'tribe-common'); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<?php esc_html_e('Troubleshooting', 'tribe-common'); ?>
				</li>
				<li>
					<a href="https://evnt.is/1aq6" target="_blank">
						<?php esc_html_e('Release notes', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aq7" target="_blank">
						<?php esc_html_e('Integrations', 'tribe-common'); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aq8" target="_blank">
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
		
		<a href="https://event.is/exts-help">
			<?php esc_html_e('All Extensions', 'tribe-common'); ?>
		</a>
	</div>

	<p class="tribe-events-admin-extensions-title">
		<?php esc_html_e('Small, lightweight WordPress plugins that add new capabilities to our core plugins. Support is not offered for extensions, but they do enhance your community with bonus features.', 'tribe-common'); ?>
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