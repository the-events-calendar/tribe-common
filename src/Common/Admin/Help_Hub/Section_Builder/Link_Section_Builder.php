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
		return $this->add_item(
			[
				'title' => $title,
				'url'   => $url,
				'icon'  => $icon,
			]
		);
	}

	/**
	 * Get the section type.
	 *
	 * @since TBD
	 *
	 * @return string The section type.
	 */
	protected function get_type(): string {
		return 'link';
	}

	/**
	 * Get the items array key.
	 *
	 * @since TBD
	 *
	 * @return string The items array key.
	 */
	protected function get_items_key(): string {
		return 'links';
	}
}
