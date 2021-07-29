<?php 
/**
 * View: Troubleshooting - Admin Notice
 * 
 * @since TBD
 * 
 */
$admin_url = admin_url();
?>
<div class="tribe-events-admin__troubleshooting-notice">
	<div class="tribe-events-admin__troubleshooting-notice_title">
		<?php
			$link = '<a href="' . $admin_url . 'edit.php?post_type=tribe_events&page=tribe-help">' . esc_html__( 'Help page?', 'tribe-common' ) . '</a>';
			echo sprintf( __( 'Hey there... did you check out the %s', 'tribe-common' ), $link );
		?>
	</div>
</div>