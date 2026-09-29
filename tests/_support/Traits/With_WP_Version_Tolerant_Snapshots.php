<?php
/**
 * HTML snapshot assertions that survive the markup differences between WordPress versions.
 *
 * @since TBD
 *
 * @package Tribe\Tests\Traits
 */

namespace Tribe\Tests\Traits;

use tad\Codeception\SnapshotAssertions\HtmlSnapshot;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Snapshots\WP_Markup_Normalizer;

/**
 * Trait With_WP_Version_Tolerant_Snapshots.
 *
 * Drop-in for `SnapshotAssertions`: `assertMatchesHtmlSnapshot()` runs `WP_Markup_Normalizer` over the
 * stored snapshot and the rendered output before comparing them.
 *
 * @since TBD
 */
trait With_WP_Version_Tolerant_Snapshots {
	use SnapshotAssertions;

	/**
	 * Asserts the rendered HTML matches the stored snapshot once both are normalized.
	 *
	 * The snapshot is built here instead of delegating to the library method: the library names the
	 * snapshot file from a short backtrace and an extra frame pushes the test method out of it.
	 *
	 * @since TBD
	 *
	 * @param string        $current     The rendered HTML.
	 * @param callable|null $dataVisitor An optional visitor applied after the normalization.
	 *
	 * @return void
	 */
	protected function assertMatchesHtmlSnapshot( $current, callable $dataVisitor = null ) {
		$snapshot = new HtmlSnapshot( $current );
		$snapshot->setDataVisitor(
			static function ( $current, $expected ) use ( $dataVisitor ) {
				$current  = WP_Markup_Normalizer::normalize( $current );
				$expected = WP_Markup_Normalizer::normalize( $expected ?? '' );

				return $dataVisitor ? $dataVisitor( $current, $expected ) : [ $current, $expected ];
			}
		);
		$snapshot->assert();
	}
}
