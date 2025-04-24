<?php
/**
 * Link Section Builder for the Help Hub.
 *
 * Concrete implementation for building sections with links.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

use InvalidArgumentException;

/**
 * Class Link_Section_Builder
 *
 * Concrete implementation for building sections with links.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */
class Link_Section_Builder extends Abstract_Section_Builder {

	/**
	 * The items array key.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected const ITEMS_KEY = 'links';

	/**
	 * Add a link to the section.
	 *
	 * @since TBD
	 *
	 * @param string $title The link title.
	 * @param string $url   The link URL.
	 * @param string $icon  Optional. The icon URL.
	 *
	 * @return $this
	 */
	public function add_link( string $title, string $url, string $icon = '' ): self {
		$link = [
			'title' => $title,
			'url'   => $url,
		];

		if ( $icon ) {
			$link['icon'] = $icon;
		}

		return $this->add_item( $link );
	}

	/**
	 * Validate an item before adding it to the section.
	 *
	 * @since TBD
	 *
	 * @throws InvalidArgumentException If the item is invalid.
	 *
	 * @param array $item The item to validate.
	 *
	 * @return void
	 *
	 */
	protected function validate_item( array $item ): void {
		parent::validate_item( $item );

		if ( empty( $item['title'] ) ) {
			throw new InvalidArgumentException( 'Link title cannot be empty' );
		}

		if ( empty( $item['url'] ) ) {
			throw new InvalidArgumentException( 'Link URL cannot be empty' );
		}

		if ( ! filter_var( $item['url'], FILTER_VALIDATE_URL ) ) {
			throw new InvalidArgumentException( 'Link URL must be a valid URL' );
		}
	}
}
