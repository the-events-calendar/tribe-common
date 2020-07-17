<?php
/**
 * Link Utilities
 *
 * @since   TBD
 * @package Tribe\Utils
 */

namespace Tribe\Utils;
class Links {
	public $local_host;

	/**
	 * Register allthethings!
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register() {
		$this->local_host = strtolower( wp_parse_url( home_url() ) );
	}

	/**
	 * Get the link host.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return string The host portion of the URL.
	 */
	public function get_link_host( $url ) {
		$url_components   = wp_parse_url( $url );

		return strtolower( $url_components['host'] );
	}

	/**
	 * Determine if a URL is relative.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return boolean
	 */
	public function is_relative_url( $url ) {
		$url_host = trim( $this->get_link_host( $url ) );

		return empty( $url_host );
	}

	/**
	 * Determine if a URL is a local subdomain.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return boolean
	 */
	public function is_local_subdomain( $url ) {
		return (bool) strrpos( $this->get_link_host( $url ), '.' . $this->local_host );
	}

	/**
	 * Determine if a link is local to the site.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return boolean
	 */
	public function is_local_link( $url ) {
		$url_host = $this->get_link_host( $url );

		return 0 === strcasecmp( $url_host, $this->local_host )
				|| $this->is_relative_url( $url )
				|| $this->is_local_subdomain( $url );
	}

	/**
	 * Get the appropriate rel attribute for a link.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return string The value of the rel attribute.
	 */
	public function get_rel_attr( $url ) {
		// The rel attribute by default is empty.
		$rel = '';

		// For external links, use "external"
		if ( ! $this->is_local_link( $url ) ) {
			$rel = 'external';
		}

		// Safety dance!
		if ( '_blank' === $this->get_target_attr( $url ) ) {
			$rel = 'noopener noreferrer';
		}

		return add_filter( 'tribe_get_link_rel_attribute', $rel );
	}

	/**
	 * Get teh appropriate target for a link.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return string The value of the target attribute.
	 */
	public function get_target_attr( $url ) {
		// The default target is _self -> the same window/tab.
		$target = "_self";


		return add_filter( 'tribe_get_link_target_attribute', $target );
	}

	public function build( $url, $args = [] ) {
		$default_args = [
			'target' => empty( $this->get_target_attr( $url ) ) ? '' : 'target=" ' . esc_attr( $this->get_target_attr( $url ) ) . ' "',
			'rel'    => empty( $this->get_rel_attr( $url ) ) ? '' : 'rel=" ' . esc_attr( $this->get_rel_attr( $url ) ) . ' "',
		];

		return wp_parse_args( $args, $default_args );
	}

	public function build_arg_string( $args ) {
		if ( empty( $args ) ) {
			return;
		}

		$args = implode(
			' ',
			array_map(
				function ( $key, $val ) {
					// Explicitly setting false skips the attribute.
					if ( false === $val ) {
						return;
					}

					// Using preg_replace since for the key we don't want to convert special chars, but remove them.
					$key = preg_replace('/[^A-Za-z_]/', '', $key);

					// Setting true or an empty string is a no-value attribute.
					if ( true === $val || '' === $val ) {
						return $key;
					}

					//$key="$value" attribute.
					return $key .'="'. esc_attr( $val ) .'"';
				},
				array_keys($args),
				$args
			)
		);

		return $args;
	}

	/**
	 * Get the HTML for a link
	 *
	 * @since TBD
	 *
	 * @param string  $url   The URL to assess.
	 * @param string  $label The link text.
	 * @param array   $args  Additional arguments. This should be additional attributes in format
	 * 							[
	 *								'class' => 'fnord',
	 *								'download' => false,
	 * 							]
	 * 							Where false values will be purposefully ignored.
	 * @param boolean $echo  Echo or return the string.
	 *
	 * @return string|void HTML string return, or echo, determined by $echo, above.
	 */
	public function render( $url, $label, $args = [], $echo = false ) {
		$args = $this->build( $url, $args );




		$html   = sprintf(
			'<a href="%s" target="%s" rel="%s">%s</a>',
			esc_url( $url ),
			$args,
			esc_html( $label )
		);

		if ( empty( $echo ) ) {
			return $html;
		}

		echo $html;
	}

}
