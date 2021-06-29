<?php

	use \Tribe\Admin\Troubleshooting;

	$issues_found = tribe( Troubleshooting::class )->get_issues_found();

	$common_issues = tribe( Troubleshooting::class )->get_common_issues();

?>

<div class="tribe-events-admin__troubleshooting-notice">
	<div class="tribe-events-admin__troubleshooting-notice_title">
		<?php 
			$link = '<a href="/wp-admin/edit.php?post_type=tribe_events&page=tribe-help">' . esc_html__( 'Help page?', 'tribe-common' ) . '</a>';
			echo sprintf( __( 'Hey there... did you check out the %s', 'tribe-common' ), $link );
		?>
	</div>
</div>

<div class="tribe-events-admin-header tribe-events-admin-container">
	<?php do_action('tec-admin-notice-area', 'help'); ?>
	<div class="tribe-events-admin-header__content-wrapper">

		<img
			class="tribe-events-admin-header__logo-word-mark"
			src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
			alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
		/>

		<h2 class="tribe-events-admin-header__title"><?php esc_html_e('Troubleshooting', 'tribe-common'); ?></h2>
		<p class="tribe-events-admin-header__description"><?php esc_html_e('Sometimes things just don’t work as expected. We’ve created a wealth of resources to get you back on track.', 'tribe-common'); ?></p>
	</div>
</div>

