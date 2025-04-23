<?php
/**
 * FAQ Section Builder for the Help Hub.
 *
 * Concrete implementation for building FAQ sections.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

/**
 * Class FAQ_Section_Builder
 *
 * Concrete implementation for building FAQ sections.
 *
 * @since   TBD
 * @package TEC\Common\Admin\Help_Hub
 */
class FAQ_Section_Builder extends Abstract_Section_Builder {
	/**
	 * Add a FAQ item to the section.
	 *
	 * @since TBD
	 *
	 * @param string $question  The FAQ question.
	 * @param string $answer    The FAQ answer.
	 * @param string $link_text Optional. The "Learn More" link text.
	 * @param string $link_url  Optional. The "Learn More" link URL.
	 *
	 * @return $this
	 */
	public function add_faq( string $question, string $answer, string $link_text = '', string $link_url = '' ): self {
		$faq = [
			'question' => $question,
			'answer'   => $answer,
		];

		if ( $link_text && $link_url ) {
			$faq['link_text'] = $link_text;
			$faq['link_url']  = $link_url;
		}

		return $this->add_item( $faq );
	}

	/**
	 * Get the section type.
	 *
	 * @since TBD
	 *
	 * @return string The section type.
	 */
	protected function get_type(): string {
		return 'faq';
	}

	/**
	 * Get the items array key.
	 *
	 * @since TBD
	 *
	 * @return string The items array key.
	 */
	protected function get_items_key(): string {
		return 'faqs';
	}
}
