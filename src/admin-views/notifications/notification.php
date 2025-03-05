<?php
/**
 * Notifications Template
 *
 * @since 6.4.0
 *
 * @var string $type The type of notification.
 * @var string $id The ID of the notification.
 * @var boolean $dismissible Whether the notification is dismissible.
 * @var string $slug The slug of the notification.
 * @var string $title The title of the notification.
 * @var string $html The content of the notification.
 * @var array $actions The action links.
 * @var boolean $read Whether the notification has been read.
 */

?>
<div class="ian-sidebar__notification ian-sidebar__notification--<?php echo esc_attr( $type ); ?>" id="notification_<?php echo esc_attr( $id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ian_nonce_' . $id ) ); ?>">
	<?php if ( $dismissible ) : ?>
	<div class="dashicons dashicons-dismiss" data-tec-ian-trigger="dismissIan" data-id="<?php echo esc_attr( $id ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>" title="<?php esc_attr_e( 'Dismiss', 'tribe-common' ); ?>"></div>
	<?php endif; ?>
	<div class="ian-sidebar__notification-title"><?php echo esc_html( $title ); ?></div>
	<div class="ian-sidebar__notification-content"><?php echo wp_kses_post( $html ); ?></div>
	<?php if ( ! empty( $actions ) ) : ?>
	<div class="ian-sidebar__notification-link">
		<div class="ian-sidebar__notification-link--left">
			<?php foreach ( $actions as $a ) : ?>
				<a href="<?php echo esc_url( $a['url'] ); ?>" target="<?php echo esc_attr( $a['target'] ); ?>"><?php echo esc_html( $a['text'] ); ?></a>
			<?php endforeach; ?>
		</div>
		<div class="ian-sidebar__notification-link--right">
			<?php if ( ! $read ) : ?>
				<a href="#" data-tec-ian-trigger="readIan" data-id="<?php echo esc_attr( $id ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>"><?php esc_html_e( 'Mark as read', 'tribe-common' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
</div>
