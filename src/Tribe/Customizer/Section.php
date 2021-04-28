<?php
// Don't load directly
defined( 'WPINC' ) or die;

use Tribe\Customizer\Controls\Heading;
use Tribe\Customizer\Controls\Radio;

/**
 * The Events Calendar Customizer Section Abstract.
 * Extend this when you are trying to create a new The Events Calendar Section
 * on the Customize from WordPress.
 *
 * @package Common
 * @subpackage Customizer
 * @since 4.0
 */
abstract class Tribe__Customizer__Section {

    /**
     * ID of the section.
     *
     * @since 4.0
     *
     * @access public
     * @var string
     */
    public $ID;

    /**
     * Load this section by default.
     *
     * @since 4.4
     *
     * @access public
     * @var string
     */
    public $load = true;

    /**
     * Default values for the settings on this class.
     *
     * @since 4.0
     *
     * @access private
     * @var array
     */
    public $defaults = [];

    /**
     * Information to setup the Section.
     *
     * @since 4.0
     *
     * @access public
     * @var array
     */
    public $arguments = [
        'priority'    => 10,
        'capability'  => 'edit_theme_options',
        'title'       => null,
        'description' => null,
    ];

    /**
     * Private variable holding the class Instance.
     *
     * @since 4.0
     *
     * @access private
     * @var Tribe__Events__Pro__Customizer__Section
     */
    private static $instances;

	protected $content_headings = [];
	protected $content_settings = [];
	protected $content_controls = [];

    /**
     * Setup and Load hooks for this Section.
     *
     * @since  4.0
     *
     * @return Tribe__Customizer__Section
     */
    final public function __construct() {
        $slug = self::get_section_slug( get_class( $this ) );

        // If for weird reason we don't have the Section name
        if ( ! is_string( $this->ID ) ){
            $this->ID = $slug;
        }

        // Allow child classes to setup the section.
        $this->setup();

        // Hook the Register methods
        add_action( "tribe_customizer_register_{$this->ID}_settings", [ $this, 'register_settings' ], 10, 2 );
        add_filter( 'tribe_customizer_pre_sections', [ $this, 'register' ], 10, 2 );

        // Append this section CSS template
        add_filter( 'tribe_customizer_css_template', [ $this, 'get_css_template' ], 15 );
        add_filter( "tribe_customizer_section_{$this->ID}_defaults", [ $this, 'get_defaults' ], 10 );

        // Create the Ghost Options
        add_filter( 'tribe_customizer_pre_get_option', [ $this, 'filter_settings' ], 10, 2 );

        // By Default Invoking a new Section will load, unless `load` is set to false
        if ( true === (bool) $this->load ) {
            tribe( 'customizer' )->load_section( $this );
        }
    }

    /**
     * This method will be executed when the Class in Initialized.
     * Overwrite this method to be able to setup the arguments of your section.
     *
     * @return void
     */
    public function setup() {
		$this->setup_defaults();
		$this->setup_arguments();
		$this->setup_content_arguments();
	}

    /**
     * Overwrite this method to create the Fields/Settings for this section.
     *
     * @param  WP_Customize_Section $section The WordPress section instance.
     * @param  WP_Customize_Manager $manager The WordPress Customizer Manager.
     *
     * @return void
     */
    public function register_settings( WP_Customize_Section $section, WP_Customize_Manager $manager ) {
		$customizer = tribe( 'customizer' );

		$headings = $this->get_content_headings();

		if ( ! empty( $headings ) ) {
			foreach( $headings as $name => $args ) {
				$setting_name = $customizer->get_setting_name( $name, $section );
				$this->add_heading(  $section, $manager, $setting_name, $args );
			}
		}

		$settings = $this->get_content_settings();

		if ( ! empty( $settings ) ) {
			foreach( $settings as $name => $args ) {
				$setting_name = $customizer->get_setting_name( $name, $section );
				$this->add_setting( $manager, $setting_name, $name, $args );
			}
		}

		$controls = $this->get_content_controls();

		if ( ! empty( $controls ) ) {
			foreach( $controls as $name => $args ) {
				$setting_name = $customizer->get_setting_name( $name, $section );
				$this->add_control(  $section, $manager, $setting_name, $args );
			}
		}
    }

    /**
     * Overwrite this method to be able to implement the CSS template related to this section.
     *
     * @return string The CSS template.
     */
    public function get_css_template( $template ) {
        return $template;
    }

