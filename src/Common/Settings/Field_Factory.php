<?php

namespace TEC\Common\Settings;


/**
 * Factory class that creates fields for use in Settings.
 *
 * @since TBD
 */
class Field_Factory {
	/**
	 * The field's id.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $id;

	/**
	 * The field's name (defaults to $id).
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The field's arguments.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $args = [];

	/**
	 * Field defaults.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	public $defaults = [
		'conditional'         => true,
		'display_callback'    => null,
		'name'                => '',
		'type'                => 'text',
		'value'               => '',
	];

	/**
	 * Valid field types.
	 *
	 *@since TBD
	 *
	 * @var array
	 */
	public $valid_field_types = [
		'checkbox_bool',
		'checkbox_list',
		'color',
		'dropdown_chosen', // Deprecated use `dropdown`
		'dropdown_select2', // Deprecated use `dropdown`
		'dropdown',
		'email',
		'fieldset',
		'heading',
		'html',
		'image',
		'license_key',
		'number',
		'radio',
		'section',
		'text',
		'textarea',
		'wrapped_html',
		'wysiwyg',
	];

	/**
	 * Class constructor
	 *
	 * @param string     $id    The field id.
	 * @param array      $field The field settings.
	 * @param null|mixed $value The field's current value.
	 *
	 * @return void
	 */
	public function __construct( $id, $field, $value = null ) {
		$this->valid_field_types = apply_filters( 'tribe_valid_field_types', $this->valid_field_types );

		// Setup some useful defaults.
		$this->defaults['name']  = $id;
		$this->defaults['value'] = $value;

		// Parse args with defaults - only the ones we care about.
		$this->args  = wp_parse_args( $field, $this->defaults );

		// These aren't needed for field generation beyond this class - extract them.
		$this->conditional      = $this->args['conditional'];
		$this->display_callback = $this->args['display_callback'];
		$this->type             = $this->args['type'];
		unset( $this->args['conditional'] );
		unset( $this->args['display_callback'] );
		unset( $this->args['type'] );

		// Massage type.
		if ( false !== stripos( $this->type, 'checkbox' ) ) {
			$this->type = 'checkbox';
		}

		if ( false !== stripos( $this->type, 'dropdown' ) ) {
			$this->type = 'dropdown';
		}

		// Test args.
		if ( ! in_array( $this->type, $this->valid_field_types ) ) {
			// Fail, log the error.
			\Tribe__Debug::debug(
				esc_html__(
					'Invalid field type supplied! Field will not display. Ensure you have ',
					'tribe-common'
				),
				[
					$this->type,
					$this->id,
				],
				'warning'
			);

			return;
		}

		if ( ! empty( $this->display_callback ) && ! is_callable( $this->display_callback ) ) {
			// Fail, log the error.
			\Tribe__Debug::debug(
				esc_html__(
					'Invalid display callback supplied! Field will not display. Ensure the display callback is correct and is publicly callable.',
					'tribe-common'
				),
				[
					$this->display_callback,
					$this->id,
					$this->type
				],
				'warning'
			);

			return;
		}

		// These get passed to the field class, along with $this->args.
		$this->value = is_array( $value ) ? array_map( 'esc_attr', $value ) : esc_attr( $value );
		$this->id    = apply_filters( 'tribe_field_id', esc_attr( $id ) );

		// Epicness!
		$this->do_field();
	}

	/**
	 * Determines how to handle this field's creation -
	 * either calls a callback function or runs this class' course of action.
	 * Logs an error if it fails.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function do_field() {

		if ( ! $this->conditional ) {
			return;
		}

		if ( ! empty( $this->display_callback ) ) {
			// If there's a callback, run it.
			call_user_func( $this->display_callback );
			return;
		}

		/**
		 * Allows filtering the full, namespaced class name for custom input classes.
		 *
		 * @since TBD
		 *
		 * @param string $field_class
		 */
		$field_class = apply_filters(
			'tec_settings_field_class',
			__NAMESPACE__ . '\\Field\\' . self::clean_type_to_classname( $this->type )
		);

		// Ensure the class is instantiatable.
		if ( ! class_exists( $field_class ) ) {
			// Fail, log the error.
			\Tribe__Debug::debug(
				esc_html__( 'Invalid field class called! Field will not display.', 'tribe-common' ),
				[
					$field_class,
					$this->type,
				],
				'warning'
			);

			return;
		}

		$field = new $field_class( $this->id, $this->args, $this->value );

		return $field;
	}

	/**
	 * Takes in a input type (string) and converts it to a proper classname in our preferred format.
	 *
	 * @since TBD
	 *
	 * @param string $type The requested input type.
	 *
	 * @return string $classname
	 */
	public static function clean_type_to_classname( $type ) {
		$regex = '/[_\-\s]/m';
		$replace = ' ';

		$classname = preg_replace( $regex, $replace, $type );
		$classname = ucwords( $classname );
		$classname =  str_replace( ' ', '_', $classname );

		/**
		 * Allows filtering the class name for custom input classes.
		 * Does not include the namespace!
		 *
		 * @since TBD
		 *
		 * @param string $classname
		 */
		return apply_filters(
			'tec_settings_input_classname',
			$classname
		);
	}
}
