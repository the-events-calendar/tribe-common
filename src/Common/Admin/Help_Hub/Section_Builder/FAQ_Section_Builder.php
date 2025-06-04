<?php
/**
 * FAQ Section Builder for the Help Hub.
 *
 * Concrete implementation for building FAQ sections.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */

namespace TEC\Common\Admin\Help_Hub\Section_Builder;

use InvalidArgumentException;

/**
 * Class FAQ_Section_Builder
 *
 * Concrete implementation for building FAQ sections.
 *
 * @since 6.8.0
 * @package TEC\Common\Admin\Help_Hub
 */
class FAQ_Section_Builder extends Abstract_Section_Builder {

	/**
	 * The items array key.
	 *
	 * @since 6.8.0
	 *
	 * @var string
	 */
	protected const ITEMS_KEY = 'faq';

	/**
	 * Add a FAQ item to the section.
	 *
	 * @since 6.8.0
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
	 * Validate an item before adding it to the section.
	 *
	 * @since 6.8.0
	 *
	 * @throws InvalidArgumentException If the item is invalid.
	 *
	 * @param array $item The item to validate.
	 *
	 * @return void
	 */
	protected function validate_item( array $item ): void {
		parent::validate_item( $item );

		if ( empty( $item['question'] ) ) {
			throw new InvalidArgumentException( 'FAQ question cannot be empty' );
		}

		if ( empty( $item['answer'] ) ) {
			throw new InvalidArgumentException( 'FAQ answer cannot be empty' );
		}

		if ( empty( $item['link_text'] ) xor empty( $item['link_url'] ) ) {
			throw new InvalidArgumentException( 'FAQ link must have both text and URL or neither' );
		}
	}
}
