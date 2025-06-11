<?php
/**
 * Template for rendering section links in the Help Hub.
 *
 * @since 6.8.0
 *
 * @var array $section The section data containing links to render.
 */

if ( empty( $section['links'] ) ) {
	return;
}
?>
<ul class="tec-help-list__list-expanded">
	<?php foreach ( $section['links'] as $resource ) : ?>
		<?php if ( empty( $resource['title'] ) || empty( $resource['url'] ) ) : ?>
			<?php continue; ?>
		<?php endif; ?>
		<li>
			<?php if ( ! empty( $resource['icon'] ) ) : ?>
				<img class="tec-help-list__icon-expanded" src="<?php echo esc_url( $resource['icon'] ); ?>" alt="<?php echo esc_attr__( 'Product Icon', 'tribe_common' ); ?>"/>
			<?php endif; ?>
			<a href="<?php echo esc_url( $resource['url'] ); ?>" rel="noopener" target="_blank">
				<?php echo esc_html( $resource['title'] ); ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
