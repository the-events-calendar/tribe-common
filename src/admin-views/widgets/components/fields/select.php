<?php
/**
 * Admin View: Widget Component Select Field
 *
 * @since TBD
 *
 * @package Tribe\Common\Views\Widgets
 */

/**
 * @var array<string,mixed> $field The field data.
 */

$field_id = $field['id'];
$field_name = $field['name'];
$value = $field['value'];
?>

<p>
	<label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
	<select
		id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( $field_name ); ?>"
		class="widefat"
	>
		<?php foreach ( $field['options'] as $option_value => $option_label ) : ?>
			<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>>
				<?php echo esc_html( $option_label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>
