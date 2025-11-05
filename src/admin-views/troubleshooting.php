<?php
/**
 * View: Troubleshooting
 */

// No direct access.
defined( 'ABSPATH' ) || exit;

?>
<div class="tribe-notice-wrap">
	<?php
	/**
	 * Trigger the conditional content header notice.
	 *
	 * @since 6.8.2
	 */
	do_action( 'tec_conditional_content_header_notice' );
	?>
	<div class="wp-header-end"></div>
</div>
<?php
$base_path = Tribe__Main::instance()->plugin_path . 'src/admin-views/troubleshooting/';

// admin notice.
require_once $base_path . 'notice.php';
// intro.
require_once $base_path . 'introduction.php';
// detected issues.
require_once $base_path . 'detected-issues.php';
// first steps.
require_once $base_path . 'first-steps.php';
// common issues.
require_once $base_path . 'common-issues.php';
// system information.
require_once $base_path . 'system-information.php';
// recent template changes.
require_once $base_path . 'recent-template-changes.php';
// recent logs.
require_once $base_path . 'event-log.php';
// ea status.
require_once $base_path . 'ea-status.php';
// support cta.
require_once $base_path . 'support-cta.php';
// footer.
require_once $base_path . 'footer-logo.php';
?>

<?php /* this is inline jQuery / javascript for extra simplicity */ ?>
<script>
	if (
		jQuery('.tribe-events-admin__issues-found-card .tribe-events-admin__issues-found-title')
		.hasClass('active')
	) {
		jQuery('.tribe-events-admin__issues-found-card .tribe-events-admin__issues-found-card-title.active')
			.closest('.tribe-events-admin__issues-found-card')
			.find('.tribe-events-admin__issues-found-description')
			.show();
	}
	jQuery('.tribe-events-admin__issues-found-card .tribe-events-admin__issues-found-card-title')
		.on('click', function() {
			var $this = jQuery(this);

			if (jQuery(this).hasClass('active')) {
				$this
					.removeClass('active')
					.closest('.tribe-events-admin__issues-found-card')
					.find('.tribe-events-admin__issues-found-card-description')
					.slideUp(200);
			} else {
				$this
					.addClass('active')
					.closest('.tribe-events-admin__issues-found-card')
					.find('.tribe-events-admin__issues-found-card-description')
					.slideDown(200);
			}
		});
</script>
