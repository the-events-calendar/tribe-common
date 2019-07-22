<?php
/**
 * Wraps the logic to lazy load a post thumbnail information.
 *
 * Example usage:
 * ```php
 * $post_thumbnail = new Tribe\Utils\Post_Thumbnail( $post_id );
 *
 * // ...some code later...
 *
 * // The post thumbnail data is fetched only now.
 * $large_url = $post_thumbnail->large->url;
 * ```
 *
 * @since   TBD
 * @package Tribe\Utils
 */


namespace Tribe\Utils;

/**
 * Class Post_Thumbnail
 *
 * @since   TBD
 * @package Tribe\Utils
 */
class Post_Thumbnail implements \ArrayAccess {
	/**
	 * An array of the site image sizes, including the `full` one.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected static $image_sizes;

	/**
	 * The post ID this images collection is for.
	 *
	 * @since TBD
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * The post thumbnail data.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	protected $post_thumbnail_data;

	/**
	 * Post_Images constructor.
	 *
	 * @param int $post_id The post ID.
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __get( $property ) {
		return $this->offsetGet( $property );
	}

	/**
	 * {@inheritDoc}
	 */
	public function __set( $property, $value ) {
		$this->offsetSet( $property, $value );
	}

	/**
	 * {@inheritDoc}
	 */
	public function __isset( $property ) {
		return $this->offsetExists( $property );
	}

	/**
	 * Fetches and returns the image sizes registered on the site, if any.
	 *
	 * @since TBD
	 *
	 * @return array An array of the registered image sizes.
	 */
	protected function fetch_image_sizes() {
		if ( null !== static::$image_sizes ) {
			return static::$image_sizes;
		}

		static::$image_sizes = array_merge( [ 'full' ], get_intermediate_image_sizes() );

		return static::$image_sizes;
	}

	/**
	 * Returns the data about the post thumbnail, if any.
	 *
	 * @since TBD
	 *
	 * @return array An array containing the post thumbnail data.
	 */
	public function get_post_thumbnail_data() {
		if ( null !== $this->post_thumbnail_data ) {
			return $this->post_thumbnail_data;
		}

		$post_id     = $this->post_id;
		$image_sizes = $this->fetch_image_sizes();

		$thumbnail_id = get_post_thumbnail_id( $post_id );

		if ( empty( $thumbnail_id ) ) {
			return [];
		}

		$thumbnail_data = array_combine(
			$image_sizes,
			array_map( static function ( $size ) use ( $thumbnail_id ) {
				return [
					'url' => wp_get_attachment_image_src( $thumbnail_id, $size )
				];
			}, $image_sizes )
		);

		/**
		 * Filters the post thumbnail data and information that will be returned for a specific post.
		 *
		 * @since TBD
		 *
		 * @param array $thumbnail_data The thumbnail data for the post.
		 * @param int   $post_id        The ID of the post the data is for.
		 */
		$thumbnail_data = apply_filters( 'tribe_post_thumbnail_data', $thumbnail_data, $post_id );

		return $thumbnail_data;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetExists( $offset ) {
		$this->post_thumbnail_data = $this->get_post_thumbnail_data();

		return isset( $this->post_thumbnail_data[ $offset ] );
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetGet( $offset ) {
		$this->post_thumbnail_data = $this->get_post_thumbnail_data();

		return isset( $this->post_thumbnail_data[ $offset ] )
			? $this->post_thumbnail_data[ $offset ]
			: null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetSet( $offset, $value ) {
		$this->post_thumbnail_data = $this->get_post_thumbnail_data();

		$this->post_thumbnail_data[ $offset ] = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function offsetUnset( $offset ) {
		$this->post_thumbnail_data = $this->get_post_thumbnail_data();

		unset( $this->post_thumbnail_data[ $offset ] );
	}
}
