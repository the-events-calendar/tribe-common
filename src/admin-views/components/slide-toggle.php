<?php
/**
 * View: Slide Toggle
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/components/switch.php
 *
 * See more documentation about our views templating system.
 *
 * @link    https://evnt.is/1aiy
 *
 * @version TBD
 *
 * @var string               $label_id       The id for the slide toggle button.
 * @var string               $label          Label for the slide toggle button.
 * @var array<string,string> $classes_wrap   An array of classes for the toggle wrap.
 * @var array<string,string> $classes_button An array of classes for the toggle button.
 * @var array<string,string> $classes_panel  An array of classes for the toggle content.
 * @var string               $panel_id       The id of the panel for the slide toggle.
 * @var string               $panel          The content of the panel for the slide toggle.
 * @var bool                 $expanded       Whether the panel starts open or closed.
 */
$toggle_wrap_classes = [ 'tribe-common-slide-toggle' ];
if ( ! empty( $classes_wrap ) ) {
	$toggle_wrap_classes = array_merge( $toggle_wrap_classes, $classes_wrap );
}
if ( $expanded ) {
	$toggle_wrap_classes[] = 'active';
}

$toggle_button_classes = [ 'tribe-common-slide-toggle__button' ];
if ( ! empty( $classes_button ) ) {
	$toggle_button_classes = array_merge( $toggle_button_classes, $classes_button );
}

$toggle_panel_classes = [ 'tribe-common-slide-toggle__panel' ];
if ( ! empty( $classes_panel ) ) {
	$toggle_panel_classes = array_merge( $toggle_panel_classes, $classes_panel );
}
?>
<div <?php tribe_classes( $toggle_wrap_classes ); ?>>
	<button
		id="<?php echo esc_attr( $label_id ); ?>"
		<?php tribe_classes( $toggle_button_classes ); ?>
		aria-expanded="<?php echo $expanded ? 'true' : 'false'; ?>"
		aria-controls="<?php echo esc_attr( $panel_id ); ?>"
	>
		<?php
		echo esc_html( $label );

		// Change to the views directory to get the icon.
		$this->set_template_folder( 'src/views' );
		$this->template( 'v2/components/icons/caret-down', [ 'classes' => [ 'tribe-common-slide-toggle__button-icon-caret-svg' ] ] );
		$this->set_template_folder( 'src/admin-views' );
		?>
	</button>
	<div
		id="<?php echo esc_attr( $panel_id ); ?>"
		<?php tribe_classes( $toggle_panel_classes ); ?>
		role="region"
		aria-labelledby="<?php echo esc_attr( $label_id ); ?>"
		aria-hidden="<?php echo $expanded ? 'false' : 'true'; ?>"
		<?php
		// Add inline style if expanded on initial load for slideToggle to work correctly.
		echo $expanded ? 'style="display:block"' : '';
		?>
	>
		<?php echo $panel; ?>
	</div>
</div>