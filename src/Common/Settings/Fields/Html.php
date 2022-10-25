<?php

namespace TEC\Common\Settings;

/**
 * Helper class that creates HTML entities for use in Settings.
 *
 * @since TBD
 */
class HTML extends Abstract_Field  {
	/**
	 * Class constructor.
	 *
	 * @since TBD
	 *
	 * @param string     $id    The field id.
	 * @param array      $args  The field settings.
	 * @param null|mixed $value The field's current value.
	 *
	 * @return void
	 */
	public function __construct( $id, $args, $value = null ) {
		parent::__construct( $id, $args, $value );

		$this->content = $this->normalize_content( $args );

		// Error reporting handled in normalize_content() - bail.
		if ( empty( $this->content ) ) {
			return;
		}
	}

	/**
	 * Test and normalize the potential content arguments.
	 *
	 * @since TBD
	 *
	 * @param array $args
	 *
	 * @return string|null
	 */
	public function normalize_content( $args ): ?string {
		if ( ! empty( $args['content'] ) && ! empty( $args['html'] ) ) {
			\Tribe__Debug::debug(
				esc_html__( 'You cannot provide both `content` and `html`! Field will not display.', 'tribe-common' ),
				[
					'id'      => $this->id,
					'type'    => $this->type,
					'html'    => $args['html'],
					'content' => $args['content'],
				],
				'warning'
			);

			return null;
		} elseif ( empty( $args['content'] ) && empty( $args['html'] ) ) {
			\Tribe__Debug::debug(
				esc_html__( 'You must provide `content` (or deprecated `html`) for an html field! Field will not display.', 'tribe-common' ),
				[
					'id'      => $this->id,
					'type'    => $this->type,
				],
				'warning'
			);

			return null;
		}

		return ! empty( $args['content'] ) ? $args['content'] : $args['html'] ;
	}

	/**
	 * Generate an html "field".
	 *
	 * @return void
	 */
	public function render() {
		$content = apply_filters(
			'tec-settings-field-html-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
