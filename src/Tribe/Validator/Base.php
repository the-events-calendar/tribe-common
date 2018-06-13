<?php

/**
 * Class Tribe__Validator__Base
 *
 * Provides methods to validate values.
 */
class Tribe__Validator__Base implements Tribe__Validator__Interface {

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_not_null( $value ) {
		return null !== $value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_null( $value ) {
		return null === $value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_numeric( $value ) {
		return is_numeric( $value );
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_string( $value ) {
		return is_string( $value );
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_string_not_empty( $value ) {
		return ! empty( $value ) && $this->is_string( $value );
	}

	/**
	 * Whether the value is a timestamp or a string parseable by the strtotime function or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_time( $value ) {
		return is_numeric( $value ) || ( is_string( $value ) && strtotime( $value ) );
	}

	/**
	 * Whether the value is a datepicker string parseable by the strtotime function
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_datepicker_time( $value ) {
		// If it is time we just know it's time
		if ( $this->is_time( $value ) ) {
			return true;
		}

		if ( is_string( $value ) ) {
			// Fetch the DatePicker Format
			$datepicker_format = Tribe__Date_Utils::datepicker_formats( tribe_get_option( 'datepickerFormat' ) );

			// Format based on Datepicker from DB
			$time = Tribe__Date_Utils::datetime_from_format( $datepicker_format, $value );

			// Check the time returned from
			if ( strtotime( $time ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Whether the value corresponds to an existing user ID or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_user_id( $value ) {
		return is_numeric( $value ) && (bool) get_user_by( 'ID', $value );
	}

	/**
	 * Whether the value is a positive integer or not.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function is_positive_int( $value ) {
		return is_numeric( $value ) && intval( $value ) == $value && intval( $value ) > 0;
	}

	/**
	 * Trims a string.
	 *
	 * Differently from the trim method it will not use the second argument.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function trim( $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}
		return trim( $value );
	}

	/**
	 * Whether the value(s) all map to existing post tags.
	 *
	 * @param mixed $tag
	 *
	 * @return bool
	 */
	public function is_post_tag( $tag ) {
		return $this->is_term_of_taxonomy( $tag, 'post_tag' );
	}

	/**
	 * Whether the term exists and is a term of the specified taxonomy.
	 *
	 * @param mixed  $term Either a single term `term_id` or `slug` or an array of
	 *                     `term_id`s and `slug`s
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public function is_term_of_taxonomy( $term, $taxonomy ) {
		$terms = Tribe__Utils__Array::list_to_array( $term, ',' );

		if ( empty( $terms ) ) {
			return false;
		}

		foreach ( $terms as $t ) {
			if ( ! term_exists( $t, $taxonomy ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Whether the provided value points to an existing attachment ID or an existing image URL.
	 *
	 * @param int|string $image
	 *
	 * @return mixed
	 */
	public function is_image( $image ) {
		if ( $this->is_numeric( $image ) ) {
			return wp_attachment_is_image( $image );
		} elseif ( is_string( $image ) ) {
			$response = wp_remote_head( $image );

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				return false;
			}

			$content_type = wp_remote_retrieve_header( $response, 'content-type' );

			if ( empty( $content_type ) || 0 !== strpos( $content_type, 'image' ) ) {
				return false;
			}

			$allowed_mime_types = get_allowed_mime_types();

			return ( in_array( $content_type, $allowed_mime_types ) );
		}

		return false;
	}

	/**
	 * Whether a string represents a valid URL or not, allowing for empty values.
	 *
	 * Valid means that the string looks like a URL, not that the URL is online and reachable.
	 *
	 * @param string $input
	 *
	 * @return bool
	 */
	public function is_url( $input ) {
		return empty( $input ) || (bool) filter_var( $input, FILTER_VALIDATE_URL );
	}

	/**
	 * Whether a non-empty string represents a valid URL or not.
	 *
	 * Valid means that the string looks like a URL, not that the URL is online and reachable.
	 *
	 * @param string $input
	 *
	 * @return bool
	 */
	public function is_url_not_empty( $input ) {
		return ! empty( $input ) && (bool) filter_var( $input, FILTER_VALIDATE_URL );
	}

	/**
	 * Whether a string represents a valid and registered post status or not.
	 *
	 * @param string $post_status
	 *
	 * @return bool
	 */
	public function is_post_status( $post_status ) {
		$post_stati = get_post_stati();
		if ( empty( $post_stati ) ) {
			return false;
		}

		return in_array( $post_status, $post_stati );
	}

	/**
	 * Returns the ID of a post for the specified name and type if any.
	 *
	 * @since TBD
	 *
	 * @param string $slug
	 * @param string $post_type
	 *
	 * @return int|false The post ID if found, `false` otherwise.
	 */
	public function get_id_for_slug( $slug, $post_type ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_name = %s",
			$post_type,
			$slug
		);

		$id = $wpdb->get_var( $query );

		return empty( $id ) ? false : (int) $id;
	}

	/**
	 * Enforce yes or false value.
	 *
	 * @since TBD
	 *
	 * @param string|bool $value
	 *
	 * @return false|string
	 */
	public function yes_or_false( $value ) {
		$value = tribe_is_truthy( $value );

		if ( $value ) {
			$value = 'yes';
		}

		return $value;
	}

	/**
	 * Reformats, implicitly validating, a date to teh `Y-m-d H:i:s` format.
	 *
	 * @since TBD
	 *
	 * @param string|int $date A date string or timestamp
	 *
	 * @return string|false The date in the new format or `false` string if invalid.
	 */
	public function reformat_date( $date ) {
		$reformatted = Tribe__Date_Utils::reformat( $date, 'Y-m-d H:i:s' );

		return empty( $reformatted ) ? false : $reformatted;
	}
}
