<?php
/**
 * View: Common Text Input.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/components/text.php
 *
 * See more documentation about our views templating system.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,string> $classes_wrap  An array of classes for the text wrap.
 * @var array<string,string> $classes_label An array of classes for the label.
 * @var array<string,string> $classes_input An array of classes for the text input.
 * @var string               $label         The label for the text input.
 * @var string               $id            ID of the text input.
 * @var string               $name          The name for the text input.
 * @var string               $placeholder   The placeholder for the text input.
 * @var array<string|mixed>  $page          The page data.
 * @var string               $value         The value of the text field.
 * @var array<string,string> $attrs         Associative array of attributes of the text input.
 */

$wrap_classes = [ 'tec-settings-control', 'tec-settings-control__text-wrap' ];
if ( ! empty( $classes_wrap ) ) {
	$wrap_classes = array_merge( $wrap_classes, $classes_wrap );
}

$label_classes = [ 'tec-settings-control__label' ];
if ( ! empty( $classes_label ) ) {
	$label_classes = array_merge( $label_classes, $classes_label );
}

$input_classes = [ 'tec-settings-control__text-input' ];
if ( ! empty( $classes_input ) ) {
	$input_classes = array_merge( $input_classes, $classes_input );
}
?>
<div <?php tribe_classes( $classes_wrap ); ?> >
	<label
		<?php tribe_classes( $classes_label ); ?>
		for="<?php echo esc_attr( $id ); ?>"
	>
		<?php echo esc_html( $label ); ?>
	</label>
	<input
		id="<?php echo esc_attr( $id ); ?>"
		<?php tribe_classes( $input_classes ); ?>
		type="text"
		name="<?php echo esc_attr( $name ); ?>"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
		<?php tribe_attributes( $attrs ); ?>
	>
</div>
