<?php
/**
 * Tooltip Basic View Template
 * The base template for Tribe Tooltips.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/tooltips/tooltip.php
 *
 * @package Tribe
 * @version TBD
 */

?>
<div class="tribe-tooltip" aria-expanded="false">
	<span class="dashicons dashicons-<?php echo sanitize_html_class( $args[ 'icon' ] ); ?> <?php echo sanitize_html_class( $args[ 'classes' ] ); ?>"></span>
	<div class="<?php echo sanitize_html_class( $args[ 'direction' ] ); ?>">
		<?php if ( is_array( $message ) ) :
			foreach( $message as $mess ) : ?>
				<p>
					<span><?php echo wp_kses_post( $mess ); ?><i></i></span>
				</p>
			<?php endforeach;
		else : ?>
			<p>
				<span><?php echo wp_kses_post( $message ); ?><i></i></span>
			</p>
		<?php endif; ?>
	</div>
</div>
