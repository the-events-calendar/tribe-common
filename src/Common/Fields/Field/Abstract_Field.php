<?php

namespace TEC\Common\Fields\Field;

/**
 * Abstract class for creating fields for use in Settings.
 *
 * @since TBD
 */
abstract class Abstract_Field {

	/**
	 * The field arguments.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $args;

	/**
	 * The field's attributes.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $attributes = [];

	/**
	 * The field's id.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $id;

	/**
	 * The field's name (also known as it's label)
	 * @var string
	 */
	public $name;

	/**
	 * The field type.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $type;

	/**
	 * Holds passed classes.
	 *
	 * @since TBD
	 *
	 * @var string|array<string>
	 */
	public $class;

	/**
	 * Holds default classes.
	 *
	 * @since TBD
	 *
	 * @var array<string>
	 */
	public $default_classes = [];

	/**
	 * The field's value.
	 *
	 * @since TBD
	 *
	 * @var mixed
	 */
	public $value = null;

	/**
	 * The allowed arguments.
	 * In the format slug -> allowed data type.
	 *
	 * @since TBD
	 *
	 * @var array<string,string>
	 */
	public $allowed_args = [
		'attributes'          => 'array',
		'can_be_empty'        => 'bool',
		'class'               => 'array',
		'clear_after'         => 'bool',
		'content'             => 'string',
		'error'               => 'bool',
		'fields'              => 'array',
		'fieldset_attributes' => 'array',
		'html'                => 'string',
		'if_empty'            => 'string',
		'label_attributes'    => 'array',
		'label'               => 'string',
		'level'               => 'string',
		'name'                => 'name',
		'options'             => 'array',
		'placeholder'         => 'string',
		'size'                => 'medium',
		'tooltip_first'       => 'bool',
		'tooltip'             => 'string',
		'value'               => 'mixed',
	];

	/**
	 * Field defaults.
	 * These are used when the field has not been saved yet.
	 *
	 * @since TBD
	 *
	 * @var array<string,mixed>
	 */
	public $defaults = [
		'attributes'          => [],
		'can_be_empty'        => false,
		'class'               => [],
		'content'             => null,
		'error'               => false,
		'fields'              => [],
		'fieldset_attributes' => [],
		'html'                => null,
		'if_empty'            => null,
		'label_attributes'    => [],
		'label'               => null,
		'level'               => null,
		'name'                => '',
		'options'             => [],
		'placeholder'         => null,
		'size'                => 'medium',
		'tooltip_first'       => false,
		'tooltip'             => null,
		'value'               => null,
	];

	/**
	 * Class constructor.
	 *
	 * @since TBD
	 *
	 * @param string     $id    The field id.
	 * @param array      $args  The field settings.
	 * @param null|mixed $value The field's current value.
	 *
	 * @return void
	 */
	public function __construct( $id, $args ) {
		// Set up some defaults
		$this->defaults['name']  = $id;

		// Parse args with defaults and extract them.
		$this->args = wp_parse_args( $args, $this->defaults );
		self::$type = $args['type'];

		// Normalize our ID.
		self::$id = $this->normalize_id( $id );

		// Set each instance variable and filter.
		foreach ( array_keys( $this->args ) as $key => $value) {
			// If a custom or incorrect arg is passed, skip over it.
			if ( ! isset( $this->allowed_args[ $key ] ) ) {
				continue;
			}

			$this->{$key} = apply_filters( 'tec_settings_field_' . $key, $value, self::$id );
		}

		// Convert class arrays to a string.
		$this->class = $this->normalize_class();

		// Handle saved and default values.
		$this->value = $this->normalize_value();

		// Convert attribute arrays to a string.
		if ( ! empty( $this->attributes ) ) {
			$this->attributes = $this->concat_attributes( $this->attributes );
		}
	}

	/**
	 * The HTML output of the field.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render() {
		/**
		 * Allows hiding fields from the admin - useful when you want to prevent changing the defaults.
		 *
		 * @since TBD
		 *
		 * @param bool $render
		 */
		$render = apply_filters(
			'tec_render_field_' . self::$id,
			true,
			$this->args,
			$this->value
		);

		if ( empty( $render ) ) {
			return;
		}

