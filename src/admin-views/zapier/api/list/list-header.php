<?php
/**
 * View: Zapier Integration - API Key List Header.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/api/list/list-header.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 */

?>
<div class="tec-automator-settings-details__container tec-settings-zapier-api-key-details__container tec-settings-zapier-api-key-details__container-header tec-automator-grid tec-automator-grid-header">
	<div class="tec-automator-grid-item tec-automator-settings-details__row">
		<?php echo esc_html_x( 'Name', 'Name header label for the settings listing of Zapier API Key Pairs.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-settings-zapier-details-api-key__user-wrap">
		<?php echo esc_html_x( 'User', 'User header label for the settings listing of Zapier API Key Pairs.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-settings-zapier-details-api-key__permissions-wrap">
		<?php echo esc_html_x( 'Permissions', 'Permissions header label for the settings listing of Zapier API Key Pairs.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-settings-zapier-details-api-key__last-access-wrap">
		<?php echo esc_html_x( 'Last Access', 'Last Access header label for the settings listing of Zapier API Key Pairs.', 'tribe-common' ); ?>
	</div>
	<div class="tec-automator-grid-item tec-automator-settings-details-action__revoke-wrap tec-common-zapier-details-action__revoke-wrap">
		<?php echo esc_html_x( 'Actions', 'Actions header label for the settings listing of Zapier API Key Pairs.', 'tribe-common' ); ?>
	</div>
</div>
