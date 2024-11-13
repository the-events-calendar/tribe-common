<?php
/**
 * In-App Notifications Template
 *
 * @since TBD
 *
 * @var string $slug  The slug of the plugin.
 * @var string $main  The main instance of the plugin.
 * @var string $optin The opt-in status for the notifications.
 * @var string $url   The URL for the data sharing agreement.
 */

?>

<div class="ian-client" data-trigger="iconIan"></div>

<div class="ian-sidebar is-hidden" data-trigger="sideIan">
	<div class="ian-sidebar__title">
	<div><?php esc_html_e( 'Notifications', 'tribe-common' ); ?></div>
	<img src="<?php echo esc_url( tribe_resource_url( 'images/icons/close.svg', false, null, $main ) ); ?>" alt="<?php esc_attr_e( 'Close icon', 'tribe-common' ); ?>" width="20" height="20" data-trigger="closeIan">
	</div>
	<div class="ian-sidebar__content" data-trigger="contentIan">
	<div class="ian-sidebar__loader"></div>
	<?php if ( tribe_is_truthy( $optin ) ) : ?>
		<div class="ian-sidebar__notifications" data-trigger="notificationsIan"></div>
		<div class="ian-sidebar__empty is-hidden">
			<div class="ian-sidebar__empty--icon"><img src="<?php echo esc_url( tribe_resource_url( 'images/icons/bell.svg', false, null, $main ) ); ?>" alt="<?php esc_attr_e( 'Notifications icon', 'tribe-common' ); ?>" width="60" height="60"></div>
			<div class="ian-sidebar__empty--title"><?php esc_html_e( 'There are no notifications', 'tribe-common' ); ?></div>
			<div class="ian-sidebar__empty--description"><p><?php esc_html_e( 'Congratulations! You are up to date.', 'tribe-common' ); ?></p></div>
		</div>
	<?php else : ?>
		<div class="ian-sidebar__optin">
		<div class="ian-sidebar__optin--icon"><img src="<?php echo esc_url( tribe_resource_url( 'images/icons/bell.svg', false, null, $main ) ); ?>" alt="<?php esc_attr_e( 'Notifications icon', 'tribe-common' ); ?>" width="60" height="60"></div>
		<div class="ian-sidebar__optin--title"><?php esc_html_e( 'There are no notifications', 'tribe-common' ); ?></div>
		<div class="ian-sidebar__optin--description">
			<p><?php esc_html_e( 'Be up to date with the latest updates, fixes and features for The Events Calendar.', 'tribe-common' ); ?></p>
			<?php $agreement = '<a href="' . esc_url( $url ) . '" target="_blank" rel="nofollow noopener">' . esc_html__( 'data sharing agreement', 'tribe-common' ) . '</a>'; ?>
			<p>
			<?php
			/* translators: %s: data sharing agreement */
			printf( esc_html__( 'To receive notifications you need to agree to our %s.', 'tribe-common' ), $agreement );
			?>
			</p>
		</div>
		<div class="ian-sidebar__optin--button" data-trigger="optinIan"><?php esc_html_e( 'Opt-in to notifications', 'tribe-common' ); ?></div>
		</div>
	<?php endif; ?>
	</div>
</div>
