<?php

use Tribe\Admin\Settings;
use Tribe\Admin\Wysiwyg;

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__Field' ) ) {
	/**
	 * helper class that creates fields for use in Settings, MetaBoxes, Users, anywhere.
	 * Instantiate it whenever you need a field
	 *
	 * @method doField()
	 * @method doFieldStart()
	 * @method doFieldEnd()
	 * @method doFieldLabel()
	 * @method doFieldDivStart()
	 * @method doFieldDivEnd()
	 * @method doToolTip()
	 * @method doFieldValue()
	 * @method doFieldName()
	 * @method doFieldAttributes()
	 * @method doScreenReaderLabel()
	 */
	class Tribe__Field {

		/**
		 * the field's id
		 * @var string
		 */
		public $id;

		/**
		 * the field's name (also known as it's label)
		 * @var string
		 */
		public $name;

		/**
		 * the fieldset attributes
		 * @var array
		 */
		public $fieldset_attributes;

		/**
		 * the field attributes
		 * @var array
		 */
		public $attributes;

		/**
		 * the field's arguments
		 * @var array
		 */
		public $args;

		/**
		 * field defaults (static)
		 * @var array
		 */
		public $defaults;

		/**
		 * valid field types (static)
		 * @var array
		 */
		public $valid_field_types;

		/**
		 * Settings array.
		 *
		 * @since 5.0.12
		 *
		 * @var array
		 */
		public $settings;

		/**
		 * @var string
		 */
		public $type;

		/**
		 * @var string
		 */
		public $class;

		/**
		 * @var string
		 */
		public $label;

		/**
		 * @var array
		 */
		public $label_attributes;

		/**
		 * @var string
		 */
		public $error;

		/**
		 * @var string
		 */
		public $tooltip;

		/**
		 * @var string
		 */
		public $size;

		/**
		 * @var string
		 */
		public $html;

		/**
		 * @var array
		 */
		public $options;

		/**
		 * @var mixed
		 */
		public $value;

		/**
		 * @var boolean
		 */
		public $conditional;

		/**
		 * @var string
		 */
		public $placeholder;

		/**
		 * @var closure
		 */
		public $display_callback;

		/**
		 * @var string
		 */
		public $if_empty;

		/**
		 * @var boolean
		 */
		public $can_be_empty;

		/**
		 * @var boolean
		 */
		public $clear_after;

		/**
		 * @var boolean
		 */
		public $tooltip_first;

		/**
		 * @var boolean
		 */
		public $allow_clear;

		/**
		 * @var string
		 */
		public $append;

		/**
		 * The raw field data.
		 *
		 * @var array
		 */
		protected $raw_field_data;

		/**
		 * Class constructor
		 *
		 * @param string     $id    The field id.
		 * @param array      $field The field settings.
		 * @param null|mixed $value The value passed when saving the field.
		 *
		 * @return void
		 */
		public function __construct( $id, $field, $value = null ) {
			// Store the raw field data.
			$this->raw_field_data = $field;

			// Set the ID.
			$id       = is_null( $id ) ? null : esc_attr( $id );
			$this->id = apply_filters( 'tribe_field_id', $id );

			// Figure out the field value.
			$value = $this->setup_field_value( $value );

			// Set up the defaults.
			$this->defaults = [
				'allow_clear'         => false,
				'append'              => '',
				'attributes'          => [],
				'can_be_empty'        => false,
				'class'               => null,
				'clear_after'         => false,
				'conditional'         => true,
				'display_callback'    => null,
				'error'               => false,
				'fieldset_attributes' => [],
				'html'                => null,
				'if_empty'            => null,
				'label_attributes'    => null,
				'label'               => null,
				'name'                => $id,
				'options'             => null,
				'placeholder'         => null,
				'settings'            => [],
				'size'                => 'medium',
				'tooltip_first'       => false,
				'tooltip'             => null,
				'type'                => 'html',
				'value'               => $value,
			];

			// Merge the defaults with the passed args.
			$args       = wp_parse_args( $field, $this->defaults );
			$this->args = $args;

			// Set the valid field types.
			$this->setup_field_types();

			// todo: move this to a separate method that runs just before the field output is generated.
			// sanitize the values just to be safe
			$type       = is_null( $args['type'] ) ? null : esc_attr( $args['type'] );
			$name       = is_null( $args['name'] ) ? null : esc_attr( $args['name'] );
			$placeholder = is_null( $args['placeholder'] ) ? null : esc_attr( $args['placeholder'] );
			$class = empty( $args['class'] ) ? '' : $this->sanitize_class_attribute( $args['class'] );
			$label      = is_null( $args['label'] ) ? null : wp_kses(
				$args['label'], [
					'a'      => [
						'href' => [],
						'title' => [],
						'class' => [],
						'style'  => [],
					],
					'br'     => [],
					'em'     => [],
					'strong' => [],
					'b'      => [],
					'i'      => [],
					'u'      => [],
					'img'    => [
						'title' => [],
						'src'   => [],
						'alt'   => [],
					],
					'span'      => [
						'class' => [],
						'style' => [],
					],
				]
			);
			$label_attributes = $args['label_attributes'];
			$tooltip    = is_null( $args['tooltip'] ) ? null : wp_kses(
				$args['tooltip'], [
					'a'      => [
						'class'  => [],
						'href'   => [],
						'title'  => [],
						'target' => [],
						'rel'    => [],
						'style'  => [],
					],
					'br'     => [],
					'em'     => [ 'class' => [] ],
					'strong' => [ 'class' => [] ],
					'b'      => [ 'class' => [] ],
					'i'      => [ 'class' => [] ],
					'u'      => [ 'class' => [] ],
					'img'    => [
						'class' => [],
						'title' => [],
						'src'   => [],
						'alt'   => [],
						'style' => [],
					],
					'code'   => [
						'class' => [],
						'style' => [],],
					'span'   => [
						'class' => [],
						'style' => [],
					],
				]
			);
			$fieldset_attributes = [];
			if ( is_array( $args['fieldset_attributes'] ) ) {
				foreach ( $args['fieldset_attributes'] as $key => $val ) {
					$fieldset_attributes[ $key ] = esc_attr( $val );
				}
			}
			$attributes = [];
			if ( is_array( $args['attributes'] ) ) {
				foreach ( $args['attributes'] as $key => $val ) {
					$attributes[ $key ] = esc_attr( $val );
				}
			}
			if ( is_array( $args['options'] ) ) {
				$options = [];
				foreach ( $args['options'] as $key => $val ) {
					$options[ $key ] = $val;
				}
			} else {
				$options = $args['options'];
			}
			$size             = is_null( $value ) ? null : esc_attr( $args['size'] );
			$html             = $args['html'];
			$error            = (bool) $args['error'];
			$value            = is_null( $value ) ? null : ( is_array( $value )  ? array_map( 'esc_attr', $value ) : esc_attr( $value ) );
			$conditional      = $args['conditional'];
			$display_callback = $args['display_callback'];
			$if_empty         = is_string( $args['if_empty'] ) ? trim( $args['if_empty'] ) : $args['if_empty'];
			$can_be_empty     = (bool) $args['can_be_empty'];
			$clear_after      = (bool) $args['clear_after'];
			$tooltip_first    = (bool) $args['tooltip_first'];
			$allow_clear      = (bool) $args['allow_clear'];
			$settings         = $args['settings'];
			$append           = $args['append'];

			// set each instance variable and filter
			foreach ( array_keys( $this->defaults ) as $key ) {
				$this->{$key} = apply_filters( 'tribe_field_' . $key, $$key, $this->id );
			}
		}

		/**
		 * Set up the valid field types.
		 *
		 * @since 6.1.0
		 *
		 * @return void
		 */
		protected function setup_field_types() {
			// Define a list of valid field types.
			$valid_field_types = [
				'checkbox_bool',
				'checkbox_list',
				'color',
				'dropdown',
				'email',
				'heading',
				'html',
				'image',
				'image_id',
				'license_key',
				'number',
				'radio',
				'text',
				'textarea',
				'toggle',
				'wrapped_html',
				'wysiwyg',

				// Deprecated field types.
				'dropdown_select2', // Use the 'dropdown' type.
				'dropdown_chosen', // Use the 'dropdown' type.
			];

			/**
			 * Filter the valid field types.
			 *
			 * @param array $valid_field_types The valid field types.
			 */
			$this->valid_field_types = (array) apply_filters( 'tribe_valid_field_types', $valid_field_types );
		}

		/**
		 * Determines how to handle this field's creation.
		 *
		 * Either calls a callback function or runs this class' course of action.
		 * Logs an error if it fails.
		 *
		 * @return void
		 */
		public function do_field() {
			if ( ! $this->conditional ) {
				return;
			}

			// If there's a callback, run it.
			if ( $this->display_callback && is_callable( $this->display_callback ) ) {
				call_user_func( $this->display_callback );
				return;
			}

			// If the field type is valid, call the appropriate method.
			if ( in_array( $this->type, $this->valid_field_types ) ) {
				$field = call_user_func( [ $this, $this->type ] );

				/**
				 * Filter the field output.
				 *
				 * @param string       $field        The field output.
				 * @param string       $id           The field ID.
				 * @param Tribe__Field $field_object The field object.
				 */
				$field = apply_filters( "tribe_field_output_{$this->type}", $field, $this->id, $this );

				/**
				 * Filter the field output by ID.
				 *
				 * @param string       $field        The field output.
				 * @param string       $id           The field ID.
				 * @param Tribe__Field $field_object The field object.
				 */
				$field = apply_filters( "tribe_field_output_{$this->type}_{$this->id}", $field, $this->id, $this );

				/**
				 * Filter the allowed tags to facilitate the wp_kses() call.
				 *
				 * @see wp_kses_allowed_html()
				 *
				 * @param array $allowedtags The allowed tags.
				 * @param string $context The context in which the tags are being used.
				 *
				 * @return array The allowed tags.
				 */
				$kses_allowed_html = function ( $allowedtags, $context ) {
					// If it's not the right context, return the allowed tags as-is.
					if ( 'tribe-field' !== $context ) {
						return $allowedtags;
					}

					static $tags = null;

					// If we've already set the tags, return them.
					if ( null !== $tags ) {
						return $tags;
					}

					// Ensure we have the elements we need in the allowed tags.
					global $allowedposttags;
					$tags = $allowedposttags;

					$common_attributes = _wp_add_global_attributes(
						[
							'checked'     => true,
							'disabled'    => true,
							'name'        => true,
							'readonly'    => true,
							'selected'    => true,
							'type'        => true,
							'value'       => true,
							'cols'        => true,
							'placeholder' => true,
						]
					);

					$tags['input']    = $common_attributes;
					$tags['span']     = [
						'class' => true,
						'style' => true,
					];
					$tags['textarea'] = $common_attributes;
					$tags['select']   = $common_attributes;
					$tags['option']   = $common_attributes;
					$tags['fieldset'] = _wp_add_global_attributes( [] );
					// Allow svg and paths for icons.
					$tags['svg']      = _wp_add_global_attributes(
						[
							'fill'    => [],
							'g'	      => [],
							'height'  => [],
							'viewbox' => [],
							'width'   => [],
							'xmlns'   => [],
						]
					);
					$tags['path']	  = [
						'd'              => [],
						'fill'           => [],
						'stroke'         => [],
						'stroke-linecap' => [],
					];

					// Allow the script and template tags for HTML fields (inserting script localization, js templates).
					if ( $this->type === 'html' || $this->type === 'wrapped_html' ) {
						$tags['script']   = _wp_add_global_attributes( [ 'type' => true ] );
						$tags['template'] = _wp_add_global_attributes( [ 'type' => true ] );
					}

					return $tags;
				};

				add_filter( 'wp_kses_allowed_html', $kses_allowed_html, 10, 2 );

				echo wp_kses( $field, 'tribe-field', self::get_kses_protocols() );

				remove_filter( 'wp_kses_allowed_html', $kses_allowed_html );

				return;
			}

			// If we got to this point, fail and log the error.
			Tribe__Debug::debug( esc_html__( 'Invalid field type specified', 'tribe-common' ), $this->type, 'notice' );
		}

		/**
		 * returns the field's start
		 *
		 * @return string the field start
		 */
		public function do_field_start() {
			$return = '<fieldset id="tribe-field-' . $this->id . '"';
			$return .= ' class="tribe-field tribe-field-' . $this->type;
			$return .= ( $this->error ) ? ' tribe-error' : '';
			$return .= ( $this->size ) ? ' tribe-size-' . $this->size : '';
			$return .= ( $this->class ) ? ' ' . $this->class . '"' : '"';
			$return .= ( $this->fieldset_attributes ) ? ' ' . $this->do_fieldset_attributes() : '';
			$return .= '>';

			return apply_filters( 'tribe_field_start', $return, $this->id, $this->type, $this->error, $this->class, $this );
		}

		/**
		 * Returns the html appended to the fieldset's end
		 *
		 * @since 6.1.0
		 *
		 * @return string the field append.
		 */
		public function do_field_append(): string {
			if ( empty( $this->append ) ) {
				return '';
			}

			return $this->append;
		}

		/**
		 * Returns the field's end.
		 *
		 * @return string the field end.
		 */
		public function do_field_end() {
			$return  = $this->do_field_append();
			$return .= '</fieldset>';
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

				if ( ! empty( $this->attributes['data-source'] ) ) {
					$return .= ' data-source-nonce="' . esc_attr( wp_create_nonce( 'tribe_dropdown' ) ) . '"';
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

		/**
		 * generate a heading field
		 *
		 * @return string the field
		 */
		public function heading() {
			ob_start();
			?>
			<h3 <?php tribe_classes( $this->class ); ?>><?php echo esc_html( $this->label ); ?></h3>
			<?php
			return ob_get_clean();
		}

		/**
		 * generate an html field
		 *
		 * @return string the field
		 */
		public function html() {
			$field = $this->do_field_label();
			$field .= $this->html;

			return $field;
		}

		/**
		 * generate a simple text field
		 *
		 * @return string the field
		 */
		public function text() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input';
			$field .= ' type="text"';
			$field .= $this->do_field_name();
			$field .= $this->do_field_value();
			$field .= $this->do_field_placeholder();
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a textarea field
		 *
		 * @return string the field
		 */
		public function textarea() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<textarea';
			$field .= $this->do_field_name();
			$field .= $this->do_field_attributes();
			$field .= '>';
			$field .= esc_html( stripslashes( $this->value ) );
			$field .= '</textarea>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a wp_editor field
		 *
		 * @return string the field
		 */
		public function wysiwyg() {
			$mce = new Wysiwyg( $this->name, $this->value, $this->settings );
			$field  = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= $mce->get_html();
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a radio button field
		 *
		 * @return string the field
		 */
		public function radio() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			if ( is_array( $this->options ) ) {
				foreach ( $this->options as $option_id => $title ) {
					$field_id = sprintf(
						'%1$s-%2$s',
						sanitize_html_class( trim( $this->id ) ),
						sanitize_html_class( trim( $option_id ) )
					);

					$field .= '<label title="' . esc_attr( strip_tags( $title ) ) . '">';
					$field .= '<input type="radio"';
					$field .= ' id="tribe-field-' . esc_attr( $field_id ) . '"';
					$field .= $this->do_field_name();
					$field .= ' value="' . esc_attr( $option_id ) . '" ' . checked( $this->value, $option_id, false ) . '/>';
					$field .= $title;
					$field .= '</label>';
				}
			} else {
				$field .= '<span class="tribe-error">' . esc_html__( 'No radio options specified', 'tribe-common' ) . '</span>';
			}
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a checkbox_list field
		 *
		 * @return string the field
		 */
		public function checkbox_list() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();

			if ( ! is_array( $this->value ) ) {
				if ( ! empty( $this->value ) ) {
					$this->value = [ $this->value ];
				} else {
					$this->value = [];
				}
			}

			if ( is_array( $this->options ) ) {
				foreach ( $this->options as $option_id => $title ) {
					$field .= '<label title="' . esc_attr( $title ) . '">';
					$field .= '<input type="checkbox"';
					$field .= $this->do_field_name( true );
					$field .= ' value="' . esc_attr( $option_id ) . '" ' . checked( in_array( $option_id, $this->value ), true, false ) . '/>';
					$field .= $title;
					$field .= '</label>';
				}
			} else {
				$field .= '<span class="tribe-error">' . esc_html__( 'No checkbox options specified', 'tribe-common' ) . '</span>';
			}
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a boolean checkbox field
		 *
		 * @return string the field
		 */
		public function checkbox_bool() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input type="checkbox"';
			$field .= $this->do_field_name();
			$field .= ' value="1" ' . checked( $this->value, true, false );
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a dropdown field
		 *
		 * @return string the field
		 */
		public function dropdown() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			if ( is_array( $this->options ) && ! empty( $this->options ) ) {
				$field .= '<select';
				$field .= $this->do_field_name();
				$field .= " id='{$this->id}-select'";
				$field .= " class='tribe-dropdown'";
				if ( empty( $this->allow_clear ) ) {
					$field .= " data-prevent-clear='true'";
				}
				$field .= $this->do_field_attributes();
				$field .= '>';
				foreach ( $this->options as $option_id => $title ) {
					$field .= '<option value="' . esc_attr( $option_id ) . '"';
					if ( is_array( $this->value ) ) {
						$field .= isset( $this->value[0] ) ? selected( $this->value[0], $option_id, false ) : '';
					} else {
						$field .= selected( $this->value, $option_id, false );
					}
					$field .= '>' . esc_html( $title ) . '</option>';
				}
				$field .= '</select>';
				$field .= $this->do_screen_reader_label();
			} elseif ( $this->if_empty ) {
				$field .= '<span class="empty-field">' . (string) $this->if_empty . '</span>';
			} else {
				$field .= '<span class="tribe-error">' . esc_html__( 'No select options specified', 'tribe-common' ) . '</span>';
			}
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * generate a chosen dropdown field - the same as the
		 * regular dropdown but wrapped so it can have the
		 * right css class applied to it
		 *
		 * @deprecated
		 *
		 * @return string the field
		 */
		public function dropdown_chosen() {
			$field = $this->dropdown();

			return $field;
		}

		/**
		 * generate a select2 dropdown field - the same as the
		 * regular dropdown but wrapped so it can have the
		 * right css class applied to it
		 *
		 * @deprecated
		 *
		 * @return string the field
		 */
		public function dropdown_select2() {
			$field = $this->dropdown();

			return $field;
		}

		/**
		 * generate a license key field
		 *
		 * @return string the field
		 */
		public function license_key() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input';
			$field .= ' type="text"';
			$field .= $this->do_field_name();
			$field .= $this->do_field_value();
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= '<p class="license-test-results"><img src="' . esc_url( admin_url( 'images/wpspin_light.gif' ) ) . '" class="ajax-loading-license" alt="Loading" style="display: none"/>';
			$field .= '<span class="key-validity"></span>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * Generate a color field.
		 *
		 * @since 5.0.0
		 *
		 * @return string The field.
		 */
		public function color() {

			tribe( Settings::class )->maybe_load_color_field_assets();

			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input';
			$field .= ' type="text"';
			$field .= ' class="tec-admin__settings-color-field-input"';
			$field .= $this->do_field_name();
			$field .= $this->do_field_value();
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * Generate an image field.
		 *
		 * @since 5.0.0
		 *
		 * @return string The field.
		 */
		public function image() {

			tribe( Settings::class )->maybe_load_image_field_assets();

			$image_exists = ! empty( $this->value );
			$upload_image_text = esc_html__( 'Select Image', 'tribe-common' );
			$remove_image_text = esc_html__( 'Remove Image', 'tribe-common' );

			// Add default fieldset attributes if none exist.
			$image_fieldset_attributes = [
				'data-select-image-text' => esc_html__( 'Select an image', 'tribe-common' ),
				'data-use-image-text'    => esc_html__( 'Use this image', 'tribe-common' ),
			];
			$this->fieldset_attributes = array_merge( $image_fieldset_attributes, $this->fieldset_attributes );

			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input';
			$field .= ' type="hidden"';
			$field .= ' class="tec-admin__settings-image-field-input"';
			$field .= $this->do_field_name();
			$field .= $this->do_field_value();
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= '<button type="button" class="button tec-admin__settings-image-field-btn-add">' . $upload_image_text . '</button>';
			$field .= '<div class="tec-admin__settings-image-field-image-container hidden">';
			if ( $image_exists ) {
				$field .= '<img src="' . esc_url( $this->value ) . '" />';
			}
			$field .= '</div>';
			$field .= '<button class="tec-admin__settings-image-field-btn-remove hidden">' . $remove_image_text . '</button>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * Generate an image field that uses the attachment instead of URL.
		 *
		 * @since 5.1.15
		 *
		 * @return string The field.
		 */
		public function image_id() {

			tribe( Settings::class )->maybe_load_image_field_assets();

			$image_exists = ! empty( $this->value );
			$upload_image_text = esc_html__( 'Select Image', 'tribe-common' );
			$remove_image_text = esc_html__( 'Remove Image', 'tribe-common' );

			// Add default fieldset attributes if none exist.
			$image_fieldset_attributes = [
				'data-select-image-text' => esc_html__( 'Select an image', 'tribe-common' ),
				'data-use-image-text'    => esc_html__( 'Use this image', 'tribe-common' ),
				'data-image-id'          => 1,
			];
			$this->fieldset_attributes = array_merge( $image_fieldset_attributes, $this->fieldset_attributes );

			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input type="hidden" class="tec-admin__settings-image-field-input"';
			$field .= $this->do_field_name();
			$field .= $this->do_field_value();
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= '<button type="button" class="button tec-admin__settings-image-field-btn-add">' . $upload_image_text . '</button>';
			$field .= '<div class="tec-admin__settings-image-field-image-container hidden">';
			if ( $image_exists ) {
				$field .= '<img src="' . esc_url( wp_get_attachment_image_url( $this->value, 'medium' ) ) . '" />';
			}
			$field .= '</div>';
			$field .= '<button class="tec-admin__settings-image-field-btn-remove hidden">' . $remove_image_text . '</button>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * Generate a toggle switch.
		 *
		 * @since 5.0.12
		 *
		 * @return string the field
		 */
		public function toggle() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= '<input type="checkbox"';
			$field .= ' class="tec-admin__settings-toggle-field-input"';
			$field .= $this->do_field_name();
			$field .= ' value="1" ' . checked( $this->value, true, false );
			$field .= $this->do_field_attributes();
			$field .= '/>';
			$field .= '<span class="tec-admin__settings-toggle-field-span"></span>';
			$field .= $this->do_screen_reader_label();
			$field .= $this->do_field_div_end();
			$field .= $this->do_field_end();

			return $field;
		}

		/**
		 * Set up the field value based on submitted value or the stored value.
		 *
		 * @param mixed $sent_value The value submitted by the user.
		 *
		 * @return mixed The field value.
		 */
		protected function setup_field_value( $sent_value = null ) {
			$value = $sent_value ?? $this->get_field_value();

			// Escape the value for display.
			if ( ! empty( $field['esc_display'] ) && function_exists( $field['esc_display'] ) ) {
				$value = $field['esc_display']( $value );
			} elseif ( is_string( $value ) ) {
				$value = esc_attr( stripslashes( $value ) );
			}

			/**
			 * Filter the value of the option before it is displayed.
			 *
			 * @param mixed  $value The value of the option.
			 * @param string $key   The key of the option.
			 * @param array  $field The field array.
			 */
			return apply_filters( 'tribe_settings_get_option_value_pre_display', $value, $this->id, $this->raw_field_data );
		}

		/**
		 * Get the field value.
		 *
		 * @return mixed The field value.
		 */
		protected function get_field_value() {
			// Some options should always be stored at network level.
			$network_option = (bool) ( $this->raw_field_data['network'] ?? false );

			if ( is_network_admin() ) {
				$parent_option = $this->raw_field_data['parent_option'] ?? Tribe__Main::OPTIONNAMENETWORK;
			} else {
				$parent_option = $this->raw_field_data['parent_option'] ?? Tribe__Main::OPTIONNAME;
			}

			/**
			 * Get the field's parent_option in order to later get the field's value.
			 *
			 * @param string $parent_option The parent option name.
			 * @param string $key           The field key.
			 */
			$parent_option = apply_filters( 'tribe_settings_do_content_parent_option', $parent_option, $this->id );

			// Determine the default value.
			$default = $this->raw_field_data['default'] ?? null;

			/**
			 * Filter the default value of the field.
			 *
			 * @param mixed $default The default value of the field.
			 * @param array $field   The field array.
			 */
			$default = apply_filters( 'tribe_settings_field_default', $default, $this->raw_field_data );

			// If there's no parent option, get the site option (for network admin) or the option.
			if ( ! $parent_option ) {
				return ( $network_option || is_network_admin() )
					? get_site_option( $this->id, $default )
					: get_option( $this->id, $default );
			}

			// Get the options from Tribe__Settings_Manager if we're getting the main array.
			if ( $parent_option === Tribe__Main::OPTIONNAME ) {
				return Tribe__Settings_Manager::get_option( $this->id, $default );
			}

			// Get the network options from Tribe__Settings_Manager.
			if ( $parent_option === Tribe__Main::OPTIONNAMENETWORK ) {
				return Tribe__Settings_Manager::get_network_option( $this->id, $default );
			}

			// Get the parent option for network admin.
			if ( is_network_admin() ) {
				$options = (array) get_site_option( $parent_option );

				return $options[ $this->id ] ?? $default;
			}

			// Else, get the parent option normally.
			$options = (array) get_option( $parent_option );

			return $options[ $this->id ] ?? $default;
		}

		/**
		 * Whether the current field has a value.
		 *
		 * @return bool
		 */
		public function has_field_value() {
			// Certain "field" types have no value.
			if ( in_array( $this->type, [ 'heading', 'html', 'wrapped_html' ], true ) ) {
				return false;
			}

			// If the value is empty, return false.
			if ( empty( $this->value ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Generate a wrapped html field.
		 *
		 * This is useful to print some HTML that should be inline with the other fieldsets.
		 *
		 * @return string The field markup.
		 */
		public function wrapped_html() {
			$field = $this->do_field_start();
			$field .= $this->do_field_label();
			$field .= $this->do_field_div_start();
			$field .= $this->html;
			$field .= $this->do_field_div_start();
			$field .= $this->do_field_end();

			return $field;
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
		 * Generate an email address field
		 *
		 * @since 4.7.4
		 *
		 * @return string The field
		 */
		public function email() {
			$this->value = trim( $this->value );
			return $this->text();
		}

		/**
		 * Sanitizes a space-separated or array of classes.
		 *
		 * @since 4.7.7
		 *
		 * @param string|array $class A single class, a space-separated list of classes
		 *                            or an array of classes.
		 *
		 * @return string A space-separated list of classes.
		 */
		protected function sanitize_class_attribute( $class ) {
			$classes   = is_array( $class ) ? $class : explode( ' ', $class );
			$sanitized = array_map( 'sanitize_html_class', $classes );

			return implode( ' ', $sanitized );
		}

		/**
		 * Get the allowed protocols for the field.
		 *
		 * This is static because it will be the same for every instance of the class, and
		 * we only need to calculate it once.
		 *
		 * @since 6.1.0
		 *
		 * @return array The allowed protocols.
		 */
		protected static function get_kses_protocols(): array {
			static $protocols = null;
			if ( null === $protocols ) {
				$protocols   = wp_allowed_protocols();
				$protocols[] = 'data';
				$protocols   = array_unique( $protocols );
			}

			return $protocols;
		}

		/**
		 * Handle calls to methods that don't exist.
		 *
		 * This is how we handle deprecated methods.
		 *
		 * @param string $name The method name.
		 * @param array  $arguments Arguments passed to the method.
		 *
		 * @return mixed The result of the method call.
		 * @throws BadMethodCallException If the method does not exist.
		 */
		#[ReturnTypeWillChange]
		public function __call( string $name, array $arguments ) {
			$method_map = [
				'doField'             => 'do_field',
				'doFieldStart'        => 'do_field_start',
				'doFieldEnd'          => 'do_field_end',
				'doFieldLabel'        => 'do_field_label',
				'doFieldDivStart'     => 'do_field_div_start',
				'doFieldDivEnd'       => 'do_field_div_end',
				'doToolTip'           => 'do_tool_tip',
				'doFieldValue'        => 'do_field_value',
				'doFieldName'         => 'do_field_name',
				'doFieldAttributes'   => 'do_field_attributes',
				'doScreenReaderLabel' => 'do_screen_reader_label',
			];

			// Helper function to prepend the class name to the method name.
			$prepend_class = function ( string $method_name ): string {
				return sprintf( '%s::%s', __CLASS__, $method_name );
			};

			if ( array_key_exists( $name, $method_map ) ) {
				_deprecated_function(
					esc_html( $prepend_class( $name ) ),
					'4.3',
					esc_html( $prepend_class( $method_map[ $name ] ) )
				);

				return $this->{$method_map[ $name ]}( ...$arguments );
			} else {
				throw new BadMethodCallException( esc_html( "Method {$prepend_class( $name )} does not exist." ) );
			}
		}
	}
}
