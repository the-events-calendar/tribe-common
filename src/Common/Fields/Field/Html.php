<?php

namespace TEC\Common\Fields\Field;

/**
 * Helper class that creates HTML entities for use in Settings.
 *
 * @since TBD
 */
class HTML extends Abstract_Field  {
	/**
	 * The field's content.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $content = '';

	/**
	 * Class constructor.
	 *
	 * @since TBD
	 *
	 * @param string     $id    The field id.
	 * @param array      $args  The field settings.
	 *
	 * @return void
	 */
	public function __construct( $id, $args ) {
		parent::__construct( $id, $args );

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
	public static function normalize_content( $args ): ?string {
		if ( ! empty( $args['content'] ) && ! empty( $args['html'] ) ) {
			\Tribe__Debug::debug(
				esc_html__( 'You cannot provide both `content` and `html`! Field will not display.', 'tribe-common' ),
				[
					'id'      => self::$id,
					'type'    => self::$type,
					'html'    => $args['html'],
					'content' => $args['content'],
				],
				'warning'
			);

			// Both are set - we need to bail rather than choose.
			return null;
		} elseif ( empty( $args['content'] ) && empty( $args['html'] ) ) {
			\Tribe__Debug::debug(
				esc_html__( 'You must provide `content` for an html field! Field will not display.', 'tribe-common' ),
				[
					'id'      => self::$id,
					'type'    => self::$type,
				],
				'warning'
			);

			// Neither is set - we need to bail now.
			return null;
		}

		if ( empty( $args['content'] ) && ! empty( $args['html'] ) ) {
			\Tribe__Debug::debug(
				esc_html__( 'You must provide `content` for an html field! `html` is deprecated.', 'tribe-common' ),
				[
					'id'      => self::$id,
					'type'    => self::$type,
					'html'    => $args['html'],
					'content' => $args['content'],
				],
				'warning'
			);

			// Neither is set - we need to bail now.
			$args['content'] = $args['html'];
		}

		return $args['content'];
	}

	/**
	 * Generate an HTML "field".
	 *
	 * @param bool $echo Whether to echo the field (default) or just return the HTML string.
	 *
	 * @return void
	 */
	public function render( $echo = true ) {
		$content = apply_filters(
			'tec-field-html-content',
			$this->content,
			$this
		);

		if ( empty( $echo ) ) {
			return $content;
		}

		echo $content;
	}
}
