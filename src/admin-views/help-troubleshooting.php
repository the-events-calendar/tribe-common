<?php

    // there should only be 4 in this list
    $commonIssues = apply_filters('tec-help-troubleshooting-issues', [
        [
            'issue' => __('I got an error message. Now what?', 'tribe-common'),
            'solution' => __('Here’s an overview of common error messages and what they mean.', 'tribe-common'),
            'link' => 'https://evnt.is/somewhere',
        ],
        [
            'issue' => __('My calendar doesn’t look right.', 'tribe-common'),
            'solution' => __('This can happen when other plugins try to improve performance. More info.'),
            'link' => 'https://evnt.is/somewhere',
        ],
        [
            'issue' => __('I installed the calendar and it crashed my site.', 'tribe-common'),
            'solution' => __('Find solutions to this and other common installation issues.', 'tribe-common'),
            'link' => 'https://evnt.is/somewhere',
        ],
        [
            'issue' => __('I keep getting “Page Not Found” on events.', 'tribe-common'),
            'solution' => __('There are a few things you can do to resolve and prevent 404 errors.', 'tribe-common'),
            'link' => 'https://evnt.is/somewhere',
        ],
    ]);
?>

<div id="tribe-troubleshooting">
    <img
        class="tribe-events-admin-header__right-image"
        src="<?php echo esc_url(tribe_resource_url('images/help/troubleshooting-hero.png', false, null, $main)); ?>"
    />

	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('We’ve detected the following issues', 'tribe-common'); ?>
		</h3>
	</div>

	<?php // toggles to appear here?>

	<?php // first steps?>
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('First Steps', 'tribe-common'); ?>
		</h3>
	</div>

	<div class="tribe-events-admin-step tribe-events-admin-2col-grid">
		<div class="tribe-events-admin-step-card">
			<div class="tribe-events-admin-step-card__icon">
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/1.png', false, null, $main)); ?>"
					alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
				/>
			</div>
			<div class="tribe-events-admin-step-card__content">
				<div class="tribe-events-admin-step__title">
					<?php esc_html_e('Share your system info', 'tribe-common'); ?>	
				</div>
				<div class="tribe-events-admin-step__description">
					<?php esc_html_e('Most issues are casued by conflicts with the theme or other plugins. Follow these steps as a first point of action. View article', 'tribe-common'); ?>
				</div>
			</div>
		</div>

		<div class="tribe-events-admin-step-card">
			<div class="tribe-events-admin-step-card__icon">
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/2.png', false, null, $main)); ?>"
					alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
				/>
			</div>
			<div class="tribe-events-admin-step-card__content">
				<div class="tribe-events-admin-step__title">
					<?php esc_html_e('Test for conflicts', 'tribe-common'); ?>	
				</div>
				<div class="tribe-events-admin-step__description">
					<?php esc_html_e('Providing the details of your calendar plugin and settings (located below) helps our support team troubleshoot an issue faster.', 'tribe-common'); ?>
				</div>
			</div>
		</div>
	</div>

	<?php // common issues?>
	<div class="tribe-events-admin-section-header">
		<h3>
			<?php esc_html_e('Common Problems', 'tribe-common'); ?>
		</h3>
	</div>

	<div class="tribe-events-admin-faq tribe-events-admin-4col-grid">
		<?php foreach ($commonIssues as $commonIssue) : ?>
			<div class="tribe-events-admin-faq-card">
				<div class="tribe-events-admin-faq-card__icon">
					<img
						src="<?php echo esc_url(tribe_resource_url('images/icons/faq.png', false, null, $main)); ?>"
						alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
					/>
				</div>
				<div class="tribe-events-admin-faq-card__content">
					<div class="tribe-events-admin-faq__question">
						<a href="<?php echo esc_html($commonIssue['link']); ?>" target="_blank">
							<?php echo esc_html($commonIssue['issue']); ?>						
						</a>
					</div>
					<div class="tribe-events-admin-faq__answer">
						<?php echo esc_html($commonIssue['solution']); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
    
	<?php // sys info?>
	<div class="tribe-events-admin__system-information">
		<div class="tribe-events-admin__system-information-content">
			<h3 class="tribe-events-admin__system-information-title">
				<?php esc_html_e('System Information', 'tribe-common'); ?>
			</h3>	
			<p class="tribe-events-admin__system-information-description">
				<?php esc_html_e('Please opt-in below to automatically share your system information with our support team. This will allow us to assist you faster if you post in our help desk.', 'tribe-common'); ?>	
			</p>
			<div class="tribe-events-admin__system-information-select">
				<input type="checkbox" name="userToggleSystemInformation" value="1">
				<label>
					<?php esc_html_e('Yes, automatically share my system information with The Events Calendar support team*', 'tribe-common'); ?>
				</label>
			</div>
			<small>
				<?php esc_html_e('* Your system information will only be used by The Events Calendar support team. All information is stored securely. We do not share this information with any third parties.', 'tribe-common'); ?>
			</small>
		</div>

		<div class="tribe-events-admin__system-information-widget">
			&nbsp;
		</div>
	</div>

	<?php // recent teamplate changes?>
	<h3 class="tribe-events-admin__system-information-title">
		<?php esc_html_e('Recent template changes', 'tribe-common'); ?>
	</h3>	
	<p class="tribe-events-admin__system-information-description">
		<?php esc_html_e('Information about recent template changes and potentiallly impacted template overrides is provided below.', 'tribe-common'); ?>	
	</p>
	<div class="tribe-events-admin__system-information-widget">
		&nbsp;
	</div>

    <?php // cta section?>
	<div class="tribe-events-admin-cta">
		<img
			class="tribe-events-admin-cta__image"
			src="<?php echo esc_url(tribe_resource_url('images/help/troubleshooting-support.png', false, null, $main)); ?>"
			alt="<?php esc_attr_e('Graphic with an electrical plug and gears', 'tribe-common'); ?>"
		/>

		<div class="tribe-events-admin-cta__content tribe-events-admin__troubleshooting-cta">
			<div class="tribe-events-admin-cta__content-title">
				<?php esc_html_e('Get support from humans', 'tribe-common'); ?>
			</div>

            <div class="tribe-events-admin-cta__content-subtitle">
                <?php esc_html_e('Included with our premium products', 'tribe-common'); ?>
            </div>

			<div class="tribe-events-admin-cta__content-description">
				<a href="">
					<?php esc_html_e('Open a ticket', 'tribe-common'); ?>
				</a>
			</div>
		</div>
	</div>
</div>