<?php
/**
 * Upsell Template
 * The base template for TEC Upsell notices.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/upsell/upsell.php
 *
 * @since TBD
 *
 * @var string        $text      Text of upsell content (excluding link text).
 * @var string        $link_text Link text.
 * @var string        $link_url  Link URL.
 * @var string        $icon_url  URL to icon.
 * @var array<string> $classes   Additional classes to add to the upsell div.
 * @var array<string> $link      Array of link properties, including 'text', 'url', 'rel', 'target' and 'classes'.
 * 
 * @version TBD
 */

$upsell_classes = [ 'tec-admin__upsell' ];
if ( ! empty( $classes ) ) {
	$upsell_classes = array_merge( $upsell_classes, $classes );
}

$link_classes = [ 'tec-admin__upsell-link '];
if ( ! empty( $link ) && ! empty( $link['classes'] ) ) {
	$link_classes = array_merge( $link_classes, $link['classes'] );
}

?>
<div class="tribe-common">
	<div <?php tribe_classes( $upsell_classes ); ?>>
		<div class="tec-admin__upsell-content">
			<div class="tec-admin__upsell-icon">
				<?php $this->template( 'icon' ); ?>
			</div>
			<div class="tec-admin__upsell-text-wrap">
				<span class="tec-admin__upsell-text">
					<?php echo esc_html( $text ); ?>
				</span>
				<a 
					<?php tribe_classes( $link_classes ); ?> 
					href="<?php echo esc_url( $link['url'] ); ?>" 
					rel="<?php echo esc_attr( $link['rel'] ); ?>"
					target="<?php echo esc_attr( $link['target'] ); ?>"
				>
					<?php echo esc_html( $link['text'] ); ?>
				</a>
			</div>
		</div>
	</div>
</div>