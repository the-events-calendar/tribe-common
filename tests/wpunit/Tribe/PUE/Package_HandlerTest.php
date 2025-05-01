<?php
namespace Tribe\PUE;

use Prophecy\Argument;
use Tribe__PUE__Package_Handler as Package_Handler;

class Package_HandlerTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @var \WP_Filesystem_Base
	 */
	protected $filesystem;

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';

		$this->filesystem = $this->prophesize( \WP_Filesystem_Base::class );
	}

	public function tearDown(): void {
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

		$this->assertInstanceOf( Package_Handler::class, $sut );
	}

	/**
	 * @return Package_Handler
	 */
	private function make_instance() {
		return new Package_Handler( $this->filesystem->reveal() );
	}

	/**
	 * @test
	 * it should not filter the download location if the pu flag is not set
	 */
	public function it_should_not_filter_the_download_location_if_the_pu_flag_is_not_set() {
		$package  = add_query_arg( [ 'foo' => 'bar', 'baz' => 23 ], 'http://example.com' );
		$upgrader = $this->prophesize( \WP_Upgrader::class );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader->reveal() );

		$this->assertFalse( $filtered );
	}

	/**
	 * @test
	 * it should not filter the download if passing the path to a file
	 */
	public function it_should_not_filter_the_download_if_passing_the_path_to_a_file() {
		$package  = __FILE__;
		$upgrader = $this->prophesize( \WP_Upgrader::class );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader->reveal() );

		$this->assertFalse( $filtered );
	}

	/**
	 * @test
	 * it should not filter the download if the url is wonky
	 */
	public function it_should_not_filter_the_download_if_the_url_is_wonky() {
		$package  = 'htt://forgot-something-dear?pu_get_download=1';
		$upgrader = $this->prophesize( \WP_Upgrader::class );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader->reveal() );

		$this->assertFalse( $filtered );
	}

	/**
	 * @test
	 * it should not filter the download if the pu_get_download flag is not 1
	 */
	public function it_should_not_filter_the_download_if_the_pu_get_download_flag_is_not_1() {
		$package  = 'htt://update.tri.be?pu_get_download=0';
		$upgrader = $this->prophesize( \WP_Upgrader::class );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader->reveal() );

		$this->assertFalse( $filtered );
	}

	/**
	 * @test
	 * it should not filter the download if the package is empty
	 */
	public function it_should_not_filter_the_download_if_the_package_is_empty() {
		$upgrader = $this->prophesize( \WP_Upgrader::class );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, '', $upgrader->reveal() );

		$this->assertFalse( $filtered );
	}

	/**
	 * @test
	 * it should return WP_Error if the file was not found
	 */
	public function it_should_return_WP_Error_if_the_file_was_not_found() {
		$package           = add_query_arg( [ 'pu_get_download' => '1' ], 'http://foo.bar' );
		$upgrader          = $this->getMockBuilder( \WP_Upgrader::class )->getMock();
		$upgrader->strings = [ 'download_failed' => 'meh' ];
		$skin              = $this->prophesize( \WP_Upgrader_Skin::class );
		$skin->feedback( 'downloading_package', $package )->shouldBeCalled();
		$upgrader->skin = $skin->reveal();

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader );

		$this->assertWPError( $filtered );
	}

	/**
	 * @test
	 * it should return false if the downloaded file could not be moved
	 */
	public function it_should_return_false_if_the_downloaded_file_could_not_be_moved() {
		$url      = wp_get_attachment_url( $this->factory()->attachment->create_upload_object( codecept_data_dir( 'some-file.txt' ) ) );
		$package  = add_query_arg( [ 'pu_get_download' => '1' ], $url );
		$upgrader = $this->getMockBuilder( \WP_Upgrader::class )->getMock();
		$skin     = $this->prophesize( \WP_Upgrader_Skin::class );
		$skin->feedback( 'downloading_package', $package )->shouldBeCalled();
		$upgrader->skin = $skin->reveal();
		$this->filesystem->move( Argument::type( 'string' ), Argument::type( 'string' ) )->willReturn( false );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader );

		$this->assertFalse( $filtered );
	}

	/**
	 * @test
	 * it should move the file and return a shorter named version of it
	 */
	public function it_should_move_the_file_and_return_a_shorter_named_version_of_it() {
		$url      = wp_get_attachment_url( $this->factory()->attachment->create_upload_object( codecept_data_dir( 'some-file.txt' ) ) );
		$package  = add_query_arg( [ 'pu_get_download' => '1' ], $url );
		$upgrader = $this->getMockBuilder( \WP_Upgrader::class )->getMock();
		$skin     = $this->prophesize( \WP_Upgrader_Skin::class );
		$skin->feedback( 'downloading_package', $package )->shouldBeCalled();
		$upgrader->skin      = $skin->reveal();
		$real_temp_file_name = '';
		$destination_file    = '';
		$this->filesystem->move( Argument::type( 'string' ), Argument::type( 'string' ) )->will( function ( $args ) use (
			&$real_temp_file_name,
			&$destination_file
		) {
			$real_temp_file_name = $args[0];
			$destination_file    = $args[1];

			unlink( $args[0] );

			return true;
		} );

		$sut      = $this->make_instance();
		$filtered = $sut->filter_upgrader_pre_download( false, $package, $upgrader );

		$expected_dir           = dirname( $real_temp_file_name );
		$expected_file_exension = pathinfo( $real_temp_file_name, PATHINFO_EXTENSION );
		$expected_file_basename = substr( md5( $real_temp_file_name ), 0, 5 ) . '.' . $expected_file_exension;
		$expected_filename      = $expected_dir . '/' . $expected_file_basename;
		$this->assertEquals( $expected_filename, $destination_file );
		$this->assertEquals( $destination_file, $filtered );
	}

}
