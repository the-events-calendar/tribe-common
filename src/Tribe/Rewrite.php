<?php


/**
 * Class Tribe__Rewrite
 *
 * Utilities to generate and manipulate rewrite rules.
 *
 * @since 4.3
 */
class Tribe__Rewrite {

	/**
	 * If we wish to setup a rewrite rule that uses percent symbols, we'll need
	 * to make use of this placeholder.
	 */
	const PERCENT_PLACEHOLDER = '~~TRIBE~PC~~';

	/**
	 * Static singleton variable
	 *
	 * @var self
	 */
	public static $instance;

	/**
	 * WP_Rewrite Instance
	 *
	 * @var WP_Rewrite
	 */
	public $rewrite;

	/**
	 * Rewrite rules Holder
	 *
	 * @var array
	 */
	public $rules = array();

	/**
	 * Base slugs for rewrite urls
	 *
	 * @var array
	 */
	public $bases = array();
	/**
	 * After creating the Hooks on WordPress we lock the usage of the function
	 *
	 * @var boolean
	 */
	protected $hook_lock = false;


	/**
	 * Static Singleton Factory Method
	 *
	 * @return self
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * When you are going to use any of the functions to create new rewrite rules you need to setup first
	 *
	 * @param  WP_Rewrite|null $wp_rewrite Pass the WP_Rewrite if you have it
	 *
	 * @return Tribe__Rewrite       The modified version of the class with the required variables in place
	 */
	public function setup( $wp_rewrite = null ) {
		if ( ! $wp_rewrite instanceof WP_Rewrite ) {
			global $wp_rewrite;
		}
		$this->rewrite = $wp_rewrite;
		$this->bases   = $this->get_bases( 'regex' );

		return $this;
	}

	/**
	 * Generate the Rewrite Rules
	 *
	 * @param  WP_Rewrite $wp_rewrite WordPress Rewrite that will be modified, pass it by reference (&$wp_rewrite)
	 */
	public function filter_generate( WP_Rewrite $wp_rewrite ) {
		// Gets the rewrite bases and completes any other required setup work
		$this->setup( $wp_rewrite );

		/**
		 * Use this to change the Tribe__Events__Rewrite instance before new rules
		 * are committed.
		 *
		 * Should be used when you want to add more rewrite rules without having to
		 * deal with the array merge, noting that rules for The Events Calendar are
		 * themselves added via this hook (default priority).
		 *
		 * @var Tribe__Events__Rewrite $rewrite
		 *
		 * @deprecated 4.3 Use `tribe_pre_rewrite`
		 */
		do_action( 'tribe_events_pre_rewrite', $this );

		/**
		 * Use this to change the Tribe__Rewrite instance before new rules
		 * are committed.
		 *
		 * Should be used when you want to add more rewrite rules without having to
		 * deal with the array merge, noting that rules for The Events Calendar are
		 * themselves added via this hook (default priority).
		 *
		 * @var Tribe__Rewrite $rewrite
		 */
		do_action( 'tribe_pre_rewrite', $this );
	}


	/**
	 * Do not allow people to Hook methods twice by mistake
	 */
	public function hooks( $remove = false ) {
		if ( false === $this->hook_lock ) {
			// Don't allow people do Double the hooks
			$this->hook_lock = true;

			$this->add_hooks();
		} elseif ( true === $remove ) {
			$this->remove_hooks();
		}
	}

	/**
	 * Converts any percentage placeholders in the array keys back to % symbols.
	 *
	 * @param  array $rules
	 *
	 * @return array
	 */
	public function remove_percent_placeholders( array $rules ) {
		foreach ( $rules as $key => $value ) {
			$this->replace_array_key( $rules, $key, str_replace( self::PERCENT_PLACEHOLDER, '%', $key ) );
		}

		return $rules;
	}

	protected function add_hooks() {
		add_filter( 'generate_rewrite_rules', array( $this, 'filter_generate' ) );

		// Remove percent Placeholders on all items
		add_filter( 'rewrite_rules_array', array( $this, 'remove_percent_placeholders' ), 25 );
	}

	protected function remove_hooks() {
		remove_filter( 'generate_rewrite_rules', array( $this, 'filter_generate' ) );
		remove_filter( 'rewrite_rules_array', array( $this, 'remove_percent_placeholders' ), 25 );
	}

