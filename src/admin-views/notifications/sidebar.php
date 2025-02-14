<?php
/**
 * In-App Notifications Template
 *
 * @since 6.4.0
 *
 * @var string $slug  The slug of the plugin.
 * @var string $main  The main instance of the plugin.
 * @var string $optin The opt-in status for the notifications.
 * @var string $url   The URL for the data sharing agreement.
 */

?>
<div class="ian-sidebar is-hidden" data-tec-ian-trigger="sideIan">
	<div class="ian-sidebar__title">
		<div class="ian-sidebar__title--left">
			<?php esc_html_e( 'Notifications', 'tribe-common' ); ?>
		</div>
		<div class="ian-sidebar__title--right">
			<a href="#" data-tec-ian-trigger="readAllIan" class="is-hidden"><?php esc_html_e( 'Mark all as read', 'tribe-common' ); ?></a>
			<img src="<?php echo esc_url( tribe_resource_url( 'images/icons/close.svg', false, null, $main ) ); ?>" alt="<?php esc_attr_e( 'Close icon', 'tribe-common' ); ?>" width="20" height="20" data-tec-ian-trigger="closeIan" title="<?php esc_attr_e( 'Close sidebar', 'tribe-common' ); ?>">
		</div>
	</div>
	<div class="ian-sidebar__content" data-tec-ian-trigger="contentIan">
		<div class="ian-sidebar__loader is-hidden" data-tec-ian-trigger="loaderIan"></div>
		<div class="ian-sidebar__notifications is-hidden" data-tec-ian-trigger="notifications" data-consent="<?php echo esc_attr( $optin ? 'true' : 'false' ); ?>"></div>
		<div class="ian-sidebar__optin is-hidden" data-tec-ian-trigger="emptyIan">
				<div class="ian-sidebar__optin--icon"><img src="<?php echo esc_url( tribe_resource_url( 'images/icons/bell.svg', false, null, $main ) ); ?>" alt="<?php esc_attr_e( 'Notifications icon', 'tribe-common' ); ?>" width="60" height="60"></div>
				<div class="ian-sidebar__optin--title"><?php esc_html_e( 'There are no notifications', 'tribe-common' ); ?></div>
				<div class="ian-sidebar__optin--description"><p><?php esc_html_e( 'Congratulations! You are up to date.', 'tribe-common' ); ?></p></div>
			</div>
		<?php if ( ! tribe_is_truthy( $optin ) ) : ?>
			<div class="ian-sidebar__optin" data-tec-ian-trigger="optinIan">
			<div class="ian-sidebar__optin--icon"><img src="<?php echo esc_url( tribe_resource_url( 'images/icons/bell.svg', false, null, $main ) ); ?>" alt="<?php esc_attr_e( 'Notifications icon', 'tribe-common' ); ?>" width="60" height="60"></div>
			<div class="ian-sidebar__optin--title"><?php esc_html_e( 'There are no notifications', 'tribe-common' ); ?></div>
			<div class="ian-sidebar__optin--description">
				<p><?php esc_html_e( 'Be up to date with the latest updates, fixes and features for The Events Calendar.', 'tribe-common' ); ?></p>
				<?php $agreement = '<a href="' . esc_url( $url ) . '" target="_blank" rel="nofollow noopener">' . esc_html__( 'data sharing agreement', 'tribe-common' ) . '</a>'; ?>
				<p>
				<?php
				/* translators: %s: data sharing agreement */
				printf( esc_html__( 'To receive notifications you need to agree to our %s.', 'tribe-common' ), wp_kses_post( $agreement ) );
				?>
				</p>
			</div>
			<div class="ian-sidebar__optin--button" data-tec-ian-trigger="optinIan"><?php esc_html_e( 'Opt-in to notifications', 'tribe-common' ); ?></div>
			</div>
		<?php endif; ?>
	</div>
</div>
