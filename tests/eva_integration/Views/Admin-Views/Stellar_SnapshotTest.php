<?php

namespace Tribe\tests\eva_integration\Views\Admin_Views;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Admin\Notice\Marketing\Stellar_Sale;
use Tribe\tests\eva_integration\Snapshot_Test_Case;
use Tribe\Tests\Traits\With_Uopz;
use Tribe__Date_Utils as Dates;
use Tribe__Dependency;

class Stellar_SnapshotTest extends Snapshot_Test_Case {
	use With_Uopz;
	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'admin-views/notices/tribe-stellar-sale.php';

	/**
	 * @test
	 */
	public function should_render_premium() {
		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		// Mock the `now` date to be this year on November 21st.
		$year = date( 'Y' );
		$this->set_class_fn_return( Dates::class, 'build_date_object', static function ( $input ) use ( $year ) {
			return $input === 'now' ?
				new \DateTime( "2022-07-30 19:23:23" )
				: new \DateTime( $input );
		}, true );

		// Mock a premium plugin installed.
		$this->set_class_fn_return( Tribe__Dependency::class, 'has_active_premium_plugin', true );

		$notice = tribe( Stellar_Sale::class );

		$this->assertMatchesHtmlSnapshot( $notice->display_notice() );
	}


	/**
	 * @test
	 */
	public function should_render_free() {
		// Ensure we're on a good screen.
		set_current_screen( 'tribe_events_page_tribe-common' );

		// Mock the `now` date to be this year on November 21st.
		$year = date( 'Y' );
		$this->set_class_fn_return( Dates::class, 'build_date_object', static function ( $input ) use ( $year ) {
			return $input === 'now' ?
				new \DateTime( "2022-07-30 19:23:23" )
				: new \DateTime( $input );
		}, true );

		// Mock a premium plugin installed.
		$this->set_class_fn_return( Tribe__Dependency::class, 'has_active_premium_plugin', false );

		$notice = tribe( Stellar_Sale::class );

		$this->assertMatchesHtmlSnapshot( $notice->display_notice() );
	}
}
