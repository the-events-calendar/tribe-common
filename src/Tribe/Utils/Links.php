<?php
/**
 * Link utilities
 */
class Tribe__Utils__Links {
	public $local_host;

	public function register() {
		$this->local_host = strtolower( wp_parse_url( home_url() ) );
	}

	public function get_link_host( $url ) {
		$url_components   = wp_parse_url( $url );

		return strtolower( $url_components['host'] );
	}

	public function is_relative_url( $url ) {
		$url_host = trim( $this->get_link_host( $url ) );

		return empty( $url_host );
	}

	public function is_local_subdomain( $url ) {
		return (bool) strrpos( $this->get_link_host( $url ), '.' . $this->local_host );
	}

	public function is_local_link( $url ) {
		$url_host = $this->get_link_host( $url );

		return 0 === strcasecmp( $url_host, $this->local_host )
				|| $this->is_relative_url( $url )
				|| $this->is_local_subdomain( $url );
	}

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

	public function get_target_attr( $url ) {
		// The default target is _self -> the same window/tab.
		$target = "_self";


		return add_filter( 'tribe_get_link_target_attribute', $target );
	}

	public function render( $url, $label, $echo ) {
		$target = empty( $this->get_target_attr( $url ) ) ? '' : 'target=" ' . esc_attr( $this->get_target_attr( $url ) ) . ' "';
		$rel    = empty( $this->get_rel_attr( $url ) ) ? '' : 'rel=" ' . esc_attr( $this->get_rel_attr( $url ) ) . ' "';
		$html   = sprintf(
			'<a href="%s" target="%s" rel="%s">%s</a>',
			esc_attr( esc_url( $url ) ),
			$target,
			$rel,
			esc_html( $label )
		);

		if ( empty( $echo ) ) {
			return $html;
		}

		echo $html;
	}

}
