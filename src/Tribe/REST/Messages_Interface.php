<?php


interface Tribe__REST__Messages_Interface {

	/**
	 * Returns the localized message associated with the slug.
	 *
	 * @param string $message_slug
	 *
	 * @return string
	 */
	public function get_message( $message_slug  );
}