	/**
	 * Get the base slugs for the rewrite rules.
	 *
	 * WARNING: Don't mess with the filters below if you don't know what you are doing
	 *
	 * @param  string $method Use "regex" to return a Regular Expression with the possible Base Slugs using l10n
	 *
	 * @return object         Return Base Slugs with l10n variations
	 */
	public function get_bases( $method = 'regex' ) {
		return new stdClass();
	}

	/**
	 * The base method for creating a new Rewrite rule
	 *
	 * @param array|string $regex The regular expression to catch the URL
	 * @param array        $args  The arguments in which the regular expression "alias" to
	 *
	 * @return Tribe__Events__Rewrite
	 */
	public function add( $regex, $args = array() ) {
		$regex = (array) $regex;

		$default = array();
		$args    = array_filter( wp_parse_args( $args, $default ) );

		$url = add_query_arg( $args, 'index.php' );

		// Optional Trailing Slash
		$regex[] = '?$';

		// Glue the pieces with slashes
		$regex = implode( '/', array_filter( $regex ) );

		// Add the Bases to the regex
		foreach ( $this->bases as $key => $value ) {
			$regex = str_replace( array( '{{ ' . $key . ' }}', '{{' . $key . '}}' ), $value, $regex );
		}

		// Apply the Preg Indexes to the URL
		preg_match_all( '/%([0-9])/', $url, $matches );
		foreach ( end( $matches ) as $index ) {
			$url = str_replace( '%' . $index, $this->rewrite->preg_index( $index ), $url );
		}

		// Add the rule
		$this->rules[ $regex ] = $url;

		return $this;
	}

	/**
	 * Returns a sanitized version of $slug that can be used in rewrite rules.
	 *
	 * This is ideal for those times where we wish to support internationalized
	 * URLs (ie, where "venue" in "venue/some-slug" may be rendered in non-ascii
	 * characters).
	 *
	 * In the case of registering new post types, $permastruct_name should
	 * generally match the CPT name itself.
	 *
	 * @param  string $slug
	 * @param  string $permastruct_name
	 * @param  string $is_regular_exp
	 *
	 * @return string
	 */
	public function prepare_slug( $slug, $permastruct_name, $is_regular_exp = true ) {
		$needs_handling = false;
		$sanitized_slug = sanitize_title( $slug );

		// Was UTF8 encoding required for the slug? %a0 type entities are a tell-tale of this
		if ( preg_match( '/(%[0-9a-f]{2})+/', $sanitized_slug ) ) {
			/**
			 * Controls whether special UTF8 URL handling is setup for the set of
			 * rules described by $permastruct_name.
			 *
			 * This only fires if Tribe__Events__Rewrite::prepare_slug() believes
			 * handling is required.
			 *
			 * @var string $permastruct_name
			 * @var string $slug
			 */
			$needs_handling = apply_filters(
				'tribe_events_rewrite_utf8_handling', true, $permastruct_name, $slug
			);
		}

		if ( $needs_handling ) {
			// User agents encode things the same way but in uppercase
			$sanitized_slug = strtoupper( $sanitized_slug );

			// UTF8 encoding results in lots of "%" chars in our string which play havoc
			// with WP_Rewrite::generate_rewrite_rules(), so we swap them out temporarily
			$sanitized_slug = str_replace( '%', Tribe__Rewrite::PERCENT_PLACEHOLDER, $sanitized_slug );
		}

		$prepared_slug = $is_regular_exp ? preg_quote( $sanitized_slug ) : $sanitized_slug;

		/**
		 * Provides an opportunity to modify the sanitized slug which will be used
		 * in rewrite rules relating to $permastruct_name.
		 *
		 * @var string $prepared_slug
		 * @var string $permastruct_name
		 * @var string $original_slug
		 */
		return apply_filters( 'tribe_rewrite_prepared_slug', $prepared_slug, $permastruct_name, $slug );
	}

	/**
	 * A way to replace an Array key without destroying the array ordering
	 *
	 * @since  4.0.6
	 *
	 * @param  array  &$array  The Rules Array should be used here
	 * @param  string $search  Search for this Key
	 * @param  string $replace Replace with this key]
	 *
	 * @return bool            Did we replace anything?
	 */
	protected function replace_array_key( &$array, $search, $replace ) {
		$keys  = array_keys( $array );
		$index = array_search( $search, $keys );

		if ( false !== $index ) {
			$keys[ $index ] = $replace;
			$array          = array_combine( $keys, $array );

			return true;
		}

		return false;
	}

}