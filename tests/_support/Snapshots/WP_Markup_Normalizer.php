<?php
/**
 * Normalizes markup differences between the WordPress versions the test suites run against.
 *
 * @since TBD
 *
 * @package Tribe\Tests\Snapshots
 */

namespace Tribe\Tests\Snapshots;

/**
 * Class WP_Markup_Normalizer.
 *
 * Each WordPress release changes some of the markup core prints (attribute quoting, entity encoding,
 * list table cells). Applying the same normalization to the stored snapshot and to the rendered output
 * keeps one snapshot valid across the whole version matrix instead of one snapshot per version.
 *
 * @since TBD
 */
class WP_Markup_Normalizer {
	/**
	 * Normalizes the markup differences known between WordPress 6.8 and 7.1.
	 *
	 * Rules are ordered so that later ones can rely on double-quoted attributes.
	 *
	 * @since TBD
	 *
	 * @param string $html The markup to normalize.
	 *
	 * @return string The normalized markup.
	 */
	public static function normalize( string $html ): string {
		$rules = [
			// 7.1 quotes attributes printed by core (`wp_admin_notice()`, `checked()`, ...) with double quotes.
			'/=\s*\'([^\']*)\'/'                                              => '="$1"',
			// 7.1 `esc_url()` encodes `&` as `&amp;` instead of `&#038;`.
			'/&#038;/'                                                        => '&amp;',
			// 7.1 `wp_kses()` re-serializes `'` as `&apos;`.
			'/&apos;/'                                                        => '&#039;',
			// 7.1 lowercases SVG attribute names through `wp_kses()`.
			'/\bviewBox=/'                                                    => 'viewbox=',
			// 7.1 adds `dir="auto"` to timezone dropdown options and groups.
			'/ dir="auto"/'                                                   => '',
			// 7.1 no longer prints the default `type` on script and style tags.
			'/ type="text\/(?:javascript|css)"/'                              => '',
			// 7.1 adds `button-compact` to list table search boxes.
			'/ button-compact\b/'                                             => '',
			// 7.1 moves the list table check column from a `th` to a `td`.
			'/<th scope="row" class="check-column">(.*?)<\/th>/s'             => '<td class="check-column">$1</td>',
			// The "0 items" pagination block differs between versions and carries nothing worth comparing.
			'/\s*<div class="tablenav-pages no-pages">(?:(?!<\/div>).)*<\/div>/s'  => '',
			// 7.1 prints a hidden bulk actions block when the table has no items.
			'/\s*<div class="alignleft actions bulkactions hidden">(?:(?!<\/div>).)*<\/div>/s' => '',
			// 7.1 drops the bottom table nav when the table has no items, so no pagination is left in it.
			'/\s*<div class="tablenav bottom">(?:(?!tablenav-pages).)*?<br class="clear" \/>\s*<\/div>/s' => '',
			// 7.1 drops the bulk actions block from the bottom table nav.
			'/<div class="alignleft actions bulkactions">(?:(?!<\/div>).)*id="doaction2"(?:(?!<\/div>).)*<\/div>\s*/s' => '',
		];

		$html = preg_replace( array_keys( $rules ), array_values( $rules ), $html );

		/*
		 * 7.1 `WP_List_Table` prints the primary column as a `th` with `scope="row"` and an optional
		 * `aria-label`; the attribute order varies by table, so the cell is matched on its class alone.
		 */
		$html = preg_replace_callback(
			'/<th((?: [\w-]+="[^"]*")+)>(.*?)<\/th>/s',
			static function ( array $match ): string {
				if ( false === strpos( $match[1], 'column-primary' ) ) {
					return $match[0];
				}

				$attributes = preg_replace( '/ (?:scope|aria-label)="[^"]*"/', '', $match[1] );

				return "<td{$attributes}>{$match[2]}</td>";
			},
			$html
		);

		// Removing a block leaves a blank line on some versions and not on others.
		return preg_replace( '/\n[ \t]*\n/', "\n", $html );
	}

	/**
	 * Normalizes every string inside a value, recursively.
	 *
	 * @since TBD
	 *
	 * @param mixed $value The value to normalize.
	 *
	 * @return mixed The value with every string normalized.
	 */
	public static function normalize_deep( $value ) {
		if ( is_string( $value ) ) {
			return static::normalize( $value );
		}

		if ( is_array( $value ) ) {
			return array_map( [ static::class, 'normalize_deep' ], $value );
		}

		return $value;
	}
}
