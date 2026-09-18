<?php
/**
 * Snapshot driver decorator that normalizes WordPress markup differences on both sides.
 *
 * @since TBD
 *
 * @package Tribe\Tests\Snapshots
 */

namespace Tribe\Tests\Snapshots;

use Spatie\Snapshots\Driver;

/**
 * Class WP_Version_Tolerant_Driver.
 *
 * Wraps a driver that stores snapshots as PHP code (`VarDriver`, `WPHtmlOutputDriver`) and runs
 * `WP_Markup_Normalizer` over the stored and the current data before the wrapped driver compares them.
 *
 * @since TBD
 */
class WP_Version_Tolerant_Driver implements Driver {
	/**
	 * The driver that stores and compares the snapshot.
	 *
	 * @since TBD
	 *
	 * @var Driver
	 */
	private $driver;

	/**
	 * WP_Version_Tolerant_Driver constructor.
	 *
	 * @since TBD
	 *
	 * @param Driver $driver The driver that stores and compares the snapshot.
	 */
	public function __construct( Driver $driver ) {
		$this->driver = $driver;
	}

	/**
	 * {@inheritDoc}
	 */
	public function serialize( $data ): string {
		return $this->driver->serialize( $data );
	}

	/**
	 * {@inheritDoc}
	 */
	public function extension(): string {
		return $this->driver->extension();
	}

	/**
	 * {@inheritDoc}
	 */
	public function match( $expected, $actual ) {
		$stored = eval( substr( $expected, strlen( '<?php ' ) ) ); // phpcs:ignore Squiz.PHP.Eval.Discouraged

		$this->driver->match(
			$this->driver->serialize( WP_Markup_Normalizer::normalize_deep( $stored ) ),
			WP_Markup_Normalizer::normalize_deep( $actual )
		);
	}
}
