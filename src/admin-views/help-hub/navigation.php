<?php
/**
 * Template for the Help Hub navigation.
 *
 * @since 6.8.0
 *
 * @var array $tabs The array of tab data containing:
 *                  - target: The tab target
 *                  - class: Additional CSS classes
 *                  - label: The tab label
 */

?>
<dialog id="tec-settings-nav-modal" class="tec-settings-form__modal" aria-labelledby="tec-settings-nav-modal-title" aria-modal="true" role="dialog">
	<div class="tec-modal__content">
		<div class="tec-modal__header">
			<h2 id="tec-settings-nav-modal-title" class="screen-reader-text"><?php esc_html_e( 'Settings Navigation', 'tribe-common' ); ?></h2>
			<button id="tec-settings-nav-modal-close" class="tec-modal__control tec-modal__control--close" data-modal-close aria-label="<?php esc_attr_e( 'Close settings navigation', 'tribe-common' ); ?>">
				<span class="screen-reader-text"><?php esc_html_e( 'Close', 'tribe-common' ); ?></span>
			</button>
		</div>
		<?php $this->template( 'help-hub/navigation-links' ); ?>
	</div>
</dialog>
<div class="tec-nav__modal-controls">
	<button
		id="tec-settings-nav-modal-open"
		class="tec-modal__control tec-modal__control--open"
		aria-controls="tec-settings-nav-modal"
		aria-expanded="false"
		aria-label="<?php esc_attr_e( 'Open settings navigation', 'tribe-common' ); ?>"
	>
		<span><?php echo esc_html( reset( $tabs )['label'] ); ?></span>
		<img
			class="tec-modal__control-icon"
			src="<?php echo esc_url( tribe_resource_url( 'images/icons/hamburger.svg', false, null, Tribe__Main::instance() ) ); ?>"
			alt="<?php esc_attr_e( 'Open settings navigation', 'tribe-common' ); ?>"
		>
	</button>
</div>
<?php $this->template( 'help-hub/navigation-links' ); ?>
