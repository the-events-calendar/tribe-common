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
	 * @var array
	 */
	private static $instances = array();

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
	 * A way for users to fetch the filter in which this was added
	 * @return string
	 */
	public function get_filter( $suffix = '' ) {
		return 'tribe_json_ld_' . strtolower( esc_attr( $this->type ) ) . '_data' . ( ! empty( $suffix ) ? '_' . $suffix : '' );
	}

	/**
	 * Compile the schema.org event data into an array
	 */
	public function get_data( $post = null, $args = array() ) {
		$post = get_post( Tribe__Main::post_id_helper( $post ) );

		if ( ! $post instanceof WP_Post ) {
			return $data;
		}

		$data = (object) array();

		// We may need to prevent the context to be triggered
		if ( ! isset( $args['context'] ) || false !== $args['context'] ) {
			$data->{'@context'} = 'http://schema.org';
		}
		$data->{'@type'}    = $this->type;

		$data->name         = esc_js( get_the_title( $post ) );
		$data->description  = esc_js( tribe_events_get_the_excerpt( $post ) );

		if ( has_post_thumbnail( $post ) ) {
			$data->image = wp_get_attachment_url( get_post_thumbnail_id( $post ) );
		}

		$data->url = esc_url_raw( get_permalink( $post ) );

		/**
		 * Allows the event data to be modifed by themes and other plugins.
		 *
		 * @example tribe_json_ld_thing_data_object
		 * @example tribe_json_ld_event_data_object
		 *
		 * @param object $data objects representing the Google Markup for each event.
		 * @param array $args the arguments used to get data
		 * @param WP_Post $post the arguments used to get data
		 */
		$data = apply_filters( $this->get_filter( 'object' ), $data, $args, $post );

		// Index by ID: this will allow filter code to identify the actual event being referred to
		// without injecting an additional property
		return array( $post->ID => $data );
	}

	/**
	 * puts together the actual html/json javascript block for output
	 * @return string
	 */
	public function get_markup( $post = null, $args = array() ) {
		$data = $this->get_data( $post, $args );

		/**
		 * Allows the event data to be modifed by themes and other plugins.
		 *
		 * @example tribe_json_ld_thing_data_array
		 * @example tribe_json_ld_event_data_array
		 *
		 * @param array $data objects representing the Google Markup for each event.
		 * @param array $args the arguments used to get data
		 */
		$data = apply_filters( $this->get_filter( 'array' ), $data, $args );

		// Strip the post ID indexing before returning
		$data = array_values( $data );

		if ( ! empty( $data ) ) {
			$html[] = '<script type="application/ld+json">';
			$html[] = str_replace( '\/', '/', json_encode( $data ) );
			$html[] = '</script>';
		}

		return implode( "\r\n", $html );
	}

	public function markup( $post = null, $args = array() ) {
		$html = $this->get_markup( $post, $args );

		/**
		 * Allows users to filter the end markup of JSON-LD
		 * @deprecated
		 * @todo Remove on 4.4
		 * @var string The HTML for the JSON LD markup
		 */
		$html = apply_filters( 'tribe_google_data_markup_json', $html );

		/**
		 * Allows users to filter the end markup of JSON-LD
		 * @var string The HTML for the JSON LD markup
		 */
		$html = apply_filters( 'tribe_json_ld_markup', $html );

		echo $html;
	}
}
