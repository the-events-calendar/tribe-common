<?php
/**
 * View: Common Message.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/components/message.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var string               $message     The message to display.
 * @var string               $type        The type of message, either standard or error.
 * @var array<string|string> $add_classes An array of message classes.
 */

// If not message, do not display.
if ( empty( $message ) ) {
	return;
}

$message_classes = [ 'tec-automator-settings-message__wrap' ];
if ( ! empty( $add_classes ) ) {
	array_push( $message_classes, $add_classes );
}

if ( ! empty( $message_classes ) ) {
	array_push( $message_classes, $type );
}
?>

<div
	id="tec-common-settings-message"
	<?php tec_classes( $message_classes ); ?>
>
	<?php echo wp_kses_post( $message ); ?>
</div>
