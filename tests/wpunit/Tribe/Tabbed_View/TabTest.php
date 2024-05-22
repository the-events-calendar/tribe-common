<?php
namespace Tribe\Tabbed_View;

use Prophecy\Argument;
use Tribe__Tabbed_View__Tab as Tab;

class TabTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \Tribe__Tabbed_View
	 */
	protected $tabbed_view;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->tabbed_view = $this->prophesize( \Tribe__Tabbed_View::class );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( \Tribe__Tabbed_View__Tab::class, $sut );
	}

	/**
	 * @return Tab
	 */
	private function make_instance() {
		return new Tab( $this->tabbed_view->reveal(), 'foo' );
	}

	/**
	 * @test
	 * it should fetch the URL from the tabbed view
	 */
	public function it_should_fetch_the_url_from_the_tabbed_view() {
		$this->tabbed_view->get_url( Argument::type( 'array' ), Argument::type( 'bool' ) )->willReturn( 'foo/bar' );

		$sut = $this->make_instance();
		$url = $sut->get_url();

		$this->assertEquals( 'foo/bar', $url );
	}

	/**
	 * @test
	 * it should not be active if another tab is active
	 */
	public function it_should_not_be_active_if_another_tab_is_active() {
		$tab = $this->prophesize( \Tribe__Tabbed_View__Tab::class );
		$tab->get_slug()->willReturn( 'bar' );
		$this->tabbed_view->get_active()->willReturn( $tab->reveal() );

		$sut       = $this->make_instance();
		$is_active = $sut->is_active();

		$this->assertFalse( $is_active );
	}

	/**
	 * @test
	 * it should be active if this is active
	 */
	public function it_should_be_active_if_this_is_active() {
		$tab = $this->prophesize( \Tribe__Tabbed_View__Tab::class );
		$tab->get_slug()->willReturn( 'foo' );
		$this->tabbed_view->get_active()->willReturn( $tab->reveal() );

		$sut       = $this->make_instance();
		$is_active = $sut->is_active();

		$this->assertTrue( $is_active );
	}

	/**
	 * @test
	 * it should not be active if active is undefined on tabbed view
	 */
	public function it_should_not_be_active_if_active_is_undefined_on_tabbed_view() {
		$tab = $this->prophesize( \Tribe__Tabbed_View__Tab::class );
		$this->tabbed_view->get_active()->willReturn( false );

		$sut       = $this->make_instance();
		$is_active = $sut->is_active();

		$this->assertFalse( $is_active );
	}
}
