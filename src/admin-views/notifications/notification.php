<?php
/**
 * Notifications Template
 *
 * @since TBD
 *
 * @var string $type The type of notification.
 * @var string $id The ID of the notification.
 * @var boolean $dismissible Whether the notification is dismissible.
 * @var string $slug The slug of the plugin.
 * @var string $title The title of the notification.
 * @var string $content The content of the notification.
 * @var array $actions The action links.
 */

?>

<div class="ian-sidebar__notification ian-sidebar__notification--<?php echo esc_attr( $type ); ?>" id="notification_<?php echo esc_attr( $id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ian_nonce_' . $id ) ); ?>">
	<?php if ( $dismissible ) : ?>
	<div class="dashicons dashicons-dismiss" data-trigger="dismissIan" data-id="<?php echo esc_attr( $id ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>"></div>
	<?php endif; ?>
	<div class="ian-sidebar__notification-title"><?php echo esc_html( $title ); ?></div>
	<div class="ian-sidebar__notification-content"><?php echo wp_kses_post( $content ); ?></div>
	<?php if ( ! empty( $actions ) || $dismissible ) : ?>
	<div class="ian-sidebar__notification-link">
		<?php foreach ( $actions as $a ) : ?>
			<a href="<?php echo esc_url( $a['link'] ); ?>" target="<?php echo esc_attr( $a['target'] ); ?>"><?php echo esc_html( $a['text'] ); ?></a>
		<?php endforeach; ?>
		<?php if ( $dismissible ) : ?>
			<a href="#" data-trigger="dismissIan" data-id="<?php echo esc_attr( $id ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>"><?php esc_html_e( 'Dismiss', 'tribe-common' ); ?></a>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>
