<?php

use Tribe__Utils__Array as Arr;

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
	 * An array cache of resolved canonical URLs in the shape `[ <url> => <canonical_url> ]`.
	 *
	 * @var array
	 */
	protected $canonical_url_cache = [];

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

	/**
	 * Returns the canonical URLs associated with a ugly link.
	 *
	 * This method will handle "our" URLs to go from their ugly form, filled with query vars, to the "pretty" one, if
	 * possible.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to try and translate into its canonical form.
	 * @param bool   $force Whether to try and use the cache or force a new canonical URL conversion.
	 *
	 * @return string|void The canonical URL, or the input URL if it could not resolved to a canonical one.
	 *
	 */
	public function get_canonical_url( $url, $force = false ) {
		if ( get_class( $this ) === Tribe__Rewrite::class ) {
			throw new BadMethodCallException(
				'Method get_canonical_url should only be called on extending classes.'
			);
		}

		$home_url = home_url();

		// It's not a path we, or WP, could possibly handle.
		$has_http_scheme = (bool) parse_url( $url, PHP_URL_SCHEME );
		if (
			$home_url === $url
			|| ( $has_http_scheme && false === strpos( $url, $home_url ) )
		) {
			return $url;
		}

		$canonical_url = $url;
		// To avoid issues with missing `path` component let's always add a trailing '/'.
		if ( false !== strpos( $url, '?' ) ) {
			$canonical_url = preg_replace( '~(\\/)*\\?~', '/?', $canonical_url );
		} elseif ( false !== strpos( $url, '#' ) ) {
			$canonical_url = preg_replace( '~(\\/)*#~', '/#', $canonical_url );
		}

		// Canonical URLs are supposed to contain the home URL.
		if ( false === strpos( $canonical_url, $home_url ) ) {
			$canonical_url = home_url( $canonical_url );
		}

		if ( empty( $canonical_url ) ) {
			return $home_url;
		}

		if ( ! $force && isset( $this->canonical_url_cache[ $url ] ) ) {
			return $this->canonical_url_cache[ $url ];
		}

		$query         = (string) parse_url( $url, PHP_URL_QUERY );
		wp_parse_str( $query, $query_vars );

		// Remove the `paged` query var if it's 1.
		if ( isset( $query_vars['paged'] ) && 1 === (int) $query_vars['paged'] ) {
			unset( $query_vars['paged'] );
		}

		ksort( $query_vars );

		$our_rules          = $this->get_handled_rewrite_rules();
		$handled_query_vars = $this->get_rules_query_vars( $our_rules );

		if (
			empty( $our_rules )
			|| ! in_array( Arr::get( $query_vars, 'post_type', 'post' ), $this->get_post_types(), true )
		) {
			$wp_canonical = redirect_canonical( $canonical_url, false );
			if ( empty( $wp_canonical ) ) {
				$wp_canonical = $canonical_url;
			}

			$this->canonical_url_cache[ $url ] = $wp_canonical;

			return $wp_canonical;
		}

		$bases = (array) $this->get_bases();
		ksort( $bases );

		$localized_matchers = $this->get_localized_matchers();
		$dynamic_matchers   = $this->get_dynamic_matchers( $query_vars );

		// Try to match only on the query vars we're actually handling.
		$matched_vars   = array_intersect_key( $query_vars, array_combine( $handled_query_vars, $handled_query_vars ) );
		$unmatched_vars = array_diff_key( $query_vars, array_combine( $handled_query_vars, $handled_query_vars ) );

		if ( empty( $matched_vars ) ) {
			// The URL does contain query vars, but none we handle.
			$wp_canonical = trailingslashit( redirect_canonical( $url, false ) );
			$this->canonical_url_cache[ $url ] = $wp_canonical;

			return $wp_canonical;
		}

		foreach ( $our_rules as $link_template => $index_path ) {
			wp_parse_str( (string) parse_url( $index_path, PHP_URL_QUERY ), $link_vars );
			ksort( $link_vars );

			if ( array_keys( $link_vars ) !== array_keys( $matched_vars ) ) {
				continue;
			}

			if ( ! (
				Arr::get( $matched_vars, 'post_type', '' ) === Arr::get( $link_vars, 'post_type', '' )
				&& Arr::get( $matched_vars, 'eventDisplay', '' ) === Arr::get( $link_vars, 'eventDisplay', '' )
			) ) {
				continue;
			}

			$replace = array_map( static function ( $index ) use ( $matched_vars ) {
				return isset( $matched_vars[ $index ] )
					? str_replace( 'tribe_', '', $matched_vars[ $index ] )
					: '';
			}, $localized_matchers );
			// Include dynamic matchers now.
			$replace = array_merge( $dynamic_matchers, $replace );

			$replaced = str_replace( array_keys( $replace ), $replace, $link_template );
			// Remove trailing chars.
			$path          = rtrim( $replaced, '?$' );
			$resolved = trailingslashit( home_url( $path ) );

			break;
		}

		if ( empty( $resolved ) ) {
			$wp_canonical = redirect_canonical( $canonical_url, false );
			$resolved     = empty( $wp_canonical ) ? $resolved : $wp_canonical;
		}

		if ( $canonical_url !== $resolved ) {
			$resolved = trailingslashit( $resolved );
		}

		if ( count( $unmatched_vars ) ) {
			$resolved = add_query_arg( $unmatched_vars, $resolved );
		}

		$this->canonical_url_cache[ $url ] = $resolved;

		return $resolved;
	}

	/**
	 * Returns an array of rewrite rules handled by the implementation.
	 *
	 * @since TBD
	 *
	 * @return array An array of rewrite rules handled by the implementation in the shape `[ <regex> => <path> ]`.
	 */
	protected function get_handled_rewrite_rules() {
		global $wp_rewrite;
		// While this is specific to The Events Calendar we're handling a small enough post type base to keep it here.
		$pattern = '/post_type=tribe_(events|venue|organizer)/';
		// Reverse the rules to try and match the most complex first.
		$handled_rewrite_rules = array_reverse( array_filter( (array) $wp_rewrite->rules,
			static function ( $rule_query_string ) use ( $pattern ) {
				return preg_match( $pattern, $rule_query_string );
			} ) );

		return $handled_rewrite_rules;
	}

	/**
	 * Returns a map relating localized regex matchers to query vars.
	 *
	 * @since TBD
	 *
	 * @return array A map of localized regex matchers in the shape `[ <localized_regex> => <query_var> ]`.
	 */
	protected function get_localized_matchers() {
		$bases         = (array) $this->get_bases();
		$translate_map = $this->get_matcher_to_query_var_map();

		$localized_matchers = [];
		foreach ( $bases as $base => $localized_matcher ) {
			if ( isset( $translate_map[ $base ] ) ) {
				$localized_matchers[ $localized_matcher ] = $translate_map[ $base ];
			}
		}

		return $localized_matchers;
	}

	/**
	 * Returns a map relating localize matcher slugs to the corresponding query var.
	 *
	 * @since TBD
	 *
	 * @return array A map relating localized matcher slugs to the corresponding query var.
	 */
	protected function get_matcher_to_query_var_map() {
		throw new BadMethodCallException(
			'This method should not be called on the base class (' . __CLASS__ . '); only on extending classes.'
		);
	}

	/**
	 * Return a list of the query vars handled in the input rewrite rules.
	 *
	 * @since TBD
	 *
	 * @param array $rules A set of rewrite rules in the shape `[ <regex> => <path> ]`.
	 *
	 * @return array A list of all the query vars handled in the rules.
	 */
	protected function get_rules_query_vars( array $rules ) {
		return array_unique( array_filter( array_merge( [], ...
				array_values( array_map( static function ( $rule_string ) {
					wp_parse_str( parse_url( $rule_string, PHP_URL_QUERY ), $vars );

					return array_keys( $vars );
				}, $rules ) ) ) )
		);
	}

	/**
	 * Sets up the dynamic matchers based on the link query vars.
	 *
	 * @since TBD
	 *
	 * @param array $query_vars An map of query vars and their values.
	 *
	 * @return array A map of dynamic matchers in the shape `[ <regex> => <value> ]`.
	 */
	protected function get_dynamic_matchers( array $query_vars ) {
		$bases            = (array) $this->get_bases();
		$dynamic_matchers = [];
		if ( isset( $query_vars['paged'] ) ) {
			$page_regex = $bases['page'];
			preg_match( '/^\(\?:(?<slug>\w+)\)/', $page_regex, $matches );
			if ( isset( $matches['slug'] ) ) {
				$dynamic_matchers["{$page_regex}/(\d+)"] = "{$matches['slug']}/{$query_vars['paged']}";
			}
		}

		if ( isset( $query_vars['tag'] ) ) {
			$tag_regex = $bases['tag'];
			preg_match( '/^\(\?:(?<slug>\w+)\)/', $tag_regex, $matches );
			if ( isset( $matches['slug'] ) ) {
				$dynamic_matchers["{$tag_regex}/([^/]+)"] = "{$matches['slug']}/{$query_vars['tag']}";
			}
		}

		if ( isset( $query_vars['feed'] ) ) {
			$feed_regex                      = 'feed/(feed|rdf|rss|rss2|atom)';
			$dynamic_matchers[ $feed_regex ] = "feed/{$query_vars['feed']}";
		}

		return $dynamic_matchers;
	}

	/**
	 * Returns a list of post types supported by the implementation.
	 *
	 * @since TBD
	 */
	protected function get_post_types() {
		throw new BadMethodCallException( 'Method get_post_types should be implemented by extending classes.' );
	}
}
