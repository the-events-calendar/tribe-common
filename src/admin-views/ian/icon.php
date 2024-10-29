<?php
/**
 * IAN Icon Template
 *
 * @since TBD
 */

$main = Tribe__Main::instance();
?>

<div class="ian-client" data-trigger="iconIan"></div>

<div class="ian-sidebar is-hidden" data-trigger="sideIan">
	<div class="ian-sidebar__title">
  	<div>Notifications</div>
    <img src="<?php echo esc_url( tribe_resource_url( 'images/icons/close.svg', false, null, $main ) ); ?>" alt="" width="20" height="20" data-trigger="closeIan">
	</div>
</div>
