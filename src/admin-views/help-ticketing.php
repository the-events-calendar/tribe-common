<?php
// products that should be highlighted on this page
$ticketing_products = apply_filters( 'tec-help-ticketing-products', [
    'event-tickets-plus',
    'tribe-eventbrite',
    'promoter',
] );

// there should only be 4 in this list
$faqs = apply_filters( 'tec-help-ticketing-faqs', [
    [
        'question' => __( 'How Do I create events with Tickets or RSVP’s?', 'tribe-common' ),
        'answer' => __( 'We’ve put together a video tutorial showing how to create events with Tickets using our plugins. Click on the link in the link in the title to learn more.', 'tribe-common' ),
        'link' => 'https://evnt.is/1art',
    ],
    [
        'question' => __( 'How Do I Set Up E-Commerce Plugins for Selling Tickets?', 'tribe-common' ),
        'answer' => __( 'You can sell tickets using our built-in e-commerce option, or upgrade to Event Tickets Plus to use ecommerce plugins such as WooCommerce.', 'tribe-common' ),
        'link' => 'https://evnt.is/1arq',
    ],
    [
        'question' => __( 'Can I have a seating chart associated with my tickets?', 'tribe-common' ),
        'answer' => __( 'Yes! You can easily accomplish this task using the stock options and multiple ticket types available with Event Tickets.', 'tribe-common' ),
        'link' => 'https://evnt.is/1arr',
    ],
    [
        'question' => __( 'How do I process refunds for tickets?', 'tribe-common' ),
        'answer' => __( 'When it comes to paid tickets, these orders can be refunded through the e-commerce platform in use.', 'tribe-common' ),
        'link' => 'https://evnt.is/1ars',
    ],
] );

// there should only be 4 in this list
$extensions = apply_filters( 'tec-help-ticketing-extensions', [
    [
        'title' => __( 'Ticket Email Settings', 'tribe-common' ),
        'description' => __( 'Adds a new settings panel in Events > Settings that gives more control over the ticket and rsvp emails that are sent to attendees after registration.', 'tribe-common' ),
        'link' => 'https://evnt.is/1arx',
        'product-slug' => 'event-tickets',
    ],
    [
        'title' => __( 'Per Event Check In API', 'tribe-common' ),
        'description' => __( 'This extension shows a meta box with an API key on each Event with Ticket/RSVP.', 'tribe-common' ),
        'link' => 'https://evnt.is/1arw',
        'product-slug' => 'event-tickets',
    ],
    [
        'title' => __( 'Add Event & Attendee Info to WooCommerce Order Details', 'tribe-common' ),
        'description' => __( 'Displays the information collected by “attendee meta fields” in the WooCommerce order screens as well.', 'tribe-common' ),
        'link' => 'https://evnt.is/1arv',
        'product-slug' => 'event-tickets',
    ],
    [
        'title' => __( 'Organizer Notification Email', 'tribe-common' ),
        'description' => __( 'This extension will send an email to event organizers whenever a user registers for their event.', 'tribe-common' ),
        'link' => 'https://evnt.is/1aru',
        'product-slug' => 'event-tickets',
    ],
] );