    /**
     * Overwrite this method to be able to create dynamic settings.
     *
     * @param  array  $settings The actual options on the database.
     *
     * @return array $settings The modified settings.
     */
    public function create_ghost_settings( $settings = [] ) {
        return $settings;
    }

    /**
     * Get the section slug based on the Class name.
     *
     * @param  string $class_name The name of this Class.
     * @return string $slug The slug for this Class.
     */
    final public static function get_section_slug( $class_name ) {
        $abstract_name = __CLASS__;
        $reflection = new ReflectionClass( $class_name );

        // Get the Slug without the Base name.
        $slug = str_replace( $abstract_name . '_', '', $reflection->getName() );

        if ( false !== strpos( $slug, '__Customizer__' ) ) {
            $slug = explode( '__Customizer__', $slug );
            $slug = end( $slug );
        }

        return strtolower( $slug );
    }

	public function setup_defaults() {}

	public function setup_arguments() {}

    /**
     * A way to apply filters when getting the Customizer options.
     *
     * @return array The filtered defaults.
     */
    public function get_defaults( $settings = [] ) {
        // Create Ghost Options
        $settings = $this->create_ghost_settings( wp_parse_args( $settings, $this->defaults ) );

		return apply_filters( 'tribe_customizer_default_settings', $settings, $this );
    }

    /**
     * Get the Default Value requested.
     *
     * @param string $key The key for the requested value.
     *
     * @return mixed The requested value.
     */
    public function get_default( $key ) {
        $defaults = $this->get_defaults();

        if ( ! isset( $defaults[ $key ] ) ) {
            return null;
        }

        return $defaults[ $key ];
    }

    /**
     * Hooks to the `tribe_customizer_pre_get_option`. This applies the `$this->create_ghost_settings()` method
     * to the settings on the correct section.
     *
     * @param  array $settings  Values from the Database from Customizer actions.
     * @param  array $search    Indexed search @see Tribe__Customizer::search_var().
     *
     * @return array
     */
    public function filter_settings( $settings, $search ) {
        // Exit early.
        if ( null === $search ) {
            return $settings;
        }

        // Only Apply if getting the full options or Section.
        if ( is_array( $search ) && count( $search ) > 1 ) {
            return $settings;
        }

        if ( is_array( $search ) && count( $search ) === 1 ) {
            $settings = $this->create_ghost_settings( $settings );
        } else {
            $settings[ $this->ID ] = $this->create_ghost_settings( $settings[ $this->ID ] );
        }

        return $settings;
    }

    /**
     * Register this Section.
     *
     * @param  array  $sections   Array of Sections.
     * @param  Tribe__Customizer $customizer Our internal Cutomizer Class Instance.
     *
     * @return array  Return the modified version of the Section array.
     */
    public function register( $sections, Tribe__Customizer $customizer ) {
        $sections[ $this->ID ] = $this->arguments;

        return $sections;
    }

	public function setup_content_arguments(){
		$this->setup_content_headings();
		$this->setup_content_settings();
		$this->setup_content_controls();
	}

	/* Headings */
	public function setup_content_headings() {}

	public function get_content_headings() {
		return $this->filter_content_headings( $this->content_headings );
	}

