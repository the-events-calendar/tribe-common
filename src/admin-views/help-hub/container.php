<?php
/**
 * Template for the Help Hub tab containers.
 *
 * @since 6.8.0
 *
 * @var array $tabs The array of tab data containing:
 *                  - id: The tab container ID
 *                  - target: The tab target
 *                  - class: Additional CSS classes
 *                  - label: The tab label
 *                  - args: Additional template arguments
 *                  - template: The template file to render
 */

?>
<div id="tec-help-hub-tab-containers" class="tec-tab-parent-container">
	<?php foreach ( $tabs as $index => $hub_tab ) : ?>
		<div
			id="<?php echo esc_attr( $hub_tab['id'] ); ?>"
			class="tec-tab-container <?php echo 0 === $index ? '' : 'hidden'; ?>"
			role="tabpanel"
			aria-labelledby="tab-<?php echo esc_attr( $index ); ?>"
			data-link-title="<?php echo esc_attr( $hub_tab['label'] ); ?>"
		>
			<?php
			$this->set_values( (array) $hub_tab['args'] ?? [] );
			$this->template( $hub_tab['template'] );
			?>
		</div>
	<?php endforeach; ?>
</div>