?>
<div id="tribe-ticketing">
	<img
		class="tribe-events-admin-header__right-image"
		src="<?php echo esc_url(tribe_resource_url( 'images/help/help-ticketing-header.png', false, null, $main)); ?>"
	/>
	<p class="tribe-events-admin-products-description">
		<?php esc_html_e( 'Get help for these products and learn more about products you don\'t have.', 'tribe-common' ); ?>
	</p>

	<?php // list of products?>
	<div class="tribe-events-admin-products tribe-events-admin-2col-grid">
	<?php //requires valid links for all the products ?>
		<?php $i = 0; ?>
		<?php foreach ( $ticketing_products as $slug ) : ?>
			<?php $i++; ?>

			<div class="tribe-events-admin-products-card">
				<img
					class="tribe-events-admin-products-card__icon"
					src="<?php echo esc_url(tribe_resource_url( $products[ $slug ]['logo'], false, null, $main)); ?>"
					alt="<?php esc_attr_e( 'logo icon', 'tribe-common' ); ?>"
				/>
				<div class="tribe-events-admin-products-card__group">
					<div class="tribe-events-admin-products-card__group-title">
						<?php echo esc_html( $products[ $slug ]['title'] ); ?>
					</div>
					<div class="tribe-events-admin-products-card__group-description">
						<?php echo esc_html( $products[ $slug ]['description-help'] ); ?>
					</div>
				</div>
				<?php if ( $products[ $slug ]['is_installed'] ) : ?>
				<button class="tribe-events-admin-products-card__button tribe-events-admin-products-card__button--active">
					<?php esc_html_e( 'Active', 'tribe-common' ); ?>
				</button>
				<?php else : ?>
					<a href="<?php echo $products[ $slug ]['link'] ?>" target="_blank" rel="noreferrer" class="tribe-events-admin-products-card__button">
						<?php esc_html_e( 'Learn More', 'tribe-common' ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e( 'Start Here', 'tribe-common' ); ?>
		</h3>
		
		<a href="https://evnt.is/1aq9" target="_blank" rel="noreferrer">
			<?php esc_html_e( 'Visit Knowledgebase', 'tribe-common' ); ?><?php //requires valid link ?>
		</a>
	</div>

	<div class="tribe-events-admin-kb tribe-events-admin-3col-grid">
		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url( 'images/help/help-start-guide-tickets.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e( 'book with The Events ticketing logo', 'tribe-common' ); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e( 'Getting Started Guides', 'tribe-common' ); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apn" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Event Tickets', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apo" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Calendar & Ticket Shortcodes', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1app" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Promoter', 'tribe-common' ); ?>
					</a>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url( 'images/help/customizing.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e( 'book with Event Tickets logo', 'tribe-common' ); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e( 'Creating Tickets & RSVPs', 'tribe-common' ); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apq" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Creating Tickets', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apr" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Creating RSVPs', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1aps" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Configuring Paypal for Tickets', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apt" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Shortcodes', 'tribe-common' ); ?>
					</a>
					<?php esc_html_e( '(Event Tickets Plus)', 'tribe-common' ); ?>
				</li>
			</ul>
		</div>

		<div class="tribe-events-admin-kb-card">
			<img
				class="tribe-events-admin-kb-card__image"
				src="<?php echo esc_url(tribe_resource_url( 'images/help/common-issues.png', false, null, $main)); ?>"
				alt="<?php esc_attr_e( 'book with The Events ticketing logo', 'tribe-common' ); ?>"
			/>
			<div class="tribe-events-admin-kb-card__title">
				<?php esc_html_e( 'Plugin Maintenance', 'tribe-common' ); ?>
			</div>
			<ul class="tribe-events-admin-kb-card__links">
				<li>
					<a href="https://evnt.is/1apu" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Troubleshooting', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apv" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Release notes', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apw" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Integrations', 'tribe-common' ); ?>
					</a>
				</li>
				<li>
					<a href="https://evnt.is/1apx" target="_blank" rel="noreferrer">
						<?php esc_html_e( 'Automatic Updates', 'tribe-common' ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<?php // faq section?>
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e( 'FAQs', 'tribe-common' ); ?>
		</h3>
		
		<a href="https://theeventscalendar.com/products/wordpress-event-tickets/#faqs" target="_blank" rel="noreferrer">
			<?php esc_html_e( 'All FAQs', 'tribe-common' ); ?>
		</a>
	</div>

	<div class="tribe-events-admin-faq tribe-events-admin-4col-grid">
		<?php foreach ( $faqs as $faq) : ?>
			<div class="tribe-events-admin-faq-card">
				<div class="tribe-events-admin-faq-card__icon">
					<img
						src="<?php echo esc_url(tribe_resource_url( 'images/icons/faq.png', false, null, $main)); ?>"
						alt="<?php esc_attr_e( 'lightbulb icon', 'tribe-common' ); ?>"
					/>
				</div>
				<div class="tribe-events-admin-faq-card__content">
					<div class="tribe-events-admin-faq__question">
						<a href="<?php echo esc_html( $faq['link'] ); ?>" target="_blank" rel="noreferrer">
							<?php echo esc_html( $faq['question'] ); ?>	
						</a>
					</div>
					<div class="tribe-events-admin-faq__answer">
						<?php echo esc_html( $faq['answer'] ); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php // extensions section?>
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e( 'Free extensions', 'tribe-common' ); ?>
		</h3>
		
		<a href="https://evnt.is/1aqa" target="_blank" rel="noreferrer">
			<?php esc_html_e( 'All Extensions', 'tribe-common' ); ?>
		</a>
	</div>

	<p class="tribe-events-admin-extensions-title">
		<?php esc_html_e( 'Small, lightweight WordPress plugins that add new capabilities to our core plugins. Support is not offered for extensions, but they do enhance your ticketing with bonus features.', 'tribe-common' ); ?>
	</p>

	<div class="tribe-events-admin-extensions tribe-events-admin-4col-grid">
		<?php foreach ( $extensions as $extension) : ?>
			<div class="tribe-events-admin-extensions-card">
				<div class="tribe-events-admin-extensions-card__title">
					<a href="<?php echo esc_html( $extension['link'] ); ?>" target="_blank" rel="noreferrer">
						<?php echo esc_html( $extension['title'] ); ?>
					</a>
				</div>
				<div class="tribe-events-admin-extensions-card__description">
					<?php echo esc_html( $extension['description'] ); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>