		echo '';
	}

	/**
	 * Concatenates an array of attributes to use in HTML tags.
	 *
	 * Example usage:
	 *
	 *      $attrs = [ 'class' => ['one', 'two'], 'style' => 'color:red;' ];
	 *      printf ( '<p %s>%s</p>', tribe_concat_attributes( $attrs ), 'bar' );
	 *
	 *      // <p> class="one two" style="color:red;">bar</p>
	 *
	 * @param array $attributes An array of attributes in the format
	 *                          [<attribute1> => <value>, <attribute2> => <value>]
	 *                          where `value` can be a string or an array.
	 *
	 * @return string The concatenated attributes.
	 */
	protected function concat_attributes( array $attributes = [] ): string {
		if ( empty( $attributes ) ) {
			return '';
		}

		$concat = [];

		foreach ( $attributes as $attribute => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ' ', $value );
			}
			$quote     = false !== strpos( $value, '"' ) ? "'" : '"';
			$concat[] = esc_attr( $attribute ) . '=' . $quote . esc_attr( $value ) . $quote;
		}

		return implode( ' ', $concat );
	}

	/**
	 * Ensure if we pass an ID as part of $this->attributes
	 * it overrides the auto ID from the array.
	 *
	 * @since TBD
	 *
	 * @param string $id
	 * @return string
	 */
	protected function normalize_id( $id ): string {
		if ( empty( $this->attributes['id'] ) ) {
			return $id;
		}

		$id = $this->attributes['id'];

		// Remove it from the attributes array, we're done with it.
		unset( $this->attributes['id'] );

		return apply_filters( 'tec_settings_field_id', $id, $this );
	}

	/**
	 * Ensure if we pass classes as part of $this->attributes
	 * they are combined with the input's default classes.
	 *
	 * We convert both to an array to eliminate duplicates,
	 * then merge them and convert the resulting array to a string.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function normalize_class(): string {
		if ( empty( $this->attributes['class'] ) && empty( $this->default_classes ) ) {
			return '';
		}

		$passed_classes = [];

		if ( ! empty( $this->attributes['class'] ) ) {
			$passed_classes = $this->attributes['class'];

			if ( is_string( $passed_classes ) ) {
				$passed_classes = explode( ' ', $passed_classes );
			}
		}

		// Remove it from the attributes array, we're done with it.
		unset( $this->attributes['class'] );

		if ( ! empty( $default_classes ) ) {
			if ( is_string( $this->default_classes ) ) {
				$default_classes = explode( ' ', $this->default_classes );
			}
		}

		$classes = array_merge( $default_classes, $passed_classes );

		$classes = implode( ' ', $classes );

		return apply_filters( 'tec_settings_field_classes', $classes, $this );
	}

	/**
	 * Handles various possible passed or saved values and defaults.
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	protected function normalize_value() {
		$value  = ! empty( $this->attributes['default'] ) ? $this->attributes['default'] : null;
		$value = ! empty( $this->attributes['value'] ) ? $this->attributes['value'] : $value;

		// Remove these from the attributes array, we're done with them.
		unset( $this->attributes['default'] );
		unset( $this->attributes['value'] );

		$value =  tribe_get_option( $this->name, $value );

		return apply_filters( 'tec_settings_field_value', $value, $this );

	}

	/**
	 * Handles output of the passed attributes.
	 *
	 * @since TBD
	 */
	protected function do_attributes() {
		if ( empty( $this->attributes ) ) {
			return;
		}

		// Escaped in concat_attributes() above.
		echo $this->attributes;
	}

	/**
	 * returns the field's tooltip/description
	 *
	 * @return string the field tooltip
	 */
	protected function do_tool_tip() {
		$return = '';
		if ( $this->tooltip ) {
			$return = '<p class="tooltip description">' . $this->tooltip . '</p>';
		}

		return apply_filters( 'tec_settings_field_tooltip', $return, $this->tooltip, $this );
	}

	/**
	 * returns the screen reader label
	 *
	 * @return string the screen reader label
	 */
	protected function do_screen_reader_label() {
		$return = '';
		if ( $this->tooltip ) {
			$return = '<label class="screen-reader-text">' . $this->tooltip . '</label>';
		}

		return apply_filters( 'tec_settings_field_screen_reader_label', $return, $this->tooltip, $this );
	}
}
