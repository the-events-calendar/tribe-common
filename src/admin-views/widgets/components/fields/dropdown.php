<?php
/**
 * Admin View: Widget Component Dropdown field.
 *
 * Administration Views cannot be overwritten by default from your theme.
 *
 * See more documentation about our views templating system.
 *
 * @link    http://evnt.is/1aiy
 *
 * @version 6.6.0
 *
 * @since 6.6.0 Added the parent_classes var and code to handle it.
 * @since 4.12.18
 * @var string                      $label          Label for the dropdown.
 * @var string                      $value          Value for the dropdown.
 * @var string                      $id             ID of the dropdown.
 * @var string                      $name           Name attribute for the dropdown.
 * @var string                      $parent_classes Classes for the dropdown parent.
 * @var string                      $classes        Classes to add to the dropdown.
 * @var string                      $dependency     The dependency attributes for the control wrapper.
 * @var array<array<string,string>> $options        An array of options in the format
 *                                                  [
 *                                                      'value => string|int,
 *                                                      'text' => string
 *                                                  ]
 */

use Tribe__Utils__Array as Arr;

$select_classes = array_merge( [ 'tribe-widget-form-control__input', ' widefat' ], Arr::list_to_array( $classes, ' ' ) );

$parent_classes = array_merge( [ 'tribe-widget-form-control', 'tribe-widget-form-control--dropdown' ], Arr::list_to_array( $parent_classes, ' ' ) );

?>
<div <?php tec_classes( $parent_classes ); ?>
	<?php
	// Not escaped - contains html (data-attr="value").
	echo $dependency; // phpcs:ignore
	?>
>
	<label
		class="tribe-widget-form-control__label"
		for="<?php echo esc_attr( $id ); ?>"
	>
		<?php echo esc_html( $label ); ?>
	</label>
	<select
		id="<?php echo esc_attr( $id ); ?>"
		name="<?php echo esc_attr( $name ); ?>"
		<?php tec_classes( $select_classes ); ?>
	>
		<?php foreach ( $options as $option ) { ?>
			<option
				value="<?php echo esc_attr( $option['value'] ); ?>"
				<?php selected( $value, $option['value'] ); ?>
			>
				<?php echo esc_html( $option['text'] ); ?>
			</option>
		<?php } ?>
	</select>
</div>
