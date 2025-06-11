<?php
/**
 * Template for the Help Hub navigation links.
 *
 * @since 6.8.0
 *
 * @var array $tabs The array of tab data containing:
 *                  - target: The tab target
 *                  - class: Additional CSS classes
 *                  - label: The tab label
 */

?>
<nav class="tec-settings__nav-wrapper" aria-label="<?php esc_attr_e( 'Main Help Hub Navigation', 'tribe-common' ); ?>">
	<ul class="tec-nav" role="tablist">
		<?php foreach ( $tabs as $index => $hub_tab ) : ?>
			<li
				data-tab-target="<?php echo esc_attr( $hub_tab['target'] ); ?>"
				class="tec-nav__tab <?php echo esc_attr( $hub_tab['class'] ); ?>"
				role="tab"
				id="tab-main-<?php echo esc_attr( $index ); ?>"
				aria-controls="<?php echo esc_attr( $hub_tab['target'] ); ?>"
			>
				<a class="tec-nav__link"><?php echo esc_html( $hub_tab['label'] ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
