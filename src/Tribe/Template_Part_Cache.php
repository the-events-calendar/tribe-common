<?php

/**
 * Class Tribe__Template_Part_Cache
 *
 * @uses TribeEventsCache
 */
class Tribe__Template_Part_Cache {

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var int
	 */
	private $expiration;

	/**
	 * @var string
	 */
	private $expiration_trigger;

	/**
	 * @var TribeEventsCache
	 */
	private $cache;

	/**
	 * @var string
	 */
	private $html;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * Short description.
	 *
	 * @param string $template           Which template in the views directory is being cached (relative path).
	 * @param string $id                 A unique identifier for this fragment.
	 * @param string $expiration         Expiration time for the cached fragment.
	 * @param string $expiration_trigger WordPress hook to expire on.
	 */
	public function __construct( $template, $id, $expiration, $expiration_trigger ) {
		$this->template           = $template;
		$this->key                = $template . '_' . $id;
		$this->expiration         = $expiration;
		$this->expiration_trigger = $expiration_trigger;
		$this->cache              = new Tribe__Cache();

		$this->add_hooks();
	}

	/**
	 * Hook in to show cached content and bypass queries where needed.
	 */
	public function add_hooks() {

		// Set the cached html in transients after the template part is included.
		add_filter( 'tribe_get_template_part_content', [ $this, 'set' ], 10, 2 );

		// Get the cached html right before the setup_view runs so it's available for bypassing any view logic.
		add_action( 'tribe_events_before_view', [ $this, 'get' ], 9, 1 );

		// When the specified template part is included, show the cached html instead.
		add_filter( 'tribe_get_template_part_path_' . $this->template, [ $this, 'display' ] );
	}

	/**
	 * Checks if there is a cached html fragment in the transients, if it's there,
	 * don't include the requested file path. If not, just return the file path like normal
	 *
	 * @param string $path File path to the month view template part.
	 *
	 * @uses tribe_get_template_part_path_[template] hook
	 *
	 * @return bool|string
	 */
	public function display( $path ) {
		if ( $this->html !== false ) {
			echo $this->html;

			return false;
		}

		return $path;

	}

	/**
	 * Set cached html in transients.
	 *
	 * @param string $html     The html to set.
	 * @param string $template The template to set.
	 *
	 * @return string The HTML.
	 *
	 * @uses tribe_get_template_part_content hook
	 */
	public function set( $html, $template ) {
		if ( $template == $this->template ) {
			$this->cache->set_transient( $this->key, $html, $this->expiration, $this->expiration_trigger );
		}

		return $html;
	}

	/**
	 * Retrieve the cached html from transients, set class property.
	 *
	 * @return string The HTML.
	 *
	 * @uses tribe_events_before_view hook
	 */
	public function get() {

		if ( isset( $this->html ) ) {

			return $this->html;
		}

		$this->html = $this->cache->get_transient( $this->key, $this->expiration_trigger );

		return $this->html;

	}
}
