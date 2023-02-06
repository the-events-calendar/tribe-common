<?php

namespace TEC\Common\Fields;

use \Tribe__Debug as Debug;

/**
 * Factory class that creates fields for use in Settings.
 *
 * @since TBD
 */
class Factory {
	/**
	 * The field's id.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public static $id;

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
		'conditional'      => true,
		'display_callback' => null,
		'name'             => '',
		'type'             => 'text',
		'value'            => null,
	];

	/**
	 * Valid field types.
	 *
	 *@since TBD
	 *
	 * @var array
	 */
	public static $valid_field_types = [
		'checkbox',
		'checkbox_bool', // Deprecated use `checkbox`
		'checkbox_list', // Deprecated use `checkbox`
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
		'toggle',
		'wrapped_html', // Deprecated use `html`
		'wysiwyg',
	];

	public static $compatibility_types = [
		'checkbox_bool'    => 'toggle',
		'checkbox_list'    => 'checkbox',
		'dropdown_chosen'  => 'dropdown',
		'dropdown_select2' => 'dropdown',
		'wrapped_html'     => 'html',
	];

	/**
	 * Class constructor
	 *
	 * @param string         $id    The field id.
	 * @param Abstract_Field $field The field settings.
	 *
	 * @return void
	 */
	public function __construct( $id, $field ) {
		$field->type = $this->normalize_type( $field->type );
		// If type is wrong, bail early
		if ( is_null( $field->type ) ) {
			return;
		}

		// Setup some useful defaults.
		$this->defaults['name']  = $id;

		// Parse args with defaults - only the ones we care about.
		$this->args  = wp_parse_args( $field, $this->defaults );

		// These aren't needed for field generation beyond this class - extract them.
		$field->conditional      = $field->args['conditional'];
		$field->display_callback = $field->args['display_callback'];
		unset( $field->args['conditional'] );
		unset( $field->args['display_callback'] );

		if ( ! empty( $field->display_callback ) && ! is_callable( $field->display_callback ) ) {
			// Fail, log the error.
			Debug::debug(
				esc_html__(
					'Invalid display callback supplied! Field will not display. Ensure the display callback is correct and is publicly callable.',
					'tribe-common'
				),
				[
					$field->display_callback,
					self::$id,
					$field->type
				],
				'warning'
			);

			return;
		}

		// These get passed to the field class, along with $this->args.
		self::$id    = apply_filters( 'tribe_field_id', esc_attr( $id ) );

		$this->do_field( $field );
	}

	/**
	 * Determines how to handle this field's creation -
	 * either calls a callback function or runs this class' course of action.
	 * Logs an error if it fails.
	 *
	 * @since TBD
	 *
	 * @param Abstract_Field $field The field settings.
	 *
	 * @return void
	 */
	public function do_field( $field ): void {
		if ( ! $field->conditional ) {
			return;
		}

		if ( ! empty( $field->display_callback ) ) {
			// If there's a callback, run it.
			call_user_func( $field->display_callback );

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
			__NAMESPACE__ . '\\Field\\' . self::clean_type_to_classname( $field->type )
		);

		// Ensure the class is instantiatable.
		if ( ! class_exists( $field_class ) ) {
			// Fail, log the error.
			Debug::debug(
				esc_html__( 'Invalid field class called! Field will not display.', 'tribe-common' ),
				[
					$field_class,
					$field->type,
				],
				'warning'
			);

			return;
		}

		$field = new $field_class( self::$id, $this->args );

		$field->render();
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
	public static function clean_type_to_classname( $type ): string {
		$regex     = '/[_\-\s]/m';
		$classname = preg_replace( $regex, ' ', $type );
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
		return apply_filters( 'tec_settings_input_classname', $classname );
	}

	/**
	 * Validate our input type against the $valid_field_types array.
	 *
	 * @since TBD
	 *
	 * @param string $type
	 * @return bool
	 */
	public static function validate_type( $type ): bool {
		// Test args.
		if ( in_array( $type, self::$valid_field_types ) ) {
			return true;
		}

		// Fail, log the error.
		Debug::debug(
			esc_html__(
				'Invalid field type supplied! Field will not display. Ensure you have ',
				'tribe-common'
			),
			[
				$type,
				self::$id,
			],
			'warning'
		);

		return false;
	}

	/**
	 * Normalize legacy types to the new Classes.
	 *
	 * @since TBD
	 *
	 * @param string $type
	 * @return string|null
	 */
	public static function normalize_type( $type ): ?string {
		self::$valid_field_types = apply_filters( 'tec_valid_field_types', self::$valid_field_types );

		// Bail if type invalid.
		if ( ! self::validate_type( $type ) ) {
			return null;
		}

		// Bail if we don't need to convert for backwards compatibility.
		if ( ! in_array( $type, array_keys( self::$compatibility_types ) ) ) {
			return $type;
		}

		// Massage input type for backwards compatibility.
		return self::$compatibility_types[ $type ];
	}
}