	public function filter_content_headings( $arguments ) {
		/**
		 * Applies a filter to the validation map for instance arguments.
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $arguments Current set of callbacks for arguments.
		 * @param static                 $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( 'tribe_customizer_section_content_headings', $arguments, $this );

		$section_slug = static::get_section_slug( get_class( $this ) );

		/**
		 * Applies a filter to the validation map for instance arguments for a specific widget. Based on the widget slug of the widget
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $arguments Current set of callbacks for arguments.
		 * @param static                 $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( "tribe_customizer_section_{$section_slug}_content_headings", $arguments, $this );

		return $arguments;
	}

    /**
     * Sugar syntax to add a heading section to the customizer content.
     * This is a control only in name: it does not, actually, control or save any setting.
     *
     * @since TBD
     *
     * @param WP_Customize_Manager $manager   The instance of the Customizer Manager.
     * @param string               $name      HTML name Attribute name of the setting.
     * @param array<string,mixed>  $arguments The control arguments.
     *
     */
    protected function add_heading( $section, $manager, $name, $args ) {
		$args['type'] = 'heading';

		$this->add_control( $section, $manager, $name, $args );
    }

	/* Settings */

	public function setup_content_settings() {}

	public function get_content_settings() {
		return $this->filter_content_settings( $this->content_settings );
	}

	public function filter_content_settings( $arguments ) {
		/**
		 * Applies a filter to the validation map for instance arguments.
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $arguments Current set of callbacks for arguments.
		 * @param static                 $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( 'tribe_customizer_section_content_settings', $arguments, $this );

		$section_slug = static::get_section_slug( get_class( $this ) );

		/**
		 * Applies a filter to the validation map for instance arguments for a specific widget. Based on the widget slug of the widget
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $arguments Current set of callbacks for arguments.
		 * @param static                 $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( "tribe_customizer_section_{$section_slug}_content_settings", $arguments, $this );

		return $arguments;
	}

    /**
     * Sugar syntax to add a setting to the customizer content.
     *
     * @since TBD
     *
     * @param WP_Customize_Manager $manager      The instance of the Customizer Manager.
     * @param string               $setting_name HTML name Attribute name of the setting.
     * @param string               $key          The key for the default value.
     * @param array<string,mixed>  $arguments    The control arguments.
     */
    protected function add_setting( $manager, $setting_name, $key, $args ) {
		// Get the default values.
		$defaults = [
			'default' => $this->get_default( $key ),
			'type'    => 'option',
		];

        // Add a setting.
        $manager->add_setting(
            $setting_name,
			array_merge( $defaults, $args )
        );
    }

	/* Controls */

	public function setup_content_controls() {}

	public function get_accepted_control_types() {
		$accepted_control_types = [
			'checkbox'       => WP_Customize_Control::class,
			'color'          => WP_Customize_Color_Control::class,
			'default'        => WP_Customize_Control::class,
			'dropdown-pages' => WP_Customize_Control::class,
			'heading'        => Heading::class,
			'image'          => WP_Customize_Image_Control::class,
			'radio'          => WP_Customize_Control::class,//Radio::class,
			'select'         => WP_Customize_Control::class,
			'textarea'       => WP_Customize_Control::class,
		];
        /**
         * Allows filtering the accepted control types.
         *
         * @since TBD
         *
         * @param array<string,string> $control_types The map of keys to WP Control classes.
         */
        return apply_filters( 'tribe_customizer_accepted_control_types', $accepted_control_types, $this );
	}

	public function is_control_type_accepted( $type ) {
		$types = $this->get_accepted_control_types();

		if ( empty( $type ) ) {
			return false;
		}

		if ( empty( $types[ $type ] ) ) {
			return false;
		}

		if ( ! class_exists( $types[ $type ] ) ) {
			return false;
		}

		return true;
	}

	public function get_control_type( $type ) {
		$types = $this->get_accepted_control_types();

		if ( empty( $type ) ) {
			return $types[ 'default' ];
		}

		if ( empty( $types[ $type ] ) ) {
			return false;
		}

		return $types[ $type ];
	}

	public function get_content_controls() {
		return $this->filter_content_controls( $this->content_controls );
	}

	public function filter_content_controls( $arguments ) {
		/**
		 * Applies a filter to the validation map for instance arguments.
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $arguments Current set of callbacks for arguments.
		 * @param static                 $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( 'tribe_customizer_section_content_controls', $arguments, $this );

		$section_slug = static::get_section_slug( get_class( $this ) );

		/**
		 * Applies a filter to the validation map for instance arguments for a specific widget. Based on the widget slug of the widget
		 *
		 * @since TBD
		 *
		 * @param array<string,callable> $arguments Current set of callbacks for arguments.
		 * @param static                 $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( "tribe_customizer_section_{$section_slug}_content_controls", $arguments, $this );

		return $arguments;
	}

	/**
     * Sugar syntax to add a control to the customizer content.
     *
     * @since TBD
     *
     * @param WP_Customize_Manager $manager      The instance of the Customizer Manager.
     * @param string               $setting_name HTML name Attribute name of the setting.
     * @param array<string,mixed>  $arguments    The control arguments.
     */
    protected function add_control( $section, $manager, $setting_name, $args  ) {
        // Validate our control choice.
        if ( ! isset( $args['type'] ) ) {
			return;
		}

		$type = $args['type'];

		if ( ! $this->is_control_type_accepted( $type ) ) {
			return;
		}

		$type = $this->get_control_type( $type );

		if ( $section instanceof WP_Customize_Section ) {
			$section = (string) $section->id;
		}

		if ( ! is_string( $section ) ) {
			return;
		}

		// Get the default values.
		$defaults = [
			'section' => $section,
		];

		$args = array_merge( $defaults, $args );

		$manager->add_control(
			new $type(
				$manager,
				$setting_name,
				$args
			)
		);
    }
}
