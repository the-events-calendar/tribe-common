<?php

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * An Abstract class that will allow us to have a base to go for all
 * the other JSON-LD classes.
 *
 * Always extend this when doing a new JSON-LD object
 */
abstract class Tribe__JSON_LD__Abstract {

	/**
	 * Holder of the Instances
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * Holder for the Already fetched Posts
	 *
	 * @var array
	 */
	protected static $posts = array();

	/**
	 * The class singleton constructor.
	 *
	 * @return Tribe__JSON_LD__Abstract
	 */
	public static function instance( $name = null ) {
		if ( empty( self::$instances[ $name ] ) ) {
			self::$instances[ $name ] = new $name();
		}

		return self::$instances[ $name ];
	}

	/**
	 * Which type of element this actually is
	 *
	 * @see https://developers.google.com/structured-data/rich-snippets/
	 * @var string
	 */
	public $type = 'Thing';

	/**
	 * Compile the schema.org event data into an array
	 *
	 * @param mixed $post Either a post ID or a WP_post object.
	 * @param array $args {
	 *      Optional. An array of arguments to control the returned data.
	 *
	 *      @type string $context         The value of the `@context` tag, defaults to 'https://schema.org'
	 * }
	 *
	 * @return array Either an array containing a post data or an empty array if the post data cannot
	 *               be generated, the `$post` parameter is not a valid post ID or object or the data
	 *               for the post has been fetched already.
	 */
	public function get_data( $post = null, $args = array() ) {
		$post_id = Tribe__Main::post_id_helper( $post );
		if ( ! $post_id ) {
			return array();
		}

		// This prevents a JSON_LD from existing twice one the same page
		if ( $this->exists( $post_id ) ) {
			return array();
		}

		$post = get_post( $post_id );

		if ( empty( $post->ID ) ) {
			return array();
		}

		$data = (object) array();

		// We may need to prevent the context to be triggered
		if ( ! isset( $args['context'] ) || false !== $args['context'] ) {
			$data->{'@context'} = 'http://schema.org';
		}
		$data->{'@type'} = $this->type;

		$data->name        = esc_js( get_the_title( $post ) );
		$data->description = esc_js( tribe_events_get_the_excerpt( $post ) );

		if ( has_post_thumbnail( $post ) ) {
			$data->image = wp_get_attachment_url( get_post_thumbnail_id( $post ) );
		}

		$data->url = esc_url_raw( get_permalink( $post ) );

		// Index by ID: this will allow filter code to identify the actual event being referred to
		// without injecting an additional property
		return array( $post->ID => $data );
	}

	/**
	 * puts together the actual html/json javascript block for output
	 *
	 * @return string
	 */
	public function get_markup( $post = null, $args = array() ) {
		$data = $this->get_data( $post, $args );

		$type = strtolower( esc_attr( $this->type ) );

		foreach ( $data as $post_id => $_data ) {
			/**
			 * Allows the event data to be modifed by themes and other plugins.
			 *
			 * @example tribe_json_ld_thing_object
			 * @example tribe_json_ld_event_object
			 *
			 * @param object  $data objects representing the Google Markup for each event.
			 * @param array   $args the arguments used to get data
			 * @param WP_Post $post the arguments used to get data
			 */
			$data[ $post_id ] = apply_filters( "tribe_json_ld_{$type}_object", $_data, $args, get_post( $post_id ) );

			// Register this post as done already
			$this->register( $post_id );
		}

		/**
		 * Allows the event data to be modifed by themes and other plugins.
		 *
		 * @example tribe_json_ld_thing_data
		 * @example tribe_json_ld_event_data
		 *
		 * @param array $data objects representing the Google Markup for each event.
		 * @param array $args the arguments used to get data
		 */
		$data = apply_filters( "tribe_json_ld_{$type}_data", $data, $args );

		// Strip the post ID indexing before returning
		$data = array_values( $data );

		if ( ! empty( $data ) ) {
			$html[] = '<script type="application/ld+json">';
			$html[] = str_replace( '\/', '/', json_encode( $data ) );
			$html[] = '</script>';
		}

		return ! empty( $html ) ? implode( "\r\n", $html ) : '';
	}

	public function markup( $post = null, $args = array() ) {
		$html = $this->get_markup( $post, $args );

		/**
		 * Allows users to filter the end markup of JSON-LD
		 *
		 * @deprecated
		 * @todo Remove on 4.4
		 *
		 * @param string The HTML for the JSON LD markup
		 */
		$html = apply_filters( 'tribe_google_data_markup_json', $html );

		/**
		 * Allows users to filter the end markup of JSON-LD
		 *
		 * @param string The HTML for the JSON LD markup
		 */
		$html = apply_filters( 'tribe_json_ld_markup', $html );

		echo $html;
	}

	/**
	 * Gets from the Posts index a specific post or fetch all of them
	 *
	 * @param  int|WP_Post  $post The Post Object or ID
	 *
	 * @return null|array|WP_Post         Returns an Indexed Array of Posts, a found Post or Null if not found
	 */
	public function get( $post = null ) {
		if ( is_null( $post ) ) {
			return self::$posts;
		}
		$id = Tribe__Main::post_id_helper( $post );

		if ( $this->exists( $id ) ) {
			return self::$posts[ $id ];
		}

		return null;
	}

	/**
	 * Checks if a Post has been registered to the JSON-LD index
	 *
	 * @param  int|WP_Post  $post The Post Object or ID
	 *
	 * @return bool
	 */
	public function exists( $post ) {
		return isset( self::$posts[ Tribe__Main::post_id_helper( $post ) ] );
	}

	/**
	 * Register the new Post on the Index of created ones
	 *
	 * @param  int|WP_Post  $post The Post Object or ID
	 *
	 * @return WP_Post            The Post Object that was registered
	 */
	public function register( $post ) {
		$id = Tribe__Main::post_id_helper( $post );
		if ( $this->exists( $id ) ) {
			return self::$posts[ $id ];
		}

		self::$posts[ $id ] = get_post( $id );

		return self::$posts[ $id ];
	}

	/**
	 * Remove an Post from the Indexed list
	 *
	 * @param  int|WP_Post  $post The Post Object or ID
	 *
	 * @return bool
	 */
	public function remove( $post ) {
		$id = Tribe__Main::post_id_helper( $post );

		if ( ! $this->exists( $id ) ) {
			return false;
		}

		unset( self::$posts[ $id ] );

		return true;
	}

	/**
	 * Empties the registered posts cache variable.
	 *
	 * Added for testing purposes.
	 */
	public static function unregister_all() {
		self::$posts = array();
	}

	/**
	 * Returns an array of the registered post IDs.
	 *
	 * @return array
	 */
	public static function get_registered_post_ids() {
		return array_keys( self::$posts );
	}
}
