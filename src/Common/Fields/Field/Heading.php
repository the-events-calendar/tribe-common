<?php

namespace TEC\Common\Fields\Field;

/**
 * Helper class that creates HTML headings for use in Settings.
 *
 * @since TBD
 */
class Heading extends Abstract_Field  {

	public function __construct( $id, $args ) {
		parent::__construct( $id, $args );

		$this->level = self::normalize_level( $args['level'] );
	}

	public static function normalize_level( $args ) {
		$default_level = 3;

		// DOn't allow level to be empty.
		if ( empty( $args['level'] ) ) {
			$args['level'] = $default_level;
		}

		// level must be a number.
		if ( ! is_numeric( $args['level'] ) ) {
			$args['level'] = $default_level;
		}

		// Force a level from 1-6.
		if ( 0 < $args['level'] || 6 < $args['level'] ) {
			$args['level'] = $default_level;
		}

		return $args['level'];
	}

	public function render() {

		$content = sprintf(
			'<h%1$d id="%2$s">%3$s<h%1$d>',
			esc_attr( $this->level ),
			esc_attr( self::$id ),
			esc_html( $this->text )
		);

		$content = apply_filters(
			'tec-field-heading-content',
			$this->content,
			$this
		);

		echo $content;
	}
}
