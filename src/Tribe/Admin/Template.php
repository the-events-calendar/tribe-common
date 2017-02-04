<?php
// Don't load directly
defined( 'WPINC' ) or die;

class Tribe__Admin__Template {
	/**
	 * The folders into we will look for the template
	 * @var string
	 */
	private $folder = array();

	/**
	 * The origin class for the plugin where the template lives
	 * @var string
	 */
	public $origin;

	/**
	 * The base data context for this instance of templates
	 * @var string
	 */
	private $data;

	/**
	 * Creates a new Instance of Admin Templates
	 */
	public function __construct( $origin, $folder, $data = array() ) {

		if ( is_string( $origin ) ) {
			// Origin needs to be a class with a `instance` method
			if ( class_exists( $origin ) && method_exists( $origin, 'instance' ) ) {
				$origin = call_user_func( array( $origin, 'instance' ) );
			}
		}

		if ( empty( $origin->plugin_path ) ) {
			throw new InvalidArgumentException( 'Invalid Origin Class for Admin Template Instance' );
		}

		$this->origin = $origin;

		// If Folder is String make it an Array
		if ( is_string( $folder ) ) {
			$folder = (array) explode( '/', $folder );
		}

		// Cast as Array and save
		$this->folder = (array) $folder;

		// Cast as Array and save
		$this->data = (array) $data;
	}

	/**
	 * Gets the base path for this Instance of Admin Templates
	 *
	 * @todo  add filter for the base path
	 *
	 * @return string
	 */
	public function get_base_path() {
		// Craft the Base Path
		$path = array_merge( (array) $this->origin->plugin_path, $this->folder );

		// Implode to avoid Window Problems
		return implode( DIRECTORY_SEPARATOR, $path );
	}

	/**
	 * Gets the default data context for this Instance of Admin Template
	 *
	 * @todo  add filter for the default data
	 *
	 * @return string
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * A very simple method to include a Aggregator Template, allowing filtering and additions using hooks.
	 *
	 * @param  string  $name Which file we are talking about including
	 * @param  array   $data Any context data you need to expose to this file
	 * @param  boolean $echo If we should also print the Template
	 * @return string        Final Content HTML
	 */
	public function template( $name, $data = array(), $echo = true ) {
		// If name is String make it an Array
		if ( is_string( $name ) ) {
			$name = (array) explode( '/', $name );
		}

		// Clean this Variable
		$name = array_map( 'sanitize_title_with_dashes', $name );

		// Apply the .php to the last item on the name
		$name[ count( $name ) - 1 ] .= '.php';

		// Build the File Path
		$file = implode( DIRECTORY_SEPARATOR, array_merge( (array) $this->get_base_path(), (array) $file ) );

		/**
		 * A more Specific Filter that will include the template name
		 *
		 * @param string $file     Complete path to include the PHP File
		 * @param string $name     Template name
		 * @param array  $data     The Data that will be used on this template
		 * @param self   $origin   Current instance of the Tribe__Admin__Template
		 */
		$file = apply_filters( 'tribe_admin_template_file', $file, $name, $data, $this );

		if ( ! file_exists( $file ) ) {
			return false;
		}

		ob_start();
		/**
		 * Fires an Action before including the template file
		 *
		 * @param string $file     Complete path to include the PHP File
		 * @param string $name     Template name
		 * @param array  $data     The Data that will be used on this template
		 * @param self   $origin   Current instance of the Tribe__Admin__Template
		 */
		do_action( 'tribe_admin_template_before_include', $file, $name, $data, $this );

		// Applies the Data on top of the default data for this template
		$data = wp_parse_args( (array) $data, $this->get_data() );

		// Make any provided variables available in the template's symbol table
		extract( $data );

		include $file;

		/**
		 * Fires an Action After including the template file
		 *
		 * @param string $file     Complete path to include the PHP File
		 * @param string $name     Template name
		 * @param array  $data     The Data that will be used on this template
		 * @param self   $origin   Current instance of the Tribe__Admin__Template
		 */
		do_action( 'tribe_admin_template_after_include', $file, $name, $data, $this );
		$html = ob_get_clean();

		/**
		 * Allow users to filter the final HTML
		 *
		 * @param string $html     The final HTML
		 * @param string $file     Complete path to include the PHP File
		 * @param string $name     Template name
		 * @param array  $data     The Data that will be used on this template
		 * @param self   $origin   Current instance of the Tribe__Admin__Template
		 */
		$html = apply_filters( 'tribe_admin_template_html', $html, $file, $name, $data, $this );

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}
}