<div class="tribe-events-admin-content-wrapper tribe-events-admin-container">
    <img
        class="tribe-events-admin-header__right-image"
        src="<?php echo esc_url(tribe_resource_url('images/help/troubleshooting-hero.png', false, null, $main)); ?>"
    />
	
	
	<?php if ( tribe( Troubleshooting::class )->is_any_issue_active() ) : //checks is there are any active issues before printing ?>
		<div class="tribe-events-admin-section-header">
			<h3>
				<?php esc_html_e('We’ve detected the following issues', 'tribe-common'); ?>
			</h3>
		</div>
	
		<?php // toggles to appear here?>
		<?php foreach ($issues_found as $issue) : ?>
			<?php 
				// yoda conditioning
				if ( false === $issue['active'] ) {
					continue;
				}
			?>
			<div class="tribe-events-admin__issues-found-card">
				<div class="tribe-events-admin__issues-found-card-title">
					<img
						src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
						alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
					/>
					<h3>
						<i></i>
						<span>
							<?php echo esc_html($issue['title']); ?>
						</span>
					</h3>
				</div>
				<div class="tribe-events-admin__issues-found-card-description">
					<p>
						<?php echo esc_html($issue['description']); ?>
					</p>
					<div class="tribe-events-admin__issues-found-card-description-actions">
						<a href="<?php echo esc_html($issue['more_info']); ?>" target="_blank">
							<?php esc_attr_e('Learn more', 'tribe-common'); ?>
						</a>
						<a href="<?php echo esc_html($issue['fix']); ?>">
							<?php esc_attr_e('Resolve it now', 'tribe-common'); ?>
						</a>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

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
					<?php 
						$article = '<br /><a href="https://theeventscalendar.com/knowledgebase/k/sharing-your-system-information/" target="_blank" rel="noreferrer">' . esc_html__( 'View article', 'tribe-common' ) . '</a>';
						echo sprintf( __( 'Most issues are casued by conflicts with the theme or other plugins. Follow these steps as a first point of action. %s', 'tribe-common' ), $article );
					?>
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
		<?php foreach ($common_issues as $commonIssue) : ?>
			<div class="tribe-events-admin-faq-card">
				<div class="tribe-events-admin-faq-card__icon">
					<img
						src="<?php echo esc_url(tribe_resource_url('images/icons/faq.png', false, null, $main)); ?>"
						alt="<?php esc_attr_e('lightbulb icon', 'tribe-common'); ?>"
					/>
				</div>
				<div class="tribe-events-admin-faq-card__content">
					<div class="tribe-events-admin-faq__question">
						<?php echo esc_html($commonIssue['issue']); ?>	
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
			<h3 class="tribe-events-admin__troubleshooting-title">
				<?php esc_html_e('System Information', 'tribe-common'); ?>
			</h3>	
			<p class="tribe-events-admin__troubleshooting-description">
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
	<h3 class="tribe-events-admin__troubleshooting-title">
		<?php esc_html_e('Recent template changes', 'tribe-common'); ?>
	</h3>	
	<p class="tribe-events-admin__troubleshooting-description">
		<?php esc_html_e('Information about recent template changes and potentiallly impacted template overrides is provided below.', 'tribe-common'); ?>	
	</p>
	<div class="tribe-events-admin__system-information-widget">
		&nbsp;
	</div>

	<?php // revent log section?>
	<h3 class="tribe-events-admin__troubleshooting-title tribe-events-admin__recent-log">
		<?php esc_html_e('Event log', 'tribe-common'); ?>
	</h3>

	<div class="tribe-events-admin__recent-log-filters">
		<div class="tribe-events-admin__recent-log-filters-field">
			<label>
				<?php esc_html_e('Logging level', 'tribe-common'); ?>
			</label>
			<div class="tribe-events-admin__recent-log-filters-select-wrapper">
				<select name="" id="">
					<option value="errors">
						<?php esc_html_e('Only errors', 'tribe-common'); ?>
					</option>
				</select>
			</div>
		</div>	

		<div class="tribe-events-admin__recent-log-filters-field">
			<label>
				<?php esc_html_e('Method', 'tribe-common'); ?>
			</label>
			<div class="tribe-events-admin__recent-log-filters-select-wrapper">
				<select name="" id="">
					<option value="default">
						<?php esc_html_e('Default (uses temporary file)', 'tribe-common'); ?>
					</option>
				</select>
			</div>
		</div>	

		<div class="tribe-events-admin__recent-log-filters-field">
			<label>
				<?php esc_html_e('View', 'tribe-common'); ?>
			</label>
			<div class="tribe-events-admin__recent-log-filters-select-wrapper">
				<select name="" id="">
					<option value="none">
						<?php esc_html_e('None currently available', 'tribe-common'); ?>
					</option>
				</select>
			</div>
		</div>	
	</div>

	<div class="tribe-events-admin__system-information-widget">
		<?php esc_html_e('The selected log file is empty or has not been generated yet.', 'tribe-common'); ?>
	</div>

	<?php // EA status?>
	<h3 class="tribe-events-admin__troubleshooting-title tribe-events-admin__ea-status">
		<?php esc_html_e('Event Aggregator system status ', 'tribe-common'); ?>
	</h3>

	<table class="tribe-events-admin__ea-status-table">
		<?php //License & Usage?>
		<tr>
			<th>
				<?php esc_html_e('License & Usage', 'tribe-common'); ?>
			</th>
		</tr>

		<tr class="tribe-events-admin__ea-status-table-dark">
			<td>
				<?php esc_html_e('License Key', 'tribe-common'); ?>
			</td>
			<td>
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/success-icon.svg', false, null, $main)); ?>"
					alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
				/>
				<?php esc_html_e('Your license is valid', 'tribe-common'); ?>
			</td>
		</tr>

		<tr>
			<td>
				<?php esc_html_e('Event Block Patterns', 'tribe-common'); ?>
			</td>
			<td>
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/success-icon.svg', false, null, $main)); ?>"
					alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
				/>
				<?php esc_html_e('100 imports used ouf of 1000 available today', 'tribe-common'); ?>
			</td>
		</tr>

		<?php //Import Services?>
		<tr>
			<th>
				<?php esc_html_e('Import Services', 'tribe-common'); ?>
			</th>
		</tr>

		<tr class="tribe-events-admin__ea-status-table-dark">
			<td>
				<?php esc_html_e('Server Connection', 'tribe-common'); ?>
			</td>
			<td>
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/error-icon.svg', false, null, $main)); ?>"
					alt="<?php esc_attr_e('error-icon', 'tribe-common'); ?>"
				/>
				<?php esc_html_e('Connected to https://ea.theeventscalendar.com', 'tribe-common'); ?></td>
			</tr>

		<tr>
			<td>
				<?php esc_html_e('Scheduler Status', 'tribe-common'); ?>
			</td>
			<td>
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/success-icon.svg', false, null, $main)); ?>"
					alt="<?php esc_attr_e('success-icon', 'tribe-common'); ?>"
				/>
				<?php esc_html_e('WP Cron enabled', 'tribe-common'); ?>
			</td>
		</tr>

		<?php //Third Party Accounts?>
		<tr>
			<th>
				<?php esc_html_e('Third Party Accounts', 'tribe-common'); ?>
			</th>
		</tr>

		<tr class="tribe-events-admin__ea-status-table-dark">
			<td class="tribe-events-admin__ea-status-table-dark">
				<?php esc_html_e('Eventbrite', 'tribe-common'); ?>
			</td>
			<td>
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
					alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
				/>
				<?php esc_html_e('Event Aggregator is not connected to Eventbrite', 'tribe-common'); ?>
			</td>
			<td>
				<a href="" target="_blank">
					<?php esc_html_e('Connect to Eventbrite', 'tribe-common'); ?>
				</a>
			</td>
		</tr>

		<tr>
			<td>
				<?php esc_html_e('Meetup', 'tribe-common'); ?>
			</td>
			<td>
				<img
					src="<?php echo esc_url(tribe_resource_url('images/help/warning-icon.svg', false, null, $main)); ?>"
					alt="<?php esc_attr_e('warning-icon', 'tribe-common'); ?>"
				/>
				<?php esc_html_e('Event Aggregator is not connected to Meetup', 'tribe-common'); ?>
			</td>
			<td>
				<a href="" target="_blank">
					<?php esc_html_e('Connect to Meetup', 'tribe-common'); ?>
				</a>
			</td>
		</tr>
	</table>

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
	<img
		class="tribe-events-admin-footer-logo"
		src="<?php echo esc_url(tribe_resource_url('images/logo/tec-brand.svg', false, null, $main)); ?>"
		alt="<?php esc_attr_e('The Events Calendar brand logo', 'tribe-common'); ?>"
	/>
</div>

<?php // this is inline jQuery / javascript for extra simplicity */?>
<script>
	if (jQuery(".tribe-events-admin__issues-found-card .tribe-events-admin__issues-found-title").hasClass('active')) {
		jQuery(".tribe-events-admin__issues-found-card .tribe-events-admin__issues-found-card-title.active").closest('.tribe-events-admin__issues-found-card').find('.tribe-events-admin__issues-found-description').show();
	}
	jQuery(".tribe-events-admin__issues-found-card .tribe-events-admin__issues-found-card-title").click(function () {
		if (jQuery(this).hasClass('active')) {
			jQuery(this).removeClass("active").closest('.tribe-events-admin__issues-found-card').find('.tribe-events-admin__issues-found-card-description').slideUp(200);
		}
		else {
			jQuery(this).addClass("active").closest('.tribe-events-admin__issues-found-card').find('.tribe-events-admin__issues-found-card-description').slideDown(200);
		}
	});
</script>