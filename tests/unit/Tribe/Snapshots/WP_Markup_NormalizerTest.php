<?php

namespace Tribe\Test\Snapshots;

use Codeception\Test\Unit;
use Tribe\Tests\Snapshots\WP_Markup_Normalizer;

class WP_Markup_NormalizerTest extends Unit {
	/**
	 * Pairs of markup as printed by WordPress 6.8 and by WordPress 7.1 for the same output.
	 *
	 * @return array<string, array{0: string, 1: string}>
	 */
	public function markup_pairs(): array {
		return [
			'attribute quotes'     => [
				"<div class='notice notice-info' data-id='x'>",
				'<div class="notice notice-info" data-id="x">',
			],
			'url ampersand'        => [
				'<a href="https://example.test/?a=1&#038;b=2">',
				'<a href="https://example.test/?a=1&amp;b=2">',
			],
			'kses apostrophe'      => [
				'<span title="it&#039;s">',
				'<span title="it&apos;s">',
			],
			'svg viewbox'          => [
				'<svg viewBox="0 0 10 10">',
				'<svg viewbox="0 0 10 10">',
			],
			'timezone dir'         => [
				'<option value="UTC">UTC</option>',
				'<option value="UTC" dir="auto">UTC</option>',
			],
			'script and style'     => [
				'<script type="text/javascript" src="a.js"></script><link rel="stylesheet" type="text/css" href="a.css" />',
				'<script src="a.js"></script><link rel="stylesheet" href="a.css" />',
			],
			'search box'           => [
				'<input type="submit" id="search-submit" class="button" value="Search">',
				'<input type="submit" id="search-submit" class="button button-compact" value="Search">',
			],
			'check column'         => [
				"<tr><th scope='row' class='check-column'><input type='checkbox' /></th></tr>",
				'<tr><td class="check-column"><input type="checkbox" /></td></tr>',
			],
			'primary column'       => [
				"<tr><td class='title column-primary'><strong>Name</strong></td></tr>",
				'<tr><th scope="row" class="title column-primary" aria-label="Name"><strong>Name</strong></th></tr>',
			],
			'list table primary column' => [
				"<tr><td class='name column-name has-row-actions column-primary' data-colname=\"Name\"><strong>Name</strong></td></tr>",
				"<tr><th class='name column-name has-row-actions column-primary' data-colname=\"Name\" scope=\"row\"><strong>Name</strong></th></tr>",
			],
			'posts table primary column' => [
				"<tr><td class='title column-title has-row-actions column-primary page-title' data-colname=\"Title\"><strong>Post</strong></td></tr>",
				'<tr><th scope="row" class="title column-title has-row-actions column-primary page-title" data-colname="Title" aria-label="Post"><strong>Post</strong></th></tr>',
			],
			'empty list table'     => [
				"<div class=\"tablenav top\"><div class='tablenav-pages no-pages'><span class=\"displaying-num\">0 items</span><span class='pagination-links'><a class='next-page button' href='#'>Next</a></span></div><br class=\"clear\" /></div><table></table><div class=\"tablenav bottom\"><div class=\"alignleft actions\"></div><br class=\"clear\" /></div>",
				'<div class="tablenav top"><div class="alignleft actions bulkactions hidden"></div><div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span></div><br class="clear" /></div><table></table>',
			],
			'bottom bulk actions'  => [
				'<div class="tablenav bottom"><div class="alignleft actions bulkactions"><select name="action2"><option value="-1">Bulk actions</option></select><input type="submit" id="doaction2" class="button action" value="Apply"></div><div class="tablenav-pages"></div></div>',
				'<div class="tablenav bottom"><div class="tablenav-pages"></div></div>',
			],
		];
	}

	/**
	 * @dataProvider markup_pairs
	 */
	public function test_it_normalizes_both_versions_to_the_same_markup( string $wp_68, string $wp_71 ): void {
		$this->assertSame( WP_Markup_Normalizer::normalize( $wp_68 ), WP_Markup_Normalizer::normalize( $wp_71 ) );
	}

	public function test_it_leaves_unrelated_markup_alone(): void {
		$html = '<p class="x" data-y="1">Hello &amp; bye</p>';

		$this->assertSame( $html, WP_Markup_Normalizer::normalize( $html ) );
	}

	public function test_it_normalizes_strings_nested_in_arrays(): void {
		$value = [ 'html' => "<a href='x'>", 'count' => 3, 'nested' => [ '&#038;' ] ];

		$this->assertSame(
			[ 'html' => '<a href="x">', 'count' => 3, 'nested' => [ '&amp;' ] ],
			WP_Markup_Normalizer::normalize_deep( $value )
		);
	}
}
