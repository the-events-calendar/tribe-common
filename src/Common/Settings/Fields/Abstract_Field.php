<?php

namespace TEC\Common\Settings\Abstract_Field;

use Tribe__Debug;

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
	public $id;

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
	public $type;

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
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $allowed_args = [
		'attributes'          => 'array',
		'can_be_empty'        => 'bool',
		'class'               => 'array',
		'clear_after'         => 'bool',
		'error'               => 'bool',
		'fields'              => 'array',
		'fieldset_attributes' => 'array',
		'html'                => 'string',
		'if_empty'            => 'string',
		'label_attributes'    => 'array',
		'label'               => 'string',
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
	 * @var array
	 */
	public $defaults = [
		'attributes'          => [],
		'can_be_empty'        => false,
		'class'               => null,
		'clear_after'         => true,
		'error'               => false,
		'fields'              => [],
		'fieldset_attributes' => [],
		'html'                => null,
		'if_empty'            => null,
		'label_attributes'    => null,
		'label'               => null,
		'name'                => '',
		'options'             => null,
		'placeholder'         => null,
		'size'                => 'medium',
		'tooltip_first'       => false,
		'tooltip'             => null,
		'value'               => '',
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
	public function __construct( $id, $args, $value = null ) {
		// setup the defaults
		$this->defaults['name']  = $id;
		$this->defaults['value'] = $value;

		$this->valid_field_types = apply_filters( 'tribe_valid_field_types', $this->valid_field_types );

		// parse args with defaults and extract them
		$this->args = wp_parse_args( $args, $this->defaults );

		// sanitize the values just to be safe
		$this->id   = $id;
		$this->type = $args['type'];

		// set the ID
		$this->id = apply_filters( 'tribe_field_id', $id );
		$this->id = apply_filters( 'tribe_field_id', $id );

		// set each instance variable and filter
		foreach ( array_keys( $this->defaults ) as $key ) {
			$this->{$key} = apply_filters( 'tribe_field_' . $key, $$key, $this->id );
		}
	}

	/**
	 * The HTML output of the field.
	 *
	 * @since TBD
	 *
	 * @return string
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
			"tec_render_field_{$this->id}",
			true,
			$this->args,
			$this->value
		);

		if ( empty( $render ) ) {
			return;
		}
	};

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
	protected function concat_attributes( array $attributes = [] ) {
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
	 * returns the field's start
	 *
	 * @return string the field start
	 */
	public function do_fieldset_start() {
		$return = '<fieldset id="tribe-field-' . $this->id . '"';
		$return .= ' class="tribe-field tribe-field-' . $this->type;
		$return .= ( $this->error ) ? ' tribe-error' : '';
		$return .= ( $this->size ) ? ' tribe-size-' . $this->size : '';
		$return .= ( $this->class ) ? ' ' . $this->class . '"' : '"';
		$return .= ( $this->fieldset_attributes ) ? ' ' . $this->do_fieldset_attributes() : '';
		$return .= '>';

		return apply_filters( 'tec_fieldset_start', $return, $this->id, $this->type, $this->error, $this->class, $this );
	}

	/**
	 * returns the field's end
	 *
	 * @return string the field end
	 */
	public function do_field_end() {
		$return = '</fieldset>';
		$return .= ( $this->clear_after ) ? '<div class="clear"></div>' : '';

		return apply_filters( 'tribe_field_end', $return, $this->id, $this );
	}

	/**
	 * returns the field's label
	 *
	 * @return string the field label
	 */
	public function do_field_label() {
		$return = '';
		if ( $this->label ) {
			if ( isset( $this->label_attributes ) ) {
				$this->label_attributes['class'] = isset( $this->label_attributes['class'] ) ?
					implode( ' ', array_merge( [ 'tribe-field-label' ], $this->label_attributes['class'] ) ) :
					[ 'tribe-field-label' ];
				$this->label_attributes = $this->concat_attributes( $this->label_attributes );
			}
			$return = sprintf( '<legend class="tribe-field-label" %s>%s</legend>', $this->label_attributes, $this->label );
		}

		return apply_filters( 'tribe_field_label', $return, $this->label, $this );
	}

	/**
	 * returns the field's div start
	 *
	 * @return string the field div start
	 */
	public function do_field_div_start() {
		$return = '<div class="tribe-field-wrap">';

		if ( true === $this->tooltip_first ) {
			$return .= $this->do_tool_tip();
			// and empty it to avoid it from being printed again
			$this->tooltip = '';
		}

		return apply_filters( 'tribe_field_div_start', $return, $this );
	}

	/**
	 * returns the field's div end
	 *
	 * @return string the field div end
	 */
	public function do_field_div_end() {
		$return = $this->do_tool_tip();
		$return .= '</div>';

		return apply_filters( 'tribe_field_div_end', $return, $this );
	}

	/**
	 * returns the field's tooltip/description
	 *
	 * @return string the field tooltip
	 */
	public function do_tool_tip() {
		$return = '';
		if ( $this->tooltip ) {
			$return = '<p class="tooltip description">' . $this->tooltip . '</p>';
		}

		return apply_filters( 'tribe_field_tooltip', $return, $this->tooltip, $this );
	}

	/**
	 * returns the screen reader label
	 *
	 * @return string the screen reader label
	 */
	public function do_screen_reader_label() {
		$return = '';
		if ( $this->tooltip ) {
			$return = '<label class="screen-reader-text">' . $this->tooltip . '</label>';
		}

		return apply_filters( 'tribe_field_screen_reader_label', $return, $this->tooltip, $this );
	}

	/**
	 * returns the field's value
	 *
	 * @return string the field value
	 */
	public function do_field_value() {
		$return = '';
		if ( $this->value ) {
			$return = ' value="' . $this->value . '"';
		}

		return apply_filters( 'tribe_field_value', $return, $this->value, $this );
	}

	/**
	 * returns the field's name
	 *
	 * @param bool $multi
	 *
	 * @return string the field name
	 */
	public function do_field_name( $multi = false ) {
		$return = '';
		if ( $this->name ) {
			if ( $multi ) {
				$return = ' name="' . $this->name . '[]"';
			} else {
				$return = ' name="' . $this->name . '"';
			}
		}

		return apply_filters( 'tribe_field_name', $return, $this->name, $this );
	}

	/**
	 * returns the field's placeholder
	 *
	 * @return string the field value
	 */
	public function do_field_placeholder() {
		$return = '';
		if ( $this->placeholder ) {
			$return = ' placeholder="' . $this->placeholder . '"';
		}

		return apply_filters( 'tribe_field_placeholder', $return, $this->placeholder, $this );
	}

	/**
	 * Return a string of attributes for the field
	 *
	 * @return string
	 **/
	public function do_field_attributes() {
		$return = '';
		if ( ! empty( $this->attributes ) ) {
			foreach ( $this->attributes as $key => $value ) {
				$return .= ' ' . $key . '="' . $value . '"';
			}
		}

		return apply_filters( 'tribe_field_attributes', $return, $this->name, $this );
	}

	/**
	 * Return a string of attributes for the fieldset
	 *
	 * @return string
	 **/
	public function do_fieldset_attributes() {
		$return = '';
		if ( ! empty( $this->fieldset_attributes ) ) {
			foreach ( $this->fieldset_attributes as $key => $value ) {
				$return .= ' ' . $key . '="' . $value . '"';
			}
		}

		return apply_filters( 'tribe_fieldset_attributes', $return, $this->name, $this );
	}
